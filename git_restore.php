<?php
exec('git checkout HEAD -- includes/templates 2>&1', $output, $returnCode);
echo "Return code: $returnCode\n";
echo implode("\n", $output);
