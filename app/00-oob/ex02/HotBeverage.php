<?php

class HotBeverage {
    private string $name;
    private int $price;
    private int $resistance;

    public function __construct($name="null", $price=null, $resistance=null) {
        $this->name = $name;
        $this->price = $price;
        $this->resistance = $resistance;
    }
    public function __destruct() {}

    public function getName() { return $this->name; }
    public function getPrice() { return $this->price; }
    public function getResistence() { return $this->resistance; }
}


?>