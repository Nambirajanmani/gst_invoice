<?php
require_once dirname(__DIR__) . '/server/includes/functions.php';
$templates = getTemplates();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Templates Gallery — GST Invoice</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav>
  <div class="logo"><span class="rupee">₹</span> GST Invoice Manager</div>
  <ul>
    <li><a href="index.php">🏠 Home</a></li>
    <li><a href="create-invoice.php">📝 New Invoice</a></li>
    <li><a href="templates.php" class="active">🎨 Templates</a></li>
  </ul>
</nav>

<div class="container">
  <div class="card">
    <div class="card-header">
      <h2>🎨 Available Invoice Templates</h2>
      <a href="create-invoice.php" class="btn btn-primary">+ Create Invoice</a>
    </div>
    <div class="template-grid">
<?php foreach ($templates as $key => $tpl): $c=$tpl['color']; ?>
      <div class="tpl-option">
        <div class="tpl-thumb" style="background:<?=$c?>;color:#fff;border-radius:8px 8px 0 0;">📄 <?=$key?></div>
        <div class="tpl-label" style="border:1px solid #ddd;border-top:none;border-radius:0 0 8px 8px;">
          <strong style="color:<?=$c?>"><?=$tpl['name']?></strong>
          <small style="display:block;color:#888"><?=$tpl['desc']?></small>
        </div>
      </div>
<?php endforeach; ?>
    </div>
  </div>
</div>
</body>
</html>
