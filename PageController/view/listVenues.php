<?php
/**
 * @var Registry\Request $request
 * @var \ApplicationController\Domain\Venue $venue
 */

$message = $request->getMessages();
$venue = $request->getProperty('venue');
?>
<html lang="ru">
<head>
    <title>Добавление заведения</title>
</head>
<body>
<h1>Список заведений</h1>
<p>Заведение: <?=$venue->getName();?></p>
</body>
</html>