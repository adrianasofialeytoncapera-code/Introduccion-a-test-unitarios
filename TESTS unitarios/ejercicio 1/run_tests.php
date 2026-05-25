<?php
declare(strict_types=1);

// ── Autoload ──────────────────────────────────────────────────────────────
spl_autoload_register(function (string $class): void {
    $map = [
        'MyApp\\'  => __DIR__ . '/src/',
        'MyTests\\' => __DIR__ . '/tests/',
    ];

    // Some files define multiple classes/interfaces (e.g. UserService.php has IUserRepository too)
    $multiMap = [
        'MyApp\\IUserRepository' => __DIR__ . '/src/UserService.php',
        'MyApp\\User'            => __DIR__ . '/src/UserService.php',
    ];

    if (isset($multiMap[$class])) {
        require_once $multiMap[$class];
        return;
    }

    foreach ($map as $prefix => $base) {
        if (str_starts_with($class, $prefix)) {
            $relative = substr($class, strlen($prefix));
            $file = $base . str_replace('\\', '/', $relative) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});

require __DIR__ . '/tests/TestRunner.php';

use MyTests\TestRunner;

$runner = new TestRunner();

// ── Registrar todos los archivos de tests ────────────────────────────────
$testFiles = glob(__DIR__ . '/tests/*Tests.php');
sort($testFiles);

foreach ($testFiles as $file) {
    $suite = require $file;
    $suite($runner);
}

// ── Resumen final ─────────────────────────────────────────────────────────
$runner->printSummary();
exit($runner->hasFailed() ? 1 : 0);
