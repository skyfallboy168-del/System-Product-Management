<?php require_once APP_ROOT . '/src/Views/inc/header.php'; ?>

<style>
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; }
    input[type="text"], input[type="date"], input[type="number"], select, textarea {
        width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;
    }
    .grid-container { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
    .items-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    .items-table th { background-color: #f2f2f2; }
    .total-section { margin-top: 20px; text-align: right; }
    .total-section div { margin-bottom: 5px; font-size: 1.1em; }
</style>

<h2><?php echo $title; ?></h2>

<form action="<?php echo URL_ROOT; ?>/public/quotes/add" method="post">
    <div class="grid-container">
        <div class="form-group">
            <label for="customer_id">Customer: <sup>*</sup></label>
            <select name="customer_id" id="customer_id">
                <option value="">Select Customer</option>
                <?php foreach($customers as $customer): ?>
                    <option value="<?php echo $customer->id; ?>"><?php echo htmlspecialchars($customer->name); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="quote_number">Quote Number:</label>
            <input type="text" name="quote_number" value="<?php echo $quote_number; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="quote_date">Quote Date:</label>
            <input type="date" name="quote_date" value="<?php echo $quote_date; ?>">
        </div>
        <div class="form-group">
            <label for="expiry_date">Expiry Date:</label>
            <input type="date" name="expiry_date" value="<?php echo $expiry_date; ?>">
        </div>
    </div>

    <hr>
    <h3>Items</h3>
    <table class="items-table" id="quote-items">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Item rows will be added here by JavaScript -->
        </tbody>
    </table>
    <button type="button" id="add-item-btn" class="btn" style="margin-top: 10px;">Add Item</button>

    <div class="total-section">
        <div>Subtotal: <span id="subtotal">0.00</span></div>
        <div>Tax (0%): <span id="tax">0.00</span></div>
        <div><strong>Total:</strong> <span id="total"><strong>0.00</strong></span></div>
    </div>

    <hr>
    <div class="form-group">
        <input type="submit" value="Save Quote" class="btn btn-success">
        <a href="<?php echo URL_ROOT; ?>/public/quotes" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<!-- Make product data available to JS -->
<script>
    const productsData = <?php echo json_encode($products); ?>;
</script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/quote-form.js"></script>

<?php require_once APP_ROOT . '/src/Views/inc/footer.php'; ?>
