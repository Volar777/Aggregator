<?php
require  __DIR__ . '/../autoload.php'; // автозагрузка классов
$path = __DIR__ . '/../xml';

if (isset($_FILES['xml']) ){

    \Models\LoadFile::incomingFile($path);

    $shop = new \Models\Imports();
    $shop->GetCategories();
    $shop->GetOffer();
    $shop->LoadingShop();
    $shop->LoadingCategories();

    echo  $shop->LoadingOffers();
}

include __DIR__ . '/../views/imports.php';

