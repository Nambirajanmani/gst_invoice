<?php
require_once 'includes/functions.php';
require_once 'includes/template-loader.php';

$inv = null;
$isPreview = false;

if (!empty($_GET['preview'])) {
    session_start();
    $inv = $_SESSION['preview_invoice'] ?? null;
    $isPreview = true;
} elseif (!empty($_GET['id'])) {
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

  <?php if ($isPreview): ?>
  <div class="alert alert-info no-print">👁 <strong>Preview Mode</strong> — Not saved yet.</div>
  <?php endif; ?>

  <div class="no-print" style="display:flex;gap:0.75rem;flex-wrap:wrap;margin-bottom:1.25rem">
    <button onclick="window.print()" class="btn btn-print">🖨 Print Invoice</button>
    <a href="?id=<?= urlencode($inv['invoice_number'] ?? '') ?>&print=1" class="btn btn-pdf" target="_blank">📄 Print/PDF View</a>
    <?php if ($isPreview): ?>
    <form method="POST" action="save-invoice.php" style="display:inline">
      <?php
      // Re-post all data to save
      $flat = $inv;
      foreach (['items','totals'] as $skip) unset($flat[$skip]);
      foreach ($flat as $k => $v) {
          if (!is_array($v)) echo '<input type="hidden" name="'.htmlspecialchars($k).'" value="'.htmlspecialchars($v).'">';
      }
      foreach ($inv['items'] ?? [] as $i => $item) {
          foreach ($item as $k => $v)
              echo '<input type="hidden" name="items['.$i.']['.$k.']" value="'.htmlspecialchars($v).'">';
      }
      ?>
      <input type="hidden" name="payment_made" value="<?= $inv['payment_made'] ?? 0 ?>">
      <input type="hidden" name="action" value="save">
      <button type="submit" class="btn btn-success">💾 Save Invoice</button>
    </form>
    <?php endif; ?>
    <a href="index.php" class="btn btn-secondary">← Back</a>
  </div>

  <?php endif; ?>

  <div class="card invoice-output" style="padding:0;overflow:hidden">
    <?php renderInvoice($inv); ?>
  </div>
</div>
</body>
</html>
