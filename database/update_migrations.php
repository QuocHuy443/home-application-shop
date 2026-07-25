<?php
$dir = __DIR__ . '/migrations/';
foreach(glob($dir . '*.php') as $file) {
    $content = file_get_contents($file);
    preg_match("/Capsule::schema\(\)->create\('([^']+)'/", $content, $matches);
    if($matches && !strpos($content, 'dropIfExists')) {
        $table = $matches[1];
        $replace = "Capsule::schema()->dropIfExists('$table');\n    Capsule::schema()->create('$table'";
        $content = str_replace("Capsule::schema()->create('$table'", $replace, $content);
        file_put_contents($file, $content);
        echo "Updated $file\n";
    }
}
