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
    <a href="<?php echo URL_ROOT; ?>/public/products/add" class="btn btn-primary">Add Product</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>SKU</th>
            <th>Name</th>
            <th>Category</th>
            <th>Supplier</th>
            <th>Selling Price</th>
            <th>Stock Qty</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($products as $product) : ?>
            <tr>
                <td><?php echo htmlspecialchars($product->sku); ?></td>
                <td><?php echo htmlspecialchars($product->name); ?></td>
                <td><?php echo htmlspecialchars($product->category_name); ?></td>
                <td><?php echo htmlspecialchars($product->supplier_name); ?></td>
                <td><?php echo number_format($product->selling_price_1, 2); ?></td>
                <td><?php echo $product->stock_quantity; ?></td>
                <td>
                    <a href="<?php echo URL_ROOT; ?>/public/products/edit/<?php echo $product->id; ?>" class="btn btn-secondary btn-sm">Edit</a>
                    <form class="action-form" action="<?php echo URL_ROOT; ?>/public/products/delete/<?php echo $product->id; ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this product?');">
                        <input type="submit" value="Delete" class="btn btn-danger btn-sm">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once APP_ROOT . '/src/Views/inc/footer.php'; ?>
