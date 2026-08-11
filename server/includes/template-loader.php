<?php
// server/includes/template-loader.php

function getTemplateStyle($tplId) {
    $styles = [
        't01' => [
            'primary' => '#1a1a2e', 'accent' => '#c8a951',
            'header_bg' => '#1a1a2e', 'font' => 'Arial, sans-serif',
            'style_class' => 'inv-t01', 'layout' => 'standard',
            'company_font_size' => '20px', 'company_font_weight' => '900',
            'company_letter_spacing' => '2px', 'title_size' => '15px',
            'row_hover' => '#f5f5f5',
            'extra_css' => '.inv-t01 .inv-hd { border-bottom: 3px solid #c8a951; }
                            .inv-t01 .inv-tbl th { background: #1a1a2e; }
                            .inv-t01 .inv-ft { border-top-color: #c8a951; }'
        ],
        't02' => [
            'primary' => '#003087', 'accent' => '#0070d2',
            'header_bg' => 'linear-gradient(135deg,#001f5e,#0047b3)', 'font' => '"Trebuchet MS", Arial, sans-serif',
            'style_class' => 'inv-t02', 'layout' => 'standard',
            'company_font_size' => '22px', 'title_size' => '14px',
            'row_hover' => '#f0f4ff',
            'extra_css' => '.inv-t02 { border: 2px solid #003087; border-radius: 4px; overflow: hidden; }
                            .inv-t02 .inv-hd { background: linear-gradient(135deg,#001f5e,#0047b3); }
                            .inv-t02 .inv-meta { background: #f0f4ff; }'
        ],
        't03' => [
            'primary' => '#1b5e20', 'accent' => '#43a047',
            'header_bg' => '#1b5e20', 'font' => 'Georgia, serif',
            'style_class' => 'inv-t03', 'layout' => 'split-header',
            'company_font_size' => '19px', 'title_size' => '14px',
            'row_hover' => '#f1f8e9',
            'extra_css' => '.inv-t03 .inv-hd { border-bottom: 3px solid #1b5e20; background: #1b5e20; }
                            .inv-t03 .inv-tbl th { background: #2e7d32; }
                            .inv-t03 .inv-ft { border-top-color: #1b5e20; }
                            .inv-t03 .inv-ft .totals-box tr.grand-row td { background: #1b5e20; }
                            .inv-t03 .sign-block .for-co { color: #1b5e20; }'
        ],
        't04' => [
            'primary' => '#222', 'accent' => '#555',
            'header_bg' => '#fff', 'font' => '"Helvetica Neue", Helvetica, Arial, sans-serif',
            'style_class' => 'inv-t04', 'layout' => 'minimal',
            'company_font_size' => '18px', 'company_font_weight' => '700',
            'company_letter_spacing' => '0', 'title_size' => '13px',
            'row_hover' => '#fafafa',
            'extra_css' => '.inv-t04 { border: 1px solid #e0e0e0; }
                            .inv-t04 .inv-meta { border-bottom: 1px solid #eee; background: #fafafa; }
                            .inv-t04 .inv-tbl th { background: #222; }
                            .inv-t04 .inv-ft .totals-box tr.grand-row td { background: #222; }
                            .inv-t04 .sign-block .for-co { color: #222; }
                            .inv-t04 .note-bar { border-color: #ccc; background: #f9f9f9; }'
        ],
        't05' => [
            'primary' => '#7b0000', 'accent' => '#d32f2f',
            'header_bg' => '#7b0000', 'font' => '"Arial Black", Arial, sans-serif',
            'style_class' => 'inv-t05', 'layout' => 'standard',
            'company_font_size' => '26px', 'company_font_weight' => '900',
            'company_letter_spacing' => '3px', 'title_size' => '18px',
            'title_style' => 'color:#ffcc02;letter-spacing:4px;',
            'row_hover' => '#fff5f5',
            'extra_css' => '.inv-t05 .inv-hd { border-top: 6px solid #ffcc02; }
                            .inv-t05 .inv-meta { background: #fff5f5; border-bottom: 2px solid #7b0000; }
                            .inv-t05 .inv-tbl th { background: #7b0000; }
                            .inv-t05 .inv-ft { border-top: 3px solid #7b0000; }
                            .inv-t05 .inv-ft .totals-box tr.grand-row td { background: #7b0000; }
                            .inv-t05 .sign-block .for-co { color: #7b0000; }'
        ],
        't06' => [
            'primary' => '#4a0072', 'accent' => '#ab47bc',
            'header_bg' => '#4a0072', 'font' => 'Calibri, Arial, sans-serif',
            'style_class' => 'inv-t06', 'layout' => 'split-header',
            'company_font_size' => '18px', 'title_size' => '13px',
            'row_hover' => '#f8f0ff',
            'extra_css' => '.inv-t06 { border: 1px solid #ce93d8; }
                            .inv-t06 .inv-meta { display: grid; grid-template-columns: 1fr 1fr; background: #f8f0ff; }
                            .inv-t06 .inv-meta .meta-box { border: 1px solid #e1bee7; padding: 8px; border-radius: 4px; margin: 4px; }
                            .inv-t06 .inv-tbl th { background: #4a0072; }
                            .inv-t06 .inv-ft { border-top: 2px solid #4a0072; }
                            .inv-t06 .inv-ft .totals-box tr.grand-row td { background: #4a0072; }
                            .inv-t06 .sign-block .for-co { color: #4a0072; }'
        ],
        't07' => [
            'primary' => '#004d40', 'accent' => '#00bfa5',
            'header_bg' => '#004d40', 'font' => 'Verdana, Geneva, sans-serif',
            'style_class' => 'inv-t07', 'layout' => 'boxed',
            'company_font_size' => '18px', 'title_size' => '13px',
            'row_hover' => '#e0f2f1',
            'extra_css' => '.inv-t07 { border: 2px solid #004d40; }
                            .inv-t07 .inv-hd { border-bottom: 3px solid #00bfa5; }
                            .inv-t07 .inv-meta { background: #e0f2f1; }
                            .inv-t07 .inv-tbl th { background: #004d40; }
                            .inv-t07 .items-outer { padding: 10px; }
                            .inv-t07 .items-outer > table { border: 1px solid #80cbc4; }
                            .inv-t07 .inv-ft { border-top: 3px solid #004d40; }
                            .inv-t07 .inv-ft .bank-info { background: #e0f2f1; padding: 10px; border-radius: 4px; border: 1px solid #80cbc4; }
                            .inv-t07 .inv-ft .totals-box { border: 1px solid #80cbc4; border-radius: 4px; overflow: hidden; }
                            .inv-t07 .inv-ft .totals-box tr.grand-row td { background: #004d40; }
                            .inv-t07 .sign-block .for-co { color: #004d40; }'
        ],
        't08' => [
            'primary' => '#0d47a1', 'accent' => '#42a5f5',
            'header_bg' => 'linear-gradient(135deg,#0d47a1,#1565c0,#0288d1)', 'font' => '"Segoe UI", Arial, sans-serif',
            'style_class' => 'inv-t08', 'layout' => 'standard',
            'company_font_size' => '21px', 'title_size' => '15px',
            'row_hover' => '#e3f2fd',
            'extra_css' => '.inv-t08 .inv-hd { background: linear-gradient(135deg,#0d47a1,#1565c0,#0288d1); border-bottom: 4px solid #42a5f5; }
                            .inv-t08 .inv-meta { background: linear-gradient(90deg,#e3f2fd,#ffffff); }
                            .inv-t08 .inv-tbl th { background: linear-gradient(90deg,#0d47a1,#1565c0); }
                            .inv-t08 .inv-ft { background: linear-gradient(135deg,#f5f9ff,#e3f2fd); border-top: 3px solid #0d47a1; }
                            .inv-t08 .inv-ft .totals-box tr.grand-row td { background: linear-gradient(90deg,#0d47a1,#1565c0); }
                            .inv-t08 .sign-block .for-co { color: #0d47a1; }'
        ],
        't09' => [
            'primary' => '#3e2723', 'accent' => '#795548',
            'header_bg' => '#3e2723', 'font' => '"Courier New", Courier, monospace',
            'style_class' => 'inv-t09', 'layout' => 'ledger',
            'company_font_size' => '18px', 'title_size' => '14px',
            'row_hover' => '#efebe9',
            'extra_css' => '.inv-t09 { border: 2px solid #3e2723; background: #fdf8f3; }
                            .inv-t09 .inv-hd { background: #3e2723; border-bottom: 4px double #795548; }
                            .inv-t09 .inv-meta { background: #f5e6c8; border: 1px solid #bcaaa4; }
                            .inv-t09 .items-outer { background: #fdf8f3; }
                            .inv-t09 .inv-tbl th { background: #4e342e; color: #f5e6c8; border: 1px solid #795548; }
                            .inv-t09 .inv-tbl td { border: 1px solid #bcaaa4; }
                            .inv-t09 .inv-ft { border-top: 2px double #795548; background: #fdf8f3; }
                            .inv-t09 .inv-ft .totals-box tr.grand-row td { background: #3e2723; }
                            .inv-t09 .sign-block .for-co { color: #3e2723; }
                            .inv-t09 .words-line { font-family: "Courier New", monospace; }'
        ],
        't10' => [
            'primary' => '#263238', 'accent' => '#78909c',
            'header_bg' => '#263238', 'font' => '"Tahoma", Geneva, sans-serif',
            'style_class' => 'inv-t10', 'layout' => 'compact',
            'company_font_size' => '14px', 'company_font_weight' => '700',
            'company_letter_spacing' => '0', 'title_size' => '12px',
            'row_hover' => '#eceff1',
            'extra_css' => '.inv-t10 { font-size: 11px; }
                            .inv-t10 .inv-hd { padding: 10px; }
                            .inv-t10 .inv-meta { padding: 6px 10px; }
                            .inv-t10 .items-outer { padding: 5px 10px; }
                            .inv-t10 .inv-tbl th { font-size: 8px; padding: 3px; }
                            .inv-t10 .inv-tbl td { padding: 3px; font-size: 10px; }
                            .inv-t10 .inv-ft { padding: 8px 10px; }
                            .inv-t10 .inv-ft .totals-box table td { padding: 2px 4px; font-size: 10px; }
                            .inv-t10 .inv-ft .totals-box tr.grand-row td { background: #263238; font-size: 11px; }
                            .inv-t10 .sign-block { padding: 8px 10px; }
                            .inv-t10 .sign-block .for-co { color: #263238; }'
        ],
    ];
    return $styles[$tplId] ?? $styles['t01'];
}

function renderInvoice($inv) {
    if (!defined('CGST_RATE')) define('CGST_RATE', 3);
    if (!defined('SGST_RATE')) define('SGST_RATE', 3);
    $tplId = $inv['template'] ?? 't01';
    $valid = ['t01','t02','t03','t04','t05','t06','t07','t08','t09','t10'];
    if (!in_array($tplId, $valid)) $tplId = 't01';
    
    $file = __DIR__ . '/templates/' . $tplId . '.php';
    if (!file_exists($file)) {
        $file = dirname(__DIR__) . '/includes/templates/' . $tplId . '.php';
    }
    if (file_exists($file)) {
        include $file;
    } else {
        echo '<div style="padding:2rem">Template file not found</div>';
    }
}

