<?php

namespace App\Tests\D07Bundle\Service;

use App\D07Bundle\Service\Ex03Service;
use PHPUnit\Framework\TestCase;

class Ex03ServiceTest extends TestCase
{
    private $ex03Service;

    protected function setUp(): void
    {
        $this->ex03Service = new Ex03Service();
    }

    public function testUppercaseWords()
    {
        $input = 'hello world';
        $expected = 'Hello World';
        $result = $this->ex03Service->uppercaseWords($input);
        $this->assertEquals($expected, $result);

        $input = 'hello';
        $expected = 'Hello';
        $result = $this->ex03Service->uppercaseWords($input);
        $this->assertEquals($expected, $result);

        $input = 'Hello World';
        $expected = 'Hello World';
        $result = $this->ex03Service->uppercaseWords($input);
        $this->assertEquals($expected, $result);
    }

    public function testCountNumbers()
    {
        $input = '123abc456';
        $expected = 6;
        $result = $this->ex03Service->countNumbers($input);
        $this->assertEquals($expected, $result);


        $input = 'abcdef';
        $expected = 0;
        $result = $this->ex03Service->countNumbers($input);
        $this->assertEquals($expected, $result);


        $input = '987654';
        $expected = 6;
        $result = $this->ex03Service->countNumbers($input);
        $this->assertEquals($expected, $result);
    }
}


?>