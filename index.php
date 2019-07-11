<?php
// Добавляем корень сайта в пути, по которым будут искаться классы
set_include_path( get_include_path() . PATH_SEPARATOR . __DIR__ );

// Функция автозагрузки
function namespaceAutoload( $path ) {
    // Заменяем символы разделяющие директории на поддерживаемые OS
    if ( preg_match( '/\\\\/', $path ) ) {
        $path = str_replace( '\\', DIRECTORY_SEPARATOR, $path );
    }

    $file = stream_resolve_include_path( "$path.php" );

    if ( file_exists( $file ) ) {
        require_once( "$file" );
        return;
    }

    // Поиск в одиночном файле-контейнере с именем namespace
    $class = substr( $path, 0, strpos( $path, DIRECTORY_SEPARATOR ) );
    $file = "{$class}.php";

    if ( file_exists( $file ) ) {
        require_once( "$file" );
        return;
    }

    $class = strrchr( $path, DIRECTORY_SEPARATOR );
    $search = str_split( $class );

    $start = null;
    $end = null;

    foreach ( $search as $key => $letter ) {
        if ( $letter === strtoupper( $letter ) )
            if ( is_null( $start ) )
                $start = $key;
            else if ( ! is_null( $start ) && is_null( $end ) )
                $end = $key;
            else
                break;
    }

    $newClass = substr( $class, $end );
    $newPath = str_replace( $class, $newClass, $path );

    $file = stream_resolve_include_path( "$newPath.php" );

    if ( file_exists( $file ) ) {
        require_once( "$file" );
    }
}

spl_autoload_register( 'namespaceAutoload' );
?>

<pre>
<?
try {
    $test = new AbstractFactoryMethod\BloggsCommsManager();
    print $test->getHeaderText();
    print $test->getApptEncoder()->encode();
    print $test->getContactEncoder()->encode();
    print $test->getTtdEncoder()->encode();
    print $test->getFooterText();

} catch ( \Exception $e ) {
    print $e->getMessage();
}
?>
</pre>
