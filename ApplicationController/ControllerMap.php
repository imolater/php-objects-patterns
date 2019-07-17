<?php

namespace ApplicationController;

class ControllerMap {
    private $viewMap = array();
    private $classMap = array();
    private $forwardMap = array();

    public function addClass( $cmd, $class ) {
        $this->classMap[$cmd] = $class;
    }

    public function getClass( $cmd ) {
        if ( isset( $this->classMap[$cmd] ) ) {
            return $this->classMap[$cmd];
        }

        return null;
    }

    public function addView( $view, $cmd = 'default', $status = 0 ) {
        $this->viewMap[$cmd][$status] = $view;
    }

    public function getView( $cmd, $status ) {
        if ( isset( $this->viewMap[$cmd][$status] ) ) {
            return $this->viewMap[$cmd][$status];
        }

        return null;
    }

    public function addForward( $cmd, $forward, $status = 0 ) {
        $this->forwardMap[$cmd][$status] = $forward;
    }

    public function getForward( $cmd, $status ) {
        if ( isset( $this->forwardMap[$cmd][$status] ) ) {
            return $this->forwardMap[$cmd][$status];
        }

        return null;
    }
}