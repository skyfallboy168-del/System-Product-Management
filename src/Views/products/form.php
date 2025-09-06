<?php require_once APP_ROOT . '/src/Views/inc/header.php'; ?>

<style>
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; }
    input[type="text"], input[type="number"], textarea, select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    textarea { height: 100px; }
    .invalid-feedback { color: #dc3545; font-size: 0.875em; }
    .is-invalid { border-color: #dc3545; }
    .btn {
        display: inline-block;
        font-weight: 400;
        color: #fff;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        background-color: #007bff;
        border: 1px solid #007bff;
        padding: .375rem .75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: .25rem;
        text-decoration: none;
    }
    .btn-secondary { background-color: #6c757d; border-color: #6c757d;}
</style>

<h2><?php echo isset($id) && $id ? 'Edit Product' : 'Add Product'; ?></h2>
<p>Please fill out the form to <?php echo isset($id) && $id ? 'update the' : 'add a new'; ?> product.</p>

<form action="<?php echo URL_ROOT; ?>/public/products/<?php echo isset($id) && $id ? 'edit/' . $id : 'add'; ?>" method="post">
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
        <label for="description">Description:</label>
        <textarea name="description"><?php echo $description; ?></textarea>
    </div>
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
    <div class="form-group">
        <label for="unit">Unit (e.g., pcs, kg, box):</label>
        <input type="text" name="unit" value="<?php echo $unit; ?>">
    </div>
    <div class="form-group">
        <label for="purchase_price">Purchase Price:</label>
        <input type="number" step="0.01" name="purchase_price" value="<?php echo $purchase_price; ?>">
    </div>
    <div class="form-group">
        <label for="selling_price_1">Selling Price: <sup>*</sup></label>
        <input type="number" step="0.01" name="selling_price_1" class="<?php echo (!empty($selling_price_1_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $selling_price_1; ?>">
        <span class="invalid-feedback"><?php echo $selling_price_1_err; ?></span>
    </div>

    <input type="submit" class="btn" value="<?php echo isset($id) && $id ? 'Update Product' : 'Add Product'; ?>">
    <a href="<?php echo URL_ROOT; ?>/public/products" class="btn btn-secondary">Cancel</a>
</form>

<?php require_once APP_ROOT . '/src/Views/inc/footer.php'; ?>
