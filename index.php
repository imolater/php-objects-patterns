<?php
// Добавляем корень сайта в пути, по которым будут искаться классы
set_include_path( get_include_path() . PATH_SEPARATOR . __DIR__ );

require_once 'autoload.php';
?>

<pre>
    <?
    try {
        $login = new Observer\Login();
        new Observer\SecurityMonitorObserver( $login );
        new Observer\GeneralLoggerObserver( $login );
        new Observer\PartnershipToolObserver( $login );

        $login->notify();

    } catch ( \Exception $e ) {
        print $e->getMessage();
    }
    ?>
</pre>
