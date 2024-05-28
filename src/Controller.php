<?php

namespace App;

class Controller {
    public function __construct()
    {
        $this->setNoCacheHeaders();
    }

    private function setNoCacheHeaders(): void
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }

    protected function render($view, $data = []): void
    {
        extract($data);
        include "Views/$view.php";
    }
}