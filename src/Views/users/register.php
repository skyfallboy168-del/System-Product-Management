<?php require_once APP_ROOT . '/src/Views/inc/header.php'; ?>

<style>
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; }
    input[type="text"], input[type="email"], input[type="password"] {
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
        color: #212529;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        background-color: #007bff;
        color: #fff;
        border: 1px solid #007bff;
        padding: .375rem .75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: .25rem;
        text-decoration: none;
    }
    .btn-block { display: block; width: 100%; }
    .card { border: 1px solid #ddd; border-radius: 5px; padding: 20px; }
    .card-body { padding: 0; }
</style>

<div class="card">
    <div class="card-body">
        <h2>Create An Account</h2>
        <p>Please fill out this form to register with us</p>
        <form action="<?php echo URL_ROOT; ?>/public/users/register" method="post">
            <div class="form-group">
                <label for="name">Name: <sup>*</sup></label>
                <input type="text" name="name" class="<?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                <span class="invalid-feedback"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group">
                <label for="email">Email: <sup>*</sup></label>
                <input type="email" name="email" class="<?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Password: <sup>*</sup></label>
                <input type="password" name="password" class="<?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password: <sup>*</sup></label>
                <input type="password" name="confirm_password" class="<?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>

            <div class="form-group">
                <input type="submit" value="Register" class="btn btn-primary btn-block">
            </div>
            <a href="<?php echo URL_ROOT; ?>/public/users/login" class="btn btn-light btn-block" style="background-color: #f8f9fa; color: #212529; border-color: #dee2e6;">Have an account? Login</a>
        </form>
    </div>
</div>


<?php require_once APP_ROOT . '/src/Views/inc/footer.php'; ?>
