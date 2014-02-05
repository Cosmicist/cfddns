<?php namespace Flatline\CfDdns\Config\Loader;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

class YmlLoader extends FileLoader
{
    public function load($resource, $type = null)
    {
        $values = Yaml::parse($resource);

        return $values;
    }

    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo(
            $resource,
            PATHINFO_EXTENSION
        );
    }
}
