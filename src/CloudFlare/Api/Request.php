<?php namespace Flatline\CfDdns\CloudFlare\Api;

class Request implements RequestInterface
{
    protected $url;
    protected $token;
    protected $email;

    public function __construct($token = null, $email = null, $api_url = 'https://www.cloudflare.com/api_json.html?')
    {
        $this->token = $token;
        $this->email = $email;
        $this->url = $api_url;
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

    public function getIp($subdomain, $domain)
    {
        $host = "$subdomain.$domain";
        $recs = $this->recLoadAll($domain);

        foreach ($recs as $rec)
        {
            if ($rec->name == $host)
            {
                return $rec->content;
            }
        }
    }

    public function getId($subdomain, $domain)
    {
        $host = "$subdomain.$domain";
        $recs = $this->recLoadAll($domain);

        foreach ($recs as $rec)
        {
            if ($rec->name == $host)
            {
                return $rec->rec_id;
            }
        }
    }

    /**
     * Call rec_load_all
     *
     * @param $domain
     * @return bool|array
     */
    private function recLoadAll($domain)
    {
        $params = [
            'a' => 'rec_load_all',
            'tkn' => $this->token,
            'email' => $this->email,
            'z' => $domain,
        ];

        $response = json_decode(file_get_contents($this->url . http_build_query($params)));

        if ($response->result == 'error') {
            return false;
        }

        return $response->response->recs->objs;
    }
}
