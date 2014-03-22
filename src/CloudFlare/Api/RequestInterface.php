<?php namespace Flatline\CfDdns\CloudFlare\Api;

interface RequestInterface
{
    public function setToken($token);

    public function setEmail($email);

    public function edit($subdomain, $domain, $ip);

    public function getId($subdomain, $domain);

    public function getIp($subdomain, $domain);
}
