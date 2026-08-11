<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once dirname(__DIR__) . '/includes/functions.php';

$amount = isset($_GET['amount']) ? (float)$_GET['amount'] : (isset($_POST['amount']) ? (float)$_POST['amount'] : 0);

echo json_encode([
    'success' => true,
    'amount' => $amount,
    'words' => convertToIndianWords($amount)
]);
