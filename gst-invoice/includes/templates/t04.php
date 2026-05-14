<?php if(!isset($inv)) die(); if(!defined('CGST_RATE')) define('CGST_RATE',3); if(!defined('SGST_RATE')) define('SGST_RATE',3); ?>
<style>
.t04{font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:11px;background:#fff;max-width:840px;margin:0 auto;color:#222;}
.t04 .hd{padding:24px 24px 18px;border-bottom:1px solid #ddd;display:flex;justify-content:space-between;align-items:flex-start;}
.t04 .hd .left{display:flex;align-items:flex-start;gap:16px;}
.t04 .hd .left img{max-width:80px;max-height:65px;object-fit:contain;}
.t04 .hd .left .no-logo{width:55px;height:50px;border:2px solid #222;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:900;color:#222;}
.t04 .hd .co-name{font-size:17px;font-weight:700;letter-spacing:.5px;margin-bottom:4px;}
.t04 .hd .co-addr{font-size:9px;color:#777;line-height:1.7;}
.t04 .hd .co-gst{font-size:9px;font-weight:600;color:#444;margin-top:3px;}
.t04 .hd .right{text-align:right;}
.t04 .hd .right .title{font-size:11px;text-transform:uppercase;letter-spacing:3px;color:#999;margin-bottom:6px;}
.t04 .hd .right .invnum{font-size:18px;font-weight:700;color:#222;}
.t04 .hd .right .dt{font-size:10px;color:#777;margin-top:4px;}
.t04 .divider{height:2px;background:#222;margin:0;}
.t04 .info-row{display:flex;border-bottom:1px solid #eee;}
.t04 .info-row .cell{flex:1;padding:12px 24px;border-right:1px solid #eee;}
.t04 .info-row .cell:last-child{border-right:none;}
.t04 .info-row .cell .lbl{font-size:8px;text-transform:uppercase;letter-spacing:1px;color:#aaa;margin-bottom:3px;}
.t04 .info-row .cell .val{font-size:12px;font-weight:700;}
.t04 table.it{width:100%;border-collapse:collapse;}
.t04 .it th{border-bottom:2px solid #222;padding:8px 6px;font-size:8px;text-transform:uppercase;letter-spacing:.5px;text-align:left;color:#666;}
.t04 .it th.r,.t04 .it td.r{text-align:right;} .t04 .it td.c,.t04 .it th.c{text-align:center;}
.t04 .it td{padding:7px 6px;border-bottom:1px solid #f0f0f0;font-size:10px;}
.t04 .it tfoot td{border-top:1px solid #222;border-bottom:none;font-weight:600;padding-top:8px;}
.t04 .note{font-size:9px;color:#666;border-top:1px dashed #ccc;padding:6px 24px;background:#f9f9f9;}
.t04 .bottom{background:#f5f5f5;display:flex;justify-content:space-between;padding:16px 24px;border-top:1px solid #ddd;}
.t04 .bottom .bank{font-size:9px;color:#777;line-height:1.8;}
.t04 .bottom .bank strong{font-size:8px;text-transform:uppercase;color:#aaa;display:block;margin-bottom:2px;}
.t04 .bottom .tots table{border-collapse:collapse;font-size:10px;}
.t04 .bottom .tots td{padding:3px 10px 3px 0;} .t04 .bottom .tots td.r{text-align:right;padding-right:0;padding-left:20px;font-weight:700;}
.t04 .bottom .tots tr.grand td{border-top:1px solid #222;font-size:11px;font-weight:900;padding-top:5px;}
.t04 .words{font-size:9px;color:#999;font-style:italic;padding:6px 24px;border-top:1px solid #eee;}
.t04 .sig{padding:12px 24px 18px;text-align:right;}
.t04 .sb .co{font-size:11px;font-weight:700;color:#222;}
.t04 .sb .ln{border-top:1px solid #aaa;margin:20px 0 4px;} .t04 .sb .desig{font-size:9px;color:#999;}
</style>
<div class="t04">
  <div class="hd">
    <div class="left">
      <?php if(!empty($inv['company_logo'])&&file_exists($inv['company_logo'])): ?><img src="<?=sanitize($inv['company_logo'])?>" alt="Logo">
      <?php else: ?><div class="no-logo">₹</div><?php endif; ?>
      <div>
        <div class="co-name"><?=sanitize($inv['company_name']??'')?></div>
        <div class="co-addr"><?=nl2br(sanitize($inv['company_address']??''))?>
          <?php if(!empty($inv['company_phone'])): ?><br>Ph: <?=sanitize($inv['company_phone'])?><?php endif; ?>
        </div>
        <div class="co-gst">GSTIN: <?=sanitize($inv['gst_number']??'')?></div>
      </div>
    </div>
    <div class="right">
      <div class="title">Tax Invoice</div>
      <div class="invnum"><?=sanitize($inv['invoice_number']??'')?></div>
      <div class="dt"><?=sanitize($inv['invoice_date']??'')?></div>
    </div>
  </div>
  <div class="divider"></div>
  <div class="info-row">
    <div class="cell"><div class="lbl">Billed To</div><div class="val"><?=sanitize($inv['bill_to']??'')?></div></div>
    <div class="cell"><div class="lbl">Invoice</div><div class="val"><?=sanitize($inv['invoice_number']??'')?></div></div>
    <div class="cell"><div class="lbl">Date</div><div class="val"><?=sanitize($inv['invoice_date']??'')?></div></div>
    <?php if(!empty($inv['vehicle_number'])): ?><div class="cell"><div class="lbl">Vehicle No.</div><div class="val"><?=sanitize($inv['vehicle_number'])?></div></div><?php endif; ?>
  </div>
  <table class="it" style="padding:0 24px;display:block;"><thead><tr>
    <th class="c" style="width:28px">#</th><th>Item &amp; Description</th><th class="c">HSN/SAC</th>
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
  <?php if(!empty($inv['notes'])): ?><div class="note">Note: <?=sanitize($inv['notes'])?></div><?php endif; ?>
  <div class="bottom">
    <div class="bank"><strong>Bank Details</strong>
      <?php if(!empty($inv['bank_account'])): ?>ACC: <?=sanitize($inv['bank_account'])?><br><?php endif; ?>
      <?php if(!empty($inv['bank_ifsc'])): ?>IFSC: <?=sanitize($inv['bank_ifsc'])?><br><?php endif; ?>
      <?php if(!empty($inv['bank_branch'])): ?>Branch: <?=sanitize($inv['bank_branch'])?><?php endif; ?>
    </div>
    <div class="tots"><table>
      <tr><td>Taxable</td><td class="r"><?=formatIndianCurrency($inv['totals']['subtotal']??0)?></td></tr>
      <tr><td>CGST @<?=CGST_RATE?>%</td><td class="r"><?=formatIndianCurrency($inv['totals']['cgst_total']??0)?></td></tr>
      <tr><td>SGST @<?=SGST_RATE?>%</td><td class="r"><?=formatIndianCurrency($inv['totals']['sgst_total']??0)?></td></tr>
      <tr class="grand"><td><b>TOTAL</b></td><td class="r"><b><?=formatIndianCurrency($inv['totals']['total']??0)?></b></td></tr>
      <tr><td>Paid</td><td class="r"><?=formatIndianCurrency($inv['payment_made']??0)?></td></tr>
      <tr><td><b>Balance</b></td><td class="r"><b><?=formatIndianCurrency($inv['balance_due']??0)?></b></td></tr>
    </table></div>
  </div>
  <div class="words">In Words: <?=sanitize($inv['amount_words']??'')?></div>
  <div class="sig"><div class="sb">
    <div class="co">For <?=sanitize($inv['company_name']??'')?></div>
    <div class="ln"></div><div class="desig"><?=sanitize($inv['proprietor']??'Proprietor')?></div>
  </div></div>
</div>
