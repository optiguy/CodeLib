<?php
use Database\DbPDO;
use Helpers\Url;

class Backup
{
    private $db;
    private $url;
    public $sqlString = null;

    public function __construct()
    {
		$this->url = new Url();
        $this->db = new DbPDO('mysql:host=' . _DB_HOST_ . ';dbname=' . _DB_NAME_ . ';charset=utf8', _DB_USER_, _DB_PASSWORD_);
        if($this->sqlString = null){
            $this->getBackupAsString();
        }
    }

    private function dbStructure($value='')
    {
return [
"
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `sort` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
",
"
CREATE TABLE IF NOT EXISTS `subcategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `sort` int(11) NOT NULL,
  `fkCategoryId` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
",
"
CREATE TABLE IF NOT EXISTS `lib` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(80) NOT NULL,
  `description` text,
  `code` text NOT NULL,
  `fkSubId` int(11) NOT NULL,
  `sort` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
"
];
    }

    public function getSqlFile()
    {
        $filename = 'backup/' . date("d_m_Y H_i_s") . '.sql';
        $file = fopen($filename, "w");
        fwrite($file, $this->sqlString);
        fclose($file);
        return $filename;
    }

    public function getBackupAsString($id = null)
    {
        $sql = '';

        foreach ($this->dbStructure() as $value) {
            $sql .= $value .  "\n";
        }
        $sql .= "-- ----------------------------------------------------------------------------------------------------\n";
        foreach ($this->generateCategory() as $catId => $catValue) {
            $sql .= $catValue[0] .  "\n" . $catValue[1];
            foreach ($this->generateSubCategory($catId) as $subId => $subValue) {
                $sql .= "\n" . $subValue[0] .  "\n" . $subValue[1];
                foreach ($this->generateLib($subId) as $key => $value) {
                    $sql .= "\n" . $value .  "\n";
                    $sql .= "-- ----------------------------------------------------------------------------------------------------\n";
                }
            }
        }
        return $sql;
    }

    protected function generateCategory($id = null)
    {
        $tempArr = [];
        foreach ($this->db->query("SELECT * FROM category") as $key => $value) {
            $tempArr[$value->id] = array(
                "INSERT INTO `category`(`name`, `sort`)
                SELECT * FROM (SELECT '{$value->name}', '{$value->sort}') AS tmp
                WHERE NOT EXISTS (
                    SELECT name FROM `category` WHERE name = '{$value->name}'
                ) LIMIT 1;",
                $this->getLastId('catId')
            );
        }
        return $tempArr;
    }

    protected function generateSubCategory($id)
    {
        $tempArr = [];
        foreach ($this->db->query("SELECT * FROM subcategory WHERE fkCategoryId = :id", [':id' => $id]) as $key => $value) {
            $tempArr[$value->id] = array(
                "INSERT INTO `subcategory`(`name`, `sort`, `fkCategoryId`)
                SELECT * FROM (SELECT '{$value->name}', '{$value->sort}', @catId) AS tmp
                WHERE NOT EXISTS (
                    SELECT name FROM `subcategory` WHERE name = '{$value->name}'
                ) LIMIT 1;",
                $this->getLastId('subId')
            );
        }
        return $tempArr;
    }

    protected function generateLib($id)
    {
        $tempArr = [];
        foreach ($this->db->query("SELECT * FROM lib WHERE fkSubId = :id", [':id' => $id]) as $key => $value) {
            $arr[$key] = "INSERT INTO `lib`(`title`, `description`, `code`, `fkSubId`, `sort`)
                SELECT * FROM (SELECT '{$value->title}', '{$value->description}', '{$value->code}', @subId, '{$value->sort}') AS tmp
                WHERE NOT EXISTS (
                    SELECT title, description FROM `lib` WHERE title = '{$value->title}' AND description = '{$value->description}'
                ) LIMIT 1;";
            $tempArr = array_merge($tempArr, $arr);
        }
        return array_unique($tempArr);
    }
    protected function getHtmlTags($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    protected function getLastId($name)
    {
        return "SELECT LAST_INSERT_ID() INTO @{$name};";
    }
}