<?php
require_once 'includes/functions.php';
require_once 'includes/form-validation.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

session_start();
$validationErrors = validate_invoice_form($_POST);
if (!empty($validationErrors)) {
    $_SESSION['last_form'] = $_POST;
    $_SESSION['invoice_form_errors'] = $validationErrors;
    header('Location: create-invoice.php');
    exit;
}

// Increment the counter only after validation passes
$actualNumber = incrementInvoiceCounter();
$actualInvoiceNumber = 'INV-' . $actualNumber;
$_POST['invoice_number'] = $actualInvoiceNumber;

// Logo
$logoPath = handleLogoUpload();

// Items
$items = [];
$serial = 1;
if (!empty($_POST['items']) && is_array($_POST['items'])) {
    foreach ($_POST['items'] as $item) {
        $desc = trim($item['description'] ?? '');
        if ($desc === '') {
            continue;
        }
        $qty = (int)($item['quantity'] ?? 0);
        $rate = (float)($item['rate'] ?? 0);
        $base = round($qty * $rate, 2);
        $cgst = round($base * 3 / 100, 2);
        $sgst = round($base * 3 / 100, 2);
        $items[] = [
            'serial'      => $serial++,
            'description' => $desc,
            'hsn'         => trim($item['hsn'] ?? ''),
            'quantity'    => $qty,
            'per_unit'    => trim($item['per_unit'] ?? ''),
            'rate'        => $rate,
            'base'        => $base,
            'cgst'        => $cgst,
            'sgst'        => $sgst,
            'amount'      => round($base + $cgst + $sgst, 2),
        ];
    }
}

$totals = calculateTotals($items);
$paymentMade = max(0, (float)($_POST['payment_made'] ?? 0));
$balanceDue  = round($totals['total'] - $paymentMade, 2);
$amountWords = convertToIndianWords($totals['total']);

$validTemplates = array_keys(getTemplates());
$tpl = in_array($_POST['template'] ?? '', $validTemplates) ? $_POST['template'] : 't01';

$invoiceData = [
    'template'        => $tpl,
    'invoice_number'  => sanitize($_POST['invoice_number'] ?? generateInvoiceNumber()),
    'invoice_title'   => sanitize($_POST['invoice_title'] ?? 'Tax Invoice'),
    'invoice_date'    => sanitize($_POST['invoice_date'] ?? date('d/m/Y')),
    'bill_to'         => sanitize($_POST['bill_to'] ?? ''),
    'vehicle_number'  => sanitize($_POST['vehicle_number'] ?? ''),
    'company_name'    => sanitize($_POST['company_name'] ?? ''),
    'company_logo'    => $logoPath,
    'company_address' => sanitize($_POST['company_address'] ?? ''),
    'gst_number'      => sanitize($_POST['gst_number'] ?? ''),
    'company_phone'   => sanitize($_POST['company_phone'] ?? ''),
    'company_email'   => sanitize($_POST['company_email'] ?? ''),
    'bank_account'    => sanitize($_POST['bank_account'] ?? ''),
    'bank_ifsc'       => sanitize(strtoupper(trim($_POST['bank_ifsc'] ?? ''))),
    'bank_branch'     => sanitize($_POST['bank_branch'] ?? ''),
    'proprietor'      => sanitize($_POST['proprietor'] ?? 'Proprietor'),
    'items'           => $items,
    'totals'          => [
        'subtotal'   => $totals['subtotal'],
        'cgst_total' => $totals['cgstTotal'],
        'sgst_total' => $totals['sgstTotal'],
        'total'      => $totals['total'],
    ],
    'payment_made'    => $paymentMade,
    'balance_due'     => $balanceDue,
    'amount_words'    => $amountWords,
    'notes'           => sanitize($_POST['notes'] ?? ''),
    'created_at'      => date('Y-m-d H:i:s'),
];

$action = $_POST['action'] ?? 'save';

if ($action === 'preview') {
    $_SESSION['preview_invoice'] = $invoiceData;
    $_SESSION['last_form'] = $_POST;
    header('Location: view-invoice.php?preview=1');
    exit;
}

$result = saveInvoice($invoiceData);
if ($result !== false) {
    header('Location: view-invoice.php?id=' . urlencode($invoiceData['invoice_number']) . '&saved=1');
} else {
    header('Location: create-invoice.php?error=' . urlencode('Failed to save invoice to the database. Check PostgreSQL connection and schema.'));
}
exit;
