<?php
require __DIR__ . '/autoload.php';

if (isset($_POST['input']) and !empty($_POST['input'])) {
$select = new \Models\Select();
$select->Connect();
$result = $select->Find($_POST);
}

$shops = new \Models\Select();
$shops->Connect();
$allShops = $shops->FindShops();

include  __DIR__ . '/views/index.php';