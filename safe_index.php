<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR])) {
        http_response_code(200); // Prevent 500 error redirect
        echo "<div style='color:red; font-family:sans-serif; padding:20px; border:1px solid red; margin:20px; background:#fee;'>";
        echo "<strong>FATAL PHP ERROR CAUGHT:</strong><br>";
        echo htmlspecialchars($error['message']) . "<br>";
        echo "File: " . $error['file'] . " on line " . $error['line'];
        echo "</div>";
    }
});

try {
    $_SERVER['SCRIPT_NAME'] = '/public/index.php';
    require __DIR__ . '/public/index.php';
} catch (Throwable $e) {
    http_response_code(200); // Prevent 500 error redirect
    echo "<div style='color:red; font-family:sans-serif; padding:20px; border:1px solid red; margin:20px; background:#fee;'>";
    echo "<strong>EXCEPTION CAUGHT:</strong><br>";
    echo htmlspecialchars($e->getMessage()) . "<br>";
    echo "File: " . $e->getFile() . " on line " . $e->getLine();
    echo "</div>";
}
