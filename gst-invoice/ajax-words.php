<?php
require_once 'includes/functions.php';
$amount = (float)($_GET['amount'] ?? 0);
echo convertToIndianWords($amount);
