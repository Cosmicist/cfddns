<?php namespace Flatline\CfDdns\CloudFlare\Api;

class RequestTest extends \TestCase
{
    /**
     * @var Request
     */
    protected $request;

    public function setUp()
    {
        $this->request = new Request('token', 'foo@bar.baz', $this->fixture('cf_api.json?'));
    }

    public function testGetIdSuccess()
    {
        $rs = $this->request->getId('foo', 'example.com');

        $this->assertEquals('16606009', $rs);
    }

    public function testEditSuccess()
    {
        $rs = $this->request->edit('foo', 'example.com', '127.0.0.1');

        $this->assertEquals('success', $rs->result);
    }
}