<?php

namespace App\Service;

class ThemeService
{
    private $primaryColor;
    private $backgroundColor;

    public function __construct($primaryColor, $backgroundColor)
    {
        $this->primaryColor = $primaryColor;
        $this->backgroundColor = $backgroundColor;
    }

    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    public function getPrimaryColor()
    {
        return $this->primaryColor;
    }
}
