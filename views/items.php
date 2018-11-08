<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Страница товара</title>
</head>
<body>
<H1>Страница товара</H1>

<p><a href="/../index.php">Вернуться на страницу поиска</a></p>
<hr align="left" width="500" size="2" color="#6666ff" />
<p>Магазин - <?php echo $item['shop'] ?> </p>
<p><a href="<?php echo $item['url'] ?>"><?php echo $item['name'] ?></a></p>
<p><a href="<?php echo $item['url'] ?>"><img src="<?php echo $item['picture'] ?>" alt="Картинка временно отсутствует" width="20%"></a></p>
<p>Цена <?php echo $item['price'] . ' ' . $item['currencyId'] ?> </p>
<p>Категория - <?php echo $item['parent'] ?> </p>
<p>Подкатегория - <?php echo $item['category'] ?></p>
<p>Описание</p>
<p><?php echo $item['description'] ?> </p>
<hr align="center" width="500" size="2" color="#ff0000" />

</body>
</html>