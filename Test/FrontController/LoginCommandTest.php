<?php

require_once 'autoload.php';

use PHPUnit\Framework\TestCase;
use Registry\ApplicationRegistry;
use FrontController\Controller;

class LoginCommandTest extends TestCase {
    public function testLoginCommand() {
        $output = $this->runCommand('login');
        $this->assertRegExp("/LoginCommand/", $output);
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

        Controller::run();

        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
}
