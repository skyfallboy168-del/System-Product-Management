<?php require_once APP_ROOT . '/src/Views/inc/header.php'; ?>

<style>
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; }
    input[type="text"], input[type="email"], input[type="tel"] {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
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

<h2><?php echo isset($id) && $id ? 'Edit Customer' : 'Add Customer'; ?></h2>
<p>Please fill out the form to <?php echo isset($id) && $id ? 'update the' : 'add a new'; ?> customer.</p>

<form action="<?php echo URL_ROOT; ?>/public/customers/<?php echo isset($id) && $id ? 'edit/' . $id : 'add'; ?>" method="post">
    <div class="form-group">
        <label for="name">Contact Name: <sup>*</sup></label>
        <input type="text" name="name" class="<?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name ?? ''; ?>">
        <span class="invalid-feedback"><?php echo $name_err ?? ''; ?></span>
    </div>
    <div class="form-group">
        <label for="customer_company_name">Company Name:</label>
        <input type="text" name="customer_company_name" value="<?php echo $customer_company_name ?? ''; ?>">
    </div>
    <div class="form-group">
        <label for="email">Email: <sup>*</sup></label>
        <input type="email" name="email" class="<?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email ?? ''; ?>">
        <span class="invalid-feedback"><?php echo $email_err ?? ''; ?></span>
    </div>
    <div class="form-group">
        <label for="phone">Phone:</label>
        <input type="tel" name="phone" value="<?php echo $phone ?? ''; ?>">
    </div>
    <div class="form-group">
        <label for="tax_id">Tax ID:</label>
        <input type="text" name="tax_id" value="<?php echo $tax_id ?? ''; ?>">
    </div>
    <div class="form-group">
        <label for="address_line_1">Address:</label>
        <input type="text" name="address_line_1" value="<?php echo $address_line_1 ?? ''; ?>">
    </div>
    <div class="form-group">
        <label for="city">City:</label>
        <input type="text" name="city" value="<?php echo $city ?? ''; ?>">
    </div>
    <div class="form-group">
        <label for="state">State/Province:</label>
        <input type="text" name="state" value="<?php echo $state ?? ''; ?>">
    </div>
    <div class="form-group">
        <label for="postal_code">Postal Code:</label>
        <input type="text" name="postal_code" value="<?php echo $postal_code ?? ''; ?>">
    </div>
    <div class="form-group">
        <label for="country">Country:</label>
        <input type="text" name="country" value="<?php echo $country ?? ''; ?>">
    </div>

    <input type="submit" class="btn" value="<?php echo isset($id) && $id ? 'Update Customer' : 'Add Customer'; ?>">
    <a href="<?php echo URL_ROOT; ?>/public/customers" class="btn btn-secondary">Cancel</a>
</form>

<?php require_once APP_ROOT . '/src/Views/inc/footer.php'; ?>
