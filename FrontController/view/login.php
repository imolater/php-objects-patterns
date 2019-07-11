<?php
/**
 * @var Request $request
 */
?>
<html lang="ru">
<head>
    <title>Woo! Введите логин!</title>
</head>

<body>
    <p><?=reset( $request->getMessages() );?></p>

    <form>
        <input type="text" name="login" placeholder="Введите логин">
        <input type="submit" value="Отправить">
    </form>
</body>
</html>