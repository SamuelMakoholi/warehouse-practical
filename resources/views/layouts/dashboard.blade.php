<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Include your CSS and JS here -->
</head>
<body>
    <nav>
        <ul>
            <li><a href="{{ route('warehouses.index') }}">Warehouses</a></li>
            <li><a href="{{ route('racks.index') }}">Racks</a></li>
            <li><a href="{{ route('lines.index') }}">Lines</a></li>
            <li><a href="{{ route('pallets.index') }}">Pallets</a></li>
            <li><a href="{{ route('packages.index') }}">Packages</a></li>
            <li><a href="{{ route('quality_marks.index') }}">Quality Marks</a></li>
        </ul>
    </nav>
    <div class="content">
        {{ $slot }}
    </div>
</body>
</html>
