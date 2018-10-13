<?php



  namespace lib;

  
  class Controller {

    protected $dir;

    public function __construct($dir) {
      $this->views = new TwigView();
      $this->views->setTmplPath('views/'.$dir);
    }

  }
  