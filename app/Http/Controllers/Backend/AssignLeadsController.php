<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\FacebookLead;
use App\Models\FacebookLeadgenForm;
use App\Models\FacebookPage;
use App\Models\LeadAssignment;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AssignLeadsController extends Controller
{
    public function index(Request $request): View
    {
        $query = FacebookLead::with(['facebookLeadgenForm', 'facebookPage', 'leadAssignment.assignedTo']);

        // Apply filters
        if ($request->filled('form_id')) {
            $query->byForm($request->form_id);
        }

        if ($request->filled('page_id')) {
            $query->byPage($request->page_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->byDateRange($startDate, $endDate);
        }

        if ($request->filled('is_processed')) {
            $query->where('is_processed', $request->is_processed);
        }

        if ($request->filled('field_name') && $request->filled('field_value')) {
            $query->byFieldValue($request->field_name, $request->field_value);
        }

        if ($request->filled('location')) {
            $query->whereJsonContains('extracted_fields->location', $request->location);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('lead_id', 'like', '%' . $searchTerm . '%')
                    ->orWhereJsonContains('extracted_fields->name', $searchTerm)
                    ->orWhereJsonContains('extracted_fields->phone', $searchTerm)
                    ->orWhereJsonContains('extracted_fields->email', $searchTerm);
            });
        }

        // Filter by assignment status
        if ($request->filled('assignment_status')) {
            if ($request->assignment_status === 'assigned') {
                $query->whereHas('leadAssignment');
            } elseif ($request->assignment_status === 'unassigned') {
                $query->whereDoesntHave('leadAssignment');
            }
        }

        $leads = $query->orderBy('created_time', 'desc')->paginate(100)->appends($request->all());

        // Get filter options
        $forms = FacebookLeadgenForm::active()
            ->select('form_id', 'name', 'page_id')
            ->with('facebookPage:id,page_id,name')
            ->orderBy('name')
            ->get();

        $pages = FacebookPage::active()
            ->select('page_id', 'name')
            ->orderBy('name')
            ->get();

        // Get unique field names for filtering
        $fieldNames = FacebookLead::selectRaw('JSON_EXTRACT(field_data, "$[*].name") as field_names')
            ->get()
            ->pluck('field_names')
            ->flatten()
            ->unique()
            ->filter()
            ->sort()
            ->values();

        // Get unique locations for filtering
        $locations = FacebookLead::whereNotNull('extracted_fields->location')
            ->get()
            ->pluck('extracted_fields.location')
            ->unique()
            ->filter()
            ->sort()
            ->values();

        // Get Student Advisor users
        $studentAdvisors = User::whereHas('adminRole', function ($query) {
            $query->where('name', 'Student Advisor');
        })->select('id', 'name', 'email')->orderBy('name')->get();

        // Calculate summary statistics
        $totalLeads = FacebookLead::count();
        $assignedLeads = FacebookLead::whereHas('leadAssignment')->count();
        $unassignedLeads = $totalLeads - $assignedLeads;

        return view('backend.pages.assign_leads', compact(
            'leads',
            'forms',
            'pages',
            'fieldNames',
            'locations',
            'studentAdvisors',
            'totalLeads',
            'assignedLeads',
            'unassignedLeads'
        ));
    }

    public function bulkAssign(Request $request)
    {
        $request->validate([
            'lead_ids' => 'required|array|min:1',
            'lead_ids.*' => 'exists:facebook_leads,id',
            'assigned_to' => 'nullable|exists:users,id',
            'assignment_type' => 'required|in:bulk,individual'
        ]);

        try {
            DB::beginTransaction();

            $leadIds = $request->lead_ids;
            $assignedTo = $request->assigned_to;
            $assignmentType = $request->assignment_type;

            if ($assignedTo) {
                // Assign all leads to specific user
                $this->assignLeadsToUser($leadIds, $assignedTo, $assignmentType);
                $message = "Successfully assigned " . count($leadIds) . " leads to the selected user and marked as processed.";
            } else {
                // Distribute leads equally among all Student Advisors
                $studentAdvisors = User::whereHas('adminRole', function ($query) {
                    $query->where('name', 'Student Advisor');
                })->pluck('id')->toArray();

                if (empty($studentAdvisors)) {
                    if ($request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No Student Advisors found. Please create users with Student Advisor role first.'
                        ]);
                    }
                    return redirect()->back()->with('error', 'No Student Advisors found. Please create users with Student Advisor role first.');
                }

                $this->distributeLeadsEqually($leadIds, $studentAdvisors, $assignmentType);
                $message = "Successfully distributed " . count($leadIds) . " leads equally among " . count($studentAdvisors) . " Student Advisors and marked as processed.";
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'An error occurred while assigning leads: ' . $e->getMessage();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ]);
            }
            return redirect()->back()->with('error', $errorMessage);
        }
    }

    public function individualAssign(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|exists:facebook_leads,id',
            'assigned_to' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            // Check if lead is already assigned
            $existingAssignment = LeadAssignment::where('lead_id', $request->lead_id)->first();

            if ($existingAssignment) {
                // Update existing assignment
                $existingAssignment->update([
                    'assigned_to' => $request->assigned_to,
                    'assigned_by' => auth()->id(),
                    'notes' => $request->notes,
                    'assigned_at' => now()
                ]);
                $message = "Lead assignment updated successfully and marked as processed.";
            } else {
                // Create new assignment
                LeadAssignment::create([
                    'lead_id' => $request->lead_id,
                    'assigned_to' => $request->assigned_to,
                    'assigned_by' => auth()->id(),
                    'assignment_type' => 'individual',
                    'notes' => $request->notes,
                    'assigned_at' => now()
                ]);
                $message = "Lead assigned successfully and marked as processed.";
            }

            // Mark lead as processed
            FacebookLead::where('id', $request->lead_id)->update(['is_processed' => true]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'An error occurred while assigning lead: ' . $e->getMessage();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ]);
            }
            return redirect()->back()->with('error', $errorMessage);
        }
    }

    public function unassign(Request $request)
    {
        $request->validate([
            'lead_ids' => 'required|array|min:1',
            'lead_ids.*' => 'exists:facebook_leads,id'
        ]);

        try {
            LeadAssignment::whereIn('lead_id', $request->lead_ids)->delete();

            // Mark leads as unprocessed
            FacebookLead::whereIn('id', $request->lead_ids)->update(['is_processed' => false]);

            $message = "Successfully unassigned " . count($request->lead_ids) . " leads and marked as unprocessed.";

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            $errorMessage = 'An error occurred while unassigning leads: ' . $e->getMessage();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ]);
            }
            return redirect()->back()->with('error', $errorMessage);
        }
    }

    private function assignLeadsToUser(array $leadIds, int $userId, string $assignmentType): void
    {
        $assignments = [];
        $now = now();

        foreach ($leadIds as $leadId) {
            // Remove existing assignment if any
            LeadAssignment::where('lead_id', $leadId)->delete();

            $assignments[] = [
                'lead_id' => $leadId,
                'assigned_to' => $userId,
                'assigned_by' => auth()->id(),
                'assignment_type' => $assignmentType,
                'assigned_at' => $now,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        LeadAssignment::insert($assignments);

        // Mark leads as processed
        FacebookLead::whereIn('id', $leadIds)->update(['is_processed' => true]);
    }

    private function distributeLeadsEqually(array $leadIds, array $studentAdvisors, string $assignmentType): void
    {
        $totalLeads = count($leadIds);
        $totalAdvisors = count($studentAdvisors);

        $leadsPerAdvisor = intval($totalLeads / $totalAdvisors);
        $remainingLeads = $totalLeads % $totalAdvisors;

        $assignments = [];
        $now = now();
        $leadIndex = 0;

        foreach ($studentAdvisors as $advisorId) {
            $leadsForThisAdvisor = $leadsPerAdvisor;

            // Distribute remaining leads to first few advisors
            if ($remainingLeads > 0) {
                $leadsForThisAdvisor++;
                $remainingLeads--;
            }

            for ($i = 0; $i < $leadsForThisAdvisor && $leadIndex < $totalLeads; $i++) {
                $leadId = $leadIds[$leadIndex];

                // Remove existing assignment if any
                LeadAssignment::where('lead_id', $leadId)->delete();

                $assignments[] = [
                    'lead_id' => $leadId,
                    'assigned_to' => $advisorId,
                    'assigned_by' => auth()->id(),
                    'assignment_type' => $assignmentType,
                    'assigned_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now
                ];

                $leadIndex++;
            }
        }

        LeadAssignment::insert($assignments);

        // Mark leads as processed
        FacebookLead::whereIn('id', $leadIds)->update(['is_processed' => true]);
    }
}
