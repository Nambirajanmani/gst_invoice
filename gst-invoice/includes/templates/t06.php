<?php if(!isset($inv)) die(); if(!defined('CGST_RATE')) define('CGST_RATE',3); if(!defined('SGST_RATE')) define('SGST_RATE',3); ?>
<style>
.t06{font-family:Calibri,Arial,sans-serif;font-size:12px;background:#f0f4f8;max-width:840px;margin:0 auto;padding:16px;}
.t06 .box{background:#fff;border-radius:8px;border:1px solid #d0d9e6;margin-bottom:12px;overflow:hidden;}
.t06 .box-header{background:#4a0072;color:#fff;padding:8px 16px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;}
.t06 .box-body{padding:14px 16px;}
.t06 .company-box{display:flex;align-items:flex-start;gap:16px;}
.t06 .logo-circle{width:80px;height:80px;border-radius:50%;background:#f8f0ff;border:3px solid #ce93d8;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;}
.t06 .logo-circle img{max-width:70px;max-height:70px;object-fit:contain;border-radius:50%;}
.t06 .logo-circle .no-logo{font-size:24px;font-weight:900;color:#4a0072;}
.t06 .co-name{font-size:18px;font-weight:700;color:#4a0072;margin-bottom:4px;}
.t06 .co-addr{font-size:10px;color:#666;line-height:1.7;}
.t06 .co-gst{display:inline-block;background:#f8f0ff;border:1px solid #ce93d8;border-radius:20px;padding:2px 10px;font-size:9px;font-weight:700;color:#4a0072;margin-top:4px;}
.t06 .inv-meta-box .inv-title{font-size:16px;font-weight:900;color:#4a0072;letter-spacing:2px;margin-bottom:10px;}
.t06 .meta-grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;}
.t06 .meta-card{background:#f8f0ff;border-radius:6px;padding:8px 10px;border:1px solid #e1bee7;}
.t06 .meta-card .lbl{font-size:8px;text-transform:uppercase;color:#888;margin-bottom:2px;letter-spacing:.5px;}
.t06 .meta-card .val{font-size:11px;font-weight:700;color:#4a0072;}
.t06 table.it{width:100%;border-collapse:collapse;}
.t06 .it th{background:#4a0072;color:#fff;padding:7px 6px;font-size:9px;text-transform:uppercase;}
.t06 .it td{padding:6px;border-bottom:1px solid #f0e8f8;font-size:11px;}
.t06 .it tfoot td{background:#f3e5f5;font-weight:700;border-top:2px solid #4a0072;}
.t06 .it td.r,.t06 .it th.r{text-align:right;} .t06 .it td.c,.t06 .it th.c{text-align:center;}
.t06 .it tbody tr:hover td{background:#fdf5ff;}
.t06 .note{background:#fff8e1;border:1px solid #ffcc02;border-radius:6px;padding:8px 12px;font-size:9px;color:#555;margin-bottom:12px;}
.t06 .bottom-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
.t06 .bank-box .bank-row{display:flex;gap:8px;margin-bottom:4px;font-size:10px;}
.t06 .bank-box .bank-row .k{font-size:9px;text-transform:uppercase;color:#888;font-weight:700;width:80px;flex-shrink:0;}
.t06 .bank-box .bank-row .v{color:#333;}
.t06 .tots-box table{width:100%;font-size:11px;border-collapse:collapse;}
.t06 .tots-box td{padding:4px 6px;border-bottom:1px solid #f0e8f8;}
.t06 .tots-box td.l{color:#555;} .t06 .tots-box td.r{text-align:right;font-weight:700;}
.t06 .tots-box tr.grand td{background:#4a0072;color:#fff;font-size:12px;font-weight:900;padding:6px;}
.t06 .words{font-size:9px;font-style:italic;color:#666;text-align:center;padding:6px;background:#f8f0ff;border-radius:6px;margin-bottom:12px;}
.t06 .sig-box{text-align:right;font-size:11px;}
.t06 .sb .co{font-weight:700;color:#4a0072;font-size:12px;}
.t06 .sb .ln{border-top:1px solid #9c27b0;margin:20px 0 4px;} .t06 .sb .desig{font-size:10px;color:#888;}
</style>
<div class="t06">
  <!-- Company Box -->
  <div class="box">
    <div class="box-header">Company Information</div>
    <div class="box-body company-box">
      <div class="logo-circle">
        <?php if(!empty($inv['company_logo'])&&file_exists($inv['company_logo'])): ?><img src="<?=sanitize($inv['company_logo'])?>" alt="Logo">
        <?php else: ?><div class="no-logo">₹</div><?php endif; ?>
      </div>
      <div style="flex:1">
        <div class="co-name"><?=sanitize($inv['company_name']??'')?></div>
        <div class="co-addr"><?=nl2br(sanitize($inv['company_address']??''))?>
          <?php if(!empty($inv['company_phone'])): ?> | Ph: <?=sanitize($inv['company_phone'])?><?php endif; ?>
        </div>
        <div class="co-gst">GSTIN: <?=sanitize($inv['gst_number']??'')?></div>
      </div>
      <div style="text-align:right;min-width:160px">
        <div class="inv-title"><?=sanitize($inv['invoice_title']??'Tax Invoice')?></div>
        <div style="font-size:11px;color:#4a0072;font-weight:600"># <?=sanitize($inv['invoice_number']??'')?></div>
        <div style="font-size:10px;color:#888;margin-top:4px"><?=sanitize($inv['invoice_date']??'')?></div>
      </div>
    </div>
  </div>
  <!-- Invoice Meta -->
  <div class="box">
    <div class="box-header">Invoice Details</div>
    <div class="box-body">
      <div class="meta-grid">
        <div class="meta-card"><div class="lbl">Bill To</div><div class="val"><?=sanitize($inv['bill_to']??'')?></div></div>
        <div class="meta-card"><div class="lbl">Invoice Number</div><div class="val"><?=sanitize($inv['invoice_number']??'')?></div></div>
        <div class="meta-card"><div class="lbl">Date<?php if(!empty($inv['vehicle_number'])): ?> / Vehicle<?php endif; ?></div><div class="val"><?=sanitize($inv['invoice_date']??'')?><?php if(!empty($inv['vehicle_number'])): ?><br><small><?=sanitize($inv['vehicle_number'])?></small><?php endif; ?></div></div>
      </div>
    </div>
  </div>
  <!-- Items -->
  <div class="box">
    <div class="box-header">Items &amp; Services</div>
    <table class="it"><thead><tr>
      <th class="c">#</th><th>Item &amp; Description</th><th class="c">HSN/SAC</th>
      <th class="r">Qty</th><th class="c">Per</th><th class="r">Rate(₹)</th>
      <th class="r">CGST<?=CGST_RATE?>%</th><th class="r">SGST<?=SGST_RATE?>%</th><th class="r">Amount(₹)</th>
    </tr></thead>
    <tbody><?php foreach($inv['items']??[] as $it): ?><tr>
      <td class="c"><?=(int)$it['serial']?></td><td><?=sanitize($it['description']??'')?></td>
      <td class="c"><?=sanitize($it['hsn']??'')?></td><td class="r"><?=number_format((float)($it['quantity']??0),2)?></td>
      <td class="c"><?=sanitize($it['per_unit']??'')?></td><td class="r"><?=formatIndianCurrency($it['rate']??0)?></td>
      <td class="r"><?=formatIndianCurrency($it['cgst']??0)?></td><td class="r"><?=formatIndianCurrency($it['sgst']??0)?></td>
      <td class="r"><?=formatIndianCurrency($it['amount']??0)?></td>
    </tr><?php endforeach; ?></tbody>
    <tfoot><tr><td colspan="6" class="r"><b>Totals</b></td>
      <td class="r"><?=formatIndianCurrency($inv['totals']['cgst_total']??0)?></td>
      <td class="r"><?=formatIndianCurrency($inv['totals']['sgst_total']??0)?></td>
      <td class="r"><?=formatIndianCurrency($inv['totals']['subtotal']??0)?></td>
    </tr></tfoot></table>
  </div>
  <?php if(!empty($inv['notes'])): ?><div class="note">📌 <?=sanitize($inv['notes'])?></div><?php endif; ?>
  <div class="bottom-grid">
    <div class="box bank-box">
      <div class="box-header">Bank Details</div>
      <div class="box-body">
        <?php if(!empty($inv['bank_account'])): ?><div class="bank-row"><span class="k">Account</span><span class="v"><?=sanitize($inv['bank_account'])?></span></div><?php endif; ?>
        <?php if(!empty($inv['bank_ifsc'])): ?><div class="bank-row"><span class="k">IFSC</span><span class="v"><?=sanitize($inv['bank_ifsc'])?></span></div><?php endif; ?>
        <?php if(!empty($inv['bank_branch'])): ?><div class="bank-row"><span class="k">Branch</span><span class="v"><?=sanitize($inv['bank_branch'])?></span></div><?php endif; ?>
      </div>
    </div>
    <div class="box tots-box">
      <div class="box-header">Payment Summary</div>
      <div class="box-body" style="padding:0">
        <table><tr><td class="l">Taxable Amount</td><td class="r"><?=formatIndianCurrency($inv['totals']['subtotal']??0)?></td></tr>
        <tr><td class="l">CGST @<?=CGST_RATE?>%</td><td class="r"><?=formatIndianCurrency($inv['totals']['cgst_total']??0)?></td></tr>
        <tr><td class="l">SGST @<?=SGST_RATE?>%</td><td class="r"><?=formatIndianCurrency($inv['totals']['sgst_total']??0)?></td></tr>
        <tr class="grand"><td>TOTAL AMOUNT</td><td class="r"><?=formatIndianCurrency($inv['totals']['total']??0)?></td></tr>
        <tr><td class="l">Payment Made</td><td class="r"><?=formatIndianCurrency($inv['payment_made']??0)?></td></tr>
        <tr><td class="l"><b>Balance Due</b></td><td class="r"><b><?=formatIndianCurrency($inv['balance_due']??0)?></b></td></tr>
        </table>
      </div>
    </div>
  </div>
  <div class="words">In Words: <?=sanitize($inv['amount_words']??'')?></div>
  <div class="box"><div class="box-body sig-box"><div class="sb">
    <div class="co">For <?=sanitize($inv['company_name']??'')?></div>
    <div class="ln"></div><div class="desig"><?=sanitize($inv['proprietor']??'Proprietor')?></div>
  </div></div></div>
</div>

