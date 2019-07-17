<?php

namespace ApplicationController\Command;

use ApplicationController\Request;

class DefaultCommand extends Command {
    public function doExecute( Request $request ) {
        $request->addTemplateData( 'msg', "Добро пожаловать в Woo!" );
        return self::getStatusCode( 'OK' );
    }
}