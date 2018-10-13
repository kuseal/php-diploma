<?php
  // Вывод ошибок
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  ini_set('error_reporting', E_ALL);

  // База данных
  define('DB_DRIVER', 'mysql');
  define('DB_HOST', 'localhost');
  define('DB_CHARSET', 'utf8');
  // Изменить
  define('DB_NAME', 'cf32186_faq'); // Название БД
  define('DB_USER', 'cf32186_faq'); // Пользователь БД
  define('DB_PASS', 'kng1262'); // Пароль пользованеля БД


  define('ROOT', dirname(__DIR__));
  //define('ROOT', __DIR__);
