<?php
require_once 'includes/functions.php';
$templates = getTemplates();
$today = date('d/m/Y');
$invNum = generateInvoiceNumber();

// Pre-fill from session (after preview or failed validation)
session_start();
$prefill = $_SESSION['last_form'] ?? [];
$invoiceFormErrors = $_SESSION['invoice_form_errors'] ?? [];
unset($_SESSION['invoice_form_errors']);

$prefillItems = [];
if (!empty($prefill['items']) && is_array($prefill['items'])) {
    foreach ($prefill['items'] as $row) {
        if (is_array($row)) {
            $prefillItems[] = $row;
        }
    }
}
if (empty($prefillItems)) {
    $prefillItems[] = [];
}
$selectedTemplate = $prefill['template'] ?? 't01';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Create Tax Invoice — GST Invoice Manager</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav>
  <div class="logo"><span class="rupee">₹</span> GST Invoice Manager</div>
  <ul>
    <li><a href="index.php">🏠 Home</a></li>
    <li><a href="create-invoice.php" class="active">📝 New Invoice</a></li>
    <li><a href="templates.php">🎨 Templates</a></li>
  </ul>
</nav>

<div class="container">
<?php if (!empty($_GET['error'])): ?>
<div class="alert alert-error">❌ <?= sanitize($_GET['error']) ?></div>
<?php endif; ?>
<?php if (!empty($invoiceFormErrors)): ?>
<div class="alert alert-error" id="validation-summary" role="alert">Please fix the highlighted fields below.</div>
<?php endif; ?>

<form method="POST" action="save-invoice.php" enctype="multipart/form-data" id="mainForm" novalidate>
  <input type="hidden" name="invoice_number" value="<?= sanitize($invNum) ?>">

  <!-- TEMPLATE SELECTION -->
  <div class="card">
    <div class="card-header"><h2>🎨 Choose Template</h2><a href="templates.php" class="btn btn-outline btn-sm" target="_blank">Preview All →</a></div>
    <style>
    .tpl-mini{display:grid;grid-template-columns:repeat(5,1fr);gap:10px;}
    @media(max-width:700px){.tpl-mini{grid-template-columns:repeat(3,1fr);}}
    .tpl-opt{position:relative;}
    .tpl-opt input{position:absolute;opacity:0;pointer-events:none;}
    .tpl-opt label{display:block;cursor:pointer;border:2px solid #ddd;border-radius:8px;overflow:hidden;transition:all .2s;}
    .tpl-opt input:checked+label{border-color:var(--primary);box-shadow:0 0 0 3px rgba(26,35,126,.15);}
    .tpl-thumb-svg{height:72px;display:block;width:100%;}
    .tpl-lbl{padding:5px 6px;text-align:center;border-top:1px solid #eee;}
    .tpl-lbl .tn{font-size:.72rem;font-weight:700;display:block;}
    .tpl-lbl .td{font-size:.62rem;color:#888;display:block;margin-top:1px;}
    </style>
    <div class="tpl-mini">
<?php foreach ($templates as $key => $tpl): $c=$tpl['color']; ?>
      <div class="tpl-opt">
        <input type="radio" name="template" id="<?=$key?>" value="<?=$key?>" <?=$key === $selectedTemplate ? 'checked' : ''?>>
        <label for="<?=$key?>">
          <svg class="tpl-thumb-svg" viewBox="0 0 120 72" xmlns="http://www.w3.org/2000/svg">
          <?php if($key==='t01'): // Classic Header: logo left, text right ?>
            <rect width="120" height="72" fill="#fff"/>
            <rect width="120" height="22" fill="<?=$c?>"/>
            <rect x="4" y="4" width="18" height="14" rx="2" fill="#fff" opacity=".3"/>
            <rect x="26" y="6" width="40" height="4" rx="1" fill="#fff"/>
            <rect x="26" y="12" width="28" height="2" rx="1" fill="#fff" opacity=".6"/>
            <rect x="80" y="6" width="34" height="5" rx="1" fill="#fff" opacity=".8"/>
            <rect x="4" y="24" width="110" height="6" fill="<?=$c?>" opacity=".15"/>
            <rect x="4" y="32" width="110" height="2" fill="<?=$c?>"/>
            <rect x="4" y="36" width="110" height="1.5" fill="#eee"/>
            <rect x="4" y="39" width="110" height="1.5" fill="#eee"/>
            <rect x="4" y="42" width="110" height="1.5" fill="#eee"/>
            <rect x="4" y="45" width="110" height="1.5" fill="#eee"/>
            <rect x="60" y="55" width="54" height="12" fill="<?=$c?>" opacity=".1"/>
            <rect x="80" y="57" width="34" height="2" rx="1" fill="<?=$c?>"/>
            <rect x="80" y="61" width="34" height="2" rx="1" fill="<?=$c?>" opacity=".6"/>
          <?php elseif($key==='t02'): // Dark Sidebar ?>
            <rect width="120" height="72" fill="#f8f9fa"/>
            <rect width="32" height="72" fill="<?=$c?>"/>
            <rect x="6" y="5" width="20" height="16" rx="8" fill="#fff" opacity=".2"/>
            <rect x="4" y="24" width="24" height="2" rx="1" fill="#fff" opacity=".6"/>
            <rect x="4" y="28" width="18" height="1.5" rx="1" fill="#fff" opacity=".4"/>
            <rect x="4" y="32" width="22" height="1.5" rx="1" fill="#fff" opacity=".4"/>
            <rect x="4" y="38" width="24" height="8" rx="3" fill="#fff" opacity=".15"/>
            <rect x="34" y="4" width="82" height="10" fill="<?=$c?>" opacity=".1"/>
            <rect x="34" y="16" width="82" height="2" fill="<?=$c?>"/>
            <rect x="34" y="20" width="82" height="1.5" fill="#ddd"/>
            <rect x="34" y="23" width="82" height="1.5" fill="#ddd"/>
            <rect x="34" y="26" width="82" height="1.5" fill="#ddd"/>
            <rect x="34" y="29" width="82" height="1.5" fill="#ddd"/>
            <rect x="34" y="32" width="82" height="1.5" fill="#ddd"/>
            <rect x="70" y="54" width="44" height="14" fill="<?=$c?>" opacity=".08"/>
            <rect x="80" y="57" width="30" height="2" rx="1" fill="<?=$c?>"/>
            <rect x="80" y="62" width="30" height="2" rx="1" fill="<?=$c?>"/>
          <?php elseif($key==='t03'): // Centered logo ?>
            <rect width="120" height="72" fill="#fff"/>
            <rect x="46" y="3" width="28" height="16" rx="8" fill="<?=$c?>" opacity=".15"/>
            <circle cx="60" cy="11" r="7" fill="<?=$c?>" opacity=".25"/>
            <rect x="20" y="21" width="80" height="4" rx="1" fill="<?=$c?>"/>
            <rect x="4" y="27" width="112" height="6" fill="<?=$c?>" opacity=".12"/>
            <rect x="4" y="35" width="112" height="2" fill="<?=$c?>"/>
            <rect x="4" y="39" width="112" height="1.5" fill="#e8f5e9"/>
            <rect x="4" y="42" width="112" height="1.5" fill="#e8f5e9"/>
            <rect x="4" y="45" width="112" height="1.5" fill="#e8f5e9"/>
            <rect x="55" y="55" width="58" height="12" fill="<?=$c?>" opacity=".1"/>
            <rect x="65" y="58" width="44" height="2" rx="1" fill="<?=$c?>"/>
          <?php elseif($key==='t04'): // Minimal ?>
            <rect width="120" height="72" fill="#fff"/>
            <rect x="4" y="4" width="15" height="14" rx="2" fill="#f0f0f0" stroke="#ddd" stroke-width="1"/>
            <rect x="22" y="6" width="45" height="3" rx="1" fill="#222"/>
            <rect x="22" y="11" width="30" height="2" rx="1" fill="#999"/>
            <rect x="90" y="4" width="25" height="6" rx="1" fill="#f0f0f0" stroke="#222" stroke-width="1.5"/>
            <rect x="4" y="22" width="112" height="1.5" fill="#222"/>
            <rect x="4" y="26" width="112" height="1" fill="#eee"/>
            <rect x="4" y="29" width="112" height="1" fill="#eee"/>
            <rect x="4" y="32" width="112" height="1" fill="#eee"/>
            <rect x="4" y="35" width="112" height="1" fill="#eee"/>
            <rect x="4" y="38" width="112" height="1" fill="#eee"/>
            <rect x="4" y="43" width="112" height="1.5" fill="#222"/>
            <rect x="65" y="55" width="48" height="12" fill="#f5f5f5"/>
            <rect x="68" y="58" width="40" height="1.5" rx="1" fill="#999"/>
            <rect x="68" y="62" width="40" height="1.5" rx="1" fill="#222"/>
          <?php elseif($key==='t05'): // Bold Crimson ?>
            <rect width="120" height="72" fill="#fff"/>
            <rect width="120" height="6" fill="#ffcc02"/>
            <rect y="6" width="120" height="22" fill="<?=$c?>"/>
            <rect x="4" y="8" width="80" height="8" rx="1" fill="#fff" opacity=".15"/>
            <rect x="4" y="18" width="50" height="2" rx="1" fill="#fff" opacity=".5"/>
            <rect x="95" y="8" width="20" height="16" rx="3" fill="#fff" opacity=".15"/>
            <rect y="28" width="120" height="6" fill="#ffcc02"/>
            <rect y="34" width="120" height="3" fill="#222"/>
            <rect x="4" y="39" width="112" height="1.5" fill="#fff0f0"/>
            <rect x="4" y="42" width="112" height="1.5" fill="#fff0f0"/>
            <rect x="4" y="45" width="112" height="1.5" fill="#fff0f0"/>
            <rect y="54" width="120" height="18" fill="#111"/>
            <rect x="65" y="57" width="48" height="1.5" rx="1" fill="#ffcc02"/>
            <rect x="65" y="61" width="48" height="1.5" rx="1" fill="#555"/>
            <rect x="65" y="65" width="48" height="1.5" rx="1" fill="#555"/>
          <?php elseif($key==='t06'): // Card Boxed ?>
            <rect width="120" height="72" fill="#f0f4f8"/>
            <rect x="2" y="2" width="116" height="18" rx="4" fill="#fff" stroke="#d0d9e6" stroke-width="1"/>
            <rect x="5" y="5" width="12" height="12" rx="6" fill="<?=$c?>" opacity=".2"/>
            <rect x="20" y="6" width="40" height="3" rx="1" fill="<?=$c?>"/>
            <rect x="20" y="11" width="25" height="2" rx="1" fill="#888"/>
            <rect x="2" y="22" width="56" height="12" rx="3" fill="#fff" stroke="#d0d9e6" stroke-width="1"/>
            <rect x="60" y="22" width="58" height="12" rx="3" fill="#fff" stroke="#d0d9e6" stroke-width="1"/>
            <rect x="2" y="36" width="116" height="22" rx="3" fill="#fff" stroke="#d0d9e6" stroke-width="1"/>
            <rect x="2" y="36" width="116" height="5" rx="3" fill="<?=$c?>"/>
            <rect x="4" y="44" width="112" height="1.5" fill="#f0e8f8"/>
            <rect x="4" y="47" width="112" height="1.5" fill="#f0e8f8"/>
            <rect x="2" y="60" width="56" height="10" rx="3" fill="#fff" stroke="#d0d9e6" stroke-width="1"/>
            <rect x="60" y="60" width="58" height="10" rx="3" fill="#fff" stroke="#d0d9e6" stroke-width="1"/>
            <rect x="60" y="60" width="58" height="4" rx="3" fill="<?=$c?>"/>
          <?php elseif($key==='t07'): // Teal gradient with floating logo ?>
            <rect width="120" height="72" fill="#fff"/>
            <rect width="120" height="30" fill="<?=$c?>"/>
            <rect x="4" y="5" width="60" height="4" rx="1" fill="#fff" opacity=".8"/>
            <rect x="4" y="11" width="40" height="2" rx="1" fill="#fff" opacity=".5"/>
            <rect x="4" y="15" width="50" height="2" rx="1" fill="#fff" opacity=".5"/>
            <rect x="84" y="4" width="28" height="6" rx="1" fill="#fff" opacity=".15"/>
            <circle cx="20" cy="30" r="10" fill="#fff" stroke="<?=$c?>" stroke-width="2"/>
            <text x="20" y="34" text-anchor="middle" font-size="8" fill="<?=$c?>" font-weight="bold">₹</text>
            <rect x="4" y="42" width="112" height="2" fill="<?=$c?>"/>
            <rect x="4" y="46" width="112" height="1.5" fill="#e0f2f1"/>
            <rect x="4" y="49" width="112" height="1.5" fill="#e0f2f1"/>
            <rect x="4" y="52" width="112" height="1.5" fill="#e0f2f1"/>
            <rect x="4" y="55" width="112" height="1.5" fill="#e0f2f1"/>
            <rect x="55" y="61" width="58" height="8" fill="<?=$c?>" opacity=".1"/>
            <rect x="65" y="63" width="44" height="1.5" rx="1" fill="<?=$c?>"/>
          <?php elseif($key==='t08'): // Split header ?>
            <rect width="120" height="72" fill="#fff"/>
            <rect width="75" height="26" fill="#fff" stroke="#ddd" stroke-width="1"/>
            <rect x="4" y="4" width="12" height="12" rx="2" fill="#e3f2fd"/>
            <rect x="19" y="5" width="38" height="3" rx="1" fill="<?=$c?>"/>
            <rect x="19" y="10" width="25" height="2" rx="1" fill="#888"/>
            <rect x="19" y="14" width="30" height="1.5" rx="1" fill="#aaa"/>
            <rect x="75" y="0" width="45" height="26" fill="<?=$c?>"/>
            <rect x="78" y="4" width="36" height="4" rx="1" fill="#fff" opacity=".8"/>
            <rect x="78" y="10" width="30" height="2" rx="1" fill="#fff" opacity=".5"/>
            <rect x="78" y="14" width="24" height="2" rx="1" fill="#fff" opacity=".4"/>
            <rect x="78" y="18" width="36" height="5" rx="2" fill="#fff" opacity=".15"/>
            <rect x="0" y="26" width="120" height="7" fill="<?=$c?>" opacity=".1"/>
            <rect x="0" y="33" width="120" height="2" fill="<?=$c?>"/>
            <rect x="4" y="37" width="112" height="1.5" fill="#e3f2fd"/>
            <rect x="4" y="40" width="112" height="1.5" fill="#e3f2fd"/>
            <rect x="4" y="43" width="112" height="1.5" fill="#e3f2fd"/>
            <rect x="55" y="55" width="58" height="12" fill="<?=$c?>" opacity=".08"/>
            <rect x="65" y="58" width="44" height="2" rx="1" fill="<?=$c?>"/>
          <?php elseif($key==='t09'): // Ledger sepia ?>
            <rect width="120" height="72" fill="#fdf8f3"/>
            <rect width="120" height="72" fill="none" stroke="#5d4037" stroke-width="2.5"/>
            <rect x="4" y="4" width="112" height="20" fill="#fff9f0" stroke="#795548" stroke-width="1"/>
            <rect x="6" y="6" width="14" height="14" fill="#fff" stroke="#795548" stroke-width="1"/>
            <rect x="23" y="7" width="35" height="3" rx="1" fill="#3e2723"/>
            <rect x="23" y="12" width="22" height="2" rx="1" fill="#795548"/>
            <rect x="80" y="6" width="32" height="12" fill="#fff9f0" stroke="#795548" stroke-width="1"/>
            <rect x="4" y="25" width="112" height="5" fill="#f5e6c8" stroke="#a1887f" stroke-width=".5"/>
            <rect x="4" y="32" width="112" height="1.5" fill="#d7ccc8"/>
            <rect x="4" y="35" width="112" height="1.5" fill="#d7ccc8"/>
            <rect x="4" y="38" width="112" height="1.5" fill="#d7ccc8"/>
            <rect x="4" y="41" width="112" height="1.5" fill="#d7ccc8"/>
            <rect x="4" y="44" width="112" height="1.5" fill="#d7ccc8"/>
            <rect y="52" width="120" height="1.5" fill="#795548"/>
            <rect y="53.5" width="120" height="1" fill="#bcaaa4"/>
          <?php elseif($key==='t10'): // Compact ?>
            <rect width="120" height="72" fill="#fff"/>
            <rect width="120" height="5" fill="<?=$c?>"/>
            <rect x="4" y="7" width="10" height="10" rx="1" fill="<?=$c?>"/>
            <rect x="16" y="8" width="45" height="3" rx="1" fill="<?=$c?>"/>
            <rect x="16" y="12" width="28" height="2" rx="1" fill="#aaa"/>
            <rect x="85" y="7" width="30" height="4" rx="1" fill="<?=$c?>" opacity=".2"/>
            <rect x="85" y="13" width="30" height="2" rx="1" fill="<?=$c?>"/>
            <rect y="20" width="120" height="4" fill="#eceff1"/>
            <rect y="24" width="120" height="3" fill="#37474f"/>
            <rect x="4" y="29" width="112" height="1.5" fill="#eceff1"/>
            <rect x="4" y="32" width="112" height="1.5" fill="#eceff1"/>
            <rect x="4" y="35" width="112" height="1.5" fill="#eceff1"/>
            <rect x="4" y="38" width="112" height="1.5" fill="#eceff1"/>
            <rect x="4" y="41" width="112" height="1.5" fill="#eceff1"/>
            <rect x="55" y="52" width="58" height="14" fill="#eceff1"/>
            <rect y="67" width="120" height="5" fill="<?=$c?>"/>
          <?php endif; ?>
          </svg>
          <div class="tpl-lbl">
            <span class="tn" style="color:<?=$c?>"><?=$tpl['name']?></span>
            <span class="td"><?=$tpl['desc']??''?></span>
          </div>
        </label>
      </div>
<?php endforeach; ?>
    </div>
  </div>

  <!-- COMPANY DETAILS -->
  <div class="card">
    <div class="card-header"><h2>🏢 Company Details</h2></div>
    <div class="form-row">
      <div class="form-group col-full">
        <label for="fld_company_name">Company Name <span style="color:red">*</span></label>
        <input type="text" name="company_name" id="fld_company_name" required value="<?= sanitize($prefill['company_name'] ?? '') ?>" placeholder="e.g. Sri Lakshmi Traders" autocomplete="organization">
        <span class="field-error-msg" id="err_company_name" role="alert"><?= !empty($invoiceFormErrors['company_name']) ? sanitize($invoiceFormErrors['company_name']) : '' ?></span>
      </div>
    </div>
    <div class="form-row-3">
      <div class="form-group">
        <label for="fld_gst_number">GST Number</label>
        <input type="text" name="gst_number" id="fld_gst_number" maxlength="15" value="<?= sanitize($prefill['gst_number'] ?? '') ?>" placeholder="e.g. 33APAPR0776B3Z7" style="text-transform:uppercase" autocomplete="off">
        <span class="field-error-msg" id="err_gst_number" role="alert"><?= !empty($invoiceFormErrors['gst_number']) ? sanitize($invoiceFormErrors['gst_number']) : '' ?></span>
      </div>
      <div class="form-group">
        <label for="fld_company_phone">Phone</label>
        <input type="text" name="company_phone" id="fld_company_phone" maxlength="10" inputmode="numeric" value="<?= sanitize($prefill['company_phone'] ?? '') ?>" placeholder="9876543210" autocomplete="tel">
        <span class="field-error-msg" id="err_company_phone" role="alert"><?= !empty($invoiceFormErrors['company_phone']) ? sanitize($invoiceFormErrors['company_phone']) : '' ?></span>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="company_email" value="<?= sanitize($prefill['company_email'] ?? '') ?>" placeholder="office@company.com">
      </div>
    </div>
    <div class="form-group">
      <label>Company Address</label>
      <textarea name="company_address" placeholder="4, Kalladi Chidambarapuram Road, Nallanathanpuram, Tirunelveli, Tamil Nadu - 627502"><?= sanitize($prefill['company_address'] ?? '') ?></textarea>
    </div>
    <div class="form-section-title">Bank Details</div>
    <div class="form-row-3">
      <div class="form-group">
        <label for="fld_bank_account">Account Number</label>
        <input type="text" name="bank_account" id="fld_bank_account" maxlength="18" inputmode="numeric" value="<?= sanitize($prefill['bank_account'] ?? '') ?>" placeholder="279150310875046">
        <span class="field-error-msg" id="err_bank_account" role="alert"><?= !empty($invoiceFormErrors['bank_account']) ? sanitize($invoiceFormErrors['bank_account']) : '' ?></span>
      </div>
      <div class="form-group">
        <label for="fld_bank_ifsc">IFSC Code</label>
        <input type="text" name="bank_ifsc" id="fld_bank_ifsc" maxlength="11" value="<?= sanitize($prefill['bank_ifsc'] ?? '') ?>" placeholder="SBIN0001234" style="text-transform:uppercase" autocomplete="off">
        <span class="field-error-msg" id="err_bank_ifsc" role="alert"><?= !empty($invoiceFormErrors['bank_ifsc']) ? sanitize($invoiceFormErrors['bank_ifsc']) : '' ?></span>
      </div>
      <div class="form-group">
        <label for="fld_bank_branch">Branch Name</label>
        <input type="text" name="bank_branch" id="fld_bank_branch" value="<?= sanitize($prefill['bank_branch'] ?? '') ?>" placeholder="Kalakad">
        <span class="field-error-msg" id="err_bank_branch" role="alert"><?= !empty($invoiceFormErrors['bank_branch']) ? sanitize($invoiceFormErrors['bank_branch']) : '' ?></span>
      </div>
    </div>
    <div class="form-row-2">
      <div class="form-group">
        <label>Proprietor / Signatory Name</label>
        <input type="text" name="proprietor" value="<?= sanitize($prefill['proprietor'] ?? 'Proprietor') ?>" placeholder="Proprietor">
      </div>
      <div class="form-group">
        <label>Company Logo (optional)</label>
        <input type="file" name="company_logo" accept=".jpg,.jpeg,.png,.gif">
        <small>JPG, PNG, GIF — shown on invoice</small>
      </div>
    </div>
  </div>

  <!-- INVOICE DETAILS -->
  <div class="card">
    <div class="card-header"><h2>📄 Invoice Details</h2></div>
    <div class="form-row-4">
      <div class="form-group">
        <label>Invoice Title</label>
        <input type="text" name="invoice_title" value="<?= sanitize($prefill['invoice_title'] ?? 'Tax Invoice') ?>" placeholder="Tax Invoice">
      </div>
      <div class="form-group">
        <label>Invoice Number</label>
        <input type="text" name="invoice_number_disp" value="<?= sanitize($invNum) ?>" readonly>
      </div>
      <div class="form-group">
        <label>Bill Date <span style="color:red">*</span></label>
        <input type="text" name="invoice_date" required value="<?= sanitize($prefill['invoice_date'] ?? $today) ?>" placeholder="dd/mm/yyyy">
      </div>
      <div class="form-group">
        <label>Vehicle Number</label>
        <input type="text" name="vehicle_number" value="<?= sanitize($prefill['vehicle_number'] ?? '') ?>" placeholder="TN 52 B 7889">
      </div>
    </div>
    <div class="form-group">
      <label for="fld_bill_to">Bill To / Customer Name <span style="color:red">*</span></label>
      <input type="text" name="bill_to" id="fld_bill_to" required value="<?= sanitize($prefill['bill_to'] ?? '') ?>" placeholder="Customer name">
      <span class="field-error-msg" id="err_bill_to" role="alert"><?= !empty($invoiceFormErrors['bill_to']) ? sanitize($invoiceFormErrors['bill_to']) : '' ?></span>
    </div>
  </div>

  <!-- LINE ITEMS -->
  <div class="card">
    <div class="card-header">
      <h2>📦 Line Items</h2>
      <div style="font-size:0.8rem;color:#888">CGST 3% + SGST 3% = 6% auto-calculated</div>
    </div>
    <?php if (!empty($invoiceFormErrors['items'])): ?>
    <div class="alert alert-error items-card-error" id="items_block_error" role="alert"><?= sanitize($invoiceFormErrors['items']) ?></div>
    <?php else: ?>
    <div class="alert alert-error items-card-error" id="items_block_error" role="alert" hidden></div>
    <?php endif; ?>
    <div class="items-wrap">
      <table class="items-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Item &amp; Description</th>
            <th>HSN/SAC</th>
            <th>Qty</th>
            <th>Rate (₹)</th>
            <th>CGST (3%)</th>
            <th>SGST (3%)</th>
            <th>Amount (₹)</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="items-tbody">
<?php foreach ($prefillItems as $i => $pit):
    $pdesc = sanitize($pit['description'] ?? '');
    $phsn = sanitize($pit['hsn'] ?? '');
    $pqty = sanitize((string)($pit['quantity'] ?? ''));
    $prate = sanitize((string)($pit['rate'] ?? ''));
    $ed = $invoiceFormErrors["items.$i.description"] ?? '';
    $eq = $invoiceFormErrors["items.$i.quantity"] ?? '';
    $rowNum = $i + 1;
?>
          <tr class="item-row" data-index="<?= (int)$i ?>">
            <td style="text-align:center"><?= $rowNum ?><input type="hidden" name="items[<?= $i ?>][serial]" value="<?= $rowNum ?>"></td>
            <td class="item-cell-desc">
              <input type="text" name="items[<?= $i ?>][description]" class="i-desc" placeholder="Item description" value="<?= $pdesc ?>">
              <span class="field-error-msg item-field-error" data-err-for="items.<?= $i ?>.description" role="alert"><?= $ed !== '' ? sanitize($ed) : '' ?></span>
            </td>
            <td><input type="text" name="items[<?= $i ?>][hsn]" class="i-hsn" placeholder="HSN" style="width:85px" value="<?= $phsn ?>"></td>
            <td class="item-cell-qty">
              <input type="text" name="items[<?= $i ?>][quantity]" class="i-qty" inputmode="numeric" placeholder="0" value="<?= $pqty ?>">
              <span class="field-error-msg item-field-error" data-err-for="items.<?= $i ?>.quantity" role="alert"><?= $eq !== '' ? sanitize($eq) : '' ?></span>
            </td>
            <td><input type="number" name="items[<?= $i ?>][rate]" class="i-rate" min="0" step="0.01" placeholder="0.00" value="<?= $prate !== '' ? $prate : '' ?>"></td>
            <td class="gst-cell i-cgst">₹0.00<input type="hidden" name="items[<?= $i ?>][cgst]" class="i-cgst-val" value="0"></td>
            <td class="gst-cell i-sgst">₹0.00<input type="hidden" name="items[<?= $i ?>][sgst]" class="i-sgst-val" value="0"></td>
            <td class="auto-cell i-total">₹0.00
              <input type="hidden" name="items[<?= $i ?>][base]" class="i-base" value="0">
              <input type="hidden" name="items[<?= $i ?>][amount]" class="i-amount-val" value="0">
            </td>
            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeItemRow(this)" title="Remove">✕</button></td>
          </tr>
<?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <button type="button" class="btn btn-outline" onclick="addItemRow()" style="margin-top:0.5rem">➕ Add Item</button>
  </div>

  <!-- TOTALS -->
  <div class="card">
    <div class="card-header"><h2>💰 Financial Summary</h2></div>
    <div class="totals-wrap" style="justify-content:flex-start">
      <div class="totals-box" style="min-width:380px">
        <div class="totals-row"><span>Taxable Amount (Subtotal)</span><strong id="disp-subtotal">₹0.00</strong></div>
        <div class="totals-row"><span>CGST @ 3%</span><span id="disp-cgst-total">₹0.00</span></div>
        <div class="totals-row"><span>SGST @ 3%</span><span id="disp-sgst-total">₹0.00</span></div>
        <div class="totals-row total-final"><span>GRAND TOTAL</span><span id="disp-grand-total">₹0.00</span></div>
        <div class="totals-row">
          <span>Payment Made (₹)</span>
          <input type="number" name="payment_made" id="payment_made" min="0" step="0.01" value="0" placeholder="0.00">
        </div>
        <div class="totals-row"><span><strong>Balance Due</strong></span><strong id="disp-balance">₹0.00</strong></div>
      </div>
    </div>
    <div class="form-group" style="margin-top:1rem">
      <label>Amount in Words</label>
      <input type="text" name="amount_words" id="inp-amount-words" readonly style="background:#f5f5f5;font-style:italic" placeholder="Auto-calculated...">
    </div>
    <!-- Hidden total inputs -->
    <input type="hidden" name="subtotal" id="inp-subtotal" value="0">
    <input type="hidden" name="cgst_total" id="inp-cgst-total" value="0">
    <input type="hidden" name="sgst_total" id="inp-sgst-total" value="0">
    <input type="hidden" name="grand_total" id="inp-grand-total" value="0">
    <input type="hidden" name="balance_due" id="inp-balance" value="0">
  </div>

  <!-- NOTES -->
  <div class="card">
    <div class="card-header"><h2>📝 Notes & Terms</h2></div>
    <div class="form-group">
      <label>Tax Note / Notification</label>
      <textarea name="notes" placeholder="Note: Tax charged at 6% as per Notification No. 02/2022-Central Tax (Rate) without availment of Input Tax Credit"><?= sanitize($prefill['notes'] ?? 'Note: Tax charged at 6% as per Notification No. 02/2022-Central Tax (Rate) without availment of Input Tax Credit') ?></textarea>
    </div>
  </div>

  <div class="form-actions no-print">
    <button type="submit" name="action" value="save" class="btn btn-success btn-lg">💾 Save Invoice</button>
    <button type="submit" name="action" value="preview" class="btn btn-primary btn-lg">👁 Preview</button>
    <a href="index.php" class="btn btn-secondary">✕ Cancel</a>
  </div>
</form>
</div>

<script>window.__INITIAL_ITEM_ROW_COUNT__ = <?= count($prefillItems) ?>;</script>
<script src="js/invoice.js"></script>
</body>
</html>
