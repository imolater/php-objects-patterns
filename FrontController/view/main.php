<?php
/**
 * @var FrontController\Request $request
 */
?>
<html lang="ru">
<head>
    <title>Woo! Это программа Woo!</title>
</head>

<body>
<p><?=reset($request->getMessages());?></p>
</body>
</html>