<?php

/*
 * Copyright (c) 2012 kc merrill
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/*
* A really simple configuration class. You can see it's usages in the test.
* It can read in a configuration file(".config") which is essentially a .ini file.
* kcmerrill - 1.13.2011
*/

namespace kcmerrill\utility;

class config implements \arrayaccess
{
    public $autoload_dir = false;
    public $autoloaded_files = array();
    public $config = array();

    /**
     * __construct() give it a string and it'll auto load any *.config file
     *
     * @param string $autoload_dir | A folder to autoload directories
     */
    public function __construct($autoload_dir = false)
    {
        $this->autoLoadDirectory($autoload_dir);
    }

    /**
     * AutoLoad a Directory based on a string
     * Look for all the *.config files(basically a .ini) file.
     *
     * @param  string $autoload_dir
     * @return bool   $success
     */
    public function autoLoadDirectory($autoload_dir)
    {
        $autoload_dir = is_string($autoload_dir) ? rtrim($autoload_dir, '/') : false;
        $succesful = $autoload_dir ? true : false;
        if (is_dir($autoload_dir)) {
            $autoload_dir .= DIRECTORY_SEPARATOR;
            foreach (glob($autoload_dir . '*.config') as $config_file) {
                $loaded = $this->loadConfigFile($config_file);
                $succesful = (!$succesful && $result) ? false : true;
            }
        }

        return $succesful;
    }

    /**
     * Load a config file(.config, syntax of .ini)!
     *
     * @param  string  $config_file
     * @return boolean $file_was_loaded
     */
    public function loadConfigFile($config_file)
    {
        if (!is_string($config_file) && !file_exists($config_file)) {
            return false;
        }

        $config = parse_ini_file($config_file, true);
        if (is_array($config)) {
            $this->set(basename(str_replace('.config', '', $config_file)) , $config);

            return true;
        }

        return false;
    }

    /**
     * Set the config value based on what you send over.
     * "." seperate collections.
     *
     * So
     * php.hello.world is the same as
     * $config['php']['hello']['world']
     *
     * @param string $config_name
     * @param mixed  $config_value
     */
    public function set($config_name, $config_value)
    {
        $path = &$this->config;
        foreach (explode('.', $config_name) as $segment) {
            if (!isset($path[$segment])) {
                $path[$segment] = array();
            }
            $path = &$path[$segment];
        }
        $path = $config_value;
    }

    /**
     * c is shorthand for get/set
     * If you give 1 param, we'll consider it a get
     * If you give 2 params, we'll consider it a set
     *
     * @return mixed
     */
    public function c()
    {
        switch (func_num_args()) {
            case 2:
                return $this->set(func_get_arg(0), func_get_arg(1));
            break;
            case 1:
                return $this->get(func_get_arg(0));
            break;
            default:
                return NULL;
        }
    }

    /**
     * Get the config value based on what you send over.
     * "." seperate collections.
     *
     * So
     * php.hello.world is the same as
     * $config['php']['hello']['world']
     *
     * @param  string $config_name    | the config name, can be regular text or collections based on "."
     * @param  mixed  $config_default | what we return if we can't find the config path
     * @return mixed  $result
     */
    public function get($config_name, $config_default = NULL)
    {
        $path = &$this->config;
        foreach (explode('.', $config_name) as $segment) {
            if (!isset($path[$segment])) {
                return $config_default;
            }
            $path = &$path[$segment];
        }

        return $path;
    }

    /**
     * Your typical array access goodies :)
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }
    
    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }
    
    public function offsetUnset($offset) {}
    
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

}