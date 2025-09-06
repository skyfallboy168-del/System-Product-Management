<?php require_once APP_ROOT . '/src/Views/inc/header.php'; ?>

    <h1><?php echo $title; ?></h1>
    <p><?php echo $description; ?></p>
    <p>You have successfully set up the basic application structure. From here, you can start building out the specific modules for the business management system.</p>
    <p><strong>Next Steps:</strong></p>
    <ul>
        <li>Set up your database using the <code>schema.sql</code> file.</li>
        <li>Begin creating the models and controllers for the "User & Role Management" module.</li>
    </ul>

<?php require_once APP_ROOT . '/src/Views/inc/footer.php'; ?>
