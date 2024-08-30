<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error-messages {
            color: red;
        }
        .product-photo {
            max-width: 150px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Product</h1>

        @if($errors->any())
            <div class="error-messages">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{ route('product.update', ['product' => $product]) }}" enctype="multipart/form-data">
            @csrf 
            @method('put')

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Name" value="{{ old('name', $product->name) }}" />
            </div>
            <div class="form-group">
                <label for="qty">Qty</label>
                <input type="number" id="qty" name="qty" placeholder="Qty" value="{{ old('qty', $product->qty) }}" />
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" placeholder="Price" value="{{ old('price', $product->price) }}" step="0.01" />
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" id="description" name="description" placeholder="Description" value="{{ old('description', $product->description) }}" />
            </div>

            <div class="form-group">
                <label for="photo">Photo</label>
                <input type="file" id="photo" name="photo" />
            </div>

            @if ($product->photo)
                <div class="form-group">
                    <img src="{{ asset('storage/' . $product->photo) }}" alt="Product Photo" class="product-photo">
                </div>
            @endif

            <div>
                <input type="submit" value="Update" />
                <a href="{{ route('product.index') }}" class="back-button">Back to Products</a>
            </div>
        </form>
    </div>
</body>
</html>
