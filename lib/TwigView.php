<?php

  namespace lib;


  class TwigView {

    protected $tmplPath;

    public function render($tmpl, $data = []) {

      $loader = new \Twig_Loader_Filesystem($this->getTmplPath());
      $twig = new \Twig_Environment($loader);
      $twig->addGlobal("session", $_SESSION);
      $template = $tmpl . '.html';
      $twig->display($template, $data);
    }

    public function getTmplPath() {
      return $this->tmplPath;
    }

    public function setTmplPath($tmplPath) {
      $this->tmplPath = $tmplPath;
    }

  }