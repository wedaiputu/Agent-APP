<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher List</title>
    <style>
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }
        .card {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            width: 180px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }
        .card h4 {
            margin: 0;
            font-size: 1.2em;
        }
        .card p {
            margin: 10px 0 0;
            font-size: 1.5em;
            color: #333;
        }
        .voucher-list {
            display: none;
            margin-top: 10px;
        }
        .stored-vouchers {
            margin-top: 20px;
            border: 2px solid #007bff;
            padding: 15px;
            background-color: #e7f3ff;
        }
        .stored-card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        .stored-card-box {
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 15px;
            background-color: #eafaf1;
            width: calc(50% - 10px);
        }
    </style>
</head>
<body>
    <h1>Voucher List</h1>
    <h2>Router: {{$routerName}}</h2>
    <a href="{{ route('logout') }}">Logout</a>
    <a href="{{ route('create.voucher') }}">Create Voucher</a>
    <hr>

    <!-- Profile Cards -->
    <div class="card-container">
        @foreach ($categories as $profile => $vouchers)
            <div class="card" onclick="toggleVoucherList('{{ Str::slug($profile) }}')">
                <h4>{{ ucfirst($profile) }}</h4>
                <p>{{ count($vouchers) }} Vouchers</p> <!-- Show the total voucher count for each profile -->
            </div>

            <!-- Hidden list of subcategories for each profile -->
            <div id="{{ Str::slug($profile) }}" class="voucher-list">
                @foreach ($timeCategories as $timePackage => $vouchersInTimePackage)
                    <div class="card" onclick="handleSubcategoryClick(event, '{{ $timePackage }}')">
                        <h4>{{ $timePackage }}</h4>
                        <p>{{ count($vouchersInTimePackage) }} Vouchers</p> <!-- Show the total vouchers count for this time package -->
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <!-- Stored Voucher Container -->
    <h2>Selected Subcategories</h2>
    <div id="stored-subcategories" class="stored-vouchers">
        <p>No subcategories selected yet.</p>
    </div>

    <script>
        let subcategoryCount = 0; // Track the total number of selected subcategories
        let currentCardBox = null; // Track the current card box for storing subcategories

        function toggleVoucherList(profileSlug) {
            var voucherList = document.getElementById(profileSlug);
            voucherList.style.display = (voucherList.style.display === "block") ? "none" : "block";
        }

        function handleSubcategoryClick(event, subcategory) {
            event.stopPropagation(); // Prevent triggering parent click events

            // Ask the user how many vouchers they want
            var quantity = prompt(`How many vouchers do you want for the "${subcategory}" subcategory?`, "1");

            if (quantity === null || quantity.trim() === "") {
                return; // User canceled or entered invalid input
            }

            quantity = parseInt(quantity, 10);

            if (isNaN(quantity) || quantity <= 0) {
                alert("Please enter a valid number greater than zero.");
                return;
            }

            const storedSubcategoriesDiv = document.getElementById('stored-subcategories');

            // Create the first card box if none exists
            if (!currentCardBox) {
                storedSubcategoriesDiv.innerHTML = ''; // Clear the "No subcategories selected yet" message
                createNewCardBox(storedSubcategoriesDiv);
            }

            // Add the subcategory to the current card box
            addSubcategoryToCardBox(currentCardBox, subcategory, quantity);

            // If the current card box has two items, create a new card box for the next subcategories
            subcategoryCount++;
            if (subcategoryCount % 2 === 0) {
                createNewCardBox(storedSubcategoriesDiv);
            }
        }

        function createNewCardBox(container) {
            const cardBox = document.createElement('div');
            cardBox.className = 'stored-card-box stored-card-container';
            container.appendChild(cardBox);
            currentCardBox = cardBox;
        }

        function addSubcategoryToCardBox(cardBox, subcategory, quantity) {
            const subcategoryDiv = document.createElement('div');
            subcategoryDiv.className = 'card';
            subcategoryDiv.innerHTML = `
                <p><strong>Subcategory:</strong> ${subcategory}</p>
                <p><strong>Requested Vouchers:</strong> ${quantity}</p>
            `;
            cardBox.appendChild(subcategoryDiv);
        }
    </script>
</body>
</html>
