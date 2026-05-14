<?php
// includes/render-template.php
// Provides $inv, sanitize(), formatIndianCurrency(), CGST_RATE, SGST_RATE
// to all templates. Include this before including a template file.

if (!defined('CGST_RATE')) define('CGST_RATE', 3);
if (!defined('SGST_RATE')) define('SGST_RATE', 3);
