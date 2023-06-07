<?php

namespace Dginanjar\CodeIgniterTwig\Trait;

trait Twig
{
    protected $data, $twig;

    public function setupTwig(): void
    {
        $this->data = empty($this->data) ? [] : $this->data;
        $this->twig = new \Dginanjar\CodeIgniterTwig\Twig();
    }

    public function display(string $template, array $data = []): ?string
    {
        $this->data = array_merge($this->data, $data);

        return $this->twig->display($template, $this->data);
    }
}