<?php 

namespace Src\Support;

use RuntimeException;

/**
 * class help with the creation of url
 */
final class Url
{
    /**
     * list of url associate with a name
     * 
     * @var array<string, string>
     */
    public array $linkedUrls;

    /**
     * build a instance of Url
     * 
     * @param array<string, string>
     */
    public function __construct(array $linkedUrls = [])
    {
        $this->linkedUrls = $linkedUrls;
    }

    /**
     * bind the parameters for the url
     * 
     * @param string $url the url to bind
     * @param array<string|int, string> $bindings value to use on bind
     * 
     * @return string
     */
    public function bind(string $url, array $bindings = []): string
    {
        preg_match_all('/(?<={)[^}]*(?=})/', $url, $parameters);

        if (count($parameters) > count($bindings)) {
            throw new RuntimeException('Missing url bindings');
        }

        foreach ($parameters as $ind => $param) {
            $value = $bindings[match (true) {
                array_key_exists($param, $bindings) => $param,
                array_key_exists($ind, $bindings) => $ind,
            }];

            $url = preg_replace("/{{$param}}/", $value, $url, 1);
        }

        return $url;
    }

    /**
     * obtains url from linked and bind it
     * 
     * @param string $link the name/index of the element
     * @param array<string|int, string> $bindings value to use on bind
     * 
     * @return string
     */
    public function linked(string $link, array $bindings = []): string
    {
        if (! array_key_exists($link, $this->linkedUrls)) {
            throw new RuntimeException("No found linked url [{$link}]");
        }

        return $this->bind($this->linkedUrls[$link], $bindings);
    }
}