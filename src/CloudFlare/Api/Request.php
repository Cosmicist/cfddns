<?php namespace Flatline\CfDdns\CloudFlare\Api;

class Request implements RequestInterface
{
    protected $url = 'https://www.cloudflare.com/api_json.html?';
    protected $token;
    protected $email;

    public function __construct($token = null, $email = null)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function edit($subdomain, $domain, $ip, $service_mode = 0, $ttl = 1)
    {
        $params = [
            'a' => 'rec_edit',
            'type' => 'A',
            'z' => $domain,
            'tkn' => $this->token,
            'email' => $this->email,
            'name' => $subdomain,
            'content' => $ip,
            'service_mode' => $service_mode,
            'ttl' => $ttl,
            'id' => $this->getId($subdomain, $domain),
        ];

        $response = json_decode(file_get_contents($this->url.http_build_query($params)));

        return $response;
    }

    public function getId($subdomain, $domain)
    {
        $params = [
            'a' => 'rec_load_all',
            'tkn' => $this->token,
            'email' => $this->email,
            'z' => $domain,
        ];

        $host = "$subdomain.$domain";

        $response = json_decode(file_get_contents($this->url.http_build_query($params)));

        $recs = $response->response->recs->objs;

        foreach ($recs as $rec)
        {
            if ($rec->name == $host)
            {
                return $rec->rec_id;
            }
        }
    }
}
