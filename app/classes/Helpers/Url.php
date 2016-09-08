<?php

/**
 * Created by PhpStorm.
 * User: liltoto
 * Date: 28-08-2016
 * Time: 16:44
 */

namespace Helpers;

class Url
{
    private $url;
    private $combined = '';
    public function link($urlFix)
    {
        $this->url = explode('/', filter_var(rtrim($urlFix, '/'), FILTER_SANITIZE_URL));
        $this->combinedUrl();
        return $this->combined;
    }
    private function combinedUrl()
    {
        $this->combined = _PROJECT_NAME_ != '' ?  _DOMAIN_URL_ . '/' . _PROJECT_NAME_ : _DOMAIN_URL_;
        foreach ($this->url as $value)
        {
            $this->combined .= '/' . $value;
        }
    }
}