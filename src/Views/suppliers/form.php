<?php require_once APP_ROOT . '/src/Views/inc/header.php'; ?>

<h2><?php echo isset($id) ? 'Edit Supplier' : 'Add Supplier'; ?></h2>

<form action="<?php echo URL_ROOT; ?>/public/suppliers/<?php echo isset($id) ? 'edit/' . $id : 'add'; ?>" method="post">
    <div class="form-group">
        <label for="name">Supplier Name: <sup>*</sup></label>
        <input type="text" name="name" class="<?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name ?? ''; ?>" required>
        <span class="invalid-feedback"><?php echo $name_err ?? ''; ?></span>
    </div>
    <div class="form-group">
        <label for="contact_person">Contact Person:</label>
        <input type="text" name="contact_person" value="<?php echo $contact_person ?? ''; ?>">
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $email ?? ''; ?>">
    </div>
    <div class="form-group">
        <label for="phone">Phone:</label>
        <input type="tel" name="phone" value="<?php echo $phone ?? ''; ?>">
    </div>
    <input type="submit" class="btn" value="<?php echo isset($id) ? 'Update Supplier' : 'Add Supplier'; ?>">
    <a href="<?php echo URL_ROOT; ?>/public/suppliers" class="btn btn-secondary">Cancel</a>
</form>

<style>
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; }
    input[type="text"], input[type="email"], input[type="tel"] {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    .invalid-feedback { color: #dc3545; font-size: 0.875em; }
    .is-invalid { border-color: #dc3545; }
</style>

<?php require_once APP_ROOT . '/src/Views/inc/footer.php'; ?>
