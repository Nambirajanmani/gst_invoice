<?php
if (!isset($inv)) die();
if (!defined('CGST_RATE'))  define('CGST_RATE',  3);
if (!defined('SGST_RATE'))  define('SGST_RATE',  3);

?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
/* ─────────────────────────────────────────
   ROSHAN TRADERS — EXACT SCREENSHOT MATCH
   ───────────────────────────────────────── */

/* Google Font for script look */
@import url('https://fonts.googleapis.com/css2?family=Pacifico&display=swap');

* { box-sizing: border-box; margin: 0; padding: 0; }

.inv-wrap {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 13.5px;
  color: #222;
  background: #fff;
  width: 794px;          /* A4 width at 96dpi */
  min-height: 1123px;    /* A4 height */
  margin: 0 auto;
  padding: 36px 44px 32px;
}

/* ══════════════════════════════════════
   1. TOP HEADER  — logo-name LEFT | TAX INVOICE RIGHT
   ══════════════════════════════════════ */
.inv-top {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 22px;
}

/* Logo + italic name */
.logo-name-block {
  display: flex;
  align-items: center;
  gap: 14px;
}
.logo-ring {
  width: 78px;
  height: 78px;
  border-radius: 50%;
  border: 3px solid #1565c0;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  background: #f0f7ff;
}
.logo-ring img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.logo-ring .nolog {
  font-size: 28px;
  font-weight: 900;
  color: #1565c0;
  font-style: italic;
  font-family: Arial, sans-serif;
}
/* Script-style company name — split per word onto two lines */
.co-script {
  font-family: 'Pacifico', 'Arial', cursive;
  font-size: 27px;
  color: #1a9ad7;           /* cyan-blue matching screenshot */
  line-height: 1.25;
  font-weight: 400;
}

/* TAX INVOICE block */
.inv-title-right {
  text-align: right;
}
.inv-title-right .title-text {
  font-size: 44px;
  font-weight: 900;
  color: #1565c0;
  letter-spacing: 1.5px;
  line-height: 1;
  margin-bottom: 14px;
}
.inv-title-right .inv-ref {
  font-size: 16px;
  font-weight: 700;
  color: #222;
  margin-bottom: 7px;
}
.inv-title-right .inv-date {
  font-size: 13.5px;
  color: #333;
}

/* ══════════════════════════════════════
   2. DIVIDER
   ══════════════════════════════════════ */
.hr-rule {
  border: none;
  border-top: 1.5px solid #ccc;
  margin: 0 0 18px;
}

/* ══════════════════════════════════════
   3. TWO-COLUMN COMPANY / BILL-TO
   ══════════════════════════════════════ */
.two-col {
  display: flex;
  margin-bottom: 22px;
  align-items: flex-start;
}
.col-left {
  flex: 1;
  padding-right: 30px;
}
.col-right {
  flex: 1;
  padding-left: 30px;
  border-left: 1px solid #e0e0e0;
}

/* Company info */
.co-name-bold {
  font-size: 15px;
  font-weight: 700;
  color: #111;
  margin-bottom: 7px;
}
.co-address {
  font-size: 13px;
  color: #333;
  line-height: 1.85;
}
.co-gstin {
  font-size: 14px;
  font-weight: 700;
  color: #111;
  margin-top: 6px;
}

/* Bill To */
.bill-to-label {
  font-size: 13.5px;
  font-weight: 700;
  color: #333;
  margin-bottom: 9px;
}
.bill-to-name {
  font-size: 15px;
  font-weight: 700;
  color: #111;
  margin-bottom: 3px;
}
.bill-to-city {
  font-size: 13.5px;
  color: #333;
  margin-bottom: 12px;
}
.vehicle-no {
  font-size: 14px;
  font-weight: 700;
  color: #111;
}

/* ══════════════════════════════════════
   4. ITEMS TABLE
   ══════════════════════════════════════ */
table.inv-tbl {
  width: 100%;
  border-collapse: collapse;
}

/* Header row — solid blue #1565c0 */
.inv-tbl thead tr {
  background: #1565c0;
}
.inv-tbl thead th {
  color: #fff;
  font-size: 12.5px;
  font-weight: 700;
  padding: 9px 8px;
  text-align: center;
  border-right: 1px solid rgba(255,255,255,0.25);
  vertical-align: middle;
}
.inv-tbl thead th:last-child { border-right: none; }
.inv-tbl thead th.al { text-align: left; }
.inv-tbl thead th.ar { text-align: right; }

/* Body rows */
.inv-tbl tbody td {
  padding: 10px 8px;
  font-size: 13.5px;
  color: #222;
  text-align: center;
  border-bottom: 1px solid #e5e5e5;
  vertical-align: middle;
}
.inv-tbl tbody td.al { text-align: left; }
.inv-tbl tbody td.ar { text-align: right; font-weight: 600; }

/* Foot totals row */
.inv-tbl tfoot td {
  padding: 9px 8px;
  font-weight: 700;
  font-size: 13.5px;
  text-align: right;
  border-top: 1.5px solid #bbb;
  background: #fff;
}
.inv-tbl tfoot td.empty {
  background: #fff;
  border-top: 1.5px solid #bbb;
}

/* Empty rows to give breathing space below items (like screenshot) */
.inv-tbl tbody tr.spacer td {
  height: 28px;
  border-bottom: none;
  background: #fff;
}

/* ══════════════════════════════════════
   5. BOTTOM SECTION
   Bank left | Totals right
   ══════════════════════════════════════ */
.bottom-wrap {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-top: 10px;
  gap: 20px;
}

/* Bank details */
.bank-block {
  flex: 1;
  font-size: 12px;
  color: #555;
  line-height: 2.1;
}
.bank-co-name {
  font-size: 13px;
  font-weight: 700;
  color: #1565c0;
  margin-bottom: 2px;
}
.bank-key  { color: #666; font-weight: 400; }
.bank-val  { font-weight: 700; color: #1565c0; }

/* Totals table */
.totals-block {
  min-width: 310px;
}
.totals-tbl {
  width: 100%;
  border-collapse: collapse;
  font-size: 13.5px;
}
.totals-tbl td {
  padding: 5px 4px;
}
.totals-tbl td.tl { text-align: left;  color: #444; }
.totals-tbl td.tr { text-align: right; }
.totals-tbl tr.paid-row td { font-weight: 700; }
.totals-tbl tr.bal-row  td {
  font-weight: 700;
  font-size: 15px;
  border-top: 1.5px solid #333;
  padding-top: 8px;
}

/* Amount in words */
.words-line {
  text-align: right;
  margin-top: 9px;
  font-size: 12.5px;
  line-height: 1.7;
  color: #333;
}
.words-line .wl { display: inline; }
.words-line .wcurr { font-weight: 700; font-style: italic; }
.words-line .wamt  { font-style: italic; font-weight: 700; font-size: 13px; }

/* Signature */
.sig-block {
  text-align: right;
  margin-top: 12px;
}
.sig-for {
  font-size: 13.5px;
  font-weight: 700;
  color: #222;
  margin-bottom: 4px;
}
.sig-swirl {
  /* Simulated handwritten swirl using a Unicode character + styling */
  font-family: 'Pacifico', Georgia, cursive;
  font-size: 28px;
  color: #555;
  line-height: 1.1;
  display: block;
}
.sig-desig {
  font-size: 12.5px;
  color: #444;
  margin-top: 3px;
}

/* ══════════════════════════════════════
   6. NOTE BAR (very bottom)
   ══════════════════════════════════════ */
.note-bar {
  margin-top: 22px;
  padding-top: 10px;
  border-top: 1px solid #ccc;
  font-size: 11.5px;
  color: #333;
  line-height: 1.65;
}
.note-bar strong { font-weight: 700; color: #111; }

/* ══ PRINT ══ */
@media print {
  .inv-wrap { padding: 10px 20px; width: 100%; min-height: unset; }
  @page { size: A4 portrait; margin: 8mm; }
}
</style>

<div class="inv-wrap">

  <!-- ① TOP HEADER -->
  <div class="inv-top">

    <!-- Logo circle + italic company name -->
    <div class="logo-name-block">
      <div class="logo-ring">
        <?php if (!empty($inv['company_logo']) && file_exists($inv['company_logo'])): ?>
          <img src="<?= sanitize($inv['company_logo']) ?>" alt="Logo">
        <?php else: ?>
          <div class="nolog"><?= strtoupper(substr(trim($inv['company_name'] ?? 'R'), 0, 1)) ?></div>
        <?php endif; ?>
      </div>
      <div class="co-script">
        <?php
          /* Split on first space so "Roshan Traders" → "Roshan<br>Traders" */
          $nameParts = explode(' ', trim(sanitize($inv['company_name'] ?? '')), 2);
          echo $nameParts[0];
          if (!empty($nameParts[1])) echo '<br>' . $nameParts[1];
        ?>
      </div>
    </div>

    <!-- TAX INVOICE + ref + date -->
    <div class="inv-title-right">
      <div class="title-text"><?= strtoupper(sanitize($inv['invoice_title'] ?? 'TAX INVOICE')) ?></div>
      <div class="inv-ref"># &nbsp;<?= sanitize($inv['invoice_number'] ?? '') ?></div>
      <div class="inv-date">Bill Date : <?= sanitize($inv['invoice_date'] ?? '') ?></div>
    </div>

  </div><!-- /inv-top -->

  <!-- ② DIVIDER -->
  <hr class="hr-rule">

  <!-- ③ TWO COLUMNS -->
  <div class="two-col">

    <!-- Left: Company details -->
    <div class="col-left">
      <div class="co-name-bold"><?= sanitize($inv['company_name'] ?? '') ?></div>
      <div class="co-address"><?= nl2br(sanitize($inv['company_address'] ?? '')) ?></div>
      <?php if (!empty($inv['gst_number'])): ?>
        <div class="co-gstin"><?= sanitize($inv['gst_number']) ?></div>
      <?php endif; ?>
    </div>

    <!-- Right: Bill To -->
    <div class="col-right">
      <div class="bill-to-label">Bill To</div>
      <?php
        /*
         * Screenshot shows "Saranya" on one line, "THIRUVAROOR" below.
         * We split on first space: first token = name, rest = city/place.
         */
        $bt     = trim($inv['bill_to'] ?? '');
        $btParts = explode(' ', $bt, 2);
      ?>
      <div class="bill-to-name"><?= sanitize($btParts[0]) ?></div>
      <?php if (!empty($btParts[1])): ?>
        <div class="bill-to-city"><?= sanitize($btParts[1]) ?></div>
      <?php endif; ?>
      <?php if (!empty($inv['vehicle_number'])): ?>
        <div class="vehicle-no">Vehicle No: <?= sanitize($inv['vehicle_number']) ?></div>
      <?php endif; ?>
    </div>

  </div><!-- /two-col -->

  <!-- ④ ITEMS TABLE -->
  <table class="inv-tbl">
    <thead>
      <tr>
        <th style="width:34px">#</th>
        <th class="al" style="width:130px">Item &amp;<br>Description</th>
        <th>HSN/SAC</th>
        <th>Qty</th>
        <th class="ar">Rate</th>
        <th class="ar">CGST(<?= CGST_RATE ?>%)</th>
        <th class="ar">SGST(<?= SGST_RATE ?>%)</th>
        <th class="ar">Amount</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($inv['items'] ?? [] as $item):
        $base = (float)($item['base']
            ?? ((float)($item['quantity'] ?? 0) * (float)($item['rate'] ?? 0)));
      ?>
      <tr>
        <td><?= (int)($item['serial'] ?? 1) ?></td>
        <td class="al"><?= sanitize($item['description'] ?? '') ?></td>
        <td><?= sanitize($item['hsn'] ?? '') ?></td>
        <td><?= number_format((float)($item['quantity'] ?? 0)) ?></td>
        <td class="ar"><?= number_format($base) ?></td>
        <td class="ar"><?= number_format((float)($item['cgst'] ?? 0)) ?></td>
        <td class="ar"><?= number_format((float)($item['sgst'] ?? 0)) ?></td>
        <td class="ar"><?= number_format((float)($item['amount'] ?? 0), 2) ?></td>
      </tr>
      <?php endforeach; ?>

      <!-- Spacer rows to replicate the empty space seen in the screenshot -->
      <tr class="spacer"><td colspan="8"></td></tr>
      <tr class="spacer"><td colspan="8"></td></tr>
      <tr class="spacer"><td colspan="8"></td></tr>
      <tr class="spacer"><td colspan="8"></td></tr>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="5" class="empty" style="text-align:left"></td>
        <td><?= number_format((float)($inv['totals']['cgst_total'] ?? 0)) ?></td>
        <td><?= number_format((float)($inv['totals']['sgst_total'] ?? 0)) ?></td>
        <td><?= number_format((float)($inv['totals']['subtotal'] ?? 0), 2) ?></td>
      </tr>
    </tfoot>
  </table>

  <!-- ⑤ BOTTOM: Bank left | Totals right -->
  <div class="bottom-wrap">

    <!-- Bank details -->
    <div class="bank-block">
      <div class="bank-co-name"><?= sanitize($inv['company_name'] ?? '') ?></div>
      <?php if (!empty($inv['bank_account'])): ?>
        <div>
          <span class="bank-key">ACC NO – </span>
          <span class="bank-val"><?= sanitize($inv['bank_account']) ?></span>
        </div>
      <?php endif; ?>
      <?php if (!empty($inv['bank_ifsc'])): ?>
        <div>
          <span class="bank-key">IFSC – </span>
          <span class="bank-val"><?= sanitize($inv['bank_ifsc']) ?></span>
        </div>
      <?php endif; ?>
      <?php if (!empty($inv['bank_branch'])): ?>
        <div>
          <span class="bank-key">BRANCH – </span>
          <span class="bank-val"><?= sanitize($inv['bank_branch']) ?></span>
        </div>
      <?php endif; ?>
    </div>

    <!-- Totals + Words + Signature -->
    <div class="totals-block">

      <table class="totals-tbl">
        <tr>
          <td class="tl">Total</td>
          <td class="tr"><?= number_format((float)($inv['totals']['total'] ?? 0), 2) ?></td>
        </tr>
        <tr class="paid-row">
          <td class="tl">Payment Made</td>
          <td class="tr"><?= number_format((float)($inv['payment_made'] ?? 0), 2) ?></td>
        </tr>
        <tr class="bal-row">
          <td class="tl">Balance Due</td>
          <td class="tr"><?= number_format((float)($inv['balance_due'] ?? 0)) ?></td>
        </tr>
      </table>

      <!-- Amount in Words -->
      <div class="words-line">
        <span class="wl">Total In Words: </span>
        <span class="wcurr">Indian Rupee</span><br>
        <span class="wamt"><?= sanitize($inv['amount_words'] ?? '') ?></span>
      </div>

      <!-- Signature -->
      <div class="sig-block">
        <div class="sig-for">For <?= sanitize($inv['company_name'] ?? '') ?></div>
        <span class="sig-swirl">&#120522;</span>
        <div class="sig-desig"><?= sanitize($inv['proprietor'] ?? 'Proprietor') ?></div>
      </div>

    </div><!-- /totals-block -->

  </div><!-- /bottom-wrap -->

  <!-- ⑥ NOTE BAR -->
  <?php if (!empty($inv['notes'])): ?>
  <div class="note-bar">
    <strong>Note</strong> : <?= sanitize($inv['notes']) ?>
  </div>
  <?php endif; ?>

</div><!-- /inv-wrap -->
