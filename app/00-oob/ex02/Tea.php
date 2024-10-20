<?php

require_once "HotBeverage.php";

class Tea extends HotBeverage {
    private string $description = "Ceylon Chai";
    private string $comment = "from India";

    public function __construct() {
        parent::__construct("Tea", 2, 4);
    }
    public function __destruct() {}

    public function getDescription() { return $this->description; }
    public function getComment() { return $this->comment; }
}



?>