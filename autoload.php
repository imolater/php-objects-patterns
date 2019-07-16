<?php
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

    // Поиск расширяющих классов
    $class = substr( strrchr( $path, DIRECTORY_SEPARATOR ), 1 );
    $search = str_split( $class );

    $start = null;
    $end = null;

    foreach ( $search as $key => $letter ) {
        if ( $letter === strtoupper( $letter ) )
            if ( is_null( $start ) )
                $start = $key;
            elseif ( !is_null( $start ) && is_null( $end ) )
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