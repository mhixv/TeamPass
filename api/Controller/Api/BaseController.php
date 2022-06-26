<?php
/**
 * Teampass - a collaborative passwords manager.
 * ---
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * ---
 *
 * @project   Teampass API
 *
 * @file      BaseController.php
 * ---
 *
 * @author    Nils Laumaillé (nils@teampass.net)
 *
 * @copyright 2009-2022 Teampass.net
 *
 * @license   https://spdx.org/licenses/GPL-3.0-only.html#licenseText GPL-3.0
 * ---
 *
 * @see       https://www.teampass.net
 */
class BaseController
{
    /**
     * __call magic method.
     */
    public function __call($name, $arguments)
    {
        $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
    }

    /**
     * Get URI elements.
     * 
     * @return array
     */
    public function getUriSegments()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode( '/', $uri );
        return $this->sanitizeUrl($uri);
    }

    /**
     * Get querystring params.
     * 
     * @return array
     */
    public function getQueryStringParams()
    {
        parse_str($_SERVER['QUERY_STRING'], $query);
        return $this->sanitizeUrl($query);
    }

    /**
     * Undocumented function
     *
     * @param array $array
     * @return array
     */
    public function sanitizeUrl(array $array) : array
    {
        $filters = [];
        $array_size = count($array);
        for ($i=0; $i < $array_size; $i++) {
            array_push($filters, 'trim|escape');
        }
        
        return dataSanitizer(
            $array,
            $filters,
            __DIR__.'/../../..'
        );
    }


    /**
     * Send API output.
     *
     * @param mixed  $data
     * @param string $httpHeader
     */
    protected function sendOutput($data, $httpHeaders=array())
    {
        header_remove('Set-Cookie');
 
        if (is_array($httpHeaders) && count($httpHeaders)) {
            foreach ($httpHeaders as $httpHeader) {
                header($httpHeader);
            }
        }
 
        echo $data;
        exit;
    }
}