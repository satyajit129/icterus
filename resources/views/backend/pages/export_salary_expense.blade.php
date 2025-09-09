<table>
    <thead>
        <tr>
            <th>#</th>
            <th>ID Number</th>
            <th>Name</th>
            <th>P. Month</th>
            <th>P. Year</th>
            <th>Working Days</th>
            <th>G. Salary</th>
            <th>Bonus</th>
            <th>E. Charge</th>
            <th>P. Amount</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($salary_expenses as $index => $salary_expense)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $salary_expense->employee->id_number }}</td>
                <td>{{ $salary_expense->employee->name }}</td>
                <td>{{ \App\UtilityFunction::getMonthName($salary_expense->payable_month) }}</td>
                <td>{{ $salary_expense->payable_year }}</td>
                <td>{{ $salary_expense->total_working_day }}</td>
                <td>{{ $salary_expense->gross_salary }}</td>
                <td>{{ $salary_expense->festival_bonus ?? '-----' }}</td>
                <td>{{ $salary_expense->extra_charge ?? '-----' }}</td>
                <td>{{ $salary_expense->payable_amount }}</td>
                <td>{{ optional($salary_expense->salary_status)->label() ?? 'N/A' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="11">No data found.</td>
            </tr>
        @endforelse
    </tbody>
</table>