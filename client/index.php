<?php
require_once dirname(__DIR__) . '/server/includes/functions.php';
$recent = getRecentInvoices(8);
$allInv = getRecentInvoices(1000);
$revenue = 0;
foreach ($allInv as $a) $revenue += (float)($a['totals']['total'] ?? 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>GST Invoice Manager — Client</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav>
  <div class="logo">
    <span class="rupee">₹</span>
    GST Invoice Manager
  </div>
  <ul>
    <li><a href="index.php" class="active">🏠 Home</a></li>
    <li><a href="create-invoice.php">📝 New Invoice</a></li>
    <li><a href="templates.php">🎨 Templates</a></li>
  </ul>
</nav>

<div class="container">
  <div class="hero">
    <h1>Professional GST Invoice Generator</h1>
    <p>Create compliant Tax Invoices with CGST, SGST calculations & 10 professional templates.</p>
    <a href="create-invoice.php" class="btn btn-orange btn-lg">➕ Create New Invoice</a>
  </div>

  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-val"><?= count($allInv) ?></div>
      <div class="stat-lbl">Total Invoices</div>
    </div>
    <div class="stat-card" style="border-top-color:#1b5e20">
      <div class="stat-val" style="color:#1b5e20;font-size:1.1rem"><?= formatIndianCurrency($revenue) ?></div>
      <div class="stat-lbl">Total Invoiced</div>
    </div>
    <div class="stat-card" style="border-top-color:#e65100">
      <div class="stat-val" style="color:#e65100">10</div>
      <div class="stat-lbl">Templates Available</div>
    </div>
    <div class="stat-card" style="border-top-color:#7b0000">
      <div class="stat-val" style="color:#7b0000">6%</div>
      <div class="stat-lbl">GST Rate (3+3)</div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h2>📋 Recent Invoices</h2>
      <a href="create-invoice.php" class="btn btn-primary">+ New Invoice</a>
    </div>
    <?php if (empty($recent)): ?>
    <div class="empty-state">
      <div class="icon">📄</div>
      <p style="margin-bottom:1rem">No invoices yet!</p>
      <a href="create-invoice.php" class="btn btn-primary">Create your first Tax Invoice</a>
    </div>
    <?php else: ?>
    <ul class="inv-list">
      <?php foreach ($recent as $inv): ?>
      <li class="inv-item">
        <div>
          <div class="inv-num"><?= sanitize($inv['invoice_number'] ?? '') ?></div>
          <div class="inv-sub"><?= sanitize($inv['bill_to'] ?? '') ?> • <?= sanitize($inv['invoice_date'] ?? '') ?></div>
          <div style="margin-top:3px">
            <span class="badge badge-blue"><?= sanitize($inv['template'] ?? 't01') ?></span>
            <?php if (!empty($inv['vehicle_number'])): ?>
            <span class="badge badge-orange" style="margin-left:4px">🚛 <?= sanitize($inv['vehicle_number']) ?></span>
            <?php endif; ?>
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:0.5rem">
          <div class="inv-amount" style="margin-right:0.5rem"><?= formatIndianCurrency($inv['totals']['total'] ?? 0) ?></div>
          <a href="view-invoice.php?id=<?= urlencode($inv['invoice_number'] ?? '') ?>" class="btn btn-outline btn-sm">👁 View</a>
          <a href="view-invoice.php?id=<?= urlencode($inv['invoice_number'] ?? '') ?>&print=1" class="btn btn-print btn-sm">🖨 Print</a>
          <button onclick="handleDeleteInvoice('<?= sanitize($inv['invoice_number'] ?? '') ?>')" class="btn btn-danger btn-sm" title="Delete Invoice">🗑 Delete</button>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </div>
</div>
<script src="js/api.js"></script>
<script>
function showConfirmModal(invNumber, onConfirm) {
  let modal = document.getElementById('custom-confirm-overlay');
  if (!modal) {
    modal = document.createElement('div');
    modal.id = 'custom-confirm-overlay';
    modal.className = 'custom-modal-overlay';
    modal.innerHTML = `
      <div class="custom-modal-box">
        <div class="custom-modal-icon-wrap" style="background:#ffebee;color:#c62828;">🗑</div>
        <h3 class="custom-modal-title">Delete Invoice</h3>
        <div class="custom-modal-content" style="background:#fff8f8;border-color:#ffcdd2;text-align:center;">
          Are you sure you want to permanently delete invoice <strong id="confirm-inv-num"></strong>?
        </div>
        <div class="custom-modal-actions">
          <button type="button" id="confirm-btn-yes" class="btn btn-danger btn-lg">Yes, Delete</button>
          <button type="button" id="confirm-btn-no" class="btn btn-secondary btn-lg">Cancel</button>
        </div>
      </div>
    `;
    document.body.appendChild(modal);
    document.getElementById('confirm-btn-no').addEventListener('click', () => { modal.style.display = 'none'; });
    modal.addEventListener('click', (e) => { if (e.target === modal) modal.style.display = 'none'; });
  }

  document.getElementById('confirm-inv-num').textContent = invNumber;
  const yesBtn = document.getElementById('confirm-btn-yes');
  yesBtn.onclick = () => {
    modal.style.display = 'none';
    onConfirm();
  };
  modal.style.display = 'flex';
}

async function handleDeleteInvoice(invNumber) {
  showConfirmModal(invNumber, async () => {
    const result = await deleteInvoiceApi(invNumber);
    if (result && result.success) {
      window.location.reload();
    } else {
      alert(result?.error || 'Failed to delete invoice');
    }
  });
}
</script>
</body>
</html>


