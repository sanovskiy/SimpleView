<?php

/**
 * Class SimpleView
 */
class SimpleView
{
    /**
     * @var string
     */
    protected $layout;
    protected $variables = [];

    /**
     * @param $name
     * @return mixed
     */
    function __get($name)
    {
        return !isset($this->variables[$name]) ? null : $this->variables[$name];
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    function __set($name, $value)
    {
        return $this->variables[$name] = $value;
    }

    /**
     * @param string $name
     */
    public function setLayout($name = 'layout')
    {
        $this->layout = $name;
    }

    /**
     * @param string $file
     * @return string
     */
    public function render($file = null)
    {
        if (is_null($file)) {
            $file = $this->layout;
        }
        if (!file_exists($file)) {
            return 'Can\'t render not existing view ' . $file;
        }
        ob_start();
        include $file;
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
}