<?php
@unlink(__DIR__ . '/git_restore.php');
@unlink(__FILE__);
echo 'Cleaned up helper scripts';
