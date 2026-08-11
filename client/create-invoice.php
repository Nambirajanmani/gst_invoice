<?php
require_once dirname(__DIR__) . '/server/includes/functions.php';
$templates = getTemplates();
$today = date('d/m/Y');
$invNum = generateInvoiceNumber();

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
    <div class="template-grid">
<?php foreach ($templates as $key => $tpl): $c=$tpl['color']; ?>
      <div class="tpl-option">
        <input type="radio" name="template" id="<?=$key?>" value="<?=$key?>" <?=$key === $selectedTemplate ? 'checked' : ''?>>
        <label for="<?=$key?>">
          <div class="tpl-thumb" style="background:<?=$c?>;color:#fff;">📄 <?=$key?></div>
          <div class="tpl-label">
            <strong><?=$tpl['name']?></strong>
            <small style="display:block;color:#888"><?=$tpl['desc']?></small>
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
        <input type="text" name="company_name" id="fld_company_name" required value="<?= sanitize($prefill['company_name'] ?? '') ?>" placeholder="e.g. Sri Lakshmi Traders">
      </div>
    </div>
    <div class="form-row-3">
      <div class="form-group">
        <label for="fld_gst_number">GST Number</label>
        <input type="text" name="gst_number" id="fld_gst_number" maxlength="15" value="<?= sanitize($prefill['gst_number'] ?? '') ?>" placeholder="e.g. 33AAAAA0000A1Z5" style="text-transform:uppercase">
      </div>
      <div class="form-group">
        <label for="fld_company_phone">Phone</label>
        <input type="text" name="company_phone" id="fld_company_phone" maxlength="10" inputmode="numeric" value="<?= sanitize($prefill['company_phone'] ?? '') ?>" placeholder="9876543210">
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="company_email" value="<?= sanitize($prefill['company_email'] ?? '') ?>" placeholder="office@company.com">
      </div>
    </div>
    <div class="form-group">
      <label>Company Address</label>
      <textarea name="company_address" placeholder="Main Road, Tirunelveli, Tamil Nadu"><?= sanitize($prefill['company_address'] ?? '') ?></textarea>
    </div>
    <div class="form-section-title">Bank Details</div>
    <div class="form-row-3">
      <div class="form-group">
        <label for="fld_bank_account">Account Number</label>
        <input type="text" name="bank_account" id="fld_bank_account" maxlength="18" inputmode="numeric" value="<?= sanitize($prefill['bank_account'] ?? '') ?>" placeholder="000000000000">
      </div>
      <div class="form-group">
        <label for="fld_bank_ifsc">IFSC Code</label>
        <input type="text" name="bank_ifsc" id="fld_bank_ifsc" maxlength="11" value="<?= sanitize($prefill['bank_ifsc'] ?? '') ?>" placeholder="SBIN0001234" style="text-transform:uppercase">
      </div>
      <div class="form-group">
        <label for="fld_bank_branch">Branch Name</label>
        <input type="text" name="bank_branch" id="fld_bank_branch" value="<?= sanitize($prefill['bank_branch'] ?? '') ?>" placeholder="Main Branch">
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
      </div>
    </div>
  </div>

  <!-- INVOICE DETAILS -->
  <div class="card">
    <div class="card-header"><h2>📄 Invoice Details</h2></div>
    <div class="form-row-4">
      <div class="form-group">
        <label>Invoice Title</label>
        <input type="text" name="invoice_title" value="<?= sanitize($prefill['invoice_title'] ?? 'Tax Invoice') ?>">
      </div>
      <div class="form-group">
        <label>Invoice Number</label>
        <input type="text" name="invoice_number_disp" value="<?= sanitize($invNum) ?>" readonly>
      </div>
      <div class="form-group">
        <label for="fld_invoice_date">Bill Date <span style="color:red">*</span></label>
        <input type="date" name="invoice_date" id="fld_invoice_date" required value="<?= date('Y-m-d') ?>">
      </div>
      <div class="form-group">
        <label>Vehicle Number</label>
        <input type="text" name="vehicle_number" value="<?= sanitize($prefill['vehicle_number'] ?? '') ?>" placeholder="TN 72 AB 1234">
      </div>
    </div>
    <div class="form-group">
      <label for="fld_bill_to">Bill To / Customer Name <span style="color:red">*</span></label>
      <input type="text" name="bill_to" id="fld_bill_to" required value="<?= sanitize($prefill['bill_to'] ?? '') ?>" placeholder="Customer name">
    </div>
  </div>

  <!-- LINE ITEMS -->
  <div class="card">
    <div class="card-header">
      <h2>📦 Line Items</h2>
      <div style="font-size:0.8rem;color:#888">CGST 3% + SGST 3% = 6% auto-calculated</div>
    </div>
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
    $rowNum = $i + 1;
?>
          <tr class="item-row" data-index="<?= (int)$i ?>">
            <td style="text-align:center"><?= $rowNum ?><input type="hidden" name="items[<?= $i ?>][serial]" value="<?= $rowNum ?>"></td>
            <td class="item-cell-desc">
              <input type="text" name="items[<?= $i ?>][description]" class="i-desc" placeholder="Item description" value="<?= $pdesc ?>">
            </td>
            <td><input type="text" name="items[<?= $i ?>][hsn]" class="i-hsn" placeholder="HSN" style="width:85px" value="<?= $phsn ?>"></td>
            <td class="item-cell-qty">
              <input type="number" name="items[<?= $i ?>][quantity]" class="i-qty" min="1" step="1" inputmode="numeric" placeholder="0" value="<?= $pqty ?>">
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
      <label>Tax Note</label>
      <textarea name="notes" placeholder="Tax charged at 6% as per Notification No. 02/2022-Central Tax (Rate)"><?= sanitize($prefill['notes'] ?? 'Note: Tax charged at 6% as per Notification No. 02/2022-Central Tax (Rate) without availment of Input Tax Credit') ?></textarea>
    </div>
  </div>

  <div class="form-actions no-print">
    <button type="submit" name="action" value="save" class="btn btn-success btn-lg">💾 Save Invoice</button>
    <a href="index.php" class="btn btn-secondary">✕ Cancel</a>
  </div>
</form>
</div>

<script>window.__INITIAL_ITEM_ROW_COUNT__ = <?= count($prefillItems) ?>;</script>
<script src="js/api.js"></script>
<script src="js/invoice.js"></script>
</body>
</html>
