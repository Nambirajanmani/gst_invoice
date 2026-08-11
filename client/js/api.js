/**
 * API Client for interacting with PHP Backend API (server/api/)
 */
const API_BASE_URL = '../server/api';

async function fetchStatsAndRecentInvoices(limit = 8) {
  try {
    const res = await fetch(`${API_BASE_URL}/invoices.php?limit=${limit}`);
    return await res.json();
  } catch (err) {
    console.error('Failed to fetch stats/invoices:', err);
    return { success: false, error: err.message };
  }
}

async function fetchInvoiceById(invoiceNumber) {
  try {
    const res = await fetch(`${API_BASE_URL}/invoices.php?id=${encodeURIComponent(invoiceNumber)}`);
    return await res.json();
  } catch (err) {
    console.error('Failed to fetch invoice:', err);
    return { success: false, error: err.message };
  }
}

async function saveInvoiceData(formData) {
  try {
    const res = await fetch(`${API_BASE_URL}/invoices.php`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(formData)
    });
    return await res.json();
  } catch (err) {
    console.error('Failed to save invoice:', err);
    return { success: false, error: err.message };
  }
}

async function fetchAmountInWords(amount) {
  try {
    const res = await fetch(`${API_BASE_URL}/words.php?amount=${amount}`);
    return await res.json();
  } catch (err) {
    console.error('Failed to convert amount to words:', err);
    return { success: false, words: '' };
  }
}

async function fetchTemplates() {
  try {
    const res = await fetch(`${API_BASE_URL}/templates.php`);
    return await res.json();
  } catch (err) {
    console.error('Failed to fetch templates:', err);
    return { success: false, templates: {} };
  }
}

async function deleteInvoiceApi(invoiceNumber) {
  try {
    const res = await fetch(`${API_BASE_URL}/invoices.php?action=delete&id=${encodeURIComponent(invoiceNumber)}`, {
      method: 'DELETE'
    });
    return await res.json();
  } catch (err) {
    console.error('Failed to delete invoice:', err);
    return { success: false, error: err.message };
  }
}

