<?php require_once APP_ROOT . '/src/Views/inc/header.php'; ?>

<style>
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-group { margin-bottom: 15px; }
    .form-section { border: 1px solid #ddd; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
    .form-section h3 { margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
    label { display: block; margin-bottom: 5px; font-weight: bold; }
    input[type="text"], input[type="number"], textarea, select {
        width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;
    }
    textarea { height: 100px; }
    .invalid-feedback { color: #dc3545; font-size: 0.875em; }
    .is-invalid { border-color: #dc3545; }
</style>

<h2><?php echo isset($id) && $id ? 'Edit Product' : 'Add Product'; ?></h2>

<form action="<?php echo URL_ROOT; ?>/public/products/<?php echo isset($id) && $id ? 'edit/' . $id : 'add'; ?>" method="post">

    <div class="form-section">
        <h3>Basic Information</h3>
        <div class="form-grid">
            <div class="form-group">
                <label for="name">Product Name: <sup>*</sup></label>
                <input type="text" name="name" class="<?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                <span class="invalid-feedback"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group">
                <label for="sku">SKU: <sup>*</sup></label>
                <input type="text" name="sku" class="<?php echo (!empty($sku_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $sku; ?>">
                <span class="invalid-feedback"><?php echo $sku_err; ?></span>
            </div>
            <div class="form-group">
                <label for="barcode">Barcode:</label>
                <input type="text" name="barcode" value="<?php echo $barcode ?? ''; ?>">
            </div>
             <div class="form-group">
                <label for="unit">Unit (e.g., pcs, kg, box):</label>
                <input type="text" name="unit" value="<?php echo $unit; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description"><?php echo $description; ?></textarea>
        </div>
    </div>

    <div class="form-section">
        <h3>Categorization</h3>
        <div class="form-grid">
            <div class="form-group">
                <label for="category_id">Category:</label>
                <select name="category_id">
                    <option value="">Select Category</option>
                    <?php foreach($categories as $category): ?>
                        <option value="<?php echo $category->id; ?>" <?php echo ($category_id == $category->id) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="supplier_id">Supplier:</label>
                <select name="supplier_id">
                    <option value="">Select Supplier</option>
                    <?php foreach($suppliers as $supplier): ?>
                        <option value="<?php echo $supplier->id; ?>" <?php echo ($supplier_id == $supplier->id) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($supplier->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="form-section">
        <h3>Pricing</h3>
        <div class="form-grid">
            <div class="form-group">
                <label for="purchase_price">Purchase Price:</label>
                <input type="number" step="0.01" name="purchase_price" value="<?php echo $purchase_price; ?>">
            </div>
            <div class="form-group">
                <label for="markup_percentage">Markup %:</label>
                <input type="number" step="0.01" name="markup_percentage" value="<?php echo $markup_percentage; ?>">
            </div>
            <div class="form-group">
                <label for="selling_price_1">Default Selling Price: <sup>*</sup></label>
                <input type="number" step="0.01" name="selling_price_1" class="<?php echo (!empty($selling_price_1_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $selling_price_1; ?>">
                <span class="invalid-feedback"><?php echo $selling_price_1_err; ?></span>
            </div>
            <div class="form-group">
                <label for="selling_price_2">Selling Price 2:</label>
                <input type="number" step="0.01" name="selling_price_2" value="<?php echo $selling_price_2; ?>">
            </div>
            <div class="form-group">
                <label for="discount_price_1">Discount Price 1:</label>
                <input type="number" step="0.01" name="discount_price_1" value="<?php echo $discount_price_1; ?>">
            </div>
            <div class="form-group">
                <label for="discount_price_2">Discount Price 2:</label>
                <input type="number" step="0.01" name="discount_price_2" value="<?php echo $discount_price_2; ?>">
            </div>
             <div class="form-group">
                <label for="discount_price_3">Discount Price 3:</label>
                <input type="number" step="0.01" name="discount_price_3" value="<?php echo $discount_price_3; ?>">
            </div>
        </div>
    </div>

    <input type="submit" class="btn btn-primary" value="<?php echo isset($id) && $id ? 'Update Product' : 'Add Product'; ?>">
    <a href="<?php echo URL_ROOT; ?>/public/products" class="btn btn-secondary">Cancel</a>
</form>

<?php require_once APP_ROOT . '/src/Views/inc/footer.php'; ?>
