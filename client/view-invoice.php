<?php
require_once dirname(__DIR__) . '/server/includes/functions.php';
require_once dirname(__DIR__) . '/server/includes/template-loader.php';

$inv = null;
if (!empty($_GET['id'])) {
    $inv = loadInvoice($_GET['id']);
}

$isPrintMode = !empty($_GET['print']);

if (!$inv) {
    echo '<!DOCTYPE html><html><head><title>Not Found</title><link rel="stylesheet" href="css/style.css"></head><body>';
    echo '<nav><div class="logo"><span class="rupee">₹</span> GST Invoice</div></nav>';
    echo '<div class="container"><div class="alert alert-error">Invoice not found. <a href="index.php">Go home</a></div></div></body></html>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title><?= sanitize($inv['invoice_number'] ?? 'Invoice') ?> — GST Invoice</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    @media print {
      .no-print { display:none !important; }
      body { background: #fff; }
      .container { padding: 0; max-width: 100%; }
      .card { box-shadow: none; padding: 0; border-radius: 0; border: none; }
    }
    @page { size: A4 portrait; margin: 10mm; }
  </style>
</head>
<body <?= $isPrintMode ? 'onload="window.print()"' : '' ?>>

<?php if (!$isPrintMode): ?>
<nav class="no-print">
  <div class="logo"><span class="rupee">₹</span> GST Invoice Manager</div>
  <ul>
    <li><a href="index.php">🏠 Home</a></li>
    <li><a href="create-invoice.php">📝 New Invoice</a></li>
    <li><a href="templates.php">🎨 Templates</a></li>
  </ul>
</nav>
<?php endif; ?>

<div class="container">
  <?php if (!$isPrintMode): ?>

  <?php if (!empty($_GET['saved'])): ?>
  <div class="alert alert-success no-print">✅ Invoice saved successfully!</div>
  <?php endif; ?>

  <div class="no-print" style="display:flex;gap:0.75rem;flex-wrap:wrap;margin-bottom:1.25rem">
    <button onclick="window.print()" class="btn btn-print">🖨 Print Invoice</button>
    <a href="?id=<?= urlencode($inv['invoice_number'] ?? '') ?>&print=1" class="btn btn-pdf" target="_blank">📄 Print/PDF View</a>
    <a href="index.php" class="btn btn-secondary">← Back to Dashboard</a>
  </div>

  <?php endif; ?>

  <div class="card invoice-output" style="padding:0;overflow:hidden">
    <?php renderInvoice($inv); ?>
  </div>
</div>
</body>
</html>
