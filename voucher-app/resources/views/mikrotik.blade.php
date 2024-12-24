<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Voucher</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Generate Voucher</h2>

        <!-- Display router identity -->
        <div class="alert alert-info text-center">
            <strong>Connected to Router:</strong> {{ session('router_identity') }}
        </div>

        <!-- Form for generating vouchers -->
        <form action="{{ route('createVoucher') }}" method="POST" id="generateForm">
            @csrf

            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required min="1" placeholder="Enter number of vouchers">
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price (IDR)</label>
                <input type="number" class="form-control" id="price" name="price" required min="1" placeholder="Enter price per voucher">
            </div>

            <div class="mb-3">
                <label for="uptime_limit" class="form-label">Uptime Limit (Minutes)</label>
                <input type="number" class="form-control" id="uptime_limit" name="uptime_limit" required min="1" placeholder="Enter uptime limit in minutes">
            </div>

            <button type="submit" class="btn btn-primary w-100">Generate Vouchers</button>
        </form>

        <!-- Vouchers display -->
        <div class="mt-5" id="vouchersContainer" style="display: none;">
            <h4>Generated Vouchers:</h4>
            <ul id="vouchersList" class="list-group"></ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.getElementById('generateForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);

            try {
                const response = await axios.post(form.action, formData);

                if (response.data.success) {
                    const vouchers = response.data.vouchers;
                    const vouchersList = document.getElementById('vouchersList');

                    vouchersList.innerHTML = '';

                    vouchers.forEach(voucher => {
                        const listItem = document.createElement('li');
                        listItem.classList.add('list-group-item');
                        listItem.textContent = `Username: ${voucher.username}, Password: ${voucher.password}, Price: ${voucher.price}, Uptime: ${voucher.uptime_limit}`;
                        vouchersList.appendChild(listItem);
                    });

                    document.getElementById('vouchersContainer').style.display = 'block';
                } else {
                    alert(response.data.message);
                }
            } catch (error) {
                alert('An error occurred while generating vouchers.');
            }
        });
    </script>
</body>
</html>