<?php
/**
 * Copyright 2016 Pavel Terentyev <pavel.terentyev@gmail.com>
 * Copyright 2016 Igor Sharendo <axe_dream@list.ru>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */

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
    protected $scriptPaths = [];

    public function __construct($paths=null)
    {
        if (!is_null($paths)){
            if (!is_array($paths)){
                $paths = [$paths];
            }
            $this->scriptPaths = $paths;
        }
    }

    /**
     * @param string $path
     */
    public function addScriptPath($path)
    {
        $this->scriptPaths[] = $path;
    }

    public function getScriptPaths(){
        return $this->scriptPaths;
    }

    /**
     * @param $name
     * @return string
     * @throws SimpleView_Exception
     */
    public function locateScript($name)
    {
        foreach ($this->scriptPaths as $path){
            if (file_exists(realpath($path).'/'.$name)){
                return realpath($path).'/'.$name;
            }
        }
        throw new SimpleView_Exception('Can\'t find view script '.$name);
    }

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
    public function render($script = null)
    {
        if (is_null($script)) {
            $script = $this->layout;
        }
        $file = $this->locateScript($script);
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