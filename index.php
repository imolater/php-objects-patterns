<?php
// Добавляем корень сайта в пути, по которым будут искаться классы
set_include_path( get_include_path() . PATH_SEPARATOR . __DIR__ );

require_once 'autoload.php';
?>

<pre>
<?
try {
    // Имитация запроса с данными
    $request = Registry\ApplicationRegistry::getRequest();
    $request->setProperty('action', 'login');
    // Активация контроллера
    FrontController\Controller::run();
} catch ( \Exception $e ) {
    print $e->getMessage();
}
?>
</pre>
