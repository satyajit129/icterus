<?php

namespace App\Services;

use App\Exports\StudentExport;
use App\Models\Student;
use App\Models\StudentPayment;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StudentService
{
    public function renderStudentList($request = null): View
    {
        $query = Student::with('payments');

        // Apply filters if request is provided
        if ($request) {
            // Date range filter (enroll_date)
            if ($request->filled('date_from')) {
                $dateFrom = Carbon::createFromFormat('d-m-Y', $request->date_from)->format('Y-m-d');
                $query->whereDate('enroll_date', '>=', $dateFrom);
            }
            if ($request->filled('date_to')) {
                $dateTo = Carbon::createFromFormat('d-m-Y', $request->date_to)->format('Y-m-d');
                $query->whereDate('enroll_date', '<=', $dateTo);
            }

            // Course filter
            if ($request->filled('course')) {
                $query->where('courses', 'like', '%' . $request->course . '%');
            }

            // Status filter
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Phone filter
            if ($request->filled('phone')) {
                $query->where('phone', 'like', '%' . $request->phone . '%');
            }
        }

        $students = $query->latest()->paginate(20);

        // Calculate summary data
        $summaryQuery = Student::with('payments');

        // Apply same filters to summary query
        if ($request) {
            if ($request->filled('date_from')) {
                $dateFrom = Carbon::createFromFormat('d-m-Y', $request->date_from)->format('Y-m-d');
                $summaryQuery->whereDate('enroll_date', '>=', $dateFrom);
            }
            if ($request->filled('date_to')) {
                $dateTo = Carbon::createFromFormat('d-m-Y', $request->date_to)->format('Y-m-d');
                $summaryQuery->whereDate('enroll_date', '<=', $dateTo);
            }
            if ($request->filled('course')) {
                $summaryQuery->where('courses', 'like', '%' . $request->course . '%');
            }
            if ($request->filled('status')) {
                $summaryQuery->where('status', $request->status);
            }
            if ($request->filled('phone')) {
                $summaryQuery->where('phone', 'like', '%' . $request->phone . '%');
            }
        }

        $filteredStudents = $summaryQuery->get();

        // Calculate totals
        $totalAmount = $filteredStudents->sum('amount');
        $totalPaid = $filteredStudents->sum(function($student) {
            return $student->payments->sum('amount');
        });
        $totalDue = $totalAmount - $totalPaid;

        $summary = [
            'total_amount' => $totalAmount,
            'total_paid' => $totalPaid,
            'total_due' => $totalDue
        ];

        return view('backend.pages.students', compact('students', 'summary'));
    }
    public function renderStudentCreateOrEdit($id = null): View
    {
        $student = $id ? Student::findOrFail($id) : null;
        return view('backend.pages.student_create_or_edit', compact('student'));
    }
    public function handleStudentSave($request, $id = null): RedirectResponse
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'nullable|email',
                'phone' => 'required|string',
                'courses' => 'required',
                'amount' => 'required|numeric|min:0',
                'enroll_date' => 'required|date',
            ]);

            $student = $id ? Student::findOrFail($id) : new Student();

            $student->name = $request->name;
            $student->email = $request->email;
            $student->phone = $request->phone;
            $student->courses = $request->courses;
            $student->amount = $request->amount;
            $student->enroll_date = Carbon::createFromFormat('d-m-Y', $request->enroll_date)->format('Y-m-d');
            $student->details = $request->details;
            // If new student, save first to generate ID
            if (!$id) {
                $student->save();
            }

            // Calculate total paid
            $student_payments = StudentPayment::where('student_id', $student->id)->sum('amount');

            // Set status based on payment completion
            if ($student_payments >= $student->amount) {
                $student->status = 2; // fully paid
            } else {
                $student->status = 1; // due
            }

            $student->save();

            return redirect()->route('adminStudentList')->with('success', 'Student saved successfully!');
        }catch (ValidationException $th) {
            Log::info( $th->getMessage());
            return redirect()
                ->back()
                ->withErrors($th->validator)
                ->withInput()
                ->with('error', 'Validation failed: ' . $th->getMessage());
        } catch (Exception $e) {
            Log::info( $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Validation failed: ' . $e->getMessage());
        }
    }
    public function handleStudentDelete($id): RedirectResponse
    {
        $student = Student::findOrFail($id);
        $student->delete();
        return redirect()->route('adminStudentList')->with('success', 'Student Deleted successfully!');
    }
    public function renderStudentPaymentList($student_id): View
    {
        $payments = StudentPayment::with('student')->where('student_id', $student_id)->latest()->get();
        return view('backend.pages.student_payments', compact('payments', 'student_id'));
    }
    public function renderStudentPaymentCreateOrEdit($student_id, $payment_id = null): View
    {
        // dd('okkkk99');
        $payment_info = $payment_id ? StudentPayment::findOrFail($payment_id) : null;
        // dd($payment_info);
        $student_info = Student::findOrFail($student_id);
        return view('backend.pages.student_payment_create_or_edit', compact('student_info', 'payment_info'));
    }
    public function handleStudentPaymentSave($request, $id = null): RedirectResponse
    {
        // dd($request->date);
        try {
            $request->validate([
                'student_id'      => 'required|exists:students,id',
                'trnx_method'  => 'required|string',
                'trnx_id'         => 'required|string|max:255',
                'amount'          => 'required|numeric|min:0',
                'date'            => 'required|date',
            ]);

            // Fetch student
            $student_due = Student::findOrFail($request->student_id);

            // Calculate total paid excluding current payment if updating
            $total_paid = StudentPayment::where('student_id', $student_due->id)
                ->when($id, function ($query) use ($id) {
                    $query->where('id', '!=', $id);
                })
                ->sum('amount');

            $total_paid_with_new = $total_paid + $request->amount;

            // Check overpayment
            if ($total_paid_with_new > $student_due->amount) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Payment exceeds remaining loan balance');
            }

            // Create or update payment
            $payment = $id ? StudentPayment::findOrFail($id) : new StudentPayment();
            $payment->student_id   = $request->student_id;
            $payment->trnx_method  = $request->trnx_method;
            $payment->trnx_id      = $request->trnx_id;
            $payment->amount       = $request->amount;
            $payment->date         = Carbon::parse($request->date)->format('Y-m-d');
            $payment->save();

            // Update student status
            $student_due->status = ($total_paid_with_new == $student_due->amount) ? 2 : 1;
            $student_due->save();

            return redirect()
                ->route('adminStudentList', $request->student_id)
                ->with('success', $id ? 'Payment updated successfully!' : 'Payment created successfully!');
        } catch (ValidationException $th) {
            return redirect()
                ->back()
                ->withErrors($th->validator)
                ->withInput()
                ->with('error', 'Failed: ' . $th->getMessage());
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed: ' . $e->getMessage());
        }
    }

    public function renderStudentExport($request): BinaryFileResponse
    {
        $data = $request->only(['date_from', 'date_to', 'course', 'status', 'phone']);
        return Excel::download(new StudentExport($data), 'students.xlsx');
    }
}
