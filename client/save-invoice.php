<?php
require_once dirname(__DIR__) . '/server/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$logoPath = handleLogoUpload();

$items = [];
if (!empty($_POST['items']) && is_array($_POST['items'])) {
    foreach ($_POST['items'] as $it) {
        if (!empty($it['description']) || !empty($it['rate'])) {
            $qty = (float)($it['quantity'] ?? 0);
            $rate = (float)($it['rate'] ?? 0);
            $calc = calculateItemAmount($qty, $rate, 3, 3);
            $items[] = [
                'serial' => (int)($it['serial'] ?? count($items)+1),
                'description' => trim($it['description'] ?? ''),
                'hsn' => trim($it['hsn'] ?? ''),
                'quantity' => $qty,
                'per_unit' => trim($it['per_unit'] ?? ''),
                'rate' => $rate,
                'base' => $calc['base'],
                'cgst' => $calc['cgst'],
                'sgst' => $calc['sgst'],
                'amount' => $calc['amount'],
            ];
        }
    }
}

$totals = calculateTotals($items);
$grandTotal = $totals['total'];
$paymentMade = (float)($_POST['payment_made'] ?? 0);
$balanceDue = max(0, $grandTotal - $paymentMade);
$amountWords = convertToIndianWords($grandTotal);

$num = incrementInvoiceCounter();
$invoiceNumber = 'INV-' . $num;

$invoiceData = [
    'template' => trim($_POST['template'] ?? 't01'),
    'invoice_number' => $invoiceNumber,
    'invoice_title' => trim($_POST['invoice_title'] ?? 'Tax Invoice'),
    'invoice_date' => trim($_POST['invoice_date'] ?? date('d/m/Y')),
    'bill_to' => trim($_POST['bill_to'] ?? ''),
    'vehicle_number' => trim($_POST['vehicle_number'] ?? ''),
    'company_name' => trim($_POST['company_name'] ?? 'Your Company Name'),
    'company_logo' => $logoPath,
    'company_address' => trim($_POST['company_address'] ?? ''),
    'gst_number' => trim($_POST['gst_number'] ?? ''),
    'company_phone' => trim($_POST['company_phone'] ?? ''),
    'company_email' => trim($_POST['company_email'] ?? ''),
    'bank_account' => trim($_POST['bank_account'] ?? ''),
    'bank_ifsc' => trim($_POST['bank_ifsc'] ?? ''),
    'bank_branch' => trim($_POST['bank_branch'] ?? ''),
    'proprietor' => trim($_POST['proprietor'] ?? 'Proprietor'),
    'items' => $items,
    'totals' => $totals,
    'payment_made' => $paymentMade,
    'balance_due' => $balanceDue,
    'amount_words' => $amountWords,
    'notes' => trim($_POST['notes'] ?? ''),
    'created_at' => date('Y-m-d H:i:s'),
];

$savedId = saveInvoice($invoiceData);

if ($savedId) {
    header('Location: view-invoice.php?id=' . urlencode($invoiceNumber) . '&saved=1');
} else {
    header('Location: create-invoice.php?error=' . urlencode('Failed to save invoice. Please check logs.'));
}
exit;
