<?php
require __DIR__ . '/../autoload.php';

$id = $_GET['id'];
$select = new \Models\Select();
$select->Connect();
$item = $select->FindOne($id);

include __DIR__ . '/../views/items.php';