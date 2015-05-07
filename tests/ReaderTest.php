<?php
namespace probe\tests;

use PHPUnit_Framework_TestCase;
use probe\Factory;
use systeminfo\Reader;

class ReaderTest extends PHPUnit_Framework_TestCase
{

    public function testGetOsType()
    {
        $this->assertInternalType('string', Factory::getOsType());
    }

    public function testGetProvider()
    {
        $this->assertInstanceOf('\probe\provider\AbstractProvider', Factory::create());
    }
}
