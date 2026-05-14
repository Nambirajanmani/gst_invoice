// js/invoice.js — Line totals + strict form validation

const CGST_RATE = 3;
const SGST_RATE = 3;
const TEXT_ONLY_WORDS = /^[A-Za-z]+(?:\s+[A-Za-z]+)*$/;
const GSTIN_PATTERN = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][1-9A-Z]Z[0-9A-Z]$/;

let rowIdx = typeof window.__INITIAL_ITEM_ROW_COUNT__ === 'number' ? window.__INITIAL_ITEM_ROW_COUNT__ : 1;

function trimStr(s) {
  return String(s ?? '').trim();
}

function fmtIN(n) {
  n = parseFloat(n) || 0;
  const parts = n.toFixed(2).split('.');
  let int = parts[0];
  const dec = parts[1];
  if (int.length > 3) {
    const last3 = int.slice(-3);
    const rest = int.slice(0, -3).replace(/(\d)(?=(\d{2})+$)/g, '$1,');
    int = rest + ',' + last3;
  }
  return '₹' + int + '.' + dec;
}

function parseQtyInt(row) {
  const raw = trimStr(row.querySelector('.i-qty')?.value);
  if (raw === '' || !/^\d+$/.test(raw)) return 0;
  return parseInt(raw, 10);
}

function setMoneyCellText(td, formatted) {
  if (!td) return;
  for (const n of td.childNodes) {
    if (n.nodeType === Node.TEXT_NODE) {
      n.nodeValue = formatted;
      return;
    }
  }
  const anchor = td.querySelector('input');
  td.insertBefore(document.createTextNode(formatted), anchor || null);
}

function recalcRow(row) {
  const qty = parseQtyInt(row);
  const rate = parseFloat(row.querySelector('.i-rate')?.value) || 0;
  const base = qty * rate;
  const cgst = parseFloat((base * CGST_RATE / 100).toFixed(2));
  const sgst = parseFloat((base * SGST_RATE / 100).toFixed(2));
  const total = base + cgst + sgst;

  const cgstEl = row.querySelector('.i-cgst');
  const sgstEl = row.querySelector('.i-sgst');
  const baseEl = row.querySelector('.i-base');
  const totalEl = row.querySelector('.i-total');
  setMoneyCellText(cgstEl, fmtIN(cgst));
  setMoneyCellText(sgstEl, fmtIN(sgst));
  if (baseEl) baseEl.value = base.toFixed(2);
  setMoneyCellText(totalEl, fmtIN(total));

  row.querySelector('.i-cgst-val')?.setAttribute('value', cgst.toFixed(2));
  row.querySelector('.i-sgst-val')?.setAttribute('value', sgst.toFixed(2));
  row.querySelector('.i-amount-val')?.setAttribute('value', total.toFixed(2));
}

function recalcAll() {
  document.querySelectorAll('.item-row').forEach(recalcRow);
  let subtotal = 0;
  let cgstTotal = 0;
  let sgstTotal = 0;
  let grandTotal = 0;
  document.querySelectorAll('.item-row').forEach((row) => {
    const qty = parseQtyInt(row);
    const rate = parseFloat(row.querySelector('.i-rate')?.value) || 0;
    const base = qty * rate;
    subtotal += base;
    cgstTotal += base * CGST_RATE / 100;
    sgstTotal += base * SGST_RATE / 100;
    grandTotal += base + (base * CGST_RATE / 100) + (base * SGST_RATE / 100);
  });
  subtotal = parseFloat(subtotal.toFixed(2));
  cgstTotal = parseFloat(cgstTotal.toFixed(2));
  sgstTotal = parseFloat(sgstTotal.toFixed(2));
  grandTotal = parseFloat(grandTotal.toFixed(2));

  const pmade = parseFloat(document.getElementById('payment_made')?.value) || 0;
  const balance = grandTotal - pmade;

  setText('disp-subtotal', fmtIN(subtotal));
  setText('disp-cgst-total', fmtIN(cgstTotal));
  setText('disp-sgst-total', fmtIN(sgstTotal));
  setText('disp-grand-total', fmtIN(grandTotal));
  setText('disp-balance', fmtIN(balance));

  setVal('inp-subtotal', subtotal);
  setVal('inp-cgst-total', cgstTotal);
  setVal('inp-sgst-total', sgstTotal);
  setVal('inp-grand-total', grandTotal);
  setVal('inp-balance', balance.toFixed(2));

  updateAmountWords(grandTotal);
}

function setText(id, v) {
  const el = document.getElementById(id);
  if (el) el.textContent = v;
}
function setVal(id, v) {
  const el = document.getElementById(id);
  if (el) el.value = v;
}

function updateAmountWords(amount) {
  fetch('ajax-words.php?amount=' + encodeURIComponent(amount))
    .then((r) => r.text())
    .then((w) => {
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
  tr.dataset.index = String(i);
  const rowNum = tbody.querySelectorAll('tr').length + 1;
  tr.innerHTML = `
    <td style="text-align:center">${rowNum}
      <input type="hidden" name="items[${i}][serial]" value="${rowNum}">
    </td>
    <td class="item-cell-desc">
      <input type="text" name="items[${i}][description]" class="i-desc" value="${escapeAttr(data.description || '')}" placeholder="Item description">
      <span class="field-error-msg item-field-error" data-err-for="items.${i}.description" role="alert"></span>
    </td>
    <td><input type="text" name="items[${i}][hsn]" class="i-hsn" value="${escapeAttr(data.hsn || '')}" placeholder="HSN code" style="width:90px"></td>
    <td class="item-cell-qty">
      <input type="text" name="items[${i}][quantity]" class="i-qty" inputmode="numeric" value="${escapeAttr(data.qty != null ? String(data.qty) : '')}" placeholder="0">
      <span class="field-error-msg item-field-error" data-err-for="items.${i}.quantity" role="alert"></span>
    </td>
    <td><input type="number" name="items[${i}][rate]" class="i-rate" value="${data.rate != null && data.rate !== '' ? Number(data.rate) : ''}" placeholder="0.00" min="0" step="0.01"></td>
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
  attachItemRowValidation(tr);
  tr.querySelector('.i-qty').addEventListener('input', () => {
    recalcRow(tr);
    recalcAll();
  });
  tr.querySelector('.i-rate').addEventListener('input', () => {
    recalcRow(tr);
    recalcAll();
  });
  recalcRow(tr);
  recalcAll();
  renumberRows();
}

function escapeAttr(s) {
  return String(s)
    .replace(/&/g, '&amp;')
    .replace(/"/g, '&quot;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;');
}

function removeItemRow(btn) {
  const rows = document.querySelectorAll('.item-row');
  if (rows.length <= 1) {
    alert('At least one item is required.');
    return;
  }
  btn.closest('tr').remove();
  renumberRows();
  recalcAll();
  maybeClearItemsBlockError();
}

function renumberRows() {
  document.querySelectorAll('.item-row').forEach((r, i) => {
    r.cells[0].childNodes[0].textContent = i + 1;
    r.querySelector('[name$="[serial]"]').value = i + 1;
  });
}

function attachRowListeners(tr) {
  tr.querySelector('.i-qty')?.addEventListener('input', () => {
    recalcRow(tr);
    recalcAll();
  });
  tr.querySelector('.i-rate')?.addEventListener('input', () => {
    recalcRow(tr);
    recalcAll();
  });
}

// ——— Validation ———

function setInputError(input, message) {
  if (!input) return;
  const msg = message || '';
  input.classList.toggle('field-invalid', !!msg);
  const group = input.closest('.form-group');
  if (group) {
    const err = group.querySelector('.field-error-msg');
    if (err) err.textContent = msg;
    return;
  }
  const cell = input.closest('td');
  if (cell) {
    const err = cell.querySelector('.field-error-msg');
    if (err) err.textContent = msg;
  }
}

function validateCompanyName(v) {
  v = trimStr(v);
  if (!v) return 'Company name is required.';
  if (!TEXT_ONLY_WORDS.test(v)) return 'Use letters and spaces only. No numbers or special characters.';
  return '';
}

function validatePhone(v) {
  v = trimStr(v);
  if (v === '') return '';
  if (!/^\d{10}$/.test(v)) return 'Enter exactly 10 digits. No spaces, letters, or symbols.';
  return '';
}

function validateGSTNumber(v) {
  v = trimStr(v).toUpperCase();
  if (v === '') return '';
  if (!GSTIN_PATTERN.test(v)) return 'Enter a valid 15-character GSTIN (e.g. 33APAPR0776B3Z7).';
  return '';
}

function validateAccount(v) {
  v = trimStr(v);
  if (v === '') return '';
  if (!/^\d{9,18}$/.test(v)) return 'Account number must be 9–18 digits only.';
  return '';
}

function validateIFSC(v) {
  v = trimStr(v).toUpperCase();
  if (v === '') return '';
  if (!/^[A-Z]{4}0[A-Z0-9]{6}$/.test(v)) {
    return 'Invalid IFSC. First 4 letters, then 0, then 6 letters or digits (e.g. SBIN0001234).';
  }
  return '';
}

function validateBranch(v) {
  v = trimStr(v);
  if (v === '') return '';
  if (!TEXT_ONLY_WORDS.test(v)) return 'Use letters and spaces only. No numbers or special characters.';
  return '';
}

function validateBillTo(v) {
  v = trimStr(v);
  if (!v) return 'Customer name is required.';
  if (!TEXT_ONLY_WORDS.test(v)) return 'Use letters and spaces only. No numbers or special characters.';
  return '';
}

function validateItemDescription(v) {
  v = trimStr(v);
  if (v === '') return '';
  if (!TEXT_ONLY_WORDS.test(v)) {
    return 'Use letters and spaces only. Numeric-only values are not allowed.';
  }
  return '';
}

function validateItemQty(desc, qtyRaw) {
  const d = trimStr(desc);
  const q = trimStr(qtyRaw);
  if (d === '') {
    if (q === '') return '';
    if (!/^\d+$/.test(q)) return 'Enter a whole number only (no decimals or text).';
    if (parseInt(q, 10) <= 0) return 'Quantity must be a positive whole number.';
    return 'Enter an item description for this line, or clear the quantity.';
  }
  if (q === '' || !/^\d+$/.test(q)) return 'Enter a whole number only (no decimals or text).';
  if (parseInt(q, 10) <= 0) return 'Quantity must be a positive whole number.';
  return '';
}

function validateItemRow(row) {
  const descIn = row.querySelector('.i-desc');
  const qtyIn = row.querySelector('.i-qty');
  if (!descIn || !qtyIn) return;
  const idx = row.dataset.index;
  const errD = validateItemDescription(descIn.value);
  const errQ = validateItemQty(descIn.value, qtyIn.value);
  setInputError(descIn, errD);
  setInputError(qtyIn, errQ);
}

function lineItemsHasCompleteRow() {
  let ok = false;
  document.querySelectorAll('.item-row').forEach((row) => {
    const d = trimStr(row.querySelector('.i-desc')?.value);
    const q = trimStr(row.querySelector('.i-qty')?.value);
    if (d && TEXT_ONLY_WORDS.test(d) && /^\d+$/.test(q) && parseInt(q, 10) > 0) {
      ok = true;
    }
  });
  return ok;
}

function runLineItemsValidation() {
  document.querySelectorAll('.item-row').forEach(validateItemRow);
  return lineItemsHasCompleteRow();
}

function maybeClearItemsBlockError() {
  const block = document.getElementById('items_block_error');
  if (!block || block.hidden) return;
  if (lineItemsHasCompleteRow()) {
    block.hidden = true;
    block.textContent = '';
  }
}

function validateStaticFields() {
  let ok = true;
  const cn = document.getElementById('fld_company_name');
  const e1 = validateCompanyName(cn?.value);
  setInputError(cn, e1);
  if (e1) ok = false;

  const gst = document.getElementById('fld_gst_number');
  const eg = validateGSTNumber(gst?.value);
  setInputError(gst, eg);
  if (eg) ok = false;

  const ph = document.getElementById('fld_company_phone');
  const e2 = validatePhone(ph?.value);
  setInputError(ph, e2);
  if (e2) ok = false;

  const ac = document.getElementById('fld_bank_account');
  const e3 = validateAccount(ac?.value);
  setInputError(ac, e3);
  if (e3) ok = false;

  const ifsc = document.getElementById('fld_bank_ifsc');
  const e4 = validateIFSC(ifsc?.value);
  setInputError(ifsc, e4);
  if (e4) ok = false;

  const br = document.getElementById('fld_bank_branch');
  const e5 = validateBranch(br?.value);
  setInputError(br, e5);
  if (e5) ok = false;

  const bt = document.getElementById('fld_bill_to');
  const e6 = validateBillTo(bt?.value);
  setInputError(bt, e6);
  if (e6) ok = false;

  return ok;
}

function runFullValidation() {
  const staticOk = validateStaticFields();
  const linesOk = runLineItemsValidation();
  const block = document.getElementById('items_block_error');
  if (block) {
    if (!linesOk) {
      block.hidden = false;
      block.textContent = 'Add at least one line item with a description and quantity.';
    } else {
      block.hidden = true;
      block.textContent = '';
    }
  }
  return staticOk && linesOk;
}

function attachItemRowValidation(tr) {
  const desc = tr.querySelector('.i-desc');
  const qty = tr.querySelector('.i-qty');
  const onChange = () => {
    validateItemRow(tr);
    maybeClearItemsBlockError();
  };
  desc?.addEventListener('input', onChange);
  desc?.addEventListener('blur', onChange);
  qty?.addEventListener('input', () => {
    const v = qty.value.replace(/[^\d]/g, '');
    if (qty.value !== v) qty.value = v;
    onChange();
    recalcRow(tr);
    recalcAll();
  });
  qty?.addEventListener('blur', onChange);
}

function applyServerErrorHighlights() {
  document.querySelectorAll('.field-error-msg').forEach((span) => {
    if (!trimStr(span.textContent)) return;
    const prev = span.previousElementSibling;
    if (prev && prev.matches && prev.matches('input, textarea, select')) {
      prev.classList.add('field-invalid');
    }
  });
}

function bindStaticFieldLiveValidation() {
  const bind = (id, fn) => {
    const el = document.getElementById(id);
    if (!el) return;
    const run = () => {
      const msg = fn(el.value);
      setInputError(el, msg);
    };
    el.addEventListener('input', run);
    el.addEventListener('blur', run);
  };

  bind('fld_company_name', validateCompanyName);
  bind('fld_gst_number', validateGSTNumber);
  bind('fld_company_phone', validatePhone);
  bind('fld_bank_account', validateAccount);
  bind('fld_bank_ifsc', validateIFSC);
  bind('fld_bank_branch', validateBranch);
  bind('fld_bill_to', validateBillTo);

  const gst = document.getElementById('fld_gst_number');
  gst?.addEventListener('input', () => {
    const clean = gst.value.replace(/[^a-zA-Z0-9]/g, '').slice(0, 15).toUpperCase();
    if (gst.value !== clean) gst.value = clean;
  });
  gst?.addEventListener('blur', () => {
    gst.value = trimStr(gst.value).toUpperCase();
    setInputError(gst, validateGSTNumber(gst.value));
  });

  const ph = document.getElementById('fld_company_phone');
  ph?.addEventListener('input', () => {
    const d = ph.value.replace(/\D/g, '').slice(0, 10);
    if (ph.value !== d) ph.value = d;
  });

  const ac = document.getElementById('fld_bank_account');
  ac?.addEventListener('input', () => {
    const d = ac.value.replace(/\D/g, '').slice(0, 18);
    if (ac.value !== d) ac.value = d;
  });

  const ifsc = document.getElementById('fld_bank_ifsc');
  ifsc?.addEventListener('blur', () => {
    ifsc.value = trimStr(ifsc.value).toUpperCase();
    setInputError(ifsc, validateIFSC(ifsc.value));
  });
}

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.item-row').forEach((tr) => {
    attachRowListeners(tr);
    attachItemRowValidation(tr);
    recalcRow(tr);
  });
  recalcAll();
  document.getElementById('payment_made')?.addEventListener('input', recalcAll);

  applyServerErrorHighlights();
  bindStaticFieldLiveValidation();

  const form = document.getElementById('mainForm');
  form?.addEventListener('submit', (e) => {
    ['fld_company_name', 'fld_bank_branch', 'fld_bill_to'].forEach((id) => {
      const el = document.getElementById(id);
      if (el) el.value = trimStr(el.value);
    });
    document.querySelectorAll('.i-desc').forEach((inp) => {
      inp.value = trimStr(inp.value);
    });
    ['fld_company_phone', 'fld_bank_account'].forEach((id) => {
      const el = document.getElementById(id);
      if (el) el.dispatchEvent(new Event('input'));
    });
    const ifsc = document.getElementById('fld_bank_ifsc');
    if (ifsc) ifsc.value = trimStr(ifsc.value).toUpperCase();
    const gst = document.getElementById('fld_gst_number');
    if (gst) gst.value = trimStr(gst.value).toUpperCase();

    if (!runFullValidation()) {
      e.preventDefault();
      const first = document.querySelector('.field-invalid');
      if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
      else document.getElementById('items_block_error')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  });
});
