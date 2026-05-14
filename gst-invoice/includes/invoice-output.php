<?php
/**
 * includes/invoice-output.php
 * Renders a full invoice given $inv array and $tplStyle config.
 * $tplStyle = ['primary', 'accent', 'header_bg', 'font', 'style_class', 'name']
 */
if (!isset($inv) || !isset($tplStyle)) die();
if (!defined('CGST_RATE')) define('CGST_RATE', 3);
if (!defined('SGST_RATE')) define('SGST_RATE', 3);

$p    = $tplStyle['primary']   ?? '#1a237e';
$acc  = $tplStyle['accent']    ?? '#ff6f00';
$hbg  = $tplStyle['header_bg'] ?? $p;
$font = $tplStyle['font']      ?? 'Arial, sans-serif';
$sc   = $tplStyle['style_class'] ?? 'inv-std';
$layout = $tplStyle['layout']  ?? 'standard'; // standard | sidebar | boxed | compact | ledger
?>
<style>
.<?= $sc ?> * { box-sizing:border-box; margin:0; padding:0; }
.<?= $sc ?> { font-family:<?= $font ?>; font-size:12px; background:#fff; color:#111; max-width:840px; margin:0 auto; }

/* HEADER */
.<?= $sc ?> .inv-hd { background:<?= $hbg ?>; color:#fff; padding:20px; }
.<?= $sc ?> .inv-hd.split { display:flex; justify-content:space-between; align-items:flex-start; }
.<?= $sc ?> .inv-hd .co-name { font-size:<?= $tplStyle['company_font_size'] ?? '20px' ?>; font-weight:<?= $tplStyle['company_font_weight'] ?? '900' ?>; text-transform:uppercase; letter-spacing:<?= $tplStyle['company_letter_spacing'] ?? '1px' ?>; }
.<?= $sc ?> .inv-hd .co-addr { font-size:10px; opacity:.85; margin-top:4px; line-height:1.5; }
.<?= $sc ?> .inv-hd .co-gst  { font-size:10px; font-weight:700; margin-top:3px; }
.<?= $sc ?> .inv-hd .inv-right { text-align:right; }
.<?= $sc ?> .inv-hd .inv-title { font-size:<?= $tplStyle['title_size'] ?? '16px' ?>; font-weight:700; letter-spacing:2px; <?= $tplStyle['title_style'] ?? '' ?> }
.<?= $sc ?> .inv-hd .inv-num { font-size:11px; opacity:.9; margin-top:4px; }

/* TITLE BAR (non-split layouts) */
.<?= $sc ?> .title-bar { background:<?= $p ?>; color:#fff; text-align:center; padding:6px; font-size:13px; font-weight:700; letter-spacing:3px; }

/* BILL META */
.<?= $sc ?> .inv-meta { display:flex; gap:10px; padding:10px 15px; border-bottom:1px solid #ddd; }
.<?= $sc ?> .inv-meta .meta-box { flex:1; }
.<?= $sc ?> .inv-meta .meta-box label { font-size:9px; font-weight:700; text-transform:uppercase; color:#888; display:block; margin-bottom:2px; }
.<?= $sc ?> .inv-meta .meta-box .mv { font-size:12px; font-weight:700; }
.<?= $sc ?> .inv-meta .meta-box .mv-sm { font-size:11px; }
<?php if ($layout === 'boxed'): ?>
.<?= $sc ?> .inv-meta .meta-box { border:1px solid #ddd; padding:8px; border-radius:4px; }
<?php endif; ?>

/* ITEMS TABLE */
.<?= $sc ?> .items-outer { padding:10px 15px; }
.<?= $sc ?> table.inv-tbl { width:100%; border-collapse:collapse; }
<?php if ($layout === 'ledger'): ?>
.<?= $sc ?> .inv-tbl th { background:#f5e6c8; color:#3e2723; border:1px solid #a1887f; padding:5px 4px; font-size:10px; }
.<?= $sc ?> .inv-tbl td { border:1px solid #bcaaa4; padding:5px 4px; font-size:11px; }
.<?= $sc ?> .inv-tbl tfoot td { background:#efebe9; font-weight:700; border:1px solid #a1887f; }
<?php elseif ($layout === 'compact'): ?>
.<?= $sc ?> .inv-tbl th { background:<?= $p ?>; color:#fff; padding:4px 3px; font-size:9px; border:none; }
.<?= $sc ?> .inv-tbl td { padding:3px; border-bottom:1px solid #eee; font-size:10px; }
.<?= $sc ?> .inv-tbl tfoot td { font-weight:700; font-size:11px; background:#f5f5f5; }
<?php else: ?>
.<?= $sc ?> .inv-tbl th { background:<?= $p ?>; color:#fff; padding:6px 5px; font-size:10px; border:1px solid <?= $p ?>; }
.<?= $sc ?> .inv-tbl td { padding:5px; border-bottom:1px solid #eee; font-size:11px; <?= $layout === 'minimal' ? '' : 'border:1px solid #eee;' ?> }
.<?= $sc ?> .inv-tbl tfoot td { font-weight:700; background:#f0f2ff; border-top:2px solid <?= $p ?>; }
<?php endif; ?>
.<?= $sc ?> .inv-tbl td.r, .<?= $sc ?> .inv-tbl th.r { text-align:right; }
.<?= $sc ?> .inv-tbl td.c, .<?= $sc ?> .inv-tbl th.c { text-align:center; }
.<?= $sc ?> .inv-tbl tr:hover td { background:<?= $tplStyle['row_hover'] ?? '#fafafa' ?>; }

/* FOOTER AREA */
.<?= $sc ?> .inv-ft { display:flex; justify-content:space-between; align-items:flex-start; padding:12px 15px; gap:15px; border-top:2px solid <?= $p ?>; }
.<?= $sc ?> .inv-ft .bank-info { flex:1; font-size:10px; line-height:1.8; }
.<?= $sc ?> .inv-ft .bank-info strong { font-size:9px; text-transform:uppercase; color:#888; display:block; margin-bottom:2px; }
.<?= $sc ?> .inv-ft .totals-box { min-width:240px; }
.<?= $sc ?> .inv-ft .totals-box table { width:100%; font-size:11px; border-collapse:collapse; }
.<?= $sc ?> .inv-ft .totals-box td { padding:3px 6px; border-bottom:1px solid #eee; }
.<?= $sc ?> .inv-ft .totals-box td.lbl { color:#555; }
.<?= $sc ?> .inv-ft .totals-box td.amt { text-align:right; font-weight:700; }
.<?= $sc ?> .inv-ft .totals-box tr.grand-row td { background:<?= $p ?>; color:#fff; font-weight:700; font-size:12px; padding:5px 6px; }
.<?= $sc ?> .words-line { font-style:italic; font-size:10px; color:#444; border:1px dashed #bbb; padding:4px 8px; margin-top:6px; background:#fafafa; }
.<?= $sc ?> .note-bar { font-size:9px; color:#555; background:#fffde7; border:1px solid #e0c97c; padding:5px 8px; margin:8px 15px; border-radius:3px; }
.<?= $sc ?> .sign-block { text-align:right; padding:12px 15px 15px; font-size:11px; }
.<?= $sc ?> .sign-block .for-co { font-weight:700; font-size:13px; color:<?= $p ?>; }
.<?= $sc ?> .sign-block .designation { color:#666; font-size:10px; }
.<?= $sc ?> .logo-img { max-height:55px; max-width:130px; object-fit:contain; margin-bottom:5px; }

<?= $tplStyle['extra_css'] ?? '' ?>
</style>

<div class="<?= $sc ?>">
  <!-- HEADER -->
  <?php if ($layout === 'split-header' || $layout === 'minimal' || $layout === 'boxed'): ?>
  <div class="inv-hd split" style="<?= $layout === 'minimal' ? 'border-bottom:3px solid '.$p.';background:#fff;color:#111;padding:15px;' : '' ?>">
    <div>
      <?php if (!empty($inv['company_logo']) && file_exists($inv['company_logo'])): ?>
      <img src="<?= sanitize($inv['company_logo']) ?>" class="logo-img" alt="Logo"><br>
      <?php endif; ?>
      <div class="co-name" style="<?= $layout === 'minimal' ? 'color:'.$p.';' : '' ?>"><?= sanitize($inv['company_name'] ?? '') ?></div>
      <div class="co-addr" style="<?= $layout === 'minimal' ? 'color:#666;' : '' ?>"><?= nl2br(sanitize($inv['company_address'] ?? '')) ?></div>
      <div class="co-gst" style="<?= $layout === 'minimal' ? 'color:#555;' : '' ?>">GSTIN: <?= sanitize($inv['gst_number'] ?? '') ?></div>
    </div>
    <div class="inv-right">
      <div class="inv-title" style="<?= $layout === 'minimal' ? 'color:'.$p.';' : '' ?>"><?= sanitize($inv['invoice_title'] ?? 'TAX INVOICE') ?></div>
      <div class="inv-num" style="<?= $layout === 'minimal' ? 'color:#555;' : '' ?>"><?= sanitize($inv['invoice_number'] ?? '') ?></div>
      <div style="font-size:10px;margin-top:3px;<?= $layout === 'minimal' ? 'color:#666;' : 'opacity:.8;' ?>"><?= sanitize($inv['invoice_date'] ?? '') ?></div>
    </div>
  </div>
  <?php else: ?>
  <div class="inv-hd">
    <div style="display:flex;justify-content:space-between;align-items:flex-start">
      <div>
        <?php if (!empty($inv['company_logo']) && file_exists($inv['company_logo'])): ?>
        <img src="<?= sanitize($inv['company_logo']) ?>" class="logo-img" alt="Logo"><br>
        <?php endif; ?>
        <div class="co-name"><?= sanitize($inv['company_name'] ?? '') ?></div>
        <div class="co-addr"><?= nl2br(sanitize($inv['company_address'] ?? '')) ?></div>
        <div class="co-gst">GSTIN: <?= sanitize($inv['gst_number'] ?? '') ?></div>
      </div>
      <div class="inv-right">
        <div class="inv-title"><?= sanitize($inv['invoice_title'] ?? 'TAX INVOICE') ?></div>
        <div class="inv-num"><?= sanitize($inv['invoice_number'] ?? '') ?></div>
        <div style="font-size:10px;opacity:.8;margin-top:3px"><?= sanitize($inv['invoice_date'] ?? '') ?></div>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- BILL META -->
  <div class="inv-meta">
    <div class="meta-box">
      <label>Bill To</label>
      <div class="mv"><?= sanitize($inv['bill_to'] ?? '') ?></div>
    </div>
    <div class="meta-box">
      <label>Invoice No.</label>
      <div class="mv"><?= sanitize($inv['invoice_number'] ?? '') ?></div>
    </div>
    <div class="meta-box">
      <label>Date</label>
      <div class="mv-sm"><?= sanitize($inv['invoice_date'] ?? '') ?></div>
    </div>
    <?php if (!empty($inv['vehicle_number'])): ?>
    <div class="meta-box">
      <label>Vehicle No.</label>
      <div class="mv"><?= sanitize($inv['vehicle_number']) ?></div>
    </div>
    <?php endif; ?>
  </div>

  <!-- ITEMS TABLE -->
  <div class="items-outer">
    <table class="inv-tbl">
      <thead>
        <tr>
          <th class="c" style="width:28px">#</th>
          <th>Item &amp; Description</th>
          <th class="c">HSN/SAC</th>
          <th class="r">Qty</th>
          <th class="c">Per</th>
          <th class="r">Rate (₹)</th>
          <th class="r">CGST (<?= CGST_RATE ?>%)</th>
          <th class="r">SGST (<?= SGST_RATE ?>%)</th>
          <th class="r">Amount (₹)</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($inv['items'] ?? [] as $item): ?>
        <tr>
          <td class="c"><?= (int)$item['serial'] ?></td>
          <td><?= sanitize($item['description'] ?? '') ?></td>
          <td class="c"><?= sanitize($item['hsn'] ?? '') ?></td>
          <td class="r"><?= number_format((float)($item['quantity'] ?? 0), 2) ?></td>
          <td class="c"><?= sanitize($item['per_unit'] ?? '') ?></td>
          <td class="r"><?= formatIndianCurrency($item['rate'] ?? 0) ?></td>
          <td class="r"><?= formatIndianCurrency($item['cgst'] ?? 0) ?></td>
          <td class="r"><?= formatIndianCurrency($item['sgst'] ?? 0) ?></td>
          <td class="r"><?= formatIndianCurrency($item['amount'] ?? 0) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="6" class="r" style="padding-right:8px">Totals</td>
          <td class="r"><?= formatIndianCurrency($inv['totals']['cgst_total'] ?? 0) ?></td>
          <td class="r"><?= formatIndianCurrency($inv['totals']['sgst_total'] ?? 0) ?></td>
          <td class="r"><?= formatIndianCurrency($inv['totals']['subtotal'] ?? 0) ?></td>
        </tr>
      </tfoot>
    </table>
  </div>

  <?php if (!empty($inv['notes'])): ?>
  <div class="note-bar">📌 <?= sanitize($inv['notes']) ?></div>
  <?php endif; ?>

  <!-- FOOTER -->
  <div class="inv-ft">
    <div class="bank-info">
      <?php if (!empty($inv['bank_account']) || !empty($inv['bank_ifsc'])): ?>
      <strong>Bank Details</strong>
      <?php endif; ?>
      <?php if (!empty($inv['bank_account'])): ?>ACC NO: <?= sanitize($inv['bank_account']) ?><br><?php endif; ?>
      <?php if (!empty($inv['bank_ifsc'])): ?>IFSC: <?= sanitize($inv['bank_ifsc']) ?><br><?php endif; ?>
      <?php if (!empty($inv['bank_branch'])): ?>BRANCH: <?= sanitize($inv['bank_branch']) ?><?php endif; ?>
    </div>
    <div class="totals-box">
      <table>
        <tr><td class="lbl">Taxable Amount</td><td class="amt"><?= formatIndianCurrency($inv['totals']['subtotal'] ?? 0) ?></td></tr>
        <tr><td class="lbl">CGST @ <?= CGST_RATE ?>%</td><td class="amt"><?= formatIndianCurrency($inv['totals']['cgst_total'] ?? 0) ?></td></tr>
        <tr><td class="lbl">SGST @ <?= SGST_RATE ?>%</td><td class="amt"><?= formatIndianCurrency($inv['totals']['sgst_total'] ?? 0) ?></td></tr>
        <tr class="grand-row"><td>TOTAL AMOUNT</td><td class="amt"><?= formatIndianCurrency($inv['totals']['total'] ?? 0) ?></td></tr>
        <tr><td class="lbl">Payment Made</td><td class="amt"><?= formatIndianCurrency($inv['payment_made'] ?? 0) ?></td></tr>
        <tr><td class="lbl"><strong>Balance Due</strong></td><td class="amt"><strong><?= formatIndianCurrency($inv['balance_due'] ?? 0) ?></strong></td></tr>
      </table>
      <div class="words-line">In Words: <?= sanitize($inv['amount_words'] ?? '') ?></div>
    </div>
  </div>

  <!-- SIGNATURE -->
  <div class="sign-block">
    <div class="for-co">For <?= sanitize($inv['company_name'] ?? '') ?></div>
    <br><br>
    ___________________________<br>
    <span class="designation"><?= sanitize($inv['proprietor'] ?? 'Proprietor') ?></span>
  </div>
</div>
