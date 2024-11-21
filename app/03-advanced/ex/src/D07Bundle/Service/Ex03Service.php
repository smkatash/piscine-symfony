<?php

namespace App\D07Bundle\Service;

class Ex03Service {

    public function uppercaseWords(string $input): string {
        return ucwords(strtolower($input));
    }

    public function countNumbers(string $input): int {
        return preg_match_all('/\d/', $input);
    }
}

?>