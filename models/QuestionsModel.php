<?php

  namespace models;

  use lib\DataBase;

  class QuestionsModel extends DataBase {

    // Все вопросы
    public static function viewAllQuestions() {
      $sql = "SELECT q.question, q.id, q.answer,q.receipt_date, c.title, c.id cat_id, s.title status_title FROM question_answer q JOIN categories c ON q.category_id = c.id JOIN status s ON s.id = q.status_id ORDER BY category_id";
      $sth = self::DB()->prepare($sql);
      $sth->execute();
      $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

      return ($result > 0) ? $result : false;
    }

    // Неотвеченные вопросы
    public static function viewEmptyQuestions() {
      $sql = "SELECT q.*, c.title, c.id cat_id, s.title status_title FROM question_answer q LEFT JOIN categories c ON q.category_id = c.id JOIN status s ON s.id = q.status_id WHERE q.status_id = '1'  ORDER BY q.receipt_date";
      $sth = self::DB()->prepare($sql);
      $sth->execute();
      $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

      return ($result > 0) ? $result : false;
    }

    public static function viewVisibleQuestions() {
      $sql = "SELECT q.id, q.question, q.answer, q.category_id FROM question_answer q WHERE q.status_id = 2";
      $sth = self::DB()->prepare($sql);
      $sth->execute();
      $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

      return ($result > 0) ? $result : false;
    }

    public static function viewQuestion($id) {
      $sql = "SELECT q.id, q.user_name, q.question, q.answer, q.email, c.title title_cat, c.id id_cat, s.id id_status, s.title title_status FROM question_answer q JOIN categories c ON c.id = q.category_id JOIN status s ON s.id = q.status_id WHERE q.id = ?";
      $sth = self::DB()->prepare($sql);
      $sth->bindParam(1, $id, \PDO::PARAM_INT);
      $sth->execute();
      $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

      return ($result > 0) ? $result : false;
    }

    public static function questionsInTheme($id) {

      $sql = "SELECT  q.id, q.question, q.answer, q.receipt_date, s.title FROM question_answer q JOIN status s ON s.id = q. status_id WHERE q.category_id = ?";
      $sth = self::DB()->prepare($sql);
      $sth->bindParam(1, $id, \PDO::PARAM_INT);
      $sth->execute();
      $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

      return ($result > 0) ? $result : false;
    }

    public static function addQuetion($user, $email, $theme, $quest) {
      if (!empty($user) && !empty($email) && !empty($theme) && !empty($quest)) {
        $sql = "INSERT INTO `question_answer` ( `user_name`, `email`, `category_id`, `question`, `status_id`) VALUES ( ?, ?, ?, ?, '1')";
        $sth = self::DB()->prepare($sql);
        $sth->bindParam(1, $user, \PDO::PARAM_STR);
        $sth->bindParam(2, $email, \PDO::PARAM_STR);
        $sth->bindParam(3, $theme, \PDO::PARAM_INT);
        $sth->bindParam(4, $quest, \PDO::PARAM_STR);
        $sth->execute();
        return $sth->rowCount() ? true : false;
      }
      return false;
    }

    public static function updateQuestion($user, $email, $theme, $quest, $answer, $status, $id) {
      if (!empty($user) && !empty($email) && !empty($theme) && !empty($quest)) {
        $status = ($answer != null) ? $status : '1';
        if($answer != null && $status == 1) $status = 3;
        $sql = "UPDATE question_answer SET user_name=?, email=?, category_id=?, question=?, answer=?, status_id=?  WHERE id=?";
        $sth = self::DB()->prepare($sql);
        $sth->bindParam(1, $user, \PDO::PARAM_STR);
        $sth->bindParam(2, $email, \PDO::PARAM_STR);
        $sth->bindParam(3, $theme, \PDO::PARAM_INT);
        $sth->bindParam(4, $quest, \PDO::PARAM_STR);
        $sth->bindParam(5, $answer, \PDO::PARAM_STR);
        $sth->bindParam(6, $status, \PDO::PARAM_INT);
        $sth->bindParam(7, $id, \PDO::PARAM_INT);
        $sth->execute();
        return $sth->rowCount() ? true : false;
      }
      return false;
    }

    public static function deleteQuestion($id) {
      $sql = "DELETE FROM question_answer WHERE id = ?";
      $sth = self::DB()->prepare($sql);
      $sth->bindParam(1, $id, \PDO::PARAM_INT);
      $sth->execute();
      return $sth->rowCount() ? true : false;

    }

    public static function status() {
      $sql = "SELECT  id, title FROM status";
      $sth = self::DB()->prepare($sql);
      $sth->execute();
      $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
      return $result ? $result : false;
    }

  }
  