<?php if(!isset($inv)) die(); if(!defined('CGST_RATE')) define('CGST_RATE',3); if(!defined('SGST_RATE')) define('SGST_RATE',3); ?>
<style>
.t09{font-family:'Courier New',Courier,monospace;font-size:11px;background:#fdf8f3;max-width:840px;margin:0 auto;border:3px double #5d4037;color:#3e2723;}
.t09 .hd{border-bottom:3px double #795548;padding:16px;display:flex;justify-content:space-between;align-items:flex-start;background:#fff9f0;}
.t09 .hd .left{display:flex;gap:14px;align-items:flex-start;}
.t09 .hd .left .logo-box{border:2px solid #795548;padding:6px;background:#fff;width:75px;height:65px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.t09 .hd .left .logo-box img{max-width:65px;max-height:55px;object-fit:contain;}
.t09 .hd .left .logo-box .no-logo{font-size:20px;font-weight:900;color:#5d4037;text-align:center;}
.t09 .hd .co-name{font-size:18px;font-weight:700;letter-spacing:1px;text-transform:uppercase;border-bottom:1px solid #bcaaa4;padding-bottom:4px;margin-bottom:4px;}
.t09 .hd .co-addr{font-size:9px;color:#5d4037;line-height:1.7;}
.t09 .hd .right{text-align:right;border:1px solid #795548;padding:8px;background:#fff9f0;}
.t09 .hd .right .rtitle{font-size:11px;font-weight:700;letter-spacing:2px;border-bottom:1px solid #bcaaa4;padding-bottom:4px;margin-bottom:6px;}
.t09 .hd .right .rrow{font-size:9px;margin-bottom:3px;}
.t09 .hd .right .rrow .k{color:#795548;}
.t09 .ruler{height:4px;background:repeating-linear-gradient(90deg,#5d4037 0px,#5d4037 8px,transparent 8px,transparent 12px);margin:0;}
.t09 .meta{display:flex;background:#f5e6c8;border-bottom:2px solid #a1887f;}
.t09 .meta .mc{flex:1;padding:8px 14px;border-right:1px solid #bcaaa4;}
.t09 .meta .mc:last-child{border-right:none;}
.t09 .meta .mc label{font-size:8px;font-weight:700;text-transform:uppercase;color:#795548;display:block;margin-bottom:2px;letter-spacing:.5px;}
.t09 .meta .mc .v{font-size:11px;font-weight:700;}
.t09 table.it{width:100%;border-collapse:collapse;}
.t09 .it th{background:#4e342e;color:#f5e6c8;padding:7px 6px;font-size:9px;text-transform:uppercase;border:1px solid #795548;}
.t09 .it td{padding:5px 6px;border:1px solid #d7ccc8;font-size:10px;}
.t09 .it tfoot td{background:#efebe9;font-weight:700;border:1px solid #a1887f;border-top:2px solid #4e342e;}
.t09 .it td.r,.t09 .it th.r{text-align:right;} .t09 .it td.c,.t09 .it th.c{text-align:center;}
.t09 .it tbody tr:nth-child(even) td{background:#fdf5ec;}
.t09 .note{font-size:9px;background:#fff9f0;border:1px dashed #bcaaa4;padding:5px 14px;font-style:italic;}
.t09 .ft{display:flex;border-top:3px double #795548;background:#fff9f0;}
.t09 .ft .bank{flex:1;padding:12px 14px;font-size:9px;line-height:1.8;border-right:2px dashed #bcaaa4;}
.t09 .ft .bank strong{font-size:8px;text-transform:uppercase;color:#795548;display:block;}
.t09 .ft .tots{min-width:250px;padding:10px 14px;}
.t09 .ft .tots table{width:100%;font-size:10px;border-collapse:collapse;}
.t09 .ft .tots td{padding:4px 6px;border-bottom:1px solid #d7ccc8;}
.t09 .ft .tots td.l{color:#5d4037;} .t09 .ft .tots td.r{text-align:right;font-weight:700;}
.t09 .ft .tots tr.grand td{background:#4e342e;color:#f5e6c8;font-size:11px;font-weight:700;padding:5px 6px;}
.t09 .words{font-size:9px;font-style:italic;color:#5d4037;background:#f5e6c8;padding:5px 14px;border-top:1px dashed #bcaaa4;}
.t09 .sig{display:flex;justify-content:flex-end;padding:10px 14px;border-top:2px double #795548;}
.t09 .sb{text-align:center;} .t09 .sb .co{font-weight:700;color:#3e2723;font-size:11px;}
.t09 .sb .ln{border-top:1px solid #5d4037;margin:18px auto 4px;width:140px;} .t09 .sb .desig{font-size:9px;color:#795548;}
</style>
<div class="t09">
  <div class="hd">
    <div class="left">
      <div class="logo-box">
        <?php if(!empty($inv['company_logo'])&&file_exists($inv['company_logo'])): ?><img src="<?=sanitize($inv['company_logo'])?>" alt="Logo">
        <?php else: ?><div class="no-logo">₹</div><?php endif; ?>
      </div>
      <div>
        <div class="co-name"><?=sanitize($inv['company_name']??'')?></div>
        <div class="co-addr"><?=nl2br(sanitize($inv['company_address']??''))?>
          <?php if(!empty($inv['company_phone'])): ?><br>Ph: <?=sanitize($inv['company_phone'])?><?php endif; ?>
        </div>
        <div class="co-addr">GSTIN: <?=sanitize($inv['gst_number']??'')?></div>
      </div>
    </div>
    <div class="right">
      <div class="rtitle"><?=strtoupper(sanitize($inv['invoice_title']??'TAX INVOICE'))?></div>
      <div class="rrow"><span class="k">Invoice: </span><?=sanitize($inv['invoice_number']??'')?></div>
      <div class="rrow"><span class="k">Date: </span><?=sanitize($inv['invoice_date']??'')?></div>
      <?php if(!empty($inv['vehicle_number'])): ?><div class="rrow"><span class="k">Vehicle: </span><?=sanitize($inv['vehicle_number'])?></div><?php endif; ?>
    </div>
  </div>
  <div class="ruler"></div>
  <div class="meta">
    <div class="mc"><label>Bill To</label><div class="v"><?=sanitize($inv['bill_to']??'')?></div></div>
    <div class="mc"><label>Invoice No.</label><div class="v"><?=sanitize($inv['invoice_number']??'')?></div></div>
    <div class="mc"><label>Date</label><div class="v"><?=sanitize($inv['invoice_date']??'')?></div></div>
    <div class="mc"><label>Grand Total</label><div class="v"><?=formatIndianCurrency($inv['totals']['total']??0)?></div></div>
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
  <?php if(!empty($inv['notes'])): ?><div class="note">Note: <?=sanitize($inv['notes'])?></div><?php endif; ?>
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
