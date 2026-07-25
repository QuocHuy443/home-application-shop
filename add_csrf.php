<?php
$dir = new RecursiveDirectoryIterator(__DIR__ . '/app/Views');
$iterator = new RecursiveIteratorIterator($dir);
foreach ($iterator as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        $content = file_get_contents($file);
        
        // Remove existing to avoid duplicate
        $content = str_replace("<?= \App\Helpers\CsrfHelper::csrfField() ?>", "", $content);

        $newContent = preg_replace_callback('/<form([^>]+)method="POST"([^>]*)>/i', function($matches) {
            $formTag = $matches[0];
            return $formTag . "\n    <?= \App\Helpers\CsrfHelper::csrfField() ?>";
        }, $content);

        if ($content !== $newContent) {
            file_put_contents($file, $newContent);
            echo "Added CSRF to $file\n";
        }
    }
}
