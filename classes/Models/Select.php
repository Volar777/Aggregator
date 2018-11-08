<?php

namespace Models;

class Select
{
    public $dbh;

    public function Connect()
    {
        $dsn = 'mysql:host=127.0.0.1; dbname=aggregator; charset=utf8';
        $this->dbh = new \PDO($dsn, 'root', '12345678');
    }

    public function Find($post)
    {
        $selectShops = [];
        foreach($post as $k =>$item){
            if ( stripos($k,'id')){
                array_push($selectShops , $item);
            }
        }
        $selectShops = implode(',',$selectShops);

        $requestText = $post['input'];
        $requestMin = $post['min'];
        $requestMax = $post['max'];

        $text = '%' . $requestText . '%';
        $sql = 'SELECT sh.name as shop, cat.value as category ,cat2.value as parent, off.* 
                FROM (
                  select * from offers  
                  where name like :name and price >= :priceMin and price <=:priceMax
                ) as off
                inner join( 
                  select * from shops where id in (' . $selectShops . ')
                       ) as sh on sh.id = off.id_shop
                left join categories as cat on off.categoryId = cat.id_category
                left join categories as cat2 on cat.id_parent = cat2.id_category
                ';
        $sth = $this->dbh->prepare($sql);
        $sth->execute([':name' => $text, ':priceMin' => $requestMin, ':priceMax' => $requestMax]);
        $data = $sth->fetchAll();

        return $data;
    }

    public function FindOne($idOffer)
    {
        $sql = 'SELECT sh.name as shop, cat.value as category ,cat2.value as parent, off.* 
                FROM (
                  select * from offers  
                  where id_offer =:id_offer
                ) as off
                inner join shops as sh on sh.id = off.id_shop
                left join categories as cat on off.categoryId = cat.id_category
                left join categories as cat2 on cat.id_parent = cat2.id_category
                ';
        $sth = $this->dbh->prepare($sql);
        $sth->execute([':id_offer' => $idOffer]);
        $data = $sth->fetch();

        return $data;
    }

    public function FindShops()
    {
        $sql = 'SELECT id, name from shops';
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $data = $sth->fetchAll();

        return $data;
    }
}
