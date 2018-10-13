<?php

  namespace lib;

  class Router {

    public function start() {

      //      Массив с url элементами
      $url = self::url();
      //      Беру первый элемент из массива
      $parameter = array_shift($url);
      //      Проверяю пустой или нет. Если пустой, то назначаю свой
      $fileName = (!$parameter) ? 'Faq' : ucfirst($parameter);
      //      if (!isset($_SESSION['status']))
      //        $fileName = 'Faq';
      //      Путь к запрашиваемому файлу
      $falePath = 'controllers/' . $fileName . 'Controller.php';
      //      Проверяю, есть такой файл или нет. Если нет, то прерываю скрипт
      if (is_file($falePath)) {
        include $falePath;
      } else {
        header($_SERVER['SERVER_PROTOCOL'] . 'HTTP/1.0 404 Not found');
        die('<h2>Ошибка 404 нет накого файла</h2>');
      }
      //      Путь до класса
      $classPath = 'controllers\\' . $fileName . 'Controller';
      //      Проверяю, есть такой класс или нет. Если нет, то прерываю работу скрипта
      if (class_exists($classPath)) {
        $class = new $classPath;
      } else {
        header($_SERVER['SERVER_PROTOCOL'] . 'HTTP/1.0 404 Not found');
        die('<h2>Ошибка 404 нет накого класса</h2>');
      }
      //      Беру второй параметр из массива. Он определяет метод в классе
      $method = array_shift($url);
      //      Проверяю, пустой или нет массив. Если пустой, то назначаю метод index
      $method = !$method ? 'index' : self::methodFormat($method);
      //      Проверяю, есть такой метод в классе. Если не, то прерываю работу скрипта
      if (method_exists($class, $method)) {
        //        Проверяю, есть ли параметры
        $params = count($url) > 0 ? array_shift($url) : '';
        //      Так, как у меня будет один параметр, то беру один из массива $url и добавляю в метод
        return $class->$method($params);
      } else {
        header($_SERVER['SERVER_PROTOCOL'] . 'HTTP/1.0 404 Not found');
        die('<h2>Ошибка 404 нет накого метода</h2>');
      }

    }

    public static function url() {
      // Получаю запрос
      $path = trim($_SERVER['REQUEST_URI'], '/');
      // Разбираю запрос по /
      $parser = explode('/', $path);
      // Проверяю в массиве первый элемент, если index.php то удаляю из массива функцией array_shift
      if ($parser[0] == 'index.php') {
        array_shift($parser);
      }
      // Вывожу путь к файлу в массиве
      return $parser;
    }

    public static function methodFormat($method) {
      $parser = explode('_', $method);
      return isset($parser[1]) ? $parser[0] . ucfirst($parser[1]) : $parser[0];
    }
  }
  