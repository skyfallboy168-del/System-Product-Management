<?php require_once APP_ROOT . '/src/Views/inc/header.php'; ?>

<h2><?php echo $title; ?></h2>
<p>Use this form to manually adjust stock levels. All adjustments are logged.</p>

<form action="<?php echo URL_ROOT; ?>/public/products/adjustments" method="post">
    <div class="form-group">
        <label for="product_id">Product: <sup>*</sup></label>
        <select name="product_id" class="<?php echo (!empty($product_err)) ? 'is-invalid' : ''; ?>">
            <option value="">Select a Product</option>
            <?php foreach($products as $product): ?>
                <option value="<?php echo $product->id; ?>" <?php echo ($product_id == $product->id) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($product->name); ?> (Current Stock: <?php echo $product->stock_quantity; ?>)
                </option>
            <?php endforeach; ?>
        </select>
        <span class="invalid-feedback"><?php echo $product_err; ?></span>
    </div>

    <div class="form-group">
        <label for="type">Adjustment Type: <sup>*</sup></label>
        <select name="type">
            <option value="stock-in" <?php if($type == 'stock-in') echo 'selected'; ?>>Stock-in (add to inventory)</option>
            <option value="adjustment" <?php if($type == 'adjustment') echo 'selected'; ?>>Adjustment (e.g., write-off, correction)</option>
        </select>
    </div>

    <div class="form-group">
        <label for="quantity">Quantity: <sup>*</sup></label>
        <input type="number" step="1" name="quantity" class="<?php echo (!empty($quantity_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $quantity; ?>">
        <span class="invalid-feedback"><?php echo $quantity_err; ?></span>
        <small>Enter a positive number. For write-offs in 'Adjustment' type, the reason should specify it.</small>
    </div>

    <div class="form-group">
        <label for="reason">Reason for Adjustment:</label>
        <textarea name="reason" rows="3"><?php echo $reason; ?></textarea>
    </div>

    <input type="submit" class="btn btn-primary" value="Submit Adjustment">
    <a href="<?php echo URL_ROOT; ?>/public/products" class="btn btn-secondary">Cancel</a>
</form>

<style>
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; font-weight: bold; }
    input[type="text"], input[type="number"], textarea, select {
        width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;
    }
    .invalid-feedback { color: #dc3545; font-size: 0.875em; }
    .is-invalid { border-color: #dc3545; }
</style>

<?php require_once APP_ROOT . '/src/Views/inc/footer.php'; ?>
