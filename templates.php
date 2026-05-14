<?php
require_once 'includes/functions.php';
require_once 'includes/template-loader.php';
$templates = getTemplates();

// Demo invoice data
$demoInv = [
    'company_name'    => 'Roshan Traders',
    'company_logo'    => '',
    'company_address' => "4, Kalladi Chidambarapuram Road\nNallanathanpuram, Tirunelveli\nTamil Nadu - 627502",
    'gst_number'      => '33APAPR0776B3Z7',
    'company_phone'   => '+91 99999 00000',
    'company_email'   => 'roshan@example.com',
    'bank_account'    => '279150310875046',
    'bank_ifsc'       => 'TMBL0000279',
    'bank_branch'     => 'KALAKAD',
    'proprietor'      => 'Proprietor',
    'invoice_number'  => 'INV/2026-02/DEMO',
    'invoice_title'   => 'Tax Invoice',
    'invoice_date'    => '24/02/2026',
    'bill_to'         => 'Saranya, THIRUVAROOR',
    'vehicle_number'  => 'TN 52 B 7889',
    'items' => [
        ['serial'=>1,'description'=>'Bricks','hsn'=>'69041000','quantity'=>8000,'per_unit'=>'Nos','rate'=>5,'base'=>40000,'cgst'=>1200,'sgst'=>1200,'amount'=>42400],
        ['serial'=>2,'description'=>'Cement Bags','hsn'=>'25232100','quantity'=>50,'per_unit'=>'Bags','rate'=>380,'base'=>19000,'cgst'=>570,'sgst'=>570,'amount'=>20140],
    ],
    'totals' => [
        'subtotal'   => 59000,
        'cgst_total' => 1770,
        'sgst_total' => 1770,
        'total'      => 62540,
    ],
    'payment_made' => 62540,
    'balance_due'  => 0,
    'amount_words' => 'Sixty Two Thousand Five Hundred and Forty Only',
    'notes'        => 'Note: Tax charged at 6% as per Notification No. 02/2022-Central Tax (Rate) without availment of Input Tax Credit',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Invoice Templates — GST Invoice Manager</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .tpl-showcase { margin-bottom: 3rem; }
    .tpl-showcase h3 { font-size: 1.15rem; font-weight: 700; margin-bottom: 0.3rem; }
    .tpl-preview-frame { overflow: hidden; height: 440px; border: 2px solid #e0e0e0; border-radius: 10px; position: relative; }
    .tpl-preview-inner { transform: scale(0.55); transform-origin: top left; width: 181.8%; height: 181.8%; pointer-events: none; }
    .tpl-actions { margin-top: 0.75rem; display: flex; align-items: center; gap: 0.75rem; }
  </style>
</head>
<body>
<nav>
  <div class="logo"><span class="rupee">₹</span> GST Invoice Manager</div>
  <ul>
    <li><a href="index.php">🏠 Home</a></li>
    <li><a href="create-invoice.php">📝 New Invoice</a></li>
    <li><a href="templates.php" class="active">🎨 Templates</a></li>
  </ul>
</nav>

<div class="container">
  <div class="card">
    <div class="card-header">
      <h2>🎨 All 10 Invoice Templates</h2>
      <a href="create-invoice.php" class="btn btn-primary">Create Invoice</a>
    </div>
    <p style="color:#666;margin-bottom:2rem;font-size:0.9rem">All templates include full GST compliance: CGST 3% + SGST 3%, Indian number formatting, and amount-in-words.</p>

    <?php
    $tColors = ['#1a1a2e','#003087','#1b5e20','#222','#7b0000','#4a0072','#004d40','#0d47a1','#3e2723','#263238'];
    $i = 0;
    foreach ($templates as $key => $tpl):
    $inv = $demoInv;
    $inv['template'] = $key;
    $color = $tColors[$i++];
    ?>
    <div class="tpl-showcase">
      <h3 style="color:<?= $color ?>"><?= ($i) ?>. <?= $tpl['name'] ?></h3>
      <div class="tpl-preview-frame">
        <div class="tpl-preview-inner">
          <?php renderInvoice($inv); ?>
        </div>
      </div>
      <div class="tpl-actions">
        <a href="create-invoice.php" class="btn btn-outline btn-sm">Use This Template</a>
        <span class="badge" style="background:<?= $color ?>20;color:<?= $color ?>"><?= $key ?></span>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>
