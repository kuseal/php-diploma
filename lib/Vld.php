<?php
  /**
   * Created by PhpStorm.
   * User: Prikol Studia
   * Date: 11.10.2018
   * Time: 12:38
   */

  namespace lib;


  class Vld {

    // Проверка формы вопроса
    public static function createQuestion() {
      if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['theme']) && !empty($_POST['message'])) {
        $name = self::checkInput($_POST['name']);
        if (!preg_match('/^[а-яА-ЯёЁъЪa-zA-Z]+$/iu', $name)) {
          return false;
        }
        $email = self::checkInput($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          return false;
        }
        $theme = (int)($_POST['theme']);
        if (!filter_var($theme, FILTER_VALIDATE_INT)) {
          return false;
        }
        $message = self::checkInput($_POST['message']);
        if (isset($name) && isset($email) && isset($theme) && isset($message)) {
          return [
              'name' => $name,
              'email' => $email,
              'theme' => $theme,
              'message' => $message
          ];
        }
      }
      return false;
    }

    // Проверка формы редактирования вопроса
    public static function editQuestion() {
      if (!empty($_POST['user']) && !empty($_POST['email']) && !empty($_POST['theme']) && !empty($_POST['status']) && !empty($_POST['question']) && !empty($_POST['id'])) {
        $user = self::checkInput($_POST['user']);
        if (!preg_match('/^[а-яА-ЯёЁъЪa-zA-Z]+$/iu', $user)) {
          return false;
        }
        $email = self::checkInput($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          return false;
        }
        $theme = (int)($_POST['theme']);
        if (!filter_var($theme, FILTER_VALIDATE_INT)) {
          return false;
        }
        $status = (int)($_POST['status']);
        if (!filter_var($status, FILTER_VALIDATE_INT)) {
          return false;
        }
        $question = self::checkInput($_POST['question']);
        $answer = self::checkInput($_POST['answer']);
        $id = (int)$_POST['id'];
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
          return false;
        }
        if (isset($user) && isset($email) && isset($theme) && isset($status) && isset($question) && isset($answer) && isset($id)) {
          return [
              'user' => $user,
              'email' => $email,
              'theme' => $theme,
              'status' => $status,
              'question' => $question,
              'answer' => $answer,
              'id' => $id
          ];
        }
      }
      return false;
    }

    // Проверка формы создания темы
    public static function createTheme() {
      if (!empty($_POST['title'])) {
        $title = self::checkInput($_POST['title']);
        if (!preg_match('/^[а-яА-ЯёЁъЪa-zA-Z0-9 ]+$/iu', $title)) {
          return false;
        }
        if (isset($title)) {
          return ['title' => $title];
        }
      }
      return false;
    }

    // Проверка формы добавления аминистратора
    public static function createAdmin() {
      $login = self::checkInput($_POST['login']);
      if (!preg_match('/^[a-zA-Z0-9]+$/iu', $login)) {
        return false;
      }
      $pass = self::checkInput($_POST['pass']);
      if (!preg_match('/^[a-zA-Z0-9]+$/iu', $pass)) {
        return false;
      }
      if (isset($login) && isset($pass)) {
        return ['login' => $login, 'pass' => $pass];
      }
      return false;
    }

    // Проверка формы изменения пароля администратора
    public static function updatePass() {
      $pass = self::checkInput($_POST['pass']);
      if (!preg_match('/^[a-zA-Z0-9]+$/iu', $pass)) {
        return false;
      }
      $id = (int)$_POST['id'];
      if (!filter_var($id, FILTER_VALIDATE_INT)) {
        return false;
      }
      if (isset($pass) && isset($id)) {
        return ['pass' => $pass, 'id' => $id];
      }
      return false;
    }



    private static function checkInput($input) {
      $input = trim($input);
      $input = stripslashes($input);
      $input = htmlspecialchars($input);
      return $input;
    }

  }