<?php require_once APP_ROOT . '/src/Views/inc/header.php'; ?>

<div class="page-header">
    <h1><?php echo $title; ?></h1>
    <a href="<?php echo URL_ROOT; ?>/public/suppliers/add" class="btn btn-primary">Add Supplier</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Supplier Name</th>
            <th>Contact Person</th>
            <th>Email</th>
            <th>Phone</th>
            <th style="width: 20%;">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($suppliers as $supplier) : ?>
            <tr>
                <td><?php echo htmlspecialchars($supplier->name); ?></td>
                <td><?php echo htmlspecialchars($supplier->contact_person); ?></td>
                <td><?php echo htmlspecialchars($supplier->email); ?></td>
                <td><?php echo htmlspecialchars($supplier->phone); ?></td>
                <td>
                    <a href="<?php echo URL_ROOT; ?>/public/suppliers/edit/<?php echo $supplier->id; ?>" class="btn btn-secondary btn-sm">Edit</a>
                    <form class="action-form" action="<?php echo URL_ROOT; ?>/public/suppliers/delete/<?php echo $supplier->id; ?>" method="post" onsubmit="return confirm('Are you sure?');">
                        <input type="submit" value="Delete" class="btn btn-danger btn-sm">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    .table th { background-color: #f2f2f2; }
    .action-form { display: inline; margin-left: 5px; }
</style>

<?php require_once APP_ROOT . '/src/Views/inc/footer.php'; ?>
