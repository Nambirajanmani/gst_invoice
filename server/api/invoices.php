<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once dirname(__DIR__) . '/includes/functions.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'DELETE' || (isset($_GET['action']) && $_GET['action'] === 'delete')) {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        $rawInput = file_get_contents('php://input');
        $input = json_decode($rawInput, true);
        $id = $input['id'] ?? null;
    }
    
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Invoice ID missing']);
        exit;
    }

    if (deleteInvoice(trim($id))) {
        echo json_encode(['success' => true, 'message' => 'Invoice deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete invoice']);
    }
    exit;
}

if ($method === 'GET') {
    if (isset($_GET['id']) && trim($_GET['id']) !== '') {
        $inv = loadInvoice(trim($_GET['id']));
        if (!$inv) {
            http_response_code(404);
            echo json_encode(['error' => 'Invoice not found']);
            exit;
        }
        echo json_encode(['success' => true, 'invoice' => $inv]);
        exit;
    }

    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 8;
    $recent = getRecentInvoices($limit);
    $all = getRecentInvoices(1000);
    $revenue = 0;
    foreach ($all as $a) {
        $revenue += (float)($a['totals']['total'] ?? 0);
    }

    echo json_encode([
        'success' => true,
        'stats' => [
            'total_invoices' => count($all),
            'total_revenue' => $revenue,
            'total_revenue_formatted' => formatIndianCurrency($revenue),
            'templates_count' => count(getTemplates()),
            'gst_rate' => '6%'
        ],
        'recent_invoices' => $recent
    ]);
    exit;
}

if ($method === 'POST') {
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);
    
    if (!$input) {
        $input = $_POST;
    }

    if (!empty($input['action']) && $input['action'] === 'delete' && !empty($input['id'])) {
        if (deleteInvoice(trim($input['id']))) {
            echo json_encode(['success' => true, 'message' => 'Invoice deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete invoice']);
        }
        exit;
    }

    if (empty($input)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid or empty input']);
        exit;
    }

    $num = incrementInvoiceCounter();
    $input['invoice_number'] = 'INV-' . $num;

    $savedId = saveInvoice($input);
    if ($savedId) {
        echo json_encode([
            'success' => true,
            'invoice_number' => $input['invoice_number'],
            'invoice_id' => $savedId
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to save invoice']);
    }
    exit;
}

