<?php
class Model {
    const DB_NAME = 'vacanciesslibrary.db';
    const DB_PREFIX = 'all_';
    protected $db;

    public function __construct()
    {
        try {
            $this->db = new PDO('sqlite:' . __DIR__ . '/' . self::DB_NAME);
        } catch (PDOException $e) {
            return "Error: ".$e;
        }
        $this->db->exec("pragma synchronous = off;");

        $this->db->exec('CREATE TABLE IF NOT EXISTS `'.self::DB_PREFIX.'vacancies` (
          `profession` TEXT(255) NOT NULL,
          `organisation` TEXT(255) NOT NULL,
          `additions` TEXT(255) NOT NULL,
          `salary` TEXT(255) NOT NULL,
          `address` TEXT(255) NOT NULL,
          `contacts` TEXT(255) NOT NULL,
          `search` TEXT(800) NOT NULL,
          `date` TEXT(10) NOT NULL,
          `hash` TEXT(32) NOT NULL,
          PRIMARY KEY ("hash")
        )');

        $this->db->exec('CREATE TABLE IF NOT EXISTS `'.self::DB_PREFIX.'service` (
          `try_date` TEXT(10) NOT NULL,
          `file_date` TEXT(255) NOT NULL,
          `hash_filedate` TEXT(32) NOT NULL,
          PRIMARY KEY ("hash_filedate")
        )');
    }

    public function getList($date)
    {
        $stmt = $this->db->prepare('SELECT `profession`, `organisation`, `additions`, `salary`, `address`, `contacts`, `search`, `date`, `hash` FROM `'. self::DB_PREFIX .'vacancies` WHERE `date` = ?');
        $stmt->bindValue(1, $date, PDO::PARAM_STR);
        $stmt->execute();

        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        unset($stmt);

        return $res;
    }

    public function getOne($id)
    {
        $stmt = $this->db->prepare('SELECT `profession`, `organisation`, `additions`, `salary`, `address`, `contacts`, `search`, `date` FROM `'. self::DB_PREFIX .'vacancies` WHERE `hash` = ? LIMIT 1');
        $stmt->bindValue(1, $id, PDO::PARAM_STR);
        $stmt->execute();

        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        unset($stmt);

        return $res;
    }

    function getLastDateRec()
    {
        $stmt = $this->db->prepare('SELECT `try_date`, `file_date`, `hash_filedate` FROM `'. self::DB_PREFIX .'service` WHERE `try_date` = (SELECT max(try_date) FROM `'. self::DB_PREFIX .'service`)');
        //$stmt->bindValue(1, $date, PDO::PARAM_STR);
        $stmt->execute();

        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        unset($stmt);

        return $res[0];
    }

    public function insertValues($values)
    {
        $hash = implode($values); //поисковая строка

        $stmt = $this->db->prepare('INSERT INTO `'. self::DB_PREFIX .'vacancies` ( `profession`, `organisation`, `additions`, `salary`, `address`, `contacts`, `search`, `date`, `hash` )
        VALUES ( :profession, :organisation, :additions, :salary, :address, :contacts, :search, :date, :hash )');
        $stmt->bindValue(':profession', $values['pro'], PDO::PARAM_STR);
        $stmt->bindValue(':organisation', $values['org'], PDO::PARAM_STR);
        $stmt->bindValue(':additions', $values['add'], PDO::PARAM_STR);
        $stmt->bindValue(':salary', $values['sal'], PDO::PARAM_STR);
        $stmt->bindValue(':address', $values['adr'], PDO::PARAM_STR);
        $stmt->bindValue(':contacts', $values['con'], PDO::PARAM_STR);
        $stmt->bindValue(':search', $hash, PDO::PARAM_STR);
        $stmt->bindValue(':date', $values['dat'], PDO::PARAM_STR);
        $stmt->bindValue(':hash', md5($hash), PDO::PARAM_STR);
        $result = $stmt->execute();

        return $result;
    }

    public function checkPrevious($datestring, $date)
    {
        $hashstring = md5($datestring);
        $rec = $this->getLastDateRec();

        if ($hashstring == $rec['hash_filedate']) {
            return true;
        } else {
            $stmt = $this->db->prepare('INSERT INTO `'. self::DB_PREFIX .'service` ( `try_date`, `file_date`, `hash_filedate` )
        VALUES ( :try_date, :file_date, :hash_filedate )');
            $stmt->bindValue(':try_date', $date, PDO::PARAM_STR);
            $stmt->bindValue(':file_date', $datestring, PDO::PARAM_STR);
            $stmt->bindValue(':hash_filedate', $hashstring, PDO::PARAM_STR);
            $result = $stmt->execute();

            return null;
        }
    }

    public function deleteDateRec($datestring)
    {
        $hashstring = md5($datestring);
        $stmt = $this->db->prepare('DELETE FROM `'. self::DB_PREFIX .'service` WHERE `hash_filedate` = :hash_filedate ');
        $stmt->bindValue(':hash_filedate', $hashstring, PDO::PARAM_STR);
        $stmt->execute();
    }
}