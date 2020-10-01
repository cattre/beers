<?php

require '../uploadFunctions.php';

use PHPUnit\Framework\TestCase;

class test extends TestCase {
    public function testCheckNewImage() {
        $expected = 'This filename already exists, please rename and try again.';
        $error = '';
        $target = '../media/gem.jpg';
        $case = checkNewImage($error, $target);
        $this->assertEquals($expected, $case);
    }

    public function testCheckFileType() {
        $expected = 'Only JPG, JPEG, PNG & GIF iamge files are allowed.';
        $error = '';
        $type = 'tif';
        $case = checkFileType($error, $type);
        $this->assertEquals($expected, $case);
    }
}