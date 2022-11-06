<?php
require './include/html-header.php';

$y = $db->get_all('SELECT 3, 7-9 AS computed, NOW() AS now');
print_r($y);
?>

It works!

<?php require './include/html-footer.php'; ?>