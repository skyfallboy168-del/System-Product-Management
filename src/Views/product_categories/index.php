<?php require_once APP_ROOT . '/src/Views/inc/header.php'; ?>

<div class="page-header">
    <h1><?php echo $title; ?></h1>
    <a href="<?php echo URL_ROOT; ?>/public/productcategories/add" class="btn btn-primary">Add Category</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Category Name</th>
            <th style="width: 20%;">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($categories as $category) : ?>
            <tr>
                <td><?php echo htmlspecialchars($category->name); ?></td>
                <td>
                    <a href="<?php echo URL_ROOT; ?>/public/productcategories/edit/<?php echo $category->id; ?>" class="btn btn-secondary btn-sm">Edit</a>
                    <form class="action-form" action="<?php echo URL_ROOT; ?>/public/productcategories/delete/<?php echo $category->id; ?>" method="post" onsubmit="return confirm('Are you sure? Deleting a category will not delete the products within it, but they will become uncategorized.');">
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
