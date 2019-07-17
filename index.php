<?php
// Добавляем корень сайта в пути, по которым будут искаться классы
set_include_path( get_include_path() . PATH_SEPARATOR . __DIR__ );

require_once 'autoload.php';
?>

<pre>
<?
try {
    // Создаём объект
    $test = Singleton\Preferences::getInstance();
    // Устанавливаем свойство
    $test->setProperty( 'name', 'Иван' );
    // Записываем объект в новую переменную
    $test2 = Singleton\Preferences::getInstance();
    // Меняем свойство
    $test2->setProperty( 'name', 'Алёша' );
    // Получаем один и тот же объект в обоих переменных
    print $test->getProperty( 'name' );
    print $test2->getProperty( 'name' );
} catch ( \Exception $e ) {
    print $e->getMessage();
}
?>
</pre>
