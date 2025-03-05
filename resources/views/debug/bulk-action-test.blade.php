<!DOCTYPE html>
<html>
<head>
    <title>Test Bulk Actions</title>
</head>
<body>
    <h1>Test Bulk Actions</h1>
    
    <h2>Direct Form POST Test</h2>
    <form action="{{ route('leads.bulk-action') }}" method="POST">
        @csrf
        <input type="hidden" name="action" value="delete">
        <ul>
            @foreach($leads as $lead)
                <li>
                    <input type="checkbox" name="selected_leads[]" value="{{ $lead->id }}" checked>
                    {{ $lead->first_name }} {{ $lead->last_name }}
                </li>
            @endforeach
        </ul>
        <button type="submit">Submit Direct Form</button>
    </form>
    
    <h2>JavaScript Form Creation Test</h2>
    <button onclick="testJsFormSubmit()">Test JS Form Submit</button>
    
    <script>
        function testJsFormSubmit() {
            // Create a temporary form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("leads.bulk-action") }}';
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Add action type
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'delete';
            form.appendChild(actionInput);
            
            // Add selected leads
            @foreach($leads as $lead)
                const leadInput{{ $lead->id }} = document.createElement('input');
                leadInput{{ $lead->id }}.type = 'hidden';
                leadInput{{ $lead->id }}.name = 'selected_leads[]';
                leadInput{{ $lead->id }}.value = '{{ $lead->id }}';
                form.appendChild(leadInput{{ $lead->id }});
            @endforeach
            
            // Append form to document and submit
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
