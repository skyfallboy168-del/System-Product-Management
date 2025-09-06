<?php require_once APP_ROOT . '/src/Views/inc/header.php'; ?>

<h2><?php echo $title; ?></h2>
<p>Manage your company's application-wide settings here.</p>

<form action="<?php echo URL_ROOT; ?>/public/settings" method="post">
    <div class="form-section">
        <h3>General</h3>
        <div class="form-group">
            <label for="currency_symbol">Currency Symbol:</label>
            <input type="text" name="currency_symbol" value="<?php echo htmlspecialchars($currency_symbol); ?>">
        </div>
    </div>

    <div class="form-section">
        <h3>Taxes</h3>
        <div class="form-group">
            <label for="default_tax_rate">Default Tax Rate (%):</label>
            <input type="number" step="0.01" name="default_tax_rate" value="<?php echo htmlspecialchars($default_tax_rate); ?>">
        </div>
    </div>

    <div class="form-section">
        <h3>Numbering</h3>
        <div class="form-group">
            <label for="quote_prefix">Quote Number Prefix:</label>
            <input type="text" name="quote_prefix" value="<?php echo htmlspecialchars($quote_prefix); ?>">
        </div>
        <div class="form-group">
            <label for="invoice_prefix">Invoice Number Prefix:</label>
            <input type="text" name="invoice_prefix" value="<?php echo htmlspecialchars($invoice_prefix); ?>">
        </div>
    </div>

    <input type="submit" class="btn btn-primary" value="Save Settings">
</form>

<style>
    .form-group { margin-bottom: 15px; max-width: 400px; }
    .form-section { border: 1px solid #ddd; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
    .form-section h3 { margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
    label { display: block; margin-bottom: 5px; font-weight: bold; }
    input[type="text"], input[type="number"] {
        width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;
    }
</style>

<?php require_once APP_ROOT . '/src/Views/inc/footer.php'; ?>
