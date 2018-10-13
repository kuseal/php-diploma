<?php

  session_start();

  include 'lib/config.php';

  // Автозагрузчик классов
  spl_autoload_register( function ( $class ) {
    $file = ROOT . '/' . str_replace( '\\', '/', $class ) . '.php';
    if ( is_file( $file ) ) {
      require $file;
    }
  } );

  // Шаблонизатор Twig
  include 'lib/Twig/Autoloader.php';
  Twig_Autoloader::register();


  $site = new \lib\Router();
  $site->start();