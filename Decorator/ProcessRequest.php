<?php

namespace Decorator;

abstract class ProcessRequest {
    abstract function process( RequestHelper $helper );
}

class MainProcessRequest extends ProcessRequest {
    public function process( RequestHelper $helper ) {
        print __CLASS__ . ": выполнение запроса \n";
    }
}