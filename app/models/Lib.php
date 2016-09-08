<?php
use Database\DbPDO;
use Helpers\Url;

class Lib
{
    private $db;
    private $tempArray = [];
    private $url;
    public $cacheName = 'sideCategory';
    public $id;

    public function __construct()
    {
		$this->url = new Url();
        $this->db = new DbPDO('mysql:host=' . _DB_HOST_ . ';dbname=' . _DB_NAME_ . ';charset=utf8', _DB_USER_, _DB_PASSWORD_);
    }

    public function render()
    {
    }

    public function getAll()
    {
        return $this->db->query('SELECT * FROM lib ORDER BY sort', [], PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        return $this->db->query('SELECT * FROM lib WHERE fkSubid = :id ORDER BY sort', [':id' => $id], PDO::FETCH_ASSOC);
    }

    public function count()
    {
        return count((array)$this->db->query('SELECT * FROM lib'));
    }

    public function sortUp($id, $subId)
    {
        $i = $this->db->query('SELECT * FROM lib WHERE id = :id', [':id' => $id], PDO::FETCH_ASSOC)[0]['sort'];
        $updateSort = $i - 1;
        $newSort = $i + 1;
        $this->db->query('UPDATE lib SET sort = :newValue WHERE sort = :sort AND fkSubId = :subId', [':newValue' => $i, ':sort' => $updateSort, ':subId' => $subId]);
        $this->db->query('UPDATE lib SET sort = :newValue WHERE id = :id', [':id' => $id, ':newValue' => $updateSort]);
    }

    public function sortDown($id, $subId)
    {
        $i = $this->db->query('SELECT * FROM lib WHERE id = :id', [':id' => $id], PDO::FETCH_ASSOC)[0]['sort'];
        $updateSort = $i + 1;
        $newSort = $i - 1;
        $this->db->query('UPDATE lib SET sort = :newValue WHERE sort = :sort AND fkSubId = :subId', [':newValue' => $i, ':sort' => $updateSort, ':subId' => $subId]);
        $this->db->query('UPDATE lib SET sort = :newValue WHERE id = :id', [':id' => $id, ':newValue' => $updateSort]);
    }

    public function sortRearrange()
    {
        $result = $this->db->query('SELECT id, sort, fkSubId FROM lib ORDER BY sort');
        $tempId = 0;
        $i = 1;
        foreach ($result as $value) {
            if($tempId != $value->fkSubId){
                $tempId = $value->fkSubId;
                $i = 1;
            }
            $this->db->query('UPDATE lib SET sort = :newValue WHERE id = :id', [':id' => $value->id, ':newValue' => $i]);
            $i++;
        }
    }

    public function delete($id)
    {
        $this->db->query('DELETE FROM `lib` WHERE id = :id', [':id' => $id]);
        $this->sortRearrange();
    }

    public function add($title, $subId, $description, $code)
    {
        $result = $this->db->query('SELECT * FROM lib WHERE fkSubId = :subId ORDER BY sort DESC LIMIT 1', [':subId' => $subId], PDO::FETCH_ASSOC)[0]['sort'];
        $sort = $result + 1;
        echo $sort;
        $this->db->query('INSERT INTO `lib`(`title`, `description`, `code`, `fkSubId`, `sort`) VALUES (:title, :description, :code, :fkSubId, :sort)', [':title' => $title, ':sort' => $sort, ':description' => $description, ':fkSubId' => $subId, ':code' => $code]);
    }

    public function edit($id, $title, $description, $code, $subId = null)
    {
        if($subId != null){
            $this->db->query('UPDATE `lib` SET `title`=:title,`description`= :description,`code`= :code,`fkSubId`=:subId WHERE id = :id',[
                ':title' => $title,
                ':description' => $description,
                ':code' => $code,
                ':fkSubId' => $subId,
                ':id' => $id
            ]);
        } else {
            $this->db->query('UPDATE `lib` SET `title`=:title,`description`= :description,`code`= :code WHERE id = :id',[
                ':title' => $title,
                ':description' => $description,
                ':code' => $code,
                ':id' => $id
            ]);
        }
    }

    public function getSingle($id)
    {
        return $this->db->query('SELECT * FROM `lib` WHERE id = :id', [':id' => $id], PDO::FETCH_ASSOC)[0];
    }

    public function searchRows($param)
    {
        return $this->db->query('SELECT lib.title, lib.description, lib.code, subcategory.name AS subName, category.name AS catName, subcategory.id FROM `lib`
                                INNER JOIN subcategory ON subcategory.id = lib.fkSubId
                                INNER JOIN category ON category.id = subcategory.fkCategoryId
                                WHERE title LIKE :search OR code LIKE :search OR description LIKE :search', [':search' => '%'.$param.'%'], PDO::FETCH_ASSOC);
    }

    public function cleanDB()
    {
        $this->db->query('DELETE * FROM `lib` WHERE fkSubId = 0');
    }
}