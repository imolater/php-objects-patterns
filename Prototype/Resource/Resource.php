<?php

namespace Prototype\Resource;


class Resource {
    private $count;

    public function __construct($count = 0) {
        $this->count = $count;
    }

    /**
     * @return int
     */
    public function getCount(): int {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount( int $count ): void {
        $this->count = $count;
    }
}

class FishResource extends Resource {}

class OilResource extends Resource {}