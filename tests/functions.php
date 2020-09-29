<?php

require '../functions.php';

use PHPUnit\Framework\TestCase;

class test extends TestCase {
    public function testLettersArray()
    {
        $expected = [
            0 => 'a',
            1 => 'b',
            2 => 'c',
            3 => 'd',
            4 => 'e',
            5 => 'g',
            6 => 'i',
            7 => 'k',
            8 => 'l',
            9 => 'm',
            10 => 'n',
            11 => 'o',
            12 => 'p',
            13 => 'r',
            14 => 's',
            15 => 't',
            16 => 'u',
            17 => 'x',
            18 => 'z'
        ];
        $input = [
            ['value' =>'alpha'],
            ['value' => 'beta'],
            ['value' => 'gamma'],
            ['value' => 'delta'],
            ['value' => 'epsilon'],
            ['value' => 'zeta'],
            ['value' => 'eta'],
            ['value' => 'theta'],
            ['value' => 'iota'],
            ['value' => 'kappa'],
            ['value' => 'lambda'],
            ['value' => 'mu'],
            ['value' => 'nu'],
            ['value' => 'xi'],
            ['value' => 'omicron'],
            ['value' => 'pi'],
            ['value' => 'rho'],
            ['value' => 'sigma'],
            ['value' => 'tau'],
            ['value' => 'upsilon'],
            ['value' => 'phi'],
            ['value' => 'chi'],
            ['value' => 'psi'],
            ['value' => 'omega']
        ];
        $case = getLetters($input, 'value');
        $this->assertEquals($expected, $case);
    }
}