<?php 

namespace Src\Support\Url;

use RuntimeException;

/**
 * class help with the creation of url
 */
final class UrlGenerator
{
    /**
     * list of url associate with a name
     * 
     * @var array<string, string>
     */
    public array $linkedUrls;

    /**
     * the previous url
     * 
     * @var string
     */
    private string $backUrl = '/';

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
     * @param array<string|int, string|int|float|boolean> $bindings value to use on bind
     * 
     * @return string
     * 
     * @throws RuntimeException
     */
    public function bind(string $url, array $bindings = []): string
    {
        $assoc = array_filter($bindings, 'is_string', ARRAY_FILTER_USE_KEY);
        $indexed = array_merge(array_filter($bindings, 'is_int', ARRAY_FILTER_USE_KEY));

        $url = $this->bindAssoc($url, $assoc);
        $url = $this->bindIndexate($url, $indexed);
        
        if (preg_match('/(?<={)[^}]*(?=})/', $url)) {
            throw new RuntimeException("Missing url parameters [{$url}]");
        }

        return $url;
    }

    /**
     * bind the parameters for the url (associative part)
     * 
     * @param string $url the url to bind
     * @param array<string, string|int|float|boolean> $bindings value to use on bind
     * 
     * @return string
     */
    private function bindAssoc(string $url, array $bindings): string
    {
        preg_match_all('/(?<={)[^}]*(?=})/', $url, $parameters);

        foreach ($parameters[0] as $item) {
            if (array_key_exists($item, $bindings)) {
                $url = str_replace("{{$item}}", $bindings[$item], $url);
            }
        }

        return $url;
    }

    /**
     * bind the parameters for the url (indexate part)
     * 
     * @param string $url the url to bind
     * @param array<string|int|float|boolean> $bindings value to use on bind
     * 
     * @return string
     */
    private function bindIndexate(string $url, array $bindings): string
    {
        preg_match_all('/(?<={)[^}]*(?=})/', $url, $parameters);

        foreach ($parameters[0] as $ind => $item) {
            if (array_key_exists($ind, $bindings)) {
                $url = str_replace("{{$item}}", $bindings[$ind], $url);
            }
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

    /**
     * obtains the previous url
     * 
     * @return string
     */
    public function back(): string
    {
        return request()->server('HTTP_REFERER') ?? $this->backUrl;
    }

    /**
     * set the previous url
     * 
     * @param string $url the new previous url
     * 
     * @return void
     */
    public function setBackUrl(string $url): void
    {
        $this->backUrl = $url;
    }

    /**
     * throw exception for a missing paramters binding
     * 
     * @throws RuntimeException
     */
    private function throwMissingParam(string $parameter): void
    {
        throw new RuntimeException("Missing binding for parameter [{$parameter}]");
    }
}