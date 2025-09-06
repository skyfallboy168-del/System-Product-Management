<?php require_once APP_ROOT . '/src/Views/inc/header.php'; ?>

<style>
    .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }
    .invoice-box table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
    .invoice-box table td { padding: 5px; vertical-align: top; }
    .invoice-box table tr.top table td { padding-bottom: 20px; }
    .invoice-box table tr.information table td { padding-bottom: 40px; }
    .invoice-box table tr.heading td { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; }
    .invoice-box table tr.details td { padding-bottom: 20px; }
    .invoice-box table tr.item td{ border-bottom: 1px solid #eee; }
    .invoice-box table tr.item.last td { border-bottom: none; }
    .invoice-box table tr.total td:nth-child(2) { border-top: 2px solid #eee; font-weight: bold; }
    .text-right { text-align: right; }
    .payment-form { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
</style>

<div class="invoice-box">
    <table>
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td>
                            <strong>INVOICE</strong><br>
                            Invoice #: <?php echo htmlspecialchars($invoice->invoice_number); ?><br>
                            Created: <?php echo date('F j, Y', strtotime($invoice->invoice_date)); ?><br>
                            Due: <?php echo date('F j, Y', strtotime($invoice->due_date)); ?>
                        </td>
                        <td>
                            <!-- Company address can be added from a settings module later -->
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="information">
            <td colspan="2">
                <table>
                    <tr>
                        <td>
                            <strong>Bill To:</strong><br>
                            <?php echo htmlspecialchars($customer->name); ?><br>
                            <?php if(!empty($customer->address_line_1)) echo htmlspecialchars($customer->address_line_1) . '<br>'; ?>
                            <?php if(!empty($customer->city)) echo htmlspecialchars($customer->city) . ', '; ?>
                            <?php if(!empty($customer->state)) echo htmlspecialchars($customer->state) . ' '; ?>
                            <?php if(!empty($customer->postal_code)) echo htmlspecialchars($customer->postal_code) . '<br>'; ?>
                            <?php if(!empty($customer->country)) echo htmlspecialchars($customer->country); ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="heading">
            <td>Item</td>
            <td class="text-right">Total</td>
        </tr>
        <?php foreach($items as $item): ?>
        <tr class="item">
            <td><?php echo htmlspecialchars($item->description); ?> (<?php echo $item->quantity; ?> x $<?php echo number_format($item->unit_price, 2); ?>)</td>
            <td class="text-right">$<?php echo number_format($item->total, 2); ?></td>
        </tr>
        <?php endforeach; ?>

        <tr class="total">
            <td></td>
            <td class="text-right">Subtotal: $<?php echo number_format($invoice->subtotal, 2); ?></td>
        </tr>
        <tr class="total">
            <td></td>
            <td class="text-right">Tax: $<?php echo number_format($invoice->tax_amount, 2); ?></td>
        </tr>
        <tr class="total">
            <td></td>
            <td class="text-right"><strong>Total: $<?php echo number_format($invoice->total, 2); ?></strong></td>
        </tr>
         <tr class="total">
            <td></td>
            <td class="text-right">Amount Paid: $<?php echo number_format($invoice->amount_paid, 2); ?></td>
        </tr>
         <tr class="total" style="font-size: 1.2em;">
            <td></td>
            <td class="text-right"><strong>Balance Due: $<?php echo number_format($invoice->total - $invoice->amount_paid, 2); ?></strong></td>
        </tr>
    </table>

    <!-- Payments Section -->
    <div class="payment-form">
        <h3>Payments</h3>
        <?php if(!empty($payments)): ?>
        <table>
            <tr class="heading">
                <td>Date</td>
                <td>Method</td>
                <td class="text-right">Amount</td>
            </tr>
            <?php foreach($payments as $payment): ?>
            <tr class="item">
                <td><?php echo date('F j, Y', strtotime($payment->payment_date)); ?></td>
                <td><?php echo htmlspecialchars($payment->payment_method); ?></td>
                <td class="text-right">$<?php echo number_format($payment->amount, 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
        <p>No payments have been recorded for this invoice.</p>
        <?php endif; ?>

        <!-- Add Payment Form -->
        <?php if(($invoice->total - $invoice->amount_paid) > 0): ?>
        <h4 style="margin-top: 20px;">Add a Payment</h4>
        <form action="<?php echo URL_ROOT; ?>/public/payments/add" method="post">
            <input type="hidden" name="invoice_id" value="<?php echo $invoice->id; ?>">
            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="number" name="amount" step="0.01" max="<?php echo ($invoice->total - $invoice->amount_paid); ?>" required>
            </div>
            <div class="form-group">
                <label for="payment_date">Payment Date:</label>
                <input type="date" name="payment_date" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="form-group">
                <label for="payment_method">Payment Method:</label>
                <input type="text" name="payment_method" placeholder="e.g., Bank Transfer, Credit Card">
            </div>
             <div class="form-group">
                <label for="notes">Notes:</label>
                <textarea name="notes" rows="2"></textarea>
            </div>
            <input type="submit" value="Add Payment" class="btn">
        </form>
        <?php endif; ?>
    </div>
</div>

<?php require_once APP_ROOT . '/src/Views/inc/footer.php'; ?>
