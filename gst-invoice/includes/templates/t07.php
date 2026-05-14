<?php if(!isset($inv)) die(); if(!defined('CGST_RATE')) define('CGST_RATE',3); if(!defined('SGST_RATE')) define('SGST_RATE',3); ?>
<style>
.t07{font-family:Verdana,Geneva,sans-serif;font-size:11px;background:#fff;max-width:840px;margin:0 auto;border:1px solid #80cbc4;}
.t07 .hd{background:linear-gradient(135deg,#004d40,#00796b);color:#fff;padding:20px 20px 50px;position:relative;}
.t07 .hd .co-name{font-size:20px;font-weight:700;letter-spacing:1px;text-transform:uppercase;}
.t07 .hd .co-addr{font-size:9px;opacity:.8;margin-top:5px;line-height:1.6;}
.t07 .hd .gst-badge{background:rgba(255,255,255,.2);display:inline-block;padding:3px 10px;border-radius:20px;font-size:9px;font-weight:700;margin-top:6px;}
.t07 .hd .inv-top-right{position:absolute;top:16px;right:20px;text-align:right;}
.t07 .hd .inv-top-right .title{font-size:16px;font-weight:700;letter-spacing:3px;color:#b2dfdb;}
.t07 .hd .inv-top-right .invnum{font-size:10px;opacity:.8;margin-top:3px;}
.t07 .logo-float{position:absolute;bottom:-35px;left:20px;z-index:10;}
.t07 .logo-float .lf-inner{width:70px;height:70px;border-radius:50%;background:#fff;border:3px solid #00bfa5;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(0,0,0,.2);overflow:hidden;}
.t07 .logo-float .lf-inner img{max-width:60px;max-height:60px;object-fit:contain;}
.t07 .logo-float .lf-inner .no-logo{font-size:20px;font-weight:900;color:#004d40;}
.t07 .meta-section{margin-top:0;padding:45px 20px 10px 110px;border-bottom:2px solid #e0f2f1;background:#f1fffe;}
.t07 .meta-row{display:flex;gap:20px;}
.t07 .meta-row .mb{flex:1;}
.t07 .meta-row .mb label{font-size:8px;text-transform:uppercase;color:#888;display:block;margin-bottom:2px;letter-spacing:.5px;}
.t07 .meta-row .mb .v{font-size:12px;font-weight:700;color:#004d40;}
.t07 .meta-row .mb .vs{font-size:11px;}
.t07 table.it{width:100%;border-collapse:collapse;}
.t07 .it th{background:#004d40;color:#b2dfdb;padding:7px 6px;font-size:9px;text-transform:uppercase;letter-spacing:.5px;}
.t07 .it td{padding:6px;font-size:10px;border:none;}
.t07 .it tbody tr:nth-child(odd) td{background:#e0f2f1;}
.t07 .it tbody tr:nth-child(even) td{background:#fff;}
.t07 .it tfoot td{background:#004d40;color:#b2dfdb;font-weight:700;padding:7px 6px;}
.t07 .it td.r,.t07 .it th.r{text-align:right;} .t07 .it td.c,.t07 .it th.c{text-align:center;}
.t07 .note{font-size:9px;background:#e8f5e9;border:1px solid #a5d6a7;padding:5px 20px;}
.t07 .ft{display:flex;border-top:3px solid #004d40;}
.t07 .ft .bank{flex:1;padding:12px 20px;font-size:9px;line-height:1.8;border-right:1px dashed #80cbc4;}
.t07 .ft .bank strong{font-size:8px;text-transform:uppercase;color:#888;display:block;}
.t07 .ft .tots{min-width:260px;padding:12px 20px;}
.t07 .ft .tots table{width:100%;font-size:10px;border-collapse:collapse;}
.t07 .ft .tots td{padding:4px 6px;border-bottom:1px solid #e0f2f1;}
.t07 .ft .tots td.l{color:#555;} .t07 .ft .tots td.r{text-align:right;font-weight:700;}
.t07 .ft .tots tr.grand td{background:#004d40;color:#fff;font-weight:700;font-size:11px;padding:5px 6px;}
.t07 .words{font-size:9px;font-style:italic;color:#555;background:#e0f2f1;padding:5px 20px;border-top:1px dashed #80cbc4;}
.t07 .sig{background:#f1fffe;display:flex;justify-content:flex-end;padding:12px 20px;border-top:1px solid #e0f2f1;}
.t07 .sb{text-align:center;} .t07 .sb .co{font-weight:700;color:#004d40;font-size:11px;}
.t07 .sb .ln{border-top:1px solid #004d40;margin:18px auto 4px;width:140px;} .t07 .sb .desig{font-size:9px;color:#666;}
</style>
<div class="t07">
  <div class="hd" style="position:relative;">
    <div class="co-name"><?=sanitize($inv['company_name']??'')?></div>
    <div class="co-addr"><?=nl2br(sanitize($inv['company_address']??''))?>
      <?php if(!empty($inv['company_phone'])): ?> | Ph: <?=sanitize($inv['company_phone'])?><?php endif; ?>
    </div>
    <div class="gst-badge">GSTIN: <?=sanitize($inv['gst_number']??'')?></div>
    <div class="inv-top-right">
      <div class="title"><?=strtoupper(sanitize($inv['invoice_title']??'TAX INVOICE'))?></div>
      <div class="invnum"># <?=sanitize($inv['invoice_number']??'')?></div>
      <div class="invnum"><?=sanitize($inv['invoice_date']??'')?></div>
    </div>
    <div class="logo-float">
      <div class="lf-inner">
        <?php if(!empty($inv['company_logo'])&&file_exists($inv['company_logo'])): ?><img src="<?=sanitize($inv['company_logo'])?>" alt="Logo">
        <?php else: ?><div class="no-logo">₹</div><?php endif; ?>
      </div>
    </div>
  </div>
  <div class="meta-section">
    <div class="meta-row">
      <div class="mb"><label>Bill To</label><div class="v"><?=sanitize($inv['bill_to']??'')?></div></div>
      <div class="mb"><label>Invoice No.</label><div class="vs"><?=sanitize($inv['invoice_number']??'')?></div></div>
      <div class="mb"><label>Bill Date</label><div class="vs"><?=sanitize($inv['invoice_date']??'')?></div></div>
      <?php if(!empty($inv['vehicle_number'])): ?><div class="mb"><label>Vehicle No.</label><div class="v"><?=sanitize($inv['vehicle_number'])?></div></div><?php endif; ?>
    </div>
  </div>
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
  <div class="sig"><div class="sb">
    <div class="co">For <?=sanitize($inv['company_name']??'')?></div>
    <div class="ln"></div><div class="desig"><?=sanitize($inv['proprietor']??'Proprietor')?></div>
  </div></div>
</div>
