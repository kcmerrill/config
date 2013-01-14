<?php
require_once __DIR__ . '/../src/kcmerrill/utility/config.php';

class configTest extends PHPUnit_Framework_TestCase
{
    public $config = false;
    public function setUp()
    {
        $this->config = new kcmerrill\utility\config;
    }

    public function testIsObject()
    {
        $this->assertTrue(is_object($this->config));
    }

    public function testAutoLoadDirectory()
    {
        $loaded = $this->config->autoLoadDirectory(__DIR__ . '/config/');
        $this->assertTrue($loaded);
        $this->assertTrue(isset($this->config['sample']['first_section']['one']));
    }

    public function testConfigNameSplitter()
    {
        $this->config->set('php.hello.world', 'awesome!');
        $this->config->set('php.hello.goodbye', 'hehe');
    }

    public function testGetFunc()
    {
        //We need to test that our . seperators are working :/
        $this->config->set('php.hello.world', 'hello_world');
        $this->assertEquals('hello_world' , $this->config->get('php.hello.world'));
        $this->assertEquals('IDONOTEXIST!', $this->config->get('php.hello.world.doesntexist', 'IDONOTEXIST!'));
        $this->assertEquals(NULL, $this->config->get('php.hello.world.doesntexist'));
    }

    public function testEverythingGenerally()
    {
        $this->config['hello.world.one'] = 'one';
        $this->assertEquals('one', $this->config->get('hello.world.one'));
        $this->assertEquals($this->config['hello']['world']['one'], $this->config->get('hello.world.one'));
    }
}
