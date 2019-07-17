<?php

namespace Test\ApplicationController;

require_once 'autoload.php';

use ApplicationController\FrontController;
use ApplicationController\Registry\ApplicationRegistry;
use PHPUnit\Framework\TestCase;

class FrontControllerTest extends TestCase {
    public function testDefaultCommand() {
        $output = $this->runCommand();
        $this->assertRegExp('/Добро пожаловать в Woo/', $output);
    }

    public function testAddVenueCommand_WithArgs() {
        $args = array('venueName' => 'Рыба-Меч');
        $output = $this->runCommand('AddVenue', $args);
        $this->assertRegExp('/ListVenues/', $output);
    }

    public function runCommand(string $command = null, array $args = null) {
        ob_start();

        $request = ApplicationRegistry::getRequest();

        if (! is_null($args)) {
            foreach ($args as $key => $value)
                $request->setProperty($key, $value);
        }

        if (!is_null($command))
            $request->setProperty('action', $command);

        FrontController::run();

        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
}
