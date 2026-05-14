<?php if(!isset($inv)) die(); if(!defined('CGST_RATE')) define('CGST_RATE',3); if(!defined('SGST_RATE')) define('SGST_RATE',3); ?>
<style>
.t05{font-family:'Arial Black',Arial,sans-serif;font-size:12px;background:#fff;max-width:840px;margin:0 auto;border-top:8px solid #ffcc02;}
.t05 .top-stripe{background:#7b0000;color:#fff;padding:20px 20px 16px;display:flex;align-items:center;justify-content:space-between;}
.t05 .top-stripe .left-co{flex:1;}
.t05 .top-stripe .co-name{font-size:28px;font-weight:900;letter-spacing:2px;text-transform:uppercase;line-height:1.1;}
.t05 .top-stripe .co-sub{font-size:10px;opacity:.8;margin-top:5px;line-height:1.5;font-family:Arial,sans-serif;font-weight:normal;}
.t05 .top-stripe .logo-right{background:#fff;border-radius:8px;padding:8px;min-width:80px;min-height:60px;display:flex;align-items:center;justify-content:center;margin-left:16px;}
.t05 .top-stripe .logo-right img{max-width:110px;max-height:65px;object-fit:contain;}
.t05 .top-stripe .logo-right .no-logo{font-size:24px;font-weight:900;color:#7b0000;}
.t05 .title-bar{background:#ffcc02;color:#7b0000;text-align:center;padding:7px;font-size:14px;font-weight:900;letter-spacing:4px;}
.t05 .gst-bar{background:#111;color:#ffcc02;text-align:center;padding:4px;font-size:10px;font-weight:700;letter-spacing:2px;}
.t05 .meta-wrap{display:flex;border-bottom:2px solid #7b0000;}
.t05 .meta-wrap .mbox{flex:1;padding:10px 16px;border-right:1px solid #eee;}
.t05 .meta-wrap .mbox:last-child{border-right:none;}
.t05 .meta-wrap .mbox label{font-size:8px;font-weight:700;text-transform:uppercase;color:#888;display:block;margin-bottom:2px;font-family:Arial,sans-serif;}
.t05 .meta-wrap .mbox .v{font-size:12px;font-weight:700;color:#7b0000;}
.t05 .meta-wrap .mbox .vs{font-size:11px;font-family:Arial,sans-serif;}
.t05 table.it{width:100%;border-collapse:collapse;}
.t05 .it th{background:#7b0000;color:#ffcc02;padding:7px 6px;font-size:9px;letter-spacing:.5px;}
.t05 .it td{padding:6px;border-bottom:1px solid #fff0f0;font-size:11px;font-family:Arial,sans-serif;}
.t05 .it tfoot td{background:#fff0f0;font-weight:700;border-top:2px solid #7b0000;font-family:'Arial Black',Arial,sans-serif;}
.t05 .it td.r,.t05 .it th.r{text-align:right;} .t05 .it td.c,.t05 .it th.c{text-align:center;}
.t05 .it tbody tr:nth-child(odd) td{background:#fffafa;}
.t05 .note{font-size:9px;background:#fff8e1;border:1px solid #ffcc02;border-left:4px solid #ffcc02;padding:5px 16px;font-family:Arial,sans-serif;}
.t05 .ft{display:flex;background:#111;color:#fff;}
.t05 .ft .bank{flex:1;padding:12px 16px;font-size:9px;line-height:1.8;border-right:1px solid #333;}
.t05 .ft .bank strong{font-size:8px;text-transform:uppercase;color:#ffcc02;display:block;margin-bottom:2px;}
.t05 .ft .tots{min-width:260px;padding:12px 16px;}
.t05 .ft .tots table{width:100%;font-size:10px;border-collapse:collapse;}
.t05 .ft .tots td{padding:3px 6px;border-bottom:1px solid #333;}
.t05 .ft .tots td.l{color:#ccc;} .t05 .ft .tots td.r{text-align:right;font-weight:700;}
.t05 .ft .tots tr.grand td{background:#ffcc02;color:#7b0000;font-size:12px;font-weight:900;padding:5px 6px;}
.t05 .words{font-size:9px;font-style:italic;color:#ccc;padding:5px 16px;background:#111;border-top:1px solid #333;}
.t05 .sig{background:#f9f0e1;display:flex;justify-content:flex-end;padding:12px 16px;border-top:4px solid #ffcc02;}
.t05 .sb{text-align:center;} .t05 .sb .co{font-weight:900;color:#7b0000;font-size:13px;}
.t05 .sb .ln{border-top:1px solid #7b0000;margin:20px auto 4px;width:150px;} .t05 .sb .desig{font-size:10px;color:#555;font-family:Arial,sans-serif;}
</style>
<div class="t05">
  <div class="top-stripe">
    <div class="left-co">
      <div class="co-name"><?=sanitize($inv['company_name']??'')?></div>
      <div class="co-sub"><?=nl2br(sanitize($inv['company_address']??''))?><?php if(!empty($inv['company_phone'])): ?> | Ph: <?=sanitize($inv['company_phone'])?><?php endif; ?></div>
    </div>
    <div class="logo-right">
      <?php if(!empty($inv['company_logo'])&&file_exists($inv['company_logo'])): ?><img src="<?=sanitize($inv['company_logo'])?>" alt="Logo">
      <?php else: ?><div class="no-logo">₹</div><?php endif; ?>
    </div>
  </div>
  <div class="title-bar"><?=strtoupper(sanitize($inv['invoice_title']??'TAX INVOICE'))?></div>
  <div class="gst-bar">GSTIN: <?=sanitize($inv['gst_number']??'')?>  |  Invoice: <?=sanitize($inv['invoice_number']??'')?>  |  Date: <?=sanitize($inv['invoice_date']??'')?></div>
  <div class="meta-wrap">
    <div class="mbox"><label>Bill To</label><div class="v"><?=sanitize($inv['bill_to']??'')?></div></div>
    <div class="mbox"><label>Invoice No.</label><div class="vs"><?=sanitize($inv['invoice_number']??'')?></div></div>
    <div class="mbox"><label>Bill Date</label><div class="vs"><?=sanitize($inv['invoice_date']??'')?></div></div>
    <?php if(!empty($inv['vehicle_number'])): ?><div class="mbox"><label>Vehicle No.</label><div class="v"><?=sanitize($inv['vehicle_number'])?></div></div><?php endif; ?>
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
      <tr><td class="l">Balance Due</td><td class="r"><?=formatIndianCurrency($inv['balance_due']??0)?></td></tr>
    </table></div>
  </div>
  <div class="words">In Words: <?=sanitize($inv['amount_words']??'')?></div>
  <div class="sig"><div class="sb">
    <div class="co">For <?=sanitize($inv['company_name']??'')?></div>
    <div class="ln"></div><div class="desig"><?=sanitize($inv['proprietor']??'Proprietor')?></div>
  </div></div>
</div>
