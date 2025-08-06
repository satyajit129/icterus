<?php 

namespace App\Services;

use App\Models\Student;
use App\Models\StudentPayment;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StudentService{
    public function renderStudentList(): View
    {
        $students = Student::latest()->paginate(20);
        return view('backend.pages.students', compact('students'));
    }
    public function renderStudentCreateOrEdit($id= null): View
    {
        $student = $id ? Student::findOrFail($id): null;
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
            $student->enroll_date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->enroll_date)->format('Y-m-d');

            $student->save();
            return redirect()->route('adminStudentList')->with('success', 'Student saved successfully!');
        }
            catch (ValidationException $th) {
            return redirect()
                ->back()
                ->withErrors($th->validator)
                ->withInput()
                ->with('error', 'Validation failed: ' . $th->getMessage());
        } catch (Exception $e) {
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
        return view('backend.pages.student_payments',compact('payments'));
    }
}