<?php

  namespace models;

  use lib\DataBase;

  class ThemesModel extends DataBase {
    public function __construct() {
      parent::__construct();
    }


    // Ввывод категорий только с разрешенными к показу вопросами
    public static function frontendThemes() {
      $sql = "SELECT c.* FROM categories c
                         JOIN question_answer q ON q.status_id='2'
                         WHERE c.id = q.category_id 
                         GROUP BY c.id";
      $sth = self::DB()->prepare($sql);
      $sth->execute();
      $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

      return $result ? $result : false;
    }

    /**
     * Просматривать список тем. По каждой теме в списке видно сколько всего вопросов в ней, сколько опубликовано, сколько без ответов.
     */
    public static function getAllThemes() {

      $sql = "SELECT c.title,c.id id_cat, q.question, q.id, COUNT(question) count_quests,COUNT(if(status_id = '1',1,null)) status_empty, COUNT(if(status_id = '2',1,null)) status_view, COUNT(if(status_id = '3',1,null)) status_hidden, c.title FROM question_answer q RIGHT JOIN categories c ON c.id = q.category_id GROUP BY c.id ORDER BY c.id";
      $sth = self::DB()->prepare($sql);
      $sth->execute();
      $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

      return $result ? $result : false;
    }

    public static function getTheme($id) {
      $sql = "SELECT c.id, c.title FROM categories c WHERE c.id = ? ";
      $sth = self::DB()->prepare($sql);
      $sth->bindParam(1, $id, \PDO::PARAM_INT);
      $sth->execute();
      $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
      return $result ? $result[0] : false;
    }

    // CREATE THEME
    public static function createTheme($title) {
      $sql = "INSERT INTO categories (`title`) VALUES (?)";
      $sth = self::DB()->prepare($sql);

      $sth->bindParam(1, $title, \PDO::PARAM_STR);

      return $sth->execute() ? self::DB()->lastInsertId() : false;
    }

    // UPDATE THEME
    public static function updateTheme($title, $id) {
      $sql = "UPDATE categories SET title=? WHERE id=?";
      $sth = self::DB()->prepare($sql);
      $sth->bindParam(1, $title, \PDO::PARAM_STR);
      $sth->bindParam(1, $id, \PDO::PARAM_INT);
      $sth->execute();
      return $sth->rowCount() ? true : false;
    }

    // DELETE THEME
    public static function deleteTheme($id) {
      $sql = "DELETE FROM categories WHERE id = ?";
      $sth = self::DB()->prepare($sql);
      $sth->bindParam(1, $id, \PDO::PARAM_INT);
      $sth->execute();
      return $sth->rowCount() ? true : false;

    }

    // DELETE THEME AND QUISTIONS
    public static function deleteThemeAndQuestions($id) {
      $sql = "DELETE c, q FROM categories c JOIN question_answer q ON c.id = q.category_id WHERE c.id = ?";
      $sth = self::DB()->prepare($sql);
      $sth->bindParam(1, $id, \PDO::PARAM_INT);
      $sth->execute();

      return $sth->rowCount() ? true : false;
    }

  }
  