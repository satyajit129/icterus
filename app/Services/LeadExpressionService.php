<?php

namespace App\Services;

use App\Models\LeadExpression;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Exception;

class LeadExpressionService
{
    public function renderExpressionList(): View
    {
        $expressions = LeadExpression::ordered()->paginate(20);
        return view('backend.pages.lead_expressions', compact('expressions'));
    }

    public function renderExpressionCreateOrEditPage($id = null): View
    {
        $expression = null;
        if ($id) {
            $expression = LeadExpression::findOrFail($id);
        }

        // Available color classes for selection
        $colorClasses = [
            'bg-primary' => 'Primary (Blue)',
            'bg-secondary' => 'Secondary (Gray)',
            'bg-success' => 'Success (Green)',
            'bg-danger' => 'Danger (Red)',
            'bg-warning' => 'Warning (Yellow)',
            'bg-info' => 'Info (Cyan)',
            'bg-light' => 'Light (Light Gray)',
            'bg-dark' => 'Dark (Dark Gray)',
        ];

        return view('backend.pages.lead_expression_create_or_edit', compact('expression', 'colorClasses'));
    }

    public function handleExpressionSave(Request $request, $id = null): RedirectResponse
    {
        try {
            // Log the request for debugging
            \Log::info('Lead Expression Save Request', [
                'id' => $id,
                'request_data' => $request->all(),
                'is_update' => !is_null($id)
            ]);

            $request->validate([
                'name' => 'required|string|max:255|unique:lead_expressions,name,' . $id,
                'color_class' => 'required|string|max:50',
                'text_color' => 'nullable|string|max:20',
                'description' => 'nullable|string|max:1000',
                'is_active' => 'nullable',
                'sort_order' => 'nullable|integer|min:0',
            ]);

            $expression = $id ? LeadExpression::findOrFail($id) : new LeadExpression();

            $expression->name = $request->name;
            $expression->color_class = $request->color_class;
            $expression->text_color = $request->text_color ?? 'text-white';
            $expression->description = $request->description;
            $expression->is_active = $request->has('is_active');
            $expression->sort_order = $request->sort_order ?? 0;

            $expression->save();

            \Log::info('Lead Expression Saved Successfully', [
                'expression_id' => $expression->id,
                'name' => $expression->name,
                'action' => $id ? 'updated' : 'created'
            ]);

            $action = $id ? 'updated' : 'created';
            return redirect()->route('adminLeadExpressionList')
                ->with('success', "Lead expression {$action} successfully.");

        } catch (ValidationException $e) {
            \Log::error('Lead Expression Validation Error', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (Exception $e) {
            \Log::error('Lead Expression Save Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return redirect()->back()
                ->with('error', 'Something went wrong: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function handleExpressionDelete($id): RedirectResponse
    {
        try {
            $expression = LeadExpression::findOrFail($id);
            $expressionName = $expression->name;
            $expression->delete();

            return redirect()->route('adminLeadExpressionList')
                ->with('success', "Lead expression '{$expressionName}' deleted successfully.");

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete expression: ' . $e->getMessage());
        }
    }

    public function handleExpressionToggleStatus($id): RedirectResponse
    {
        try {
            $expression = LeadExpression::findOrFail($id);
            $expression->is_active = !$expression->is_active;
            $expression->save();

            $status = $expression->is_active ? 'activated' : 'deactivated';
            return redirect()->back()
                ->with('success', "Lead expression '{$expression->name}' {$status} successfully.");

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update expression status: ' . $e->getMessage());
        }
    }

    public function renderExpressionView($id): View
    {
        $expression = LeadExpression::findOrFail($id);
        return view('backend.pages.lead_expression_view', compact('expression'));
    }
}
