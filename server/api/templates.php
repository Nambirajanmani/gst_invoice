<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once dirname(__DIR__) . '/includes/functions.php';

echo json_encode([
    'success' => true,
    'templates' => getTemplates()
]);
