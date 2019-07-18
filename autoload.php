<?php
// Функция автозагрузки
function namespaceAutoload( $path ) {
    // Поиск по namespace соответствующему структуре каталогов
    // Заменяем символы разделяющие директории на поддерживаемые OS
    if ( preg_match( '/\\\\/', $path ) ) {
        $path = str_replace( '\\', DIRECTORY_SEPARATOR, $path );
    }

    $file = stream_resolve_include_path( "$path.php" );

    if ( file_exists( $file ) ) {
        require_once( "$file" );
        return;
    }

    // Поиск расширяющих классов:
    // StructureRequestDecorator -> RequestDecorator -> Decorator
    $class = substr( strrchr( $path, DIRECTORY_SEPARATOR ), 1 );
    $search = str_split( $class );

    foreach ( $search as $key => $letter ) {
        if ( $letter === strtoupper( $letter ) && $key > 0 ) {
            $newClass = substr( $class, $key );
            $newPath = str_replace( $class, $newClass, $path );
            $file = stream_resolve_include_path( "$newPath.php" );

            if ( file_exists( $file ) ) {
                require_once( "$file" );
                break;
            }
        }
    }
}

spl_autoload_register( 'namespaceAutoload' );