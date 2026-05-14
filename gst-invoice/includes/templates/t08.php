<?php if(!isset($inv)) die(); if(!defined('CGST_RATE')) define('CGST_RATE',3); if(!defined('SGST_RATE')) define('SGST_RATE',3); ?>
<style>
.t08{font-family:'Segoe UI',Arial,sans-serif;font-size:12px;background:#fff;max-width:840px;margin:0 auto;}
.t08 .hd{display:flex;min-height:130px;}
.t08 .hd .co-panel{flex:1;background:#fff;padding:18px 20px;border-bottom:3px solid #0d47a1;display:flex;align-items:flex-start;gap:14px;}
.t08 .hd .co-panel img{max-width:80px;max-height:70px;object-fit:contain;border:1px solid #e3f2fd;padding:4px;border-radius:6px;}
.t08 .hd .co-panel .no-logo{width:65px;height:60px;background:linear-gradient(135deg,#0d47a1,#1565c0);border-radius:6px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:22px;font-weight:900;flex-shrink:0;}
.t08 .hd .co-panel .co-name{font-size:17px;font-weight:700;color:#0d47a1;margin-bottom:4px;}
.t08 .hd .co-panel .co-addr{font-size:9px;color:#666;line-height:1.6;}
.t08 .hd .co-panel .co-gst{font-size:9px;font-weight:700;color:#0d47a1;margin-top:3px;}
.t08 .hd .inv-panel{width:240px;flex-shrink:0;background:linear-gradient(135deg,#0d47a1,#1565c0,#0288d1);color:#fff;padding:18px;display:flex;flex-direction:column;justify-content:space-between;}
.t08 .hd .inv-panel .title{font-size:14px;font-weight:700;letter-spacing:3px;margin-bottom:10px;border-bottom:1px solid rgba(255,255,255,.3);padding-bottom:8px;}
.t08 .hd .inv-panel .row{display:flex;justify-content:space-between;font-size:10px;margin-bottom:6px;}
.t08 .hd .inv-panel .row .k{opacity:.7;} .t08 .hd .inv-panel .row .v{font-weight:700;}
.t08 .hd .inv-panel .amt{background:rgba(255,255,255,.15);border-radius:6px;padding:6px 8px;margin-top:6px;text-align:center;}
.t08 .hd .inv-panel .amt .lbl{font-size:8px;opacity:.7;text-transform:uppercase;}
.t08 .hd .inv-panel .amt .val{font-size:14px;font-weight:900;color:#b3e5fc;}
.t08 .bill-bar{background:#e3f2fd;padding:8px 20px;border-bottom:1px solid #bbdefb;display:flex;align-items:center;gap:20px;font-size:11px;}
.t08 .bill-bar label{font-size:9px;text-transform:uppercase;color:#888;font-weight:700;}
.t08 .bill-bar .bn{font-size:13px;font-weight:700;color:#0d47a1;}
<?php if(!empty($inv['vehicle_number'])): ?>.t08 .bill-bar .veh{font-size:11px;color:#333;margin-left:auto;}<?php endif; ?>
.t08 table.it{width:100%;border-collapse:collapse;}
.t08 .it th{background:linear-gradient(90deg,#0d47a1,#1565c0);color:#fff;padding:7px 6px;font-size:9px;text-transform:uppercase;}
.t08 .it td{padding:6px;border-bottom:1px solid #e3f2fd;font-size:11px;}
.t08 .it tfoot td{background:#e3f2fd;font-weight:700;border-top:2px solid #0d47a1;}
.t08 .it td.r,.t08 .it th.r{text-align:right;} .t08 .it td.c,.t08 .it th.c{text-align:center;}
.t08 .it tbody tr:hover td{background:#f5f9ff;}
.t08 .note{font-size:9px;background:#fffde7;border-left:3px solid #f9a825;padding:6px 20px;}
.t08 .ft{display:flex;border-top:3px solid #0d47a1;background:linear-gradient(90deg,#f5f9ff,#fff);}
.t08 .ft .bank{flex:1;padding:12px 20px;font-size:9px;line-height:1.8;border-right:1px dashed #bbdefb;}
.t08 .ft .bank strong{font-size:8px;text-transform:uppercase;color:#888;display:block;}
.t08 .ft .tots{min-width:260px;padding:12px 20px;}
.t08 .ft .tots table{width:100%;font-size:10px;border-collapse:collapse;}
.t08 .ft .tots td{padding:4px 6px;border-bottom:1px solid #e3f2fd;}
.t08 .ft .tots td.l{color:#555;} .t08 .ft .tots td.r{text-align:right;font-weight:700;}
.t08 .ft .tots tr.grand td{background:linear-gradient(90deg,#0d47a1,#1565c0);color:#fff;font-size:11px;font-weight:900;padding:6px;}
.t08 .words{font-size:9px;font-style:italic;color:#555;padding:5px 20px;background:#e3f2fd;border-top:1px dashed #bbdefb;}
.t08 .sig{display:flex;justify-content:flex-end;padding:12px 20px;border-top:1px solid #e3f2fd;}
.t08 .sb{text-align:center;} .t08 .sb .co{font-weight:700;color:#0d47a1;font-size:11px;}
.t08 .sb .ln{border-top:1px solid #0d47a1;margin:18px auto 4px;width:140px;} .t08 .sb .desig{font-size:9px;color:#666;}
</style>
<div class="t08">
  <div class="hd">
    <div class="co-panel">
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
    <div class="inv-panel">
      <div class="title"><?=strtoupper(sanitize($inv['invoice_title']??'TAX INVOICE'))?></div>
      <div class="row"><span class="k">Invoice No.</span><span class="v"><?=sanitize($inv['invoice_number']??'')?></span></div>
      <div class="row"><span class="k">Date</span><span class="v"><?=sanitize($inv['invoice_date']??'')?></span></div>
      <?php if(!empty($inv['vehicle_number'])): ?>.t08 .bill-bar .veh{font-size:11px;color:#333;margin-left:auto;}<?php endif; ?>
        <div class="amt"><div class="lbl">Total Amount</div><div class="val"><?=formatIndianCurrency($inv['totals']['total']??0)?></div></div>
    </div>
  </div>
  <div class="bill-bar">
    <div><label>Billed To</label><div class="bn"><?=sanitize($inv['bill_to']??'')?></div></div>
    <div style="margin-left:auto;text-align:right"><label>Balance Due</label><div style="font-size:13px;font-weight:700;color:#0d47a1"><?=formatIndianCurrency($inv['balance_due']??0)?></div></div>
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
      <tr class="grand"><td>GRAND TOTAL</td><td class="r"><?=formatIndianCurrency($inv['totals']['total']??0)?></td></tr>
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

