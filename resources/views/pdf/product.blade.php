<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; }
    </style>
</head>
<body>

<h2>{{ $product->name }}</h2>
<p>{{ $product->description }}</p>

<table>
    <thead>
        <tr>
            <th>Brand</th>
            <th>Image</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0; @endphp
        @foreach ($product->brands as $brand)
            @php $total += $brand->price; @endphp
            <tr>
                <td>{{ $brand->name }}</td>
                <td>
                    <img src="{{ public_path('storage/' . $brand->image) }}" width="80">
                </td>
                <td>{{ $brand->price }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<h3>Total Price: {{ $total }}</h3>

</body>
</html>
