<?php

class TestCase extends \PHPUnit_Framework_TestCase
{
    public function fixture($filename)
    {
        return __DIR__."/fixtures/$filename";
    }

    public function teardown()
    {
        \Mockery::close();
    }
}
