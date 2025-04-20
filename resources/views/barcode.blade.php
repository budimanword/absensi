<!DOCTYPE html>
<html>
<head>
    <title>Barcode</title>
</head>
<body>
    <h3>Generated Barcode:</h3>
    {!! DNS1D::getBarcodeHTML($barcode, 'C128') !!} <!-- Tipe barcode: Code 128 -->
    <p>{{ $barcode }}</p>
</body>
</html>
