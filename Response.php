<?php


namespace talhaouy\phpmvc;


class Response
{
    public function setStatusCode(int $code)
    {
        http_response_code($code);
    }

    public function redirect(string $url)
    {
        header("Location: ${url}");
    }

    /**
     * Return json
     *
     * @param array $obj The associative array to return as json
     * @param integer $flags 
     * @param integer $depth Set the maximum depth. Must be greater than zero.
     * @return string
     */
    public function json(array $obj, int $flags = 0, int $depth = 512): string
    {
        return json_encode($obj, $flags, $depth);
    }
}
