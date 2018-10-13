<?php


  namespace models;


  use lib\DataBase;

  class AdminModel extends DataBase {
    // Все администраторы
    public static function viewAllAdmins() {
      $sql = "SELECT a.login, a.id, r.title status FROM admin a JOIN roles r ON r.id=a.role_id ORDER BY a.id DESC";
      $sth = self::DB()->prepare($sql);
      $sth->execute();
      $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
      return $result ? $result : false;
    }

    public static function viewAdmin($id) {
      $sql = "SELECT a.login, a.role_id, a.id, r.title title_role, r.id id_role FROM admin a JOIN roles r ON r.id = a.role_id WHERE a.id = ?";
      $sth = self::DB()->prepare($sql);
      $sth->bindParam(1, $id, \PDO::PARAM_INT);
      $sth->execute();
      $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

      return $result ? $result[0] : false;
    }

    // Проверка логина
    public static function checkLogin($login) {
      $sql = "SELECT login, pass, role_id, id FROM admin WHERE login = ?";
      $sth = self::DB()->prepare($sql);
      $sth->bindParam(1, $login, \PDO::PARAM_STR);
      $sth->execute();
      $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
      return $result ? $result : false;
    }

    // Проверка логина и паспорта
    public static function checkLoginPass($login, $pass) {
      $result = self::checkLogin($login)[0];
      if (password_verify($pass, $result['pass'])) {
        return $result;
      }
      return false;
    }

    // Новый администратор
    public static function createAdmin($login, $pass) {
      if (!empty($login) && !empty($pass)) {
        $pass = password_hash($pass, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `admin` ( `login`, `pass`, `role_id`) VALUES ( ?, ?, '1')";
        $sth = self::DB()->prepare($sql);
        $sth->bindParam(1, $login, \PDO::PARAM_STR);
        $sth->bindParam(2, $pass, \PDO::PARAM_STR);
        $sth->execute();
        return $sth->rowCount() ? true : false;
      }
      return false;
    }

    // Изменить пароль администратора
    public static function updatePassAdmin($pass, $id) {
      $pass = password_hash($pass, PASSWORD_DEFAULT);
      $sql = "UPDATE admin SET pass=? WHERE id=?";
      $sth = self::DB()->prepare($sql);
      $sth->bindParam(1, $pass, \PDO::PARAM_STR);
      $sth->bindParam(2, $id, \PDO::PARAM_INT);
      $sth->execute();
      return $sth->rowCount() ? true : false;
    }

    // Удалить администратора
    public static function deleteAdmin($id) {
      $sql = "DELETE FROM admin WHERE id=?";
      $sth = self::DB()->prepare($sql);
      $sth->bindParam(1, $id, \PDO::PARAM_INT);
      $sth->execute();
      return $sth->rowCount() ? true : false;
    }
  }
  