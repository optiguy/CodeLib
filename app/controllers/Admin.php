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

class Admin extends Controller
{
    protected $categorys;
    protected $subCategorys;
    protected $subjects;
    protected $url;
    protected $backup;

	public function __construct()
	{
        $this->url = new Url();
        $this->categorys = $this->model('Category');
        $this->subCategorys = $this->model('SubCategory');
        $this->subjects = $this->model('Lib');
        $this->backup = $this->model('Backup');
	}
    public function index($param = null)
    {
        $this->view('admin/index',[
            'categoryRender' => $this->categorys->render(),
            'categorys' => $this->categorys->count(),
            'subCategorys' => $this->subCategorys->count(),
            'subjects' => $this->subjects->count(),
            'backupString' => $this->backup->getBackupAsString()
        ]);
    }

    public function category($action = null, $id = null)
    {
        $param = ['categorys' => $this->categorys->getAll()];
        if(isset($_POST['sortUp']) && isset($_POST['id'])){
            $this->categorys->sortUp($_POST['id']);
            header('Location:' . $this->url->link('admin/category'));
        }
        if(isset($_POST['sortDown']) && isset($_POST['id'])){
            $this->categorys->sortDown($_POST['id']);
            header('Location:' . $this->url->link('admin/category'));
        }

        if(($action == 'delete') && ($id != null && is_numeric($id))){
            $this->categorys->delete($id);
            header('Location:' . $this->url->link('admin/category'));
        }

        if(($action == 'edit') && ($id != null && is_numeric($id))){
            $param = ['edit' => $this->categorys->getSingle($id)];
        }
        if(isset($_POST['edit']) && (isset($_POST['name']) && strlen($_POST['name']) > 0)){
            $this->categorys->edit($_POST['id'], $_POST['name']);
            header('Location:' . $this->url->link('admin/category'));
        }

        if(isset($_POST['add']) && (isset($_POST['name']) && strlen($_POST['name']) > 0)){
            $this->categorys->add($_POST['name']);
            header('Location:' . $this->url->link('admin/category'));
        }

        //$this->categorys->sortRearrange();
        $this->view('admin/category', $param);
    }

    public function subcategory($catId = null, $catName = null, $action = null, $subId = null)
    {
        $param = ['categorys' => $this->categorys->getAll(), 'catId' => $catId, 'catName' => $catName];
        $link = $this->url->link('admin/subcategory/'.$catId.'/'.$catName);
        if(isset($_POST['sortUp']) && isset($_POST['id'])){
            $this->subCategorys->sortUp($_POST['id'], $catId);
            header('Location:' . $link);
        }
        if(isset($_POST['sortDown']) && isset($_POST['id'])){
            $this->subCategorys->sortDown($_POST['id'], $catId);
            header('Location:' . $link);
        }

        if(($action == 'delete') && ($subId != null && is_numeric($subId))){
            $this->subCategorys->delete($subId);
            header('Location:' . $link);
        }

        if(($action == 'edit') && ($subId != null && is_numeric($subId))){
            $param['edit'] = [$this->subCategorys->getSingle($subId)];
        }
        if(isset($_POST['edit']) && (isset($_POST['name']) && strlen($_POST['name']) > 0)){
            if(isset($_POST['catId'])){
                $this->subCategorys->edit($_POST['id'], $_POST['name'], $_POST['catId']);
            } else {
                $this->subCategorys->edit($_POST['id'], $_POST['name']);
            }
            header('Location:' . $link);
        }

        if(isset($_POST['add']) && (isset($_POST['name']) && strlen($_POST['name']) > 0)){
            $this->subCategorys->add($_POST['name'], $catId);
            header('Location:' . $link);
        }

        $this->view('admin/subcategory',$param);
    }

    public function subject($catId = null, $catName = null, $subId = null, $subName = null, $action = null, $id = null)
    {
        $param = [
            'categorys' => $this->categorys->getAll(),
            'catId' => $catId,
            'catName' => $catName,
            'subId' => $subId,
            'subName' => $subName
        ];
        if($subId != null && $subName != null){
            $param['subjects'] = $this->subjects->getById($subId);
            $link = $this->url->link('admin/subject/'.$catId.'/'.$catName . '/' . $subId . '/' . $subName);

            if(isset($_POST['sortUp']) && isset($_POST['id'])){
                $this->subjects->sortUp($_POST['id'], $subId);
                header('Location:' . $link);
            }
            if(isset($_POST['sortDown']) && isset($_POST['id'])){
                $this->subjects->sortDown($_POST['id'], $subId);
                header('Location:' . $link);
            }

            if(($action == 'delete') && ($subId != null && is_numeric($subId))){
                $this->subjects->delete($id);
                header('Location:' . $link);
            }

            if(($action == 'edit') && ($id != null && is_numeric($id))){
                $param['edit'] = [$this->subjects->getSingle($id)];
                if(isset($_POST['edit']) && (isset($_POST['title']) && strlen($_POST['title']) > 0)){
                    $code = base64_encode($_POST['codes']);
                    echo "<pre>",print_r($_POST),"<pre>";
                    if(isset($_POST['subId'])){
                        $this->subjects->edit($_POST['id'], $_POST['title'], $_POST['desc'], $code, $_POST['subId']);
                    } else {
                        $this->subjects->edit($_POST['id'], $_POST['title'], $_POST['desc'], $code);
                    }
                    header('Location:' . $link);
                }
            }

            if(isset($_POST['add']) && (isset($_POST['title']) && strlen($_POST['title']) > 0) && (isset($_POST['desc']) && strlen($_POST['desc']) > 0) && (isset($_POST['codes']) && strlen($_POST['codes']) > 0)){
                $code = base64_encode($_POST['codes']);
                $this->subjects->add($_POST['title'], $subId, $_POST['desc'], $code);
                header('Location:' . $link);
                echo "<pre>{$code}</pre>";
            }
        }
        $this->view('admin/subject',$param);
    }
    public function cleanDB()
    {
        $this->subCategorys->cleanDB();
        $this->subjects->cleanDB();

        $this->categorys->sortRearrange();
        $this->subCategorys->sortRearrange();
        $this->subjects->sortRearrange();

        header('Location:' . $this->url->link('admin/index/cleanDB'));
    }
}