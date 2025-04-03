<?php
// Load Config
require_once 'config/config.php';

// Load Database
// File Database.php chưa tồn tại trong thư mục config, tạm thời comment lại
require_once 'config/Database.php';

// Load Helpers
require_once 'helpers/Helpers.php';
require_once 'helpers/session_helper.php';

// Autoload Core Libraries
spl_autoload_register(function($className) {
    $paths = [
        'helpers/' . $className . '.php',
        'models/' . $className . '.php',
        'controllers/' . $className . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists(__DIR__ . '/' . $path)) {
            require_once __DIR__ . '/' . $path;
            return true;
        }
    }
    
    return false;
});

// Session is already started in session_helper.php
?> 