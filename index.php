<?php
// Добавляем корень сайта в пути, по которым будут искаться классы
set_include_path( get_include_path() . PATH_SEPARATOR . __DIR__ );

require_once 'autoload.php';
?>

<pre>
<?
try {
    // Имитируем отправку запроса
    $request = ApplicationController\Registry\ApplicationRegistry::getRequest();
    $request->setProperty( 'action', 'AddVenue' );
    $request->setProperty( 'venueName', 'Рыба-Меч' );
    // Вызываем контроллер
    ApplicationController\FrontController::run();
} catch ( \Exception $e ) {
    print $e->getMessage();
}
?>
</pre>
