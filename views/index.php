<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Страница поиска</title>
</head>
<body>
<H1>Страница поиска</H1>
<hr align="left" width="500" size="2" color="#6666ff" />
<p><a href="/../imports/index.php">Загрузка файла YML</a></p>
<hr align="left" width="500" size="2" color="#6666ff" />

<form action="" method="post" >
    <p><b>Введите запрос</b><br>
        <input type="text" name="input" >
    <p><b>Минимальная цена</b><br>
        <input type="number" name="min" value="<?php echo 0; ?>">
    <p><b>Максимальная цена</b><br>
        <input type="number" name="max" value="<?php echo 9999999999; ?>">
    <p><b>Магазины</b><br>

<?php
        foreach ($allShops as $shop) {
?>
            <input type="checkbox" name="<?php echo  $shop['id'] ?>id" value="<?php echo  $shop['id'] ?>"checked> <?php echo  $shop['name'] ?><br>
<?php
        }
?>

        <br><br>
        <input type="submit" value="Найти">
</form>

<?php
foreach ($result as $item) {
    ?>
    <p><a href="/../items/items.php?id=<?php echo  $item['id_offer'] ?>"><?php echo $item['name'] ?></a></p>
    <p><a href="/../items/items.php?id=<?php echo  $item['id_offer'] ?>"><img src="<?php echo $item['picture'] ?>" alt="Картинка временно отсутствует" width="20%"></a></p>
    <p>Цена <?php echo $item['price'] . ' ' . $item['currencyId'] ?></p>

    <hr align="center" width="500" size="2" color="#ff0000" />
    <?php
}
?>

</body>
</html>
