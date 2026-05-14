<?php if(!isset($inv)) die(); if(!defined('CGST_RATE')) define('CGST_RATE',3); if(!defined('SGST_RATE')) define('SGST_RATE',3); ?>
<style>
.t10{font-family:Tahoma,Geneva,sans-serif;font-size:10px;background:#fff;max-width:840px;margin:0 auto;border:1px solid #cfd8dc;}
.t10 .top-bar{background:#263238;height:8px;}
.t10 .hd{padding:10px 14px;border-bottom:1px solid #cfd8dc;display:flex;justify-content:space-between;align-items:center;}
.t10 .hd .left{display:flex;align-items:center;gap:10px;}
.t10 .hd .badge{width:55px;height:50px;background:#263238;border-radius:4px;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;}
.t10 .hd .badge img{max-width:52px;max-height:48px;object-fit:contain;}
.t10 .hd .badge .no-logo{color:#78909c;font-size:16px;font-weight:900;}
.t10 .hd .co-name{font-size:14px;font-weight:700;color:#263238;}
.t10 .hd .co-addr{font-size:8px;color:#777;line-height:1.6;margin-top:2px;}
.t10 .hd .co-gst{font-size:8px;font-weight:700;color:#263238;margin-top:2px;}
.t10 .hd .inv-right{text-align:right;}
.t10 .hd .inv-right .title{font-size:11px;font-weight:700;color:#263238;letter-spacing:2px;text-transform:uppercase;}
.t10 .hd .inv-right .invnum{font-size:9px;color:#777;margin-top:3px;}
.t10 .hd .inv-right .amt{font-size:13px;font-weight:900;color:#263238;margin-top:4px;}
.t10 .meta-strip{background:#eceff1;padding:6px 14px;border-bottom:1px solid #cfd8dc;display:flex;gap:16px;flex-wrap:wrap;}
.t10 .meta-strip .ms{font-size:9px;} .t10 .meta-strip .ms .k{color:#90a4ae;font-weight:700;} .t10 .meta-strip .ms .v{font-weight:700;color:#263238;}
.t10 table.it{width:100%;border-collapse:collapse;}
.t10 .it th{background:#37474f;color:#cfd8dc;padding:5px 4px;font-size:8px;text-transform:uppercase;letter-spacing:.3px;}
.t10 .it td{padding:4px;border-bottom:1px solid #eceff1;font-size:9px;}
.t10 .it tfoot td{background:#eceff1;font-weight:700;border-top:1px solid #263238;font-size:9px;}
.t10 .it td.r,.t10 .it th.r{text-align:right;} .t10 .it td.c,.t10 .it th.c{text-align:center;}
.t10 .it tbody tr:nth-child(even) td{background:#f5f7f8;}
.t10 .note{font-size:8px;color:#666;background:#fff8e1;border-left:3px solid #ffc107;padding:4px 14px;}
.t10 .ft{display:flex;border-top:2px solid #263238;background:#f5f7f8;}
.t10 .ft .bank{flex:1;padding:8px 14px;font-size:8px;line-height:1.8;border-right:1px dashed #b0bec5;}
.t10 .ft .bank strong{font-size:7px;text-transform:uppercase;color:#90a4ae;display:block;}
.t10 .ft .tots{min-width:230px;padding:8px 14px;}
.t10 .ft .tots table{width:100%;font-size:9px;border-collapse:collapse;}
.t10 .ft .tots td{padding:3px 4px;border-bottom:1px solid #eceff1;}
.t10 .ft .tots td.l{color:#555;} .t10 .ft .tots td.r{text-align:right;font-weight:700;}
.t10 .ft .tots tr.grand td{background:#263238;color:#fff;font-size:10px;font-weight:700;padding:4px;}
.t10 .words{font-size:8px;font-style:italic;color:#666;padding:4px 14px;background:#eceff1;border-top:1px dashed #b0bec5;}
.t10 .sig{display:flex;justify-content:flex-end;padding:8px 14px;border-top:1px solid #cfd8dc;}
.t10 .sb{text-align:center;} .t10 .sb .co{font-weight:700;color:#263238;font-size:10px;}
.t10 .sb .ln{border-top:1px solid #546e7a;margin:14px auto 3px;width:120px;} .t10 .sb .desig{font-size:8px;color:#78909c;}
.t10 .bot-bar{background:#263238;height:5px;}
</style>
<div class="t10">
  <div class="top-bar"></div>
  <div class="hd">
    <div class="left">
      <div class="badge">
        <?php if(!empty($inv['company_logo'])&&file_exists($inv['company_logo'])): ?><img src="<?=sanitize($inv['company_logo'])?>" alt="Logo">
        <?php else: ?><div class="no-logo">₹</div><?php endif; ?>
      </div>
      <div>
        <div class="co-name"><?=sanitize($inv['company_name']??'')?></div>
        <div class="co-addr"><?=nl2br(sanitize($inv['company_address']??''))?>
          <?php if(!empty($inv['company_phone'])): ?> | Ph: <?=sanitize($inv['company_phone'])?><?php endif; ?>
        </div>
        <div class="co-gst">GSTIN: <?=sanitize($inv['gst_number']??'')?></div>
      </div>
    </div>
    <div class="inv-right">
      <div class="title"><?=sanitize($inv['invoice_title']??'Tax Invoice')?></div>
      <div class="invnum"><?=sanitize($inv['invoice_number']??'')?> | <?=sanitize($inv['invoice_date']??'')?></div>
      <div class="amt"><?=formatIndianCurrency($inv['totals']['total']??0)?></div>
    </div>
  </div>
  <div class="meta-strip">
    <div class="ms"><span class="k">Bill To: </span><span class="v"><?=sanitize($inv['bill_to']??'')?></span></div>
    <div class="ms"><span class="k">Inv#: </span><span class="v"><?=sanitize($inv['invoice_number']??'')?></span></div>
    <div class="ms"><span class="k">Date: </span><span class="v"><?=sanitize($inv['invoice_date']??'')?></span></div>
    <?php if(!empty($inv['vehicle_number'])): ?><div class="ms"><span class="k">Vehicle: </span><span class="v"><?=sanitize($inv['vehicle_number'])?></span></div><?php endif; ?>
    <div class="ms"><span class="k">Balance: </span><span class="v"><?=formatIndianCurrency($inv['balance_due']??0)?></span></div>
  </div>
  <table class="it"><thead><tr>
    <th class="c">#</th><th>Item &amp; Description</th><th class="c">HSN</th>
    <th class="r">Qty</th><th class="c">Per</th><th class="r">Rate</th>
    <th class="r">CGST<?=CGST_RATE?>%</th><th class="r">SGST<?=SGST_RATE?>%</th><th class="r">Amount</th>
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
      <tr><td class="l">Taxable</td><td class="r"><?=formatIndianCurrency($inv['totals']['subtotal']??0)?></td></tr>
      <tr><td class="l">CGST @<?=CGST_RATE?>%</td><td class="r"><?=formatIndianCurrency($inv['totals']['cgst_total']??0)?></td></tr>
      <tr><td class="l">SGST @<?=SGST_RATE?>%</td><td class="r"><?=formatIndianCurrency($inv['totals']['sgst_total']??0)?></td></tr>
      <tr class="grand"><td>TOTAL</td><td class="r"><?=formatIndianCurrency($inv['totals']['total']??0)?></td></tr>
      <tr><td class="l">Paid</td><td class="r"><?=formatIndianCurrency($inv['payment_made']??0)?></td></tr>
      <tr><td class="l"><b>Balance</b></td><td class="r"><b><?=formatIndianCurrency($inv['balance_due']??0)?></b></td></tr>
    </table></div>
  </div>
  <div class="words">In Words: <?=sanitize($inv['amount_words']??'')?></div>
  <div class="sig"><div class="sb">
    <div class="co">For <?=sanitize($inv['company_name']??'')?></div>
    <div class="ln"></div><div class="desig"><?=sanitize($inv['proprietor']??'Proprietor')?></div>
  </div></div>
  <div class="bot-bar"></div>
</div>
