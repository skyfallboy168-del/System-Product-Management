<?php require_once APP_ROOT . '/src/Views/inc/header.php'; ?>

<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    .table th { background-color: #f2f2f2; }
    .table tr:nth-child(even){background-color: #f9f9f9;}
    .status-draft { color: #6c757d; font-weight: bold; }
    .status-sent { color: #007bff; font-weight: bold; }
    .status-paid { color: #28a745; font-weight: bold; }
    .status-partially_paid { color: #fd7e14; font-weight: bold; }
    .status-overdue { color: #dc3545; font-weight: bold; }
</style>

<div class="page-header">
    <h1><?php echo $title; ?></h1>
    <a href="<?php echo URL_ROOT; ?>/public/invoices/add" class="btn btn-primary">Add Invoice</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Invoice #</th>
            <th>Customer</th>
            <th>Date</th>
            <th>Due Date</th>
            <th>Total</th>
            <th>Amount Paid</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($invoices as $invoice) : ?>
            <tr>
                <td><?php echo htmlspecialchars($invoice->invoice_number); ?></td>
                <td><?php echo htmlspecialchars($invoice->customer_name); ?></td>
                <td><?php echo date('Y-m-d', strtotime($invoice->invoice_date)); ?></td>
                <td><?php echo date('Y-m-d', strtotime($invoice->due_date)); ?></td>
                <td><?php echo number_format($invoice->total, 2); ?></td>
                <td><?php echo number_format($invoice->amount_paid, 2); ?></td>
                <td><span class="status-<?php echo str_replace(' ', '_', strtolower($invoice->status)); ?>"><?php echo ucfirst($invoice->status); ?></span></td>
                <td>
                    <a href="<?php echo URL_ROOT; ?>/public/invoices/view/<?php echo $invoice->id; ?>" class="btn btn-secondary btn-sm">View</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once APP_ROOT . '/src/Views/inc/footer.php'; ?>
