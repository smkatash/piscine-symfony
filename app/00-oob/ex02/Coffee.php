<?php

require_once "HotBeverage.php";

class Coffee extends HotBeverage {
    private string $description = "Latte Macchiato";
    private string $comment = "with natural milk.";

    public function __construct() {
        parent::__construct("Coffee", 3, 5);
    }
    public function __destruct() {}

    public function getDescription() { return $this->description; }
    public function getComment() { return $this->comment; }
}

?>