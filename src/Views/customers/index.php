<?php require_once APP_ROOT . '/src/Views/inc/header.php'; ?>

<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    .table th { background-color: #f2f2f2; }
    .table tr:nth-child(even){background-color: #f9f9f9;}
    .btn-sm { padding: .25rem .5rem; font-size: .875rem; line-height: 1.5; border-radius: .2rem; }
    .btn-danger { color: #fff; background-color: #dc3545; border-color: #dc3545; }
    .btn-secondary { color: #fff; background-color: #6c757d; border-color: #6c757d; }
    .action-form { display: inline; }
</style>

<div class="page-header">
    <h1><?php echo $title; ?></h1>
    <a href="<?php echo URL_ROOT; ?>/public/customers/add" class="btn btn-primary">Add Customer</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Company Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($customers as $customer) : ?>
            <tr>
                <td><?php echo htmlspecialchars($customer->name); ?></td>
                <td><?php echo htmlspecialchars($customer->customer_company_name); ?></td>
                <td><?php echo htmlspecialchars($customer->email); ?></td>
                <td><?php echo htmlspecialchars($customer->phone); ?></td>
                <td>
                    <a href="<?php echo URL_ROOT; ?>/public/customers/edit/<?php echo $customer->id; ?>" class="btn btn-secondary btn-sm">Edit</a>
                    <form class="action-form" action="<?php echo URL_ROOT; ?>/public/customers/delete/<?php echo $customer->id; ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                        <input type="submit" value="Delete" class="btn btn-danger btn-sm">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once APP_ROOT . '/src/Views/inc/footer.php'; ?>
