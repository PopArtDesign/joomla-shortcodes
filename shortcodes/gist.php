<?php

\defined('_JEXEC') or die;

$url = $attributes[0] ?? '';
$file = $attributes['file'] ?? '';

if (!$url || strpos($url, 'https://gist.github.com') !== 0) {
    return;
}

$scriptUrl = rtrim($url, '/') . '.js';

if ($file) {
    $scriptUrl .= '?file=' . urlencode($file);
}

?>
<script src="<?php echo htmlspecialchars($scriptUrl); ?>"></script>
