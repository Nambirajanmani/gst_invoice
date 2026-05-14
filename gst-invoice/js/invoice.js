// js/invoice.js — All form logic for GST Invoice App

const CGST_RATE = 3;
const SGST_RATE = 3;
let rowIdx = 1;

function fmtIN(n) {
  n = parseFloat(n) || 0;
  let parts = n.toFixed(2).split('.');
  let int = parts[0];
  let dec = parts[1];
  if (int.length > 3) {
    let last3 = int.slice(-3);
    let rest = int.slice(0, -3).replace(/(\d)(?=(\d{2})+$)/g, '$1,');
    int = rest + ',' + last3;
  }
  return '₹' + int + '.' + dec;
}

function recalcRow(row) {
  const qty   = parseFloat(row.querySelector('.i-qty')?.value) || 0;
  const rate  = parseFloat(row.querySelector('.i-rate')?.value) || 0;
  const base  = qty * rate;
  const cgst  = parseFloat((base * CGST_RATE / 100).toFixed(2));
  const sgst  = parseFloat((base * SGST_RATE / 100).toFixed(2));
  const total = base + cgst + sgst;

  const cgstEl  = row.querySelector('.i-cgst');
  const sgstEl  = row.querySelector('.i-sgst');
  const baseEl  = row.querySelector('.i-base');
  const totalEl = row.querySelector('.i-total');
  if (cgstEl)  cgstEl.textContent  = fmtIN(cgst);
  if (sgstEl)  sgstEl.textContent  = fmtIN(sgst);
  if (baseEl)  baseEl.value = base.toFixed(2);
  if (totalEl) totalEl.textContent = fmtIN(total);

  // hidden inputs for server
  row.querySelector('.i-cgst-val')?.setAttribute('value', cgst.toFixed(2));
  row.querySelector('.i-sgst-val')?.setAttribute('value', sgst.toFixed(2));
  row.querySelector('.i-amount-val')?.setAttribute('value', total.toFixed(2));
}

function recalcAll() {
  document.querySelectorAll('.item-row').forEach(recalcRow);
  let subtotal = 0, cgstTotal = 0, sgstTotal = 0, grandTotal = 0;
  document.querySelectorAll('.item-row').forEach(row => {
    const qty  = parseFloat(row.querySelector('.i-qty')?.value)  || 0;
    const rate = parseFloat(row.querySelector('.i-rate')?.value) || 0;
    const base = qty * rate;
    subtotal  += base;
    cgstTotal += base * CGST_RATE / 100;
    sgstTotal += base * SGST_RATE / 100;
    grandTotal += base + (base * CGST_RATE / 100) + (base * SGST_RATE / 100);
  });
  subtotal   = parseFloat(subtotal.toFixed(2));
  cgstTotal  = parseFloat(cgstTotal.toFixed(2));
  sgstTotal  = parseFloat(sgstTotal.toFixed(2));
  grandTotal = parseFloat(grandTotal.toFixed(2));

  const pmade = parseFloat(document.getElementById('payment_made')?.value) || 0;
  const balance = grandTotal - pmade;

  setText('disp-subtotal',   fmtIN(subtotal));
  setText('disp-cgst-total', fmtIN(cgstTotal));
  setText('disp-sgst-total', fmtIN(sgstTotal));
  setText('disp-grand-total',fmtIN(grandTotal));
  setText('disp-balance',    fmtIN(balance));

  setVal('inp-subtotal',    subtotal);
  setVal('inp-cgst-total',  cgstTotal);
  setVal('inp-sgst-total',  sgstTotal);
  setVal('inp-grand-total', grandTotal);
  setVal('inp-balance',     balance.toFixed(2));

  // Amount in words
  updateAmountWords(grandTotal);
}

function setText(id, v) { const el = document.getElementById(id); if (el) el.textContent = v; }
function setVal(id, v)  { const el = document.getElementById(id); if (el) el.value = v; }

function updateAmountWords(amount) {
  // Basic Indian words for frontend preview (full version done server-side)
  fetch('ajax-words.php?amount=' + encodeURIComponent(amount))
    .then(r => r.text())
    .then(w => {
      setVal('inp-amount-words', w);
      setText('disp-amount-words', w);
    })
    .catch(() => {});
}

function addItemRow(data = {}) {
  const tbody = document.getElementById('items-tbody');
  const i = rowIdx++;
  const tr = document.createElement('tr');
  tr.className = 'item-row';
  tr.dataset.index = i;
  tr.innerHTML = `
    <td style="text-align:center">${tbody.querySelectorAll('tr').length + 1}
      <input type="hidden" name="items[${i}][serial]" value="${tbody.querySelectorAll('tr').length + 1}">
    </td>
    <td><input type="text" name="items[${i}][description]" class="i-desc" value="${data.description||''}" placeholder="Item name" required></td>
    <td><input type="text" name="items[${i}][hsn]" class="i-hsn" value="${data.hsn||''}" placeholder="HSN code" style="width:90px"></td>
    <td><input type="number" name="items[${i}][quantity]" class="i-qty" value="${data.qty||''}" placeholder="0" min="0" step="0.001"></td>
    <td><input type="text" name="items[${i}][per_unit]" class="i-per" value="${data.per||''}" placeholder="Unit" style="width:70px"></td>
    <td><input type="number" name="items[${i}][rate]" class="i-rate" value="${data.rate||''}" placeholder="0.00" min="0" step="0.01"></td>
    <td class="gst-cell i-cgst">₹0.00
      <input type="hidden" name="items[${i}][cgst]" class="i-cgst-val" value="0">
    </td>
    <td class="gst-cell i-sgst">₹0.00
      <input type="hidden" name="items[${i}][sgst]" class="i-sgst-val" value="0">
    </td>
    <td class="auto-cell i-total">₹0.00
      <input type="hidden" name="items[${i}][base]" class="i-base" value="0">
      <input type="hidden" name="items[${i}][amount]" class="i-amount-val" value="0">
    </td>
    <td><button type="button" class="btn btn-danger btn-sm" onclick="removeItemRow(this)" title="Remove">✕</button></td>
  `;
  tbody.appendChild(tr);
  tr.querySelector('.i-qty').addEventListener('input', () => { recalcRow(tr); recalcAll(); });
  tr.querySelector('.i-rate').addEventListener('input', () => { recalcRow(tr); recalcAll(); });
  recalcRow(tr);
  recalcAll();
  renumberRows();
}

function removeItemRow(btn) {
  const rows = document.querySelectorAll('.item-row');
  if (rows.length <= 1) { alert('At least one item is required.'); return; }
  btn.closest('tr').remove();
  renumberRows();
  recalcAll();
}

function renumberRows() {
  document.querySelectorAll('.item-row').forEach((r, i) => {
    r.cells[0].childNodes[0].textContent = i + 1;
    r.querySelector('[name$="[serial]"]').value = i + 1;
  });
}

function attachRowListeners(tr) {
  tr.querySelector('.i-qty')?.addEventListener('input', () => { recalcRow(tr); recalcAll(); });
  tr.querySelector('.i-rate')?.addEventListener('input', () => { recalcRow(tr); recalcAll(); });
}

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.item-row').forEach(tr => {
    attachRowListeners(tr);
    recalcRow(tr);
  });
  recalcAll();
  document.getElementById('payment_made')?.addEventListener('input', recalcAll);
});
