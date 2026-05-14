<?php
/**
 * Server-side validation for create invoice form (mirrors client rules).
 * Returns [ 'field_key' => 'message', ... ] — empty array means valid.
 */
function validate_invoice_form(array $post): array {
    $errors = [];

    $company = trim((string)($post['company_name'] ?? ''));
    if ($company === '') {
        $errors['company_name'] = 'Company name is required.';
    } elseif (!preg_match('/^[A-Za-z]+(?:\s+[A-Za-z]+)*$/u', $company)) {
        $errors['company_name'] = 'Use letters and spaces only. No numbers or special characters.';
    }

    $phone = trim((string)($post['company_phone'] ?? ''));
    if ($phone !== '' && !preg_match('/^\d{10}$/', $phone)) {
        $errors['company_phone'] = 'Enter exactly 10 digits. No spaces or symbols.';
    }

    $acct = trim((string)($post['bank_account'] ?? ''));
    if ($acct !== '' && !preg_match('/^\d{9,18}$/', $acct)) {
        $errors['bank_account'] = 'Account number must be 9–18 digits only.';
    }

    $ifsc = strtoupper(trim((string)($post['bank_ifsc'] ?? '')));
    if ($ifsc !== '' && !preg_match('/^[A-Z]{4}0[A-Z0-9]{6}$/', $ifsc)) {
        $errors['bank_ifsc'] = 'Invalid IFSC. Format: 4 letters, 0, then 6 letters/digits (e.g. SBIN0001234).';
    }

    $branch = trim((string)($post['bank_branch'] ?? ''));
    if ($branch !== '' && !preg_match('/^[A-Za-z]+(?:\s+[A-Za-z]+)*$/u', $branch)) {
        $errors['bank_branch'] = 'Use letters and spaces only. No numbers or special characters.';
    }

    $billTo = trim((string)($post['bill_to'] ?? ''));
    if ($billTo === '') {
        $errors['bill_to'] = 'Customer name is required.';
    } elseif (!preg_match('/^[A-Za-z]+(?:\s+[A-Za-z]+)*$/u', $billTo)) {
        $errors['bill_to'] = 'Use letters and spaces only. No numbers or special characters.';
    }

    $items = $post['items'] ?? [];
    if (!is_array($items)) {
        $items = [];
    }

    $hasLine = false;
    foreach ($items as $idx => $item) {
        if (!is_array($item)) {
            continue;
        }
        $desc = trim((string)($item['description'] ?? ''));
        if ($desc === '') {
            continue;
        }
        $hasLine = true;
        $keyD = "items.$idx.description";
        $keyQ = "items.$idx.quantity";

        if (!preg_match('/^[A-Za-z]+(?:\s+[A-Za-z]+)*$/u', $desc)) {
            $errors[$keyD] = 'Use letters and spaces only. Numeric-only values are not allowed.';
        }

        $qtyRaw = trim((string)($item['quantity'] ?? ''));
        if ($qtyRaw === '' || !preg_match('/^\d+$/', $qtyRaw)) {
            $errors[$keyQ] = 'Enter a whole number only (no decimals or text).';
        } elseif ((int)$qtyRaw <= 0) {
            $errors[$keyQ] = 'Quantity must be a positive whole number.';
        }
    }

    if (!$hasLine) {
        $errors['items'] = 'Add at least one line item with a description and quantity.';
    }

    return $errors;
}
