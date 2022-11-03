<?php

namespace Dginanjar\CodeigniterTwig;

trait Twig
{
    protected $twig;

    public function setupTwig()
    {
        $this->data = [];
        $this->twig = new TwigLibrary();
    }

    public function display($view, $data = [])
    {
        $this->data = array_merge($this->data, $data);
        $this->twig->display($view, $this->data);
    }
}