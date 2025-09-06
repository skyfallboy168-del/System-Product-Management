<?php require_once APP_ROOT . '/src/Views/inc/header.php'; ?>

<style>
    .quote-box {
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
    .quote-box table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
    .quote-box table td { padding: 5px; vertical-align: top; }
    .quote-box table tr.top table td { padding-bottom: 20px; }
    .quote-box table tr.information table td { padding-bottom: 40px; }
    .quote-box table tr.heading td { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; }
    .quote-box table tr.item td{ border-bottom: 1px solid #eee; }
    .quote-box table tr.total td:nth-child(2) { border-top: 2px solid #eee; font-weight: bold; }
    .text-right { text-align: right; }
    .actions-box { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
</style>

<div class="quote-box">
    <table>
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td>
                            <strong>QUOTE</strong><br>
                            Quote #: <?php echo htmlspecialchars($quote->quote_number); ?><br>
                            Date: <?php echo date('F j, Y', strtotime($quote->quote_date)); ?><br>
                            Expires: <?php echo date('F j, Y', strtotime($quote->expiry_date)); ?>
                        </td>
                        <td>
                            <!-- Company address -->
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
                            <strong>Quote For:</strong><br>
                            <?php echo htmlspecialchars($customer->name); ?><br>
                            <?php if(!empty($customer->address_line_1)) echo htmlspecialchars($customer->address_line_1) . '<br>'; ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="heading">
            <td>Item Description</td>
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
            <td class="text-right">Subtotal: $<?php echo number_format($quote->subtotal, 2); ?></td>
        </tr>
        <tr class="total">
            <td></td>
            <td class="text-right">Tax: $<?php echo number_format($quote->tax_amount, 2); ?></td>
        </tr>
        <tr class="total" style="font-size: 1.2em;">
            <td></td>
            <td class="text-right"><strong>Total: $<?php echo number_format($quote->total, 2); ?></strong></td>
        </tr>
    </table>

    <div class="actions-box">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <form action="<?php echo URL_ROOT; ?>/public/quotes/updateStatus/<?php echo $quote->id; ?>" method="post" style="display: inline-flex; align-items: center; gap: 10px;">
                    <label for="status" style="margin-bottom: 0;"><strong>Status:</strong></label>
                    <select name="status" id="status">
                        <option value="draft" <?php if($quote->status == 'draft') echo 'selected'; ?>>Draft</option>
                        <option value="sent" <?php if($quote->status == 'sent') echo 'selected'; ?>>Sent</option>
                        <option value="approved" <?php if($quote->status == 'approved') echo 'selected'; ?>>Approved</option>
                        <option value="rejected" <?php if($quote->status == 'rejected') echo 'selected'; ?>>Rejected</option>
                        <option value="expired" <?php if($quote->status == 'expired') echo 'selected'; ?>>Expired</option>
                    </select>
                    <input type="submit" value="Update Status" class="btn btn-secondary btn-sm">
                </form>
            </div>
            <div>
                <?php if($quote->status == 'approved'): ?>
                    <a href="<?php echo URL_ROOT; ?>/public/invoices/createFromQuote/<?php echo $quote->id; ?>" class="btn btn-success">Convert to Invoice</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/src/Views/inc/footer.php'; ?>
