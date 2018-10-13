<?php

  namespace lib;

  class DataBase {

    private static $_db = NULL;

    public static function DB() {
      if (NULL === self::$_db)
      
        self::$_db = new \PDO(DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS);
      return self::$_db;
    }

  }
  