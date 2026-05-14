<?php if(!isset($inv)) die(); if(!defined('CGST_RATE')) define('CGST_RATE',3); if(!defined('SGST_RATE')) define('SGST_RATE',3); ?>
<style>
.t03{font-family:Georgia,serif;font-size:12px;background:#fff;max-width:840px;margin:0 auto;position:relative;overflow:hidden;}
.t03::before{content:'GST';position:absolute;top:50%;left:50%;transform:translate(-50%,-50%) rotate(-30deg);font-size:180px;font-weight:900;color:rgba(27,94,32,.04);pointer-events:none;z-index:0;}
.t03>*{position:relative;z-index:1;}
.t03 .hd{text-align:center;padding:20px;border-bottom:4px solid #1b5e20;}
.t03 .hd .logo-box{margin-bottom:10px;}
.t03 .hd .logo-box img{max-height:80px;max-width:160px;object-fit:contain;}
.t03 .hd .logo-box .no-logo{display:inline-block;width:70px;height:60px;background:linear-gradient(135deg,#1b5e20,#43a047);border-radius:50%;color:#fff;font-size:24px;font-weight:900;line-height:60px;}
.t03 .hd .co-name{font-size:22px;font-weight:700;color:#1b5e20;letter-spacing:1px;margin-bottom:4px;}
.t03 .hd .co-addr{font-size:10px;color:#555;line-height:1.6;}
.t03 .hd .co-gst{display:inline-block;background:#e8f5e9;border:1px solid #a5d6a7;border-radius:20px;padding:3px 12px;font-size:10px;font-weight:700;color:#1b5e20;margin-top:6px;}
.t03 .ribbon{background:linear-gradient(90deg,#1b5e20,#2e7d32,#1b5e20);color:#fff;text-align:center;padding:6px;font-size:14px;font-weight:700;letter-spacing:4px;}
.t03 .meta-grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:0;border-bottom:2px solid #e8f5e9;}
.t03 .meta-grid .mg{padding:10px 16px;border-right:1px solid #e8f5e9;}
.t03 .meta-grid .mg:last-child{border-right:none;}
.t03 .meta-grid .mg label{font-size:9px;font-weight:700;text-transform:uppercase;color:#888;display:block;margin-bottom:3px;font-family:'Segoe UI',sans-serif;}
.t03 .meta-grid .mg .v{font-size:12px;font-weight:700;color:#1b5e20;}
.t03 table.it{width:100%;border-collapse:collapse;}
.t03 .it th{background:#2e7d32;color:#fff;padding:7px 6px;font-size:9px;font-family:'Segoe UI',sans-serif;text-transform:uppercase;}
.t03 .it td{padding:6px;border-bottom:1px solid #e8f5e9;font-size:11px;}
.t03 .it tfoot td{background:#e8f5e9;font-weight:700;border-top:2px solid #1b5e20;font-family:'Segoe UI',sans-serif;}
.t03 .it td.r,.t03 .it th.r{text-align:right;} .t03 .it td.c,.t03 .it th.c{text-align:center;}
.t03 .it tbody tr:nth-child(even) td{background:#f9fef9;}
.t03 .note{font-size:9px;background:#f1f8e9;border:1px solid #c5e1a5;padding:6px 16px;margin:0;}
.t03 .ft{display:flex;border-top:3px solid #1b5e20;}
.t03 .ft .bank{flex:1;padding:12px 16px;font-size:10px;line-height:1.8;border-right:2px dashed #c8e6c9;font-family:'Segoe UI',sans-serif;}
.t03 .ft .bank strong{font-size:9px;text-transform:uppercase;color:#888;display:block;}
.t03 .ft .tots{min-width:260px;padding:12px 16px;}
.t03 .ft .tots table{width:100%;font-size:11px;border-collapse:collapse;font-family:'Segoe UI',sans-serif;}
.t03 .ft .tots td{padding:4px 6px;border-bottom:1px solid #e8f5e9;}
.t03 .ft .tots td.l{color:#555;} .t03 .ft .tots td.r{text-align:right;font-weight:700;}
.t03 .ft .tots tr.grand td{background:#1b5e20;color:#fff;font-weight:900;padding:6px;}
.t03 .words{font-style:italic;font-size:9px;color:#555;background:#f1f8e9;padding:5px 16px;border-top:1px dashed #c5e1a5;font-family:'Segoe UI',sans-serif;}
.t03 .sig{display:flex;justify-content:space-between;padding:12px 16px;border-top:1px solid #e8f5e9;}
.t03 .sb{text-align:center;} .t03 .sb .co{font-weight:700;color:#1b5e20;font-size:12px;}
.t03 .sb .ln{border-top:1px solid #555;margin:20px auto 4px;width:140px;} .t03 .sb .desig{font-size:10px;color:#666;font-family:'Segoe UI',sans-serif;}
</style>
<div class="t03">
  <div class="hd">
    <div class="logo-box">
      <?php if(!empty($inv['company_logo'])&&file_exists($inv['company_logo'])): ?><img src="<?=sanitize($inv['company_logo'])?>" alt="Logo">
      <?php else: ?><div class="no-logo">₹</div><?php endif; ?>
    </div>
    <div class="co-name"><?=sanitize($inv['company_name']??'')?></div>
    <div class="co-addr"><?=nl2br(sanitize($inv['company_address']??''))?>
      <?php if(!empty($inv['company_phone'])): ?> | Ph: <?=sanitize($inv['company_phone'])?><?php endif; ?>
    </div>
    <div class="co-gst">GSTIN: <?=sanitize($inv['gst_number']??'')?></div>
  </div>
  <div class="ribbon"><?=strtoupper(sanitize($inv['invoice_title']??'TAX INVOICE'))?></div>
  <div class="meta-grid">
    <div class="mg"><label>Bill To</label><div class="v"><?=sanitize($inv['bill_to']??'')?></div></div>
    <div class="mg"><label>Invoice No.</label><div class="v"><?=sanitize($inv['invoice_number']??'')?></div></div>
    <div class="mg"><label>Date<?php if(!empty($inv['vehicle_number'])): ?> | Vehicle<?php endif; ?></label>
      <div class="v"><?=sanitize($inv['invoice_date']??'')?><?php if(!empty($inv['vehicle_number'])): ?><br><span style="font-size:11px"><?=sanitize($inv['vehicle_number'])?></span><?php endif; ?></div>
    </div>
  </div>
  <table class="it"><thead><tr>
    <th class="c">#</th><th>Item &amp; Description</th><th class="c">HSN/SAC</th>
    <th class="r">Qty</th><th class="r">Rate(₹)</th>
    <th class="r">CGST<?=CGST_RATE?>%</th><th class="r">SGST<?=SGST_RATE?>%</th><th class="r">Amount(₹)</th>
  </tr></thead>
  <tbody><?php foreach($inv['items']??[] as $it): ?><tr>
    <td class="c"><?=(int)$it['serial']?></td><td><?=sanitize($it['description']??'')?></td>
    <td class="c"><?=sanitize($it['hsn']??'')?></td><td class="r"><?=number_format((float)($it['quantity']??0),2)?></td>
    <td class="r"><?=formatIndianCurrency($it['rate']??0)?></td>
    <td class="r"><?=formatIndianCurrency($it['cgst']??0)?></td><td class="r"><?=formatIndianCurrency($it['sgst']??0)?></td>
    <td class="r"><?=formatIndianCurrency($it['amount']??0)?></td>
  </tr><?php endforeach; ?></tbody>
  <tfoot><tr><td colspan="5" class="r"><b>Totals</b></td>
    <td class="r"><?=formatIndianCurrency($inv['totals']['cgst_total']??0)?></td>
    <td class="r"><?=formatIndianCurrency($inv['totals']['sgst_total']??0)?></td>
    <td class="r"><?=formatIndianCurrency($inv['totals']['subtotal']??0)?></td>
  </tr></tfoot></table>
  <?php if(!empty($inv['notes'])): ?><div class="note">📌 <?=sanitize($inv['notes'])?></div><?php endif; ?>
  <div class="ft">
    <div class="bank"><strong>Bank Details</strong>
      <?php if(!empty($inv['bank_account'])): ?>ACC: <?=sanitize($inv['bank_account'])?><br><?php endif; ?>
      <?php if(!empty($inv['bank_ifsc'])): ?>IFSC: <?=sanitize($inv['bank_ifsc'])?><br><?php endif; ?>
      <?php if(!empty($inv['bank_branch'])): ?>Branch: <?=sanitize($inv['bank_branch'])?><?php endif; ?>
    </div>
    <div class="tots"><table>
      <tr><td class="l">Taxable Amount</td><td class="r"><?=formatIndianCurrency($inv['totals']['subtotal']??0)?></td></tr>
      <tr><td class="l">CGST @<?=CGST_RATE?>%</td><td class="r"><?=formatIndianCurrency($inv['totals']['cgst_total']??0)?></td></tr>
      <tr><td class="l">SGST @<?=SGST_RATE?>%</td><td class="r"><?=formatIndianCurrency($inv['totals']['sgst_total']??0)?></td></tr>
      <tr class="grand"><td>TOTAL</td><td class="r"><?=formatIndianCurrency($inv['totals']['total']??0)?></td></tr>
      <tr><td class="l">Payment Made</td><td class="r"><?=formatIndianCurrency($inv['payment_made']??0)?></td></tr>
      <tr><td class="l"><b>Balance Due</b></td><td class="r"><b><?=formatIndianCurrency($inv['balance_due']??0)?></b></td></tr>
    </table></div>
  </div>
  <div class="words">In Words: <?=sanitize($inv['amount_words']??'')?></div>
  <div class="sig">
    <div style="font-size:10px;color:#666;font-style:italic;align-self:flex-end">Thank you for your business!</div>
    <div class="sb"><div class="co">For <?=sanitize($inv['company_name']??'')?></div>
    <div class="ln"></div><div class="desig"><?=sanitize($inv['proprietor']??'Proprietor')?></div></div>
  </div>
</div>
