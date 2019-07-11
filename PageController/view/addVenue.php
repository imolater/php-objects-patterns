<?php
/**
 * @var Registry\Request $request
 */

$message = reset($request->getMessages());
$name = $request->getProperty( 'venueName' );
$owner = $request->getProperty( 'venueOwner' );
?>
<html lang="ru">
<head>
    <title>Добавление заведения</title>
</head>
<body>
<h1>Добавление заведения</h1>
<p><?=$message;?></p>
<form method="get">
    <label>
        Имя: <input type="text" name="venueName" value="<?=$name;?>"/>
    </label>
    <label>
        Владелец: <input type="text" name="venueOwner" value="<?=$owner;?>">
    </label>
    <input type="submit" value="Отправить">
</form>
</body>
</html>