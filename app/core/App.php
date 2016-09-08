<?php
/**
 * Created by PhpStorm.
 * User: liltoto
 * Date: 28-08-2016
 * Time: 01:46
 */

class App
{
    protected $controller = 'home';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        if(isset($url[0])) {
            if (file_exists(getcwd() .'/app/controllers/' . ucfirst($url[0]) . '.php')) {
                $this->controller = $url[0];
                unset($url[0]);
            } else {
                header("HTTP/1.0 404 Not Found");
                $this->controller = 'ErrorPage';
            }
        }

        require_once getcwd() . '/app/controllers/' . ucfirst($this->controller) . '.php';
        $this->controller = new $this->controller;

        if(isset($url[1])){
            if(method_exists($this->controller, $url[1])){
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    protected function parseUrl()
    {
        if(isset($_GET['url'])){
            return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }
}