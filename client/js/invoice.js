// client/js/invoice.js — Line totals + strict form validation

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
  fetch('../server/api/words.php?amount=' + encodeURIComponent(amount))
    .then((r) => r.json())
    .then((res) => {
      if (res && res.words) {
        setVal('inp-amount-words', res.words);
        setText('disp-amount-words', res.words);
      }
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
      <input type="number" name="items[${i}][quantity]" class="i-qty" min="1" step="1" inputmode="numeric" value="${escapeAttr(data.qty != null ? String(data.qty) : '')}" placeholder="0">
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
  const descInp = tr.querySelector('.i-desc');
  if (descInp) {
    descInp.addEventListener('input', function() {
      this.value = this.value.replace(/[0-9]/g, '');
    });
  }
  const qtyInp = tr.querySelector('.i-qty');
  qtyInp.addEventListener('input', function() {
    this.value = this.value.replace(/\D/g, '');
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
}

function renumberRows() {
  document.querySelectorAll('.item-row').forEach((r, i) => {
    r.cells[0].childNodes[0].textContent = i + 1;
    r.querySelector('[name$="[serial]"]').value = i + 1;
  });
}

document.addEventListener('DOMContentLoaded', () => {
  // 1. Company Name should NOT accept numbers
  const companyNameEl = document.getElementById('fld_company_name');
  if (companyNameEl) {
    companyNameEl.addEventListener('input', function() {
      this.value = this.value.replace(/[0-9]/g, '');
    });
  }

  // 2. Phone No should NOT accept characters (only digits)
  const companyPhoneEl = document.getElementById('fld_company_phone');
  if (companyPhoneEl) {
    companyPhoneEl.addEventListener('input', function() {
      this.value = this.value.replace(/\D/g, '');
    });
  }

  // 3. Account Number should NOT accept characters (only digits)
  const bankAccountEl = document.getElementById('fld_bank_account');
  if (bankAccountEl) {
    bankAccountEl.addEventListener('input', function() {
      this.value = this.value.replace(/\D/g, '');
    });
  }

  // 4. Bill To / Customer Name should NOT accept numbers
  const billToEl = document.getElementById('fld_bill_to');
  if (billToEl) {
    billToEl.addEventListener('input', function() {
      this.value = this.value.replace(/[0-9]/g, '');
    });
  }

  // Item rows calculation
  document.querySelectorAll('.item-row').forEach((tr) => {
    const descInp = tr.querySelector('.i-desc');
    if (descInp) {
      descInp.addEventListener('input', function() {
        this.value = this.value.replace(/[0-9]/g, '');
      });
    }
    const qtyInp = tr.querySelector('.i-qty');
    if (qtyInp) {
      qtyInp.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
        recalcRow(tr);
        recalcAll();
      });
    }
    tr.querySelector('.i-rate')?.addEventListener('input', () => {
      recalcRow(tr);
      recalcAll();
    });
    recalcRow(tr);
  });
  document.getElementById('payment_made')?.addEventListener('input', recalcAll);
  recalcAll();

  // Form submission validation
  const mainForm = document.getElementById('mainForm');
  if (mainForm) {
    mainForm.addEventListener('submit', function(e) {
      let valid = true;
      const errors = [];

      const companyName = trimStr(companyNameEl?.value);
      if (!companyName) {
        valid = false;
        errors.push('Company Name is required.');
      } else if (/[0-9]/.test(companyName)) {
        valid = false;
        errors.push('Company Name should not contain numbers.');
      }

      const billTo = trimStr(billToEl?.value);
      if (!billTo) {
        valid = false;
        errors.push('Bill To / Customer Name is required.');
      } else if (/[0-9]/.test(billTo)) {
        valid = false;
        errors.push('Bill To / Customer Name should not contain numbers.');
      }

      const billDate = trimStr(document.getElementById('fld_invoice_date')?.value);
      if (!billDate) {
        valid = false;
        errors.push('Bill Date is required.');
      }

      const rows = document.querySelectorAll('.item-row');
      let hasValidItem = false;
      rows.forEach(r => {
        const desc = trimStr(r.querySelector('.i-desc')?.value);
        if (/[0-9]/.test(desc)) {
          valid = false;
          errors.push('Item Description should not contain numbers.');
        }
        const rate = parseFloat(r.querySelector('.i-rate')?.value) || 0;
        if (desc && rate > 0) {
          hasValidItem = true;
        }
      });

      if (!hasValidItem) {
        valid = false;
        errors.push('Please fill in Item Description and Rate for at least one line item.');
      }

      if (!valid) {
        e.preventDefault();
        showCustomAlert('Required Fields Missing', errors);
      }
    });
  }
});

function showCustomAlert(title, errors) {
  let modalOverlay = document.getElementById('custom-modal-overlay');
  if (!modalOverlay) {
    modalOverlay = document.createElement('div');
    modalOverlay.id = 'custom-modal-overlay';
    modalOverlay.className = 'custom-modal-overlay';
    modalOverlay.innerHTML = `
      <div class="custom-modal-box">
        <div class="custom-modal-icon-wrap">⚠️</div>
        <h3 id="custom-modal-title" class="custom-modal-title">Attention Required</h3>
        <div id="custom-modal-content" class="custom-modal-content"></div>
        <div class="custom-modal-actions">
          <button type="button" id="custom-modal-btn" class="btn btn-primary btn-lg" style="min-width:140px;">OK, Got It</button>
        </div>
      </div>
    `;
    document.body.appendChild(modalOverlay);
    document.getElementById('custom-modal-btn').addEventListener('click', closeCustomModal);
    modalOverlay.addEventListener('click', function(e) {
      if (e.target === modalOverlay) closeCustomModal();
    });
  }

  document.getElementById('custom-modal-title').textContent = title || 'Attention Required';
  const contentEl = document.getElementById('custom-modal-content');
  if (Array.isArray(errors)) {
    let html = '<div style="margin-bottom:0.4rem;font-weight:600;">Please fix the following issues before saving:</div><ul>';
    errors.forEach(err => {
      html += `<li>${escapeAttr(err)}</li>`;
    });
    html += '</ul>';
    contentEl.innerHTML = html;
  } else {
    contentEl.textContent = errors;
  }

  modalOverlay.style.display = 'flex';
}

function closeCustomModal() {
  const modalOverlay = document.getElementById('custom-modal-overlay');
  if (modalOverlay) {
    modalOverlay.style.display = 'none';
  }
}


