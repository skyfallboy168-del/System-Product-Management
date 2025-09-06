<?php require_once APP_ROOT . '/src/Views/inc/header.php'; ?>

<style>
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; }
    input[type="email"], input[type="password"] {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    .invalid-feedback { color: #dc3545; font-size: 0.875em; }
    .is-invalid { border-color: #dc3545; }
    .alert {
        padding: .75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: .25rem;
    }
    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }
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
        <?php if(!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <h2>Login</h2>
        <p>Please fill in your credentials to log in</p>
        <form action="<?php echo URL_ROOT; ?>/public/users/login" method="post">
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
                <input type="submit" value="Login" class="btn btn-success btn-block">
            </div>
            <a href="<?php echo URL_ROOT; ?>/public/users/register" class="btn btn-light btn-block" style="background-color: #f8f9fa; color: #212529; border-color: #dee2e6;">No account? Register</a>
        </form>
    </div>
</div>

<?php require_once APP_ROOT . '/src/Views/inc/footer.php'; ?>
