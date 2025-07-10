<!DOCTYPE html>
<html>
<head>
    <title>Import Data Sekolah</title>
</head>
<body>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

<form action="{{ route('sekolah.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label>Pilih file CSV:</label>
    <input type="file" name="file" required>
    <button type="submit">Import</button>
</form>

</body>
</html>
