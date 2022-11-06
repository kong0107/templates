<?php
require_once 'config.php';
require_once 'functions.php';
require_once 'database.php';

if(session_status() !== PHP_SESSION_ACTIVE) session_start();

$db = new mysqlii(DB_HOST, DB_NAME, DB_PASS, DB_NAME);
