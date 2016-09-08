<?php

/**
 * Created by PhpStorm.
 * User: liltoto
 * Date: 28-08-2016
 * Time: 01:49
 */

use Helpers\Url;
use Database\DbPDO;
use Dynamic\Menu\TopMenu;
use Security\CSRF;
use Security\XSS;

class Home extends Controller
{
	private $category;
	private $categoryData;
	private $lib;

	public function __construct()
	{
        $this->search();
		$this->category = $this->model('Category');
		$this->categoryData = $this->category->render();
        $this->lib = $this->model('Lib');
	}
    public function index($param = null)
    {
        $this->search();
    	$html = '
    		<div class="col m12">
    			<h3 class="center-align">Velkommen til CodeLib</h3>
    		</div>
    	';
        $this->view('home/index',['category' => $this->categoryData, 'html' => $html, 'search' => $this->search()]);
    }

    public function category($id = null)
    {
        $this->view('home/index',['category' => $this->categoryData, 'code' => $this->lib->getById($id), 'search' => $this->search()]);
    }

    private function search()
    {
        if(isset($_POST['search'])){
            $tempArr = $this->model('Lib')->searchRows($_POST['search']);
            if(!empty($tempArr)){
                return $tempArr;
            } else {
                return ['error' => true];
            }
        }
        return [];
    }
}