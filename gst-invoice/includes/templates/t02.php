<?php if(!isset($inv)) die(); if(!defined('CGST_RATE')) define('CGST_RATE',3); if(!defined('SGST_RATE')) define('SGST_RATE',3); ?>
<style>
.t02{font-family:'Segoe UI',Arial,sans-serif;font-size:12px;background:#fff;max-width:840px;margin:0 auto;display:flex;min-height:900px;}
.t02 .sidebar{width:210px;flex-shrink:0;background:#003087;color:#fff;padding:20px 16px;display:flex;flex-direction:column;}
.t02 .sidebar .logo-wrap{background:#fff;border-radius:8px;padding:8px;margin-bottom:16px;text-align:center;min-height:70px;display:flex;align-items:center;justify-content:center;}
.t02 .sidebar .logo-wrap img{max-width:120px;max-height:60px;object-fit:contain;}
.t02 .sidebar .logo-wrap .no-logo{font-size:28px;font-weight:900;color:#003087;}
.t02 .sidebar .co-name{font-size:13px;font-weight:700;margin-bottom:6px;line-height:1.3;}
.t02 .sidebar .co-addr{font-size:9px;opacity:.8;line-height:1.7;margin-bottom:10px;}
.t02 .sidebar .gst-tag{background:rgba(255,255,255,.15);border-radius:4px;padding:4px 8px;font-size:9px;font-weight:700;margin-bottom:14px;}
.t02 .sidebar hr{border:none;border-top:1px solid rgba(255,255,255,.2);margin:10px 0;}
.t02 .sidebar .sl{margin-bottom:10px;}
.t02 .sidebar .sl .lbl{font-size:8px;text-transform:uppercase;opacity:.6;letter-spacing:.5px;display:block;}
.t02 .sidebar .sl .val{font-size:11px;font-weight:600;line-height:1.4;}
.t02 .sidebar .amt-big{margin-top:auto;background:rgba(255,255,255,.15);border-radius:6px;padding:10px;text-align:center;}
.t02 .sidebar .amt-big .lbl{font-size:9px;opacity:.7;text-transform:uppercase;}
.t02 .sidebar .amt-big .val{font-size:16px;font-weight:900;color:#7ec8e3;}
.t02 .sidebar .bank-info{margin-top:12px;font-size:9px;opacity:.75;line-height:1.7;}
.t02 .sidebar .bank-info strong{font-size:8px;text-transform:uppercase;opacity:.6;display:block;}
.t02 .main{flex:1;display:flex;flex-direction:column;}
.t02 .main .top-bar{background:#e8f0fe;padding:12px 16px;border-bottom:2px solid #003087;display:flex;justify-content:space-between;align-items:center;}
.t02 .main .top-bar .title{font-size:18px;font-weight:900;color:#003087;letter-spacing:2px;}
.t02 .main .top-bar .meta{text-align:right;font-size:10px;color:#555;}
.t02 .main .bill-row{background:#f8faff;padding:10px 16px;border-bottom:1px solid #ddd;font-size:11px;}
.t02 .main .bill-row label{font-size:9px;text-transform:uppercase;color:#888;font-weight:700;}
.t02 .main .bill-row .bn{font-size:13px;font-weight:700;color:#003087;}
.t02 .main table.it{width:100%;border-collapse:collapse;}
.t02 .it th{background:#003087;color:#fff;padding:6px 5px;font-size:9px;text-transform:uppercase;}
.t02 .it td{padding:5px;border-bottom:1px solid #eef2ff;font-size:10px;}
.t02 .it tfoot td{background:#e8f0fe;font-weight:700;border-top:2px solid #003087;}
.t02 .it td.r,.t02 .it th.r{text-align:right;} .t02 .it td.c,.t02 .it th.c{text-align:center;}
.t02 .it tbody tr:hover td{background:#f0f4ff;}
.t02 .note{font-size:9px;background:#fffde7;border-left:3px solid #f9a825;padding:5px 10px;margin:6px 16px;}
.t02 .main .tots-wrap{padding:10px 16px;background:#f8faff;border-top:2px solid #003087;}
.t02 .tots-right{display:flex;justify-content:flex-end;}
.t02 .tots-right table{font-size:10px;border-collapse:collapse;min-width:250px;}
.t02 .tots-right td{padding:3px 8px;border-bottom:1px solid #ddd;}
.t02 .tots-right td.l{color:#555;} .t02 .tots-right td.r{text-align:right;font-weight:700;}
.t02 .tots-right tr.grand td{background:#003087;color:#fff;font-weight:900;padding:5px 8px;}
.t02 .words{font-size:9px;font-style:italic;color:#555;text-align:right;padding:4px 16px 8px;border-top:1px dashed #ccc;margin:4px 0;}
.t02 .sig{display:flex;justify-content:flex-end;padding:10px 16px;}
.t02 .sb{text-align:center;} .t02 .sb .co{font-weight:700;color:#003087;font-size:11px;}
.t02 .sb .ln{border-top:1px solid #555;margin:18px auto 4px;width:130px;} .t02 .sb .desig{font-size:9px;color:#666;}
</style>
<div class="t02">
  <div class="sidebar">
    <div class="logo-wrap">
      <?php if(!empty($inv['company_logo'])&&file_exists($inv['company_logo'])): ?><img src="<?=sanitize($inv['company_logo'])?>" alt="Logo">
      <?php else: ?><div class="no-logo">₹</div><?php endif; ?>
    </div>
    <div class="co-name"><?=sanitize($inv['company_name']??'')?></div>
    <div class="co-addr"><?=nl2br(sanitize($inv['company_address']??''))?></div>
    <div class="gst-tag">GSTIN: <?=sanitize($inv['gst_number']??'')?></div>
    <hr>
    <div class="sl"><span class="lbl">Invoice No.</span><span class="val"><?=sanitize($inv['invoice_number']??'')?></span></div>
    <div class="sl"><span class="lbl">Date</span><span class="val"><?=sanitize($inv['invoice_date']??'')?></span></div>
    <?php if(!empty($inv['vehicle_number'])): ?><div class="sl"><span class="lbl">Vehicle</span><span class="val"><?=sanitize($inv['vehicle_number'])?></span></div><?php endif; ?>
    <hr>
    <div class="bank-info"><strong>Bank Details</strong>
      <?php if(!empty($inv['bank_account'])): ?>ACC: <?=sanitize($inv['bank_account'])?><br><?php endif; ?>
      <?php if(!empty($inv['bank_ifsc'])): ?>IFSC: <?=sanitize($inv['bank_ifsc'])?><br><?php endif; ?>
      <?php if(!empty($inv['bank_branch'])): ?>Branch: <?=sanitize($inv['bank_branch'])?><?php endif; ?>
    </div>
    <div class="amt-big">
      <div class="lbl">Total Amount</div>
      <div class="val"><?=formatIndianCurrency($inv['totals']['total']??0)?></div>
    </div>
  </div>
  <div class="main">
    <div class="top-bar">
      <div class="title"><?=strtoupper(sanitize($inv['invoice_title']??'TAX INVOICE'))?></div>
      <div class="meta">Balance Due: <b style="color:#003087;font-size:12px"><?=formatIndianCurrency($inv['balance_due']??0)?></b></div>
    </div>
    <div class="bill-row"><label>Billed To</label><div class="bn"><?=sanitize($inv['bill_to']??'')?></div></div>
    <table class="it"><thead><tr>
      <th class="c">#</th><th>Item &amp; Description</th><th class="c">HSN</th>
      <th class="r">Qty</th><th class="c">Per</th><th class="r">Rate</th>
      <th class="r">CGST</th><th class="r">SGST</th><th class="r">Amount</th>
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
    <div class="tots-wrap"><div class="tots-right"><table>
      <tr><td class="l">Taxable Amount</td><td class="r"><?=formatIndianCurrency($inv['totals']['subtotal']??0)?></td></tr>
      <tr><td class="l">CGST @<?=CGST_RATE?>%</td><td class="r"><?=formatIndianCurrency($inv['totals']['cgst_total']??0)?></td></tr>
      <tr><td class="l">SGST @<?=SGST_RATE?>%</td><td class="r"><?=formatIndianCurrency($inv['totals']['sgst_total']??0)?></td></tr>
      <tr class="grand"><td>GRAND TOTAL</td><td class="r"><?=formatIndianCurrency($inv['totals']['total']??0)?></td></tr>
      <tr><td class="l">Payment Made</td><td class="r"><?=formatIndianCurrency($inv['payment_made']??0)?></td></tr>
      <tr><td class="l"><b>Balance Due</b></td><td class="r"><b><?=formatIndianCurrency($inv['balance_due']??0)?></b></td></tr>
    </table></div></div>
    <div class="words">In Words: <?=sanitize($inv['amount_words']??'')?></div>
    <div class="sig"><div class="sb">
      <div class="co">For <?=sanitize($inv['company_name']??'')?></div>
      <div class="ln"></div><div class="desig"><?=sanitize($inv['proprietor']??'Proprietor')?></div>
    </div></div>
  </div>
</div>
