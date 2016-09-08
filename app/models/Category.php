<?php
use Database\DbPDO;
use Helpers\Url;

class Category
{
    private $db;
    private $tempArray = [];
    private $url;
    public $cacheName = 'sideCategory';

    public function __construct()
    {
		$this->url = new Url();
        $this->db = new DbPDO('mysql:host=' . _DB_HOST_ . ';dbname=' . _DB_NAME_ . ';charset=utf8', _DB_USER_, _DB_PASSWORD_);
    }

    public function render()
    {
    	if(empty($this->tempArray)){
    		$this->getAll();
    	}

    	$html = '<nav>';
    	foreach($this->tempArray as $key => $value){
    		$html .= '<ul><li><a href="#">' . $value['name'] . '</a><ul>';
            if(!empty($value['submenu'])){
    			foreach($value['submenu'] as $subKey => $subValue){
    				$html .= '<li><a href="' . $this->url->link('home/category/' . $subValue['id']) . '">' . $subValue['name'] . '</a></li>';
    			}
            }
			$html .= '</ul></li></ul>';
    	}
    	$html .= '<hr><ul><li><a class="admin" href="'. $this->url->link('admin/') .'">Admin</a></li></ul></nav><p class="version">Version ' . _VERSION_ . ' </p>';

    	//echo '<pre>',print_r($this->tempArray),'</pre>';
    	return $html;
    }

    public function getAll()
    {
    	foreach($this->db->query('SELECT category.id as catId, category.name, subCategory.id AS subId, subCategory.name AS subName FROM category LEFT JOIN subCategory ON subCategory.fkCategoryId = category.id ORDER BY category.sort, subCategory.sort') as $value){
    		if(!key_exists($value->catId, $this->tempArray)){
    			$this->tempArray[$value->catId] = ['name' => $value->name];
    		}
            if($value->subId != null){
        		$this->tempArray[$value->catId]['submenu'][$value->subId] = array(
        			'name' => $value->subName,
        			'id' => $value->subId
        		);
            }
    	}

    	return $this->tempArray;
    }

    public function count()
    {
        return count((array)$this->db->query('SELECT * FROM category'));
    }

    public function sortUp($id)
    {
        $i = $this->db->query('SELECT * FROM category WHERE id = :id', [':id' => $id], PDO::FETCH_ASSOC)[0]['sort'];
        $updateSort = $i - 1;
        $newSort = $i + 1;
        $this->db->query('UPDATE category SET sort = :newValue WHERE sort = :sort', [':newValue' => $i, ':sort' => $updateSort]);
        $this->db->query('UPDATE category SET sort = :newValue WHERE id = :id', [':id' => $id, ':newValue' => $updateSort]);
    }
    public function sortDown($id)
    {
        $i = $this->db->query('SELECT * FROM category WHERE id = :id', [':id' => $id], PDO::FETCH_ASSOC)[0]['sort'];
        $updateSort = $i + 1;
        $newSort = $i - 1;
        $this->db->query('UPDATE category SET sort = :newValue WHERE sort = :sort', [':newValue' => $i, ':sort' => $updateSort]);
        $this->db->query('UPDATE category SET sort = :newValue WHERE id = :id', [':id' => $id, ':newValue' => $updateSort]);
    }

    public function sortRearrange()
    {
        $result = $this->db->query('SELECT id, sort FROM category ORDER BY sort');

        $i = 1;
        foreach ($result as $value) {
            $this->db->query('UPDATE category SET sort = :newValue WHERE id = :id', [':id' => $value->id, ':newValue' => $i]);
            $i++;
        }
    }

    public function delete($id)
    {
        foreach ($this->db->query('SELECT * FROM `subcategory` WHERE fkCategoryId = :id', [':id' => $id]) as $key => $value) {
            $this->db->query('DELETE FROM `lib` WHERE fkSubId = :id', [':id' => $value->id]);
        }
        $this->db->query('DELETE FROM `subcategory` WHERE fkCategoryId = :id', [':id' => $id]);
        $this->db->query('DELETE FROM `category` WHERE id = :id', [':id' => $id]);
        $this->sortRearrange();
    }

    public function edit($id, $name)
    {
        $this->db->query('UPDATE `category` SET `name`= :name WHERE id = :id', [':id' => $id, ':name' => $name]);
    }

    public function add($name)
    {
        $result = $this->db->query('SELECT * FROM category ORDER BY sort DESC LIMIT 1', [], PDO::FETCH_ASSOC)[0]['sort'];
        $sort = $result + 1;
        $this->db->query('INSERT INTO `category`(`name`, `sort`) VALUES (:name,:sort)', [':name' => $name, ':sort' => $sort]);
    }

    public function getSingle($id)
    {
        return $this->db->query('SELECT * FROM `category` WHERE id = :id', [':id' => $id], PDO::FETCH_ASSOC)[0];
    }
}