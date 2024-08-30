<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .search-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-container input {
            width: 100%;
            max-width: 300px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .user-info {
            text-align: center;
            margin-bottom: 20px;
        }
        .user-info img {
            border-radius: 50%;
            width: 80px;
            height: 80px;
            object-fit: cover;
            display: block;
            margin: 0 auto;
        }
        .user-info .name {
            margin-top: 10px;
            font-size: 18px;
            font-weight: bold;
        }
        @media (max-width: 768px) {
            th, td {
                display: block;
                width: 100%;
                box-sizing: border-box;
            }
            th {
                background-color: #f9f9f9;
                font-size: 18px;
            }
        }
        .product-photo {
            width: 100px;
            height: auto;
            max-height: 100px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Product</h1>
        
        <!-- User Info -->
        <div class="user-info">
            <!-- Display User Photo and Name -->
            <img src="{{ $user->photo ? asset('storage/' . $user->photo) : 'https://via.placeholder.com/80' }}" alt="{{ $user->name }}">
            <div class="name">{{ $user->name }}</div>
        </div>
        
        <div>
            @if(session()->has('success'))
                <div>
                    {{ session('success') }}
                </div>
            @endif
        </div>
        
        <div>
            <a href="{{ route('product.create') }}" class="btn btn-primary">Create a Product</a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Go to Dashboard</a>
        </div>
        
        <div class="search-container">
            <input type="text" id="searchInput" placeholder="Search for products..." onkeyup="searchFunction()">
        </div>
        
        <table id="productTable">
            <thead>
                <tr>
                <th>S.No</th> 
                    <th>Name</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Photo</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                    <td>{{ $loop->iteration }}</td> 
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->qty }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->description }}</td>
                        <td>
    @if($product->photo)
        <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" class="product-photo">
    @else
        <img src="https://via.placeholder.com/100" alt="No image" class="product-photo">
    @endif
</td>

                        <td>
                            <a href="{{ route('product.edit', ['product' => $product]) }}">Edit</a>
                        </td>
                        <td>
                            <form method="post" action="{{ route('product.destroy', ['product' => $product]) }}">
                                @csrf 
                                @method('delete')
                                <input type="submit" value="Delete" />
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div id="paginationControls">
        <span id="pageInfo"></span>
        <button id="prevPage">Previous</button>
    <span id="pageButtons"></span> <!-- Container for dynamic page buttons -->
    <button id="nextPage">Next</button>
</div>
    </div>

    <script>
const rowsPerPage = 3; // Number of rows per page
let currentPage = 1;

function displayProductTable() {
    const table = document.getElementById("productTable");
    const rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");
    const totalRows = rows.length;
    const totalPages = Math.ceil(totalRows / rowsPerPage);

    // Hide all rows initially
    for (let i = 0; i < totalRows; i++) {
        rows[i].style.display = "none";
    }

    // Calculate the start and end index of the rows to be displayed on the current page
    const startIndex = (currentPage - 1) * rowsPerPage;
    const endIndex = Math.min(startIndex + rowsPerPage, totalRows);

    // Display the rows for the current page
    for (let i = startIndex; i < endIndex; i++) {
        rows[i].style.display = "";
    }

    // Update pagination info
    document.getElementById("pageInfo").textContent = `Page ${currentPage} of ${totalPages}`;

    // Disable/Enable pagination buttons
    document.getElementById("prevPage").disabled = currentPage === 1;
    document.getElementById("nextPage").disabled = currentPage === totalPages;

    // Create dynamic page number buttons
    createPageButtons(totalPages);
}

function createPageButtons(totalPages) {
    const pageButtonsContainer = document.getElementById("pageButtons");
    pageButtonsContainer.innerHTML = ''; // Clear existing buttons

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement("button");
        button.textContent = i;
        button.classList.add("page-button");
        if (i === currentPage) {
            button.disabled = true; // Disable current page button
        }
        button.addEventListener("click", () => {
            currentPage = i;
            displayProductTable();
        });
        pageButtonsContainer.appendChild(button);
    }
}

function setupPagination() {
    document.getElementById("prevPage").addEventListener("click", () => {
        if (currentPage > 1) {
            currentPage--;
            displayProductTable();
        }
    });

    document.getElementById("nextPage").addEventListener("click", () => {
        const totalRows = document.getElementById("productTable").getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;
        const totalPages = Math.ceil(totalRows / rowsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            displayProductTable();
        }
    });

    displayProductTable();
}

setupPagination();

        
        function searchFunction() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('productTable');
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                const td = tr[i].getElementsByTagName('td');
                let match = false;
                for (let j = 0; j < td.length; j++) {
                    if (td[j]) {
                        const txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                            match = true;
                            break;
                        }
                    }
                }
                tr[i].style.display = match ? '' : 'none';
            }
        }
        
    </script>
</body>
</html>
