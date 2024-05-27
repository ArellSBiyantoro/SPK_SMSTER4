<!DOCTYPE html>
<html>
<head>
    <title>Decision Making</title>
</head>
<body>
    <h1>Decision Making</h1>
    <form action="{{ route('participants.store') }}" method="POST">
        @csrf
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        
        <label for="tiu">TIU:</label>
        <input type="number" id="tiu" name="tiu" required>
        
        <label for="twk">TWK:</label>
        <input type="number" id="twk" name="twk" required>
        
        <label for="tkp">TKP:</label>
        <input type="number" id="tkp" name="tkp" required>
        
        <label for="method">Method:</label>
        <select id="method" name="method" required>
            <option value="SAW">SAW</option>
            <option value="WASPAS">WASPAS</option>
        </select>
        
        <button type="submit">Submit</button>
    </form>
</body>
</html>