<?
namespace ApplicationController\Domain;

class Venue {
    private $id;
    private $name;
    private static $count = 0;

    public function __construct($name) {
        $this->id = self::$count++;
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }
}