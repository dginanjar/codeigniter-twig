<?php

namespace Dginanjar\CodeigniterTwig;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;
use Twig\Extension\DebugExtension;
use Twig\Extra\Intl\IntlExtension;

class TwigLibrary
{
    protected $globals, $filters, $functions;

    public function __construct()
    {
        $this->config = [
            'debug' => getenv('CI_ENVIRONMENT') === 'development',
            'cache' => getenv('CI_ENVIRONMENT') === 'development' ? false : WRITEPATH . 'Twig',
            'autoescape' => false,
        ];

        $config = config('Twig');
        $this->paths = isset($config->paths) ? $config->paths : [ APPPATH . 'Views', ];

        $this->globals = [];
        $this->filters = [];
        $this->functions = [];
    }

    public function addGlobal($name, $value = null)
    {
        if (! is_array($name)) {
            $this->globals = array_merge($this->globals, [['name' => $name, 'value' => $value]]);
            return;
        }

        $globals = $name;
        foreach ($globals as $global) {
            $global = is_array($global) ? $global : [$global];
            $this->addGlobal(reset($global), end($global));
        }
    }

    public function addFilter($name, $callback = null)
    {
        if (! is_array($name)) {
            $this->filters = array_merge($this->filters, [['name' => $name, 'callback' => $callback ?? $name]]);
            return;
        }

        $filters = $name;
        foreach ($filters as $filter) {
            $filter = is_array($filter) ? $filter : [$filter];
            $this->addFilter(reset($filter), end($filter));
        }
    }

    public function addFunction($name, $callback = null)
    {
        if (! is_array($name)) {
            $this->functions = array_merge($this->functions, [['name' => $name, 'callback' => $callback ?? $name]]);
            return;
        }

        $functions = $name;
        foreach ($functions as $function) {
            $function = is_array($function) ? $function : [$function];
            $this->addFunction(reset($function), end($function));
        }
    }

    protected function registerGlobals()
    {
        foreach ($this->globals as $global) {
            $this->twig->addGlobal($global['name'], $global['value']);
        }
    }

    protected function registerFilters()
    {
        foreach ($this->filters as $filter) {
            $this->twig->addFilter(new TwigFilter($filter['name'], $filter['callback']));
        }
    }

    protected function registerFunctions()
    {
        foreach ($this->functions as $function) {
            $this->twig->addFunction(new TwigFunction($function['name'], $function['callback']));
        }
    }

    private function init()
    {
        $this->loader = new FileSystemLoader($this->paths);
        $this->twig = new Environment($this->loader, $this->config);

        if (getenv('CI_ENVIRONMENT') === 'development') {
            $this->twig->addExtension(new DebugExtension());
            $this->addFunction(['d', 'dd']);
        }

        $this->twig->addExtension(new IntlExtension());

        $this->registerGlobals();
        $this->registerFilters();
        $this->registerFunctions();
    }

    public function display($template, $context = [])
    {
        $this->init();
        $this->twig->display("{$template}.twig", $context);
    }
}