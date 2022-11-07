<?php
require_once 'config.php';
require_once 'functions.php';
require_once 'database.php';

switch(session_status()) {
    case PHP_SESSION_DISABLED: exit('session disabled');
    case PHP_SESSION_NONE: session_start();
}

try {
    $db = new mysqlii(DB_HOST, DB_NAME, DB_PASS, DB_NAME);
} catch (mysqli_sql_exception $e) {
    printf('Error %d: %s', $e->getCode(), $e->getMessage());
    exit;
}
