<?php require_once APP_ROOT . '/src/Views/inc/header.php'; ?>

<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    .table th { background-color: #f2f2f2; }
    .table tr:nth-child(even){background-color: #f9f9f9;}
    .status-draft { color: #6c757d; font-weight: bold; }
    .status-sent { color: #007bff; font-weight: bold; }
    .status-approved { color: #28a745; font-weight: bold; }
    .status-rejected { color: #dc3545; font-weight: bold; }
</style>

<div class="page-header">
    <h1><?php echo $title; ?></h1>
    <a href="<?php echo URL_ROOT; ?>/public/quotes/add" class="btn btn-primary">Add Quote</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Quote #</th>
            <th>Customer</th>
            <th>Date</th>
            <th>Expiry Date</th>
            <th>Total</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($quotes as $quote) : ?>
            <tr>
                <td><?php echo htmlspecialchars($quote->quote_number); ?></td>
                <td><?php echo htmlspecialchars($quote->customer_name); ?></td>
                <td><?php echo date('Y-m-d', strtotime($quote->quote_date)); ?></td>
                <td><?php echo date('Y-m-d', strtotime($quote->expiry_date)); ?></td>
                <td><?php echo number_format($quote->total, 2); ?></td>
                <td><span class="status-<?php echo htmlspecialchars($quote->status); ?>"><?php echo ucfirst($quote->status); ?></span></td>
                <td>
                    <a href="<?php echo URL_ROOT; ?>/public/quotes/view/<?php echo $quote->id; ?>" class="btn btn-secondary btn-sm">View</a>
                    <!-- Edit and Delete buttons can be added here later -->
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once APP_ROOT . '/src/Views/inc/footer.php'; ?>
