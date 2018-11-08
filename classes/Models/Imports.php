<?php

namespace Models;

class Imports
{
    public $idShop;
    public $name;
    public $company;
    public $url;
    public $currencies;
    public $categories = [];
    public $offers = [];
    public $yml;
    const PATH = '/../../xml/file.xml';

    public $dbh; // конннект к базе

    public function __construct(){
        $path = __DIR__ . self::PATH ;
        $xmlToString = file_get_contents($path); // xml  в строку
        $this->yml = new \SimpleXMLElement($xmlToString);

        $this->name = (string) $this->yml->shop->name;
        $this->company = (string) $this->yml->shop->company;
        $this->url = (string) $this->yml->shop->url;

        $this->Connect(); /// коннектимся к базе
    }

    public function Connect(){
        $dsn = 'mysql:host=127.0.0.1; dbname=aggregator; charset=utf8';
        $this->dbh = new \PDO($dsn, 'root', '12345678');
    }

    public function GetCategories(){
        foreach ($this->yml->shop->categories->category as $i) {
            array_push($this->categories, [
                    'id' =>(int) $i['id'],
                    'parentId' => (int) $i['parentId'],
                    'value' =>  (string)$i,
                ]
            );
        }
    }

    public function GetOffer(){
        foreach ($this->yml->shop->offers->offer as $item) {
            array_push($this->offers, [
                                        'id_offer' =>(int) $item['id'],
                                        'available' => (bool) $item['available'],
                                        'group_id' => (int) $item['group_id'],
                                        'value' => [
                                                    'url' => (string) $item->url,
                                                    'price' => (string) $item->price,
                                                    'currencyId' => (string) $item->currencyId,
                                                    'categoryId' => (string) $item->categoryId,
                                                    'picture' => (string) $item->picture,
                                                    'delivery' => (bool) $item->delivery,
                                                    'name' => (string) $item->name,
                                                    'description' => (string) $item->description,
                                                    ],
                                        ]
            );
        }
    }

    public function LoadingShop(){
        $sth = $this->dbh->prepare('select * from shops where name =:name');
        $sth->execute([':name' => $this->name]);
        $data = $sth->fetch();
        if (!empty ($data)){
            $this->idShop = $data['id'];
        }else{
            $sth = $this->dbh->prepare(
                'INSERT INTO shops (name, company, url) VALUES (:name, :company, :url)'
            );
            $sth->bindParam(':name', $this->name);
            $sth->bindParam(':company', $this->company);
            $sth->bindParam(':url', $this->url);
            $res = $sth->execute();
            if ($res === true){
                $sth = $this->dbh->prepare('select * from shops where name =:name');
                $sth->execute([':name' => $this->name]);
                $data = $sth->fetch();
                $this->idShop = $data['id'];
            }else{
                var_dump($sth->errorInfo()[2]); // вывод текста ошибки
                var_dump('LoadingShop'); // вывод текста ошибки
                die;
            }
        }
    }

    public function LoadingCategories(){
        if (!empty ($this->idShop)){
            // перебор категорий
            foreach ($this->categories as $category) {
                $sth = $this->dbh->prepare('select id from categories where id_category =:id_category and id_shop =:id_shop');
                $sth->execute([':id_category' => $category['id'] , ':id_shop' => $this->idShop]);
                $data = $sth->fetch();
                $idCategory = $data['id'];
//                var_dump($idCategory = $data['id']);
                if(isset($idCategory)){
                    $sth = $this->dbh->prepare(
                        'UPDATE `categories` SET `id_parent`=:id_parent,`value`=:value WHERE `id`=:id'
                    );
                    $sth->bindParam(':id', $idCategory);
                    $sth->bindParam(':id_parent', $category['parentId']);
                    $sth->bindParam(':value', $category['value']);
                    $res = $sth->execute();

                    if ($res === false){
                        var_dump('error iinsert in LoadingCategories');
                        var_dump($sth->errorInfo()[2]); // вывод текста ошибки
                        die;
                    }
                }else{
                    $sth = $this->dbh->prepare(
                        'INSERT INTO categories (id_category, id_parent, value, id_shop) VALUES (:id_category, :id_parent, :value, :id_shop)'
                    );
                    $sth->bindParam(':id_category', $category['id']);
                    $sth->bindParam(':id_parent', $category['parentId']);
                    $sth->bindParam(':value', $category['value']);
                    $sth->bindParam(':id_shop', $this->idShop);
                    $res = $sth->execute();

                    if ($res === false){
                        var_dump('error iinsert in LoadingCategories');
                        var_dump($sth->errorInfo()[2]); // вывод текста ошибки
                        die;
                    }
                }
            }

        }else{
            var_dump('error idShop in LoadingCategories');
            die;
        }
    }


    public function LoadingOffers(){
        if (!empty ($this->idShop)){

            foreach ($this->offers as $offer){
                $sth = $this->dbh->prepare('select * from offers where id_offer =:id_offer and id_shop =:id_shop');
                $sth->execute([':id_offer' => $offer['id_offer'], ':id_shop' => $this->idShop]);
                $data = $sth->fetch();
                $idOffer = $data['id'];
                if(isset($idOffer)){
                    $sth = $this->dbh->prepare(
                        'UPDATE `offers` SET 
                 `available` =:available,
                  `group_id` =:group_id,
                  `url` =:url,
                  `price` =:price,
                  `currencyId` =:currencyId,
                  `categoryId` =:categoryId,
                  `picture` =:picture,
                  `delivery` =:delivery,
                  `name` =:name,
                  `description` =:description
                  WHERE `id`=:id
                  '
                    );
                    $sth->bindParam(':id', $idOffer);

                    $sth->bindParam(':available', $offer['available']);
                    $sth->bindParam(':group_id', $offer['group_id']);

                    $sth->bindParam(':url', $offer['value']['url']);
                    $sth->bindParam(':price', $offer['value']['price']);
                    $sth->bindParam(':currencyId', $offer['value']['currencyId']);
                    $sth->bindParam(':categoryId', $offer['value']['categoryId']);
                    $sth->bindParam(':picture', $offer['value']['picture']);
                    $sth->bindParam(':delivery', $offer['value']['delivery']);
                    $sth->bindParam(':name', $offer['value']['name']);
                    $sth->bindParam(':description', $offer['value']['description']);
                    $res = $sth->execute();
                    if ($res === false){
                        var_dump('error insert in LoadingCategories');
                        var_dump($sth->errorInfo()[2]); // вывод текста ошибки
                        die;
                    }
                }else {
                    $sth = $this->dbh->prepare(
                        'INSERT INTO offers (
                    id_shop, id_offer, available, group_id,url,price,currencyId,categoryId,picture,delivery,name,description
                    ) VALUES (
                    :id_shop, :id_offer, :available, :group_id,:url,:price,:currencyId,:categoryId,:picture,:delivery,:name,:description
                    )'
                    );
                    $sth->bindParam(':id_shop', $this->idShop);

                    $sth->bindParam(':id_offer', $offer['id_offer']);
                    $sth->bindParam(':available', $offer['available']);
                    $sth->bindParam(':group_id', $offer['group_id']);

                    $sth->bindParam(':url', $offer['value']['url']);
                    $sth->bindParam(':price', $offer['value']['price']);
                    $sth->bindParam(':currencyId', $offer['value']['currencyId']);
                    $sth->bindParam(':categoryId', $offer['value']['categoryId']);
                    $sth->bindParam(':picture', $offer['value']['picture']);
                    $sth->bindParam(':delivery', $offer['value']['delivery']);
                    $sth->bindParam(':name', $offer['value']['name']);
                    $sth->bindParam(':description', $offer['value']['description']);
                    $res = $sth->execute();

                    if ($res === false) {
                        var_dump('error iinsert in LoadingOffers');
                        var_dump($sth->errorInfo()[2]); // вывод текста ошибки
                        die;
                    }
                }
            }
            return 'Документ YML магазина ' . $this->name .' загружен';
        }else{
            var_dump('error idShop in LoadingOffers');
            die;
        }
    }
}