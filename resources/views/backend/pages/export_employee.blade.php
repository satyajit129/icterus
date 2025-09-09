<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Picture</th> <!-- Image will be inserted here by Excel, keep empty or placeholder -->
            <th>ID</th>
            <th>Name</th>
            <th>Designation</th>
            <th>Department</th>
            <th>Address</th>
            <th>J. Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($employees as $index => $employee)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td></td> <!-- leave empty, image will be inserted here -->
                <td>{{ $employee->id_number }}</td>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->designation->designation ?? '-' }}</td>
                <td>{{ $employee->department->department ?? '-' }}</td>
                <td>{{ $employee->address }}</td>
                <td>{{ \Carbon\Carbon::parse($employee->joining_date)->format('d/m/Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
