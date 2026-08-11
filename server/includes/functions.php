<?php
require_once dirname(__DIR__) . '/config/db.php';

function sanitize($v) {
    return htmlspecialchars(trim((string) $v), ENT_QUOTES, 'UTF-8');
}

function formatIndianCurrency($number) {
    $number = (float) $number;
    if ($number < 0) {
        return '₹-' . ltrim(formatIndianCurrency(abs($number)), '₹');
    }

    $parts = explode('.', number_format($number, 2, '.', ''));
    $intPart = str_replace(',', '', $parts[0]);
    $decPart = $parts[1];

    if (strlen($intPart) > 3) {
        $last3 = substr($intPart, -3);
        $rest = substr($intPart, 0, -3);
        $rest = preg_replace('/(\d)(?=(\d{2})+$)/', '$1,', $rest);
        $intPart = $rest . ',' . $last3;
    }

    return '₹' . $intPart . '.' . $decPart;
}

function convertToIndianWords($number) {
    $number = round((float) $number, 2);
    if ($number == 0) {
        return 'Zero Only';
    }

    $parts = explode('.', (string) $number);
    $intPart = (int) $parts[0];
    $decPart = isset($parts[1]) ? (int) str_pad($parts[1], 2, '0') : 0;

    $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
        'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen',
        'Seventeen', 'Eighteen', 'Nineteen'];
    $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

    $numToWords = function ($n) use (&$numToWords, $ones, $tens) {
        if ($n == 0) {
            return '';
        }
        if ($n < 20) {
            return $ones[$n] . ' ';
        }
        if ($n < 100) {
            return $tens[(int) ($n / 10)] . ($n % 10 ? ' ' . $ones[$n % 10] : '') . ' ';
        }
        return $ones[(int) ($n / 100)] . ' Hundred ' . $numToWords($n % 100);
    };

    $words = '';
    if ($intPart >= 10000000) {
        $words .= $numToWords((int) ($intPart / 10000000)) . 'Crore ';
        $intPart %= 10000000;
    }
    if ($intPart >= 100000) {
        $words .= $numToWords((int) ($intPart / 100000)) . 'Lakh ';
        $intPart %= 100000;
    }
    if ($intPart >= 1000) {
        $words .= $numToWords((int) ($intPart / 1000)) . 'Thousand ';
        $intPart %= 1000;
    }
    if ($intPart > 0) {
        $words .= $numToWords($intPart);
    }

    $words = trim($words);
    if ($decPart > 0) {
        $words .= ' and ' . $numToWords($decPart) . 'Paise';
    }

    return trim($words) . ' Only';
}

function calculateGST($baseAmount, $cgstRate = 3, $sgstRate = 3) {
    $cgst = $baseAmount * ($cgstRate / 100);
    $sgst = $baseAmount * ($sgstRate / 100);
    return ['cgst' => round($cgst, 2), 'sgst' => round($sgst, 2)];
}

function calculateItemAmount($qty, $rate, $cgstRate = 3, $sgstRate = 3) {
    $base = $qty * $rate;
    $gst = calculateGST($base, $cgstRate, $sgstRate);
    return [
        'base' => round($base, 2),
        'cgst' => $gst['cgst'],
        'sgst' => $gst['sgst'],
        'amount' => round($base + $gst['cgst'] + $gst['sgst'], 2),
    ];
}

function calculateTotals($items) {
    $subtotal = 0;
    $cgstTotal = 0;
    $sgstTotal = 0;
    $total = 0;

    foreach ($items as $item) {
        $subtotal += (float) ($item['base'] ?? 0);
        $cgstTotal += (float) ($item['cgst'] ?? 0);
        $sgstTotal += (float) ($item['sgst'] ?? 0);
        $total += (float) ($item['amount'] ?? 0);
    }

    return compact('subtotal', 'cgstTotal', 'sgstTotal', 'total');
}

function getCurrentInvoiceYear(): int {
    return (int) date('Y');
}

function parseInvoiceDateForDb($value): string {
    $value = trim((string) $value);
    if ($value === '') {
        return date('Y-m-d');
    }

    $formats = ['d/m/Y', 'Y-m-d', 'd-m-Y'];
    foreach ($formats as $format) {
        $dt = DateTime::createFromFormat($format, $value);
        if ($dt instanceof DateTime) {
            return $dt->format('Y-m-d');
        }
    }

    $timestamp = strtotime($value);
    return $timestamp ? date('Y-m-d', $timestamp) : date('Y-m-d');
}

function formatInvoiceDateFromDb($value): string {
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }

    $timestamp = strtotime($value);
    return $timestamp ? date('d/m/Y', $timestamp) : $value;
}

function getVisibleCompanyCondition(string $alias = 'companies'): string {
    $alias = trim($alias);
    $prefix = $alias !== '' ? $alias . '.' : '';
    return 'COALESCE(' . $prefix . 'is_active, TRUE) = TRUE';
}

function normalizeCompanyText(string $value): string {
    $value = preg_replace('/\s+/u', ' ', trim($value));
    return $value === null ? '' : $value;
}

function normalizeCompanyLookupText(string $value): string {
    return strtolower(normalizeCompanyText($value));
}

function normalizeCompanyDigits(string $value): string {
    return preg_replace('/\D+/', '', $value) ?? '';
}

function normalizeCompanyEmail(string $value): string {
    return strtolower(trim($value));
}

function hasCompanyIdentityData(array $data): bool {
    return normalizeCompanyText((string) ($data['company_name'] ?? '')) !== ''
        || trim((string) ($data['gst_number'] ?? '')) !== ''
        || normalizeCompanyDigits((string) ($data['company_phone'] ?? '')) !== ''
        || normalizeCompanyEmail((string) ($data['company_email'] ?? '')) !== '';
}

function findMatchingCompanyId(PDO $pdo, array $data): ?int {
    $gst = strtoupper(trim((string) ($data['gst_number'] ?? '')));
    if ($gst !== '') {
        $stmt = $pdo->prepare(
            'SELECT id
             FROM companies
             WHERE ' . getVisibleCompanyCondition('companies') . '
               AND UPPER(BTRIM(COALESCE(gst_number, \'\'))) = :gst
             ORDER BY id ASC
             LIMIT 1'
        );
        $stmt->execute([':gst' => $gst]);
        $companyId = $stmt->fetchColumn();
        if ($companyId !== false) {
            return (int) $companyId;
        }
    }

    $companyName = normalizeCompanyLookupText((string) ($data['company_name'] ?? ''));
    if ($companyName === '') {
        return null;
    }

    $phone = normalizeCompanyDigits((string) ($data['company_phone'] ?? ''));
    $email = normalizeCompanyEmail((string) ($data['company_email'] ?? ''));

    $stmt = $pdo->prepare(
        'SELECT id
         FROM companies
         WHERE ' . getVisibleCompanyCondition('companies') . '
           AND LOWER(REGEXP_REPLACE(BTRIM(COALESCE(company_name, \'\')), \'\s+\', \' \', \'g\')) = :company_name
         ORDER BY
           CASE
             WHEN :phone = \'\' THEN 0
             WHEN REGEXP_REPLACE(COALESCE(phone, \'\'), \'\\D+\', \'\', \'g\') = :phone THEN 0
             WHEN BTRIM(COALESCE(phone, \'\')) = \'\' THEN 1
             ELSE 2
           END,
           CASE
             WHEN :email = \'\' THEN 0
             WHEN LOWER(BTRIM(COALESCE(email, \'\'))) = :email THEN 0
             WHEN BTRIM(COALESCE(email, \'\')) = \'\' THEN 1
             ELSE 2
           END,
           id ASC
         LIMIT 1'
    );
    $stmt->execute([
        ':company_name' => $companyName,
        ':phone' => $phone,
        ':email' => $email,
    ]);

    $companyId = $stmt->fetchColumn();
    return $companyId === false ? null : (int) $companyId;
}

function getActiveCompanyId(PDO $pdo): int {
    $stmt = $pdo->query(
        "SELECT id
         FROM companies
         WHERE " . getVisibleCompanyCondition('companies') . "
         ORDER BY CASE WHEN is_active IS TRUE THEN 0 ELSE 1 END, id ASC
         LIMIT 1"
    );
    $companyId = $stmt->fetchColumn();

    if ($companyId !== false) {
        return (int) $companyId;
    }

    $pdo->exec(
        "INSERT INTO companies (
            company_name, company_address, gst_number, phone, email, logo_path,
            bank_account, bank_ifsc, bank_branch, proprietor, is_active
        ) VALUES (
            'Your Company Name', '', '', '', '', '', '', '', '', 'Proprietor', TRUE
        )"
    );

    return (int) $pdo->lastInsertId('companies_id_seq');
}

function saveCompanyRecord(PDO $pdo, array $data, string $logoPath = ''): int {
    $companyId = findMatchingCompanyId($pdo, $data);

    if ($companyId === null) {
        if (!hasCompanyIdentityData($data)) {
            return getActiveCompanyId($pdo);
        }

        $stmt = $pdo->prepare(
            'INSERT INTO companies (
                company_name, company_address, gst_number, phone, email, logo_path,
                bank_account, bank_ifsc, bank_branch, proprietor, is_active
            ) VALUES (
                :company_name, :company_address, :gst_number, :phone, :email, :logo_path,
                :bank_account, :bank_ifsc, :bank_branch, :proprietor, TRUE
            )'
        );
        $stmt->execute([
            ':company_name' => normalizeCompanyText((string) ($data['company_name'] ?? '')),
            ':company_address' => trim((string) ($data['company_address'] ?? '')),
            ':gst_number' => strtoupper(trim((string) ($data['gst_number'] ?? ''))),
            ':phone' => trim((string) ($data['company_phone'] ?? '')),
            ':email' => trim((string) ($data['company_email'] ?? '')),
            ':logo_path' => $logoPath,
            ':bank_account' => trim((string) ($data['bank_account'] ?? '')),
            ':bank_ifsc' => strtoupper(trim((string) ($data['bank_ifsc'] ?? ''))),
            ':bank_branch' => trim((string) ($data['bank_branch'] ?? '')),
            ':proprietor' => trim((string) ($data['proprietor'] ?? 'Proprietor')),
        ]);

        return (int) $pdo->lastInsertId('companies_id_seq');
    }

    if ($logoPath === '') {
        $logoStmt = $pdo->prepare('SELECT logo_path FROM companies WHERE id = :id');
        $logoStmt->execute([':id' => $companyId]);
        $logoPath = (string) ($logoStmt->fetchColumn() ?: '');
    }

    $stmt = $pdo->prepare(
        'UPDATE companies SET
            company_name = :company_name,
            company_address = :company_address,
            gst_number = :gst_number,
            phone = :phone,
            email = :email,
            logo_path = :logo_path,
            bank_account = :bank_account,
            bank_ifsc = :bank_ifsc,
            bank_branch = :bank_branch,
            proprietor = :proprietor,
            is_active = TRUE
         WHERE id = :id'
    );
    $stmt->execute([
        ':company_name' => normalizeCompanyText((string) ($data['company_name'] ?? '')),
        ':company_address' => trim((string) ($data['company_address'] ?? '')),
        ':gst_number' => strtoupper(trim((string) ($data['gst_number'] ?? ''))),
        ':phone' => trim((string) ($data['company_phone'] ?? '')),
        ':email' => trim((string) ($data['company_email'] ?? '')),
        ':logo_path' => $logoPath,
        ':bank_account' => trim((string) ($data['bank_account'] ?? '')),
        ':bank_ifsc' => strtoupper(trim((string) ($data['bank_ifsc'] ?? ''))),
        ':bank_branch' => trim((string) ($data['bank_branch'] ?? '')),
        ':proprietor' => trim((string) ($data['proprietor'] ?? 'Proprietor')),
        ':id' => $companyId,
    ]);

    return $companyId;
}

function generateInvoiceNumber() {
    $pdo = getDbConnection();
    $companyId = getActiveCompanyId($pdo);
    $year = getCurrentInvoiceYear();

    $stmt = $pdo->prepare('SELECT last_number FROM invoice_counters WHERE company_id = :company_id AND year = :year');
    $stmt->execute([
        ':company_id' => $companyId,
        ':year' => $year,
    ]);

    $lastNumber = $stmt->fetchColumn();
    $nextNumber = $lastNumber === false ? 1 : ((int) $lastNumber + 1);

    return 'INV-' . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
}

function incrementInvoiceCounter() {
    $pdo = getDbConnection();
    $companyId = getActiveCompanyId($pdo);
    $year = getCurrentInvoiceYear();

    $stmt = $pdo->prepare('SELECT sp_next_invoice_number(:company_id, :year)');
    $stmt->execute([
        ':company_id' => $companyId,
        ':year' => $year,
    ]);

    $invoiceNumber = (string) $stmt->fetchColumn();
    return preg_replace('/^INV-/', '', $invoiceNumber);
}

function getUploadsDir() { return dirname(dirname(__DIR__)) . '/uploads/'; }

function saveInvoice($data) {
    $pdo = getDbConnection();

    try {
        $pdo->beginTransaction();

        $logoPath = trim((string) ($data['company_logo'] ?? ''));
        $companyId = saveCompanyRecord($pdo, $data, $logoPath);

        $invoiceStmt = $pdo->prepare(
            'INSERT INTO invoices (
                company_id, invoice_number, invoice_title, template, invoice_date,
                bill_to, vehicle_number, subtotal, cgst_total, sgst_total, total,
                payment_made, balance_due, amount_words, notes, status
            ) VALUES (
                :company_id, :invoice_number, :invoice_title, :template, :invoice_date,
                :bill_to, :vehicle_number, :subtotal, :cgst_total, :sgst_total, :total,
                :payment_made, :balance_due, :amount_words, :notes, :status
            )
            RETURNING id'
        );
        $invoiceStmt->execute([
            ':company_id' => $companyId,
            ':invoice_number' => (string) ($data['invoice_number'] ?? ''),
            ':invoice_title' => (string) ($data['invoice_title'] ?? 'Tax Invoice'),
            ':template' => (string) ($data['template'] ?? 't01'),
            ':invoice_date' => parseInvoiceDateForDb($data['invoice_date'] ?? ''),
            ':bill_to' => (string) ($data['bill_to'] ?? ''),
            ':vehicle_number' => (string) ($data['vehicle_number'] ?? ''),
            ':subtotal' => (float) ($data['totals']['subtotal'] ?? 0),
            ':cgst_total' => (float) ($data['totals']['cgst_total'] ?? 0),
            ':sgst_total' => (float) ($data['totals']['sgst_total'] ?? 0),
            ':total' => (float) ($data['totals']['total'] ?? 0),
            ':payment_made' => (float) ($data['payment_made'] ?? 0),
            ':balance_due' => (float) ($data['balance_due'] ?? 0),
            ':amount_words' => (string) ($data['amount_words'] ?? ''),
            ':notes' => (string) ($data['notes'] ?? ''),
            ':status' => 'saved',
        ]);
        $invoiceId = (int) $invoiceStmt->fetchColumn();

        $itemStmt = $pdo->prepare(
            'INSERT INTO invoice_items (
                invoice_id, serial, description, hsn, quantity, per_unit, rate,
                base_amount, cgst_rate, sgst_rate, cgst_amount, sgst_amount, amount
            ) VALUES (
                :invoice_id, :serial, :description, :hsn, :quantity, :per_unit, :rate,
                :base_amount, :cgst_rate, :sgst_rate, :cgst_amount, :sgst_amount, :amount
            )'
        );

        foreach (($data['items'] ?? []) as $item) {
            $itemStmt->execute([
                ':invoice_id' => $invoiceId,
                ':serial' => (int) ($item['serial'] ?? 1),
                ':description' => (string) ($item['description'] ?? ''),
                ':hsn' => (string) ($item['hsn'] ?? ''),
                ':quantity' => (float) ($item['quantity'] ?? 0),
                ':per_unit' => (string) ($item['per_unit'] ?? ''),
                ':rate' => (float) ($item['rate'] ?? 0),
                ':base_amount' => (float) ($item['base'] ?? 0),
                ':cgst_rate' => 3.00,
                ':sgst_rate' => 3.00,
                ':cgst_amount' => (float) ($item['cgst'] ?? 0),
                ':sgst_amount' => (float) ($item['sgst'] ?? 0),
                ':amount' => (float) ($item['amount'] ?? 0),
            ]);
        }

        $pdo->commit();
        return $invoiceId;
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('saveInvoice failed: ' . $e->getMessage());
        return false;
    }
}

function loadInvoice($invoiceNumber) {
    $pdo = getDbConnection();

    $invoiceStmt = $pdo->prepare(
        'SELECT
            i.id,
            i.invoice_number,
            i.invoice_title,
            i.template,
            i.invoice_date,
            i.bill_to,
            i.vehicle_number,
            i.subtotal,
            i.cgst_total,
            i.sgst_total,
            i.total,
            i.payment_made,
            i.balance_due,
            i.amount_words,
            i.notes,
            i.created_at,
            c.company_name,
            c.company_address,
            c.gst_number,
            c.phone AS company_phone,
            c.email AS company_email,
            c.logo_path AS company_logo,
            c.bank_account,
            c.bank_ifsc,
            c.bank_branch,
            c.proprietor
         FROM invoices i
         LEFT JOIN companies c ON c.id = i.company_id
         WHERE i.invoice_number = :invoice_number
         LIMIT 1'
    );
    $invoiceStmt->execute([':invoice_number' => $invoiceNumber]);
    $invoice = $invoiceStmt->fetch();

    if (!$invoice) {
        return null;
    }

    $itemStmt = $pdo->prepare(
        'SELECT serial, description, hsn, quantity, per_unit, rate,
                base_amount, cgst_amount, sgst_amount, amount
         FROM invoice_items
         WHERE invoice_id = :invoice_id
         ORDER BY serial ASC, id ASC'
    );
    $itemStmt->execute([':invoice_id' => (int) $invoice['id']]);

    $items = [];
    foreach ($itemStmt->fetchAll() as $itemRow) {
        $items[] = [
            'serial' => (int) $itemRow['serial'],
            'description' => (string) $itemRow['description'],
            'hsn' => (string) $itemRow['hsn'],
            'quantity' => (float) $itemRow['quantity'],
            'per_unit' => (string) $itemRow['per_unit'],
            'rate' => (float) $itemRow['rate'],
            'base' => (float) $itemRow['base_amount'],
            'cgst' => (float) $itemRow['cgst_amount'],
            'sgst' => (float) $itemRow['sgst_amount'],
            'amount' => (float) $itemRow['amount'],
        ];
    }

    return [
        'template' => (string) $invoice['template'],
        'invoice_number' => (string) $invoice['invoice_number'],
        'invoice_title' => (string) $invoice['invoice_title'],
        'invoice_date' => formatInvoiceDateFromDb($invoice['invoice_date']),
        'bill_to' => (string) $invoice['bill_to'],
        'vehicle_number' => (string) $invoice['vehicle_number'],
        'company_name' => (string) $invoice['company_name'],
        'company_logo' => (string) $invoice['company_logo'],
        'company_address' => (string) $invoice['company_address'],
        'gst_number' => (string) $invoice['gst_number'],
        'company_phone' => (string) $invoice['company_phone'],
        'company_email' => (string) $invoice['company_email'],
        'bank_account' => (string) $invoice['bank_account'],
        'bank_ifsc' => (string) $invoice['bank_ifsc'],
        'bank_branch' => (string) $invoice['bank_branch'],
        'proprietor' => (string) $invoice['proprietor'],
        'items' => $items,
        'totals' => [
            'subtotal' => (float) $invoice['subtotal'],
            'cgst_total' => (float) $invoice['cgst_total'],
            'sgst_total' => (float) $invoice['sgst_total'],
            'total' => (float) $invoice['total'],
        ],
        'payment_made' => (float) $invoice['payment_made'],
        'balance_due' => (float) $invoice['balance_due'],
        'amount_words' => (string) $invoice['amount_words'],
        'notes' => (string) $invoice['notes'],
        'created_at' => (string) $invoice['created_at'],
    ];
}

function getRecentInvoices($limit = 8) {
    $pdo = getDbConnection();
    $limit = max(1, (int) $limit);
    $stmt = $pdo->prepare(
        'SELECT
            i.invoice_number,
            i.invoice_title,
            i.template,
            i.invoice_date,
            i.bill_to,
            i.vehicle_number,
            i.subtotal,
            i.cgst_total,
            i.sgst_total,
            i.total,
            i.payment_made,
            i.balance_due,
            i.amount_words,
            i.notes,
            i.created_at,
            c.company_name,
            c.company_address,
            c.gst_number,
            c.phone AS company_phone,
            c.email AS company_email,
            c.logo_path AS company_logo,
            c.bank_account,
            c.bank_ifsc,
            c.bank_branch,
            c.proprietor
         FROM invoices i
         LEFT JOIN companies c ON c.id = i.company_id
         ORDER BY i.created_at DESC, i.id DESC
         LIMIT :limit'
    );
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    $out = [];
    foreach ($stmt->fetchAll() as $row) {
        $out[] = [
            'template' => (string) $row['template'],
            'invoice_number' => (string) $row['invoice_number'],
            'invoice_title' => (string) $row['invoice_title'],
            'invoice_date' => formatInvoiceDateFromDb($row['invoice_date']),
            'bill_to' => (string) $row['bill_to'],
            'vehicle_number' => (string) $row['vehicle_number'],
            'company_name' => (string) $row['company_name'],
            'company_logo' => (string) $row['company_logo'],
            'company_address' => (string) $row['company_address'],
            'gst_number' => (string) $row['gst_number'],
            'company_phone' => (string) $row['company_phone'],
            'company_email' => (string) $row['company_email'],
            'bank_account' => (string) $row['bank_account'],
            'bank_ifsc' => (string) $row['bank_ifsc'],
            'bank_branch' => (string) $row['bank_branch'],
            'proprietor' => (string) $row['proprietor'],
            'items' => [],
            'totals' => [
                'subtotal' => (float) $row['subtotal'],
                'cgst_total' => (float) $row['cgst_total'],
                'sgst_total' => (float) $row['sgst_total'],
                'total' => (float) $row['total'],
            ],
            'payment_made' => (float) $row['payment_made'],
            'balance_due' => (float) $row['balance_due'],
            'amount_words' => (string) $row['amount_words'],
            'notes' => (string) $row['notes'],
            'created_at' => (string) $row['created_at'],
        ];
    }

    return $out;
}

function handleLogoUpload() {
    if (empty($_FILES['company_logo']['name'])) {
        return '';
    }

    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($_FILES['company_logo']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed, true) || $_FILES['company_logo']['error'] !== UPLOAD_ERR_OK) {
        return '';
    }

    $dir = getUploadsDir();
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $fname = 'logo_' . uniqid('', true) . '.' . $ext;
    if (move_uploaded_file($_FILES['company_logo']['tmp_name'], $dir . $fname)) {
        return 'uploads/' . $fname;
    }

    return '';
}

function getTemplates() {
    return [
        't01' => ['name' => 'Classic Header', 'color' => '#1a1a2e', 'desc' => 'Logo left, navy'],
        't02' => ['name' => 'Dark Sidebar', 'color' => '#003087', 'desc' => 'Left sidebar layout'],
        't03' => ['name' => 'Centered Logo', 'color' => '#1b5e20', 'desc' => 'Logo center, green ribbon'],
        't04' => ['name' => 'Minimal Clean', 'color' => '#222222', 'desc' => 'No fills, thin rules'],
        't05' => ['name' => 'Bold Crimson', 'color' => '#7b0000', 'desc' => 'Large name, dark footer'],
        't06' => ['name' => 'Card Boxed', 'color' => '#4a0072', 'desc' => 'Card sections, purple'],
        't07' => ['name' => 'Teal Gradient', 'color' => '#004d40', 'desc' => 'Floating logo badge'],
        't08' => ['name' => 'Split Header', 'color' => '#0d47a1', 'desc' => 'Left co / right blue panel'],
        't09' => ['name' => 'Ledger Style', 'color' => '#3e2723', 'desc' => 'Sepia, double borders'],
        't10' => ['name' => 'Compact Dense', 'color' => '#263238', 'desc' => 'Small fonts, fits more items'],
    ];
}

function deleteInvoice($invoiceNumber) {
    $pdo = getDbConnection();
    try {
        $stmt = $pdo->prepare('DELETE FROM invoices WHERE invoice_number = :invoice_number');
        return $stmt->execute([':invoice_number' => $invoiceNumber]);
    } catch (Throwable $e) {
        error_log('deleteInvoice failed: ' . $e->getMessage());
        return false;
    }
}

