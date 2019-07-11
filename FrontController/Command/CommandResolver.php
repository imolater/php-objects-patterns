<?php
/**
 * Шаблон Command
 */

namespace FrontController\Command;

use FrontController\Request;

class CommandResolver {
    private $baseCmd = null;
    private $defaultCmd = null;
    private $dir = __DIR__;

    /**
     * CommandResolver constructor.
     * @throws \ReflectionException
     */
    public function __construct() {
        $this->baseCmd = new \ReflectionClass( "FrontController\Command\Command" );
        $this->defaultCmd = new DefaultCommand();
    }

    /**
     * @param Request $request
     * @return DefaultCommand|object|null
     * @throws \ReflectionException
     */
    public function getCommand( Request $request ) {
        $cmd = $request->getProperty( 'action' );
        $dir = $this->dir;
        $sep = DIRECTORY_SEPARATOR;

        if ( ! $cmd ) {
            return $this->defaultCmd;
        }

        $class = ucfirst( $cmd ) . "Command";
        $file = "{$dir}{$sep}{$class}.php";

        if ( file_exists( $file ) ) {
            require_once( "$file" );

            $class = "FrontController\\Command\\" . $class;
            if ( class_exists( $class ) ) {
                $cmdReflection = new \ReflectionClass( $class );

                if ( $cmdReflection->isSubclassOf( $this->baseCmd ) ) {
                    return $cmdReflection->newInstance();
                }
            }
        }

        $request->addMessage( "Команда $cmd не найдена" );
        return $this->defaultCmd;
    }
}