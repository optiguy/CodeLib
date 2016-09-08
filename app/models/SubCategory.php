<?php
use Database\DbPDO;
use Helpers\Url;

class SubCategory
{
    private $db;
    private $url;
    public $cacheName = 'SubCategory';

    public function __construct()
    {
		$this->url = new Url();
        $this->db = new DbPDO('mysql:host=' . _DB_HOST_ . ';dbname=' . _DB_NAME_ . ';charset=utf8', _DB_USER_, _DB_PASSWORD_);
    }

    public function getAll()
    {
        return $this->db->query('SELECT * FROM subcategory', [], PDO::FETCH_ASSOC);
    }

    public function count()
    {
        return count((array)$this->db->query('SELECT * FROM subcategory'));
    }

    public function sortUp($id, $catId)
    {
        $i = $this->db->query('SELECT * FROM subcategory WHERE id = :id', [':id' => $id], PDO::FETCH_ASSOC)[0]['sort'];
        $updateSort = $i - 1;
        $newSort = $i + 1;
        $this->db->query('UPDATE subcategory SET sort = :newValue WHERE sort = :sort AND fkCategoryId = :catId', [':newValue' => $i, ':sort' => $updateSort, ':catId' => $catId]);
        $this->db->query('UPDATE subcategory SET sort = :newValue WHERE id = :id', [':id' => $id, ':newValue' => $updateSort]);
    }
    public function sortDown($id, $catId)
    {
        $i = $this->db->query('SELECT * FROM subcategory WHERE id = :id', [':id' => $id], PDO::FETCH_ASSOC)[0]['sort'];
        $updateSort = $i + 1;
        $newSort = $i - 1;
        $this->db->query('UPDATE subcategory SET sort = :newValue WHERE sort = :sort AND fkCategoryId = :catId', [':newValue' => $i, ':sort' => $updateSort, ':catId' => $catId]);
        $this->db->query('UPDATE subcategory SET sort = :newValue WHERE id = :id', [':id' => $id, ':newValue' => $updateSort]);
    }

    public function sortRearrange()
    {
        $result = $this->db->query('SELECT id, sort, fkCategoryId FROM subcategory ORDER BY sort');
        $tempId = 0;
        $i = 1;
        foreach ($result as $value) {
            if($tempId != $value->fkCategoryId){
                $tempId = $value->fkCategoryId;
                $i = 1;
            }
            $this->db->query('UPDATE subcategory SET sort = :newValue WHERE id = :id', [':id' => $value->id, ':newValue' => $i]);
            $i++;
        }
    }

    public function edit($id, $name, $catId = null)
    {
        if($catId != null){
            $this->db->query('UPDATE `subcategory` SET `name`= :name, `fkCategoryId`= :catId WHERE id = :id', [':id' => $id, ':name' => $name, ':catId' => $catId]);
        } else {
            $this->db->query('UPDATE `subcategory` SET `name`= :name WHERE id = :id', [':id' => $id, ':name' => $name]);
        }
    }

    public function delete($id)
    {
        $this->db->query('DELETE FROM `lib` WHERE fkSubId = :id', [':id' => $id]);
        $this->db->query('DELETE FROM `subcategory` WHERE id = :id', [':id' => $id]);
        $this->sortRearrange();
    }

    public function add($name, $catId)
    {
        $result = $this->db->query('SELECT * FROM subcategory WHERE fkCategoryId = :catId ORDER BY sort DESC LIMIT 1', [':catId' => $catId], PDO::FETCH_ASSOC)[0]['sort'];
        $sort = $result + 1;
        $this->db->query('INSERT INTO `subcategory`(`name`, `sort`, `fkCategoryId`) VALUES (:name, :sort, :catId)', [':name' => $name, ':sort' => $sort, ':catId' => $catId]);
    }

    public function getSingle($id)
    {
        return $this->db->query('SELECT * FROM `subcategory` WHERE id = :id', [':id' => $id], PDO::FETCH_ASSOC)[0];
    }

    public function cleanDB()
    {
        $this->db->query('DELETE * FROM `subcategory` WHERE fkCategoryId = 0');
    }
}