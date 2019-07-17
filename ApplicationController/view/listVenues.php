<?php
/**
 * @var array $mess
 */
?>
<html lang="ru">
<head>
    <title>Woo! Это программа Woo!</title>
</head>

<body>
<p>Цепочка вызовов:</p>
<pre>
    <? print_r( get_included_files() ); ?>
</pre>
<p><?=reset( $mess );?></p>
</body>
</html>