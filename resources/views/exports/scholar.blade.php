<table>
    <thead>
        <tr>
            <th>Column 1</th>
            <th>Column 2</th>
            <!-- Add other columns as needed -->
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ $item->column1 }}</td>
                <td>{{ $item->column2 }}</td>
                <!-- Add other columns as needed -->
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    // Add the dropdown list with options from the database
    var dropdownOptions = @json($dropdownOptions);
</script>