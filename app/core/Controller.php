<?php
/**
 * Created by PhpStorm.
 * User: liltoto
 * Date: 28-08-2016
 * Time: 01:47
 */

use Database\DbPDO;
class Controller
{
    protected function model($model)
    {
        if(file_exists(getcwd() .'/app/models/' . $model . '.php')) {
            require_once getcwd() . '/app/models/' . $model . '.php';
            return new $model();
        }
    }

    public function view($view, $data = [])
    {
        if(file_exists(getcwd() . '/app/views/' . $view . '.php')) {
            require_once getcwd() . '/app/views/' . $view . '.php';
        }
    }
    public function db()
    {
        return new DbPDO('mysql:host=' . _DB_HOST_ . ';dbname=' . _DB_NAME_ . ';charset=utf8', _DB_USER_, _DB_PASSWORD_);
    }
}