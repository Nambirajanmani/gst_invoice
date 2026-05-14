<?php
require_once 'includes/functions.php';
$recent = getRecentInvoices(8);
$allInv = getRecentInvoices(1000);
$totalRevenue = array_sum(array_column($allInv, array_key_exists('totals', $allInv[0] ?? []) ? 'dummy' : 'dummy'));
$revenue = 0;
foreach ($allInv as $a) $revenue += (float)($a['totals']['total'] ?? 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>GST Invoice Manager — Tirunelveli</title>
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
    <p>Create compliant Tax Invoices with CGST, SGST calculations. 10 professional templates. No database needed.</p>
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
        <div style="display:flex;align-items:center;gap:1rem">
          <div class="inv-amount"><?= formatIndianCurrency($inv['totals']['total'] ?? 0) ?></div>
          <a href="view-invoice.php?id=<?= urlencode($inv['invoice_number'] ?? '') ?>" class="btn btn-outline btn-sm">👁 View</a>
          <a href="view-invoice.php?id=<?= urlencode($inv['invoice_number'] ?? '') ?>&print=1" class="btn btn-print btn-sm">🖨 Print</a>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </div>

  <div class="card" style="background:#fffde7;border:1px solid #ffe082">
    <h3 style="color:#e65100;margin-bottom:0.5rem">⚡ Quick Start</h3>
    <p style="font-size:0.9rem;color:#555;line-height:1.7">
      1. Click <strong>New Invoice</strong> → 2. Fill in company & customer details → 3. Add items (GST auto-calculated) → 4. Choose template → 5. Save & Print. All invoices are stored as JSON files — no database required. Supports Indian number formatting (₹ with lakhs/crores) and amount-to-words in Indian English.
    </p>
  </div>
</div>
</body>
</html>
