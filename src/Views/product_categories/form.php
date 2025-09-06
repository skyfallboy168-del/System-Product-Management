<?php require_once APP_ROOT . '/src/Views/inc/header.php'; ?>

<h2><?php echo isset($id) ? 'Edit Category' : 'Add Category'; ?></h2>

<form action="<?php echo URL_ROOT; ?>/public/productcategories/<?php echo isset($id) ? 'edit/' . $id : 'add'; ?>" method="post">
    <div class="form-group">
        <label for="name">Category Name: <sup>*</sup></label>
        <input type="text" name="name" class="<?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>" required>
        <span class="invalid-feedback"><?php echo $name_err; ?></span>
    </div>
    <input type="submit" class="btn" value="<?php echo isset($id) ? 'Update Category' : 'Add Category'; ?>">
    <a href="<?php echo URL_ROOT; ?>/public/productcategories" class="btn btn-secondary">Cancel</a>
</form>

<style>
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; }
    input[type="text"] {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    .invalid-feedback { color: #dc3545; font-size: 0.875em; }
    .is-invalid { border-color: #dc3545; }
</style>

<?php require_once APP_ROOT . '/src/Views/inc/footer.php'; ?>
