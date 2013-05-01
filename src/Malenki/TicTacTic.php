<?php

/*
Copyright (c) 2013 Michel Petit <petit.michel@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Malenki;


/**
 * Simple singleton class to have time counters into your projects.
 *
 * @author Michel Petit <petit.michel@gmail.com>
 */
class TicTacTic implements \Countable
{
    /**
     * Object instance of TicTacTic goes here.
     *
     * @var object
     */
    protected static $obj_instance = null;

    /**
     * Created timers go here.
     *
     * @var array
     */
    protected $arr_timers = array();



    /**
     * To work like a singleton should work…
     *
     * @return object
     */
    public static function getInstance()
    {
        if(is_null(self::$obj_instance))
        {
            self::$obj_instance = new self();
        }

        return self::$obj_instance;
    }



    /**
     * Checks whether time foo is already defined.
     *
     * @param string $name Timer's name
     * @return boolean
     */
    public function has($name)
    {
        return array_key_exists($name, $this->arr_timers);
    }



    /**
     * Implements abstract method form Countable interface.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->arr_timers);
    }



    /**
     * Start a new timer giving its name.
     *
     * @param string $name Timer name.
     * @return void
     * @throws Exception If timer is already defined
     */
    public function start($name)
    {
        if($this->has($name))
        {
            throw new \Exception(_('This timer is already defined!'));
        }
        else
        {
            $this->arr_timers[$name] = -microtime(true);
        }
    }



    /**
     * Finishes given timer.
     *
     * @param string $name Timer name.
     * @return void
     * @throws Exception If timer is not defined
     */
    public function finish($name)
    {
        if($this->has($name))
        {
            $this->arr_timers[$name] += microtime(true);
        }
        else
        {
            throw new \Exception(_('This timer does not exist!'));
        }
    }



    /**
     * Checks whether the given timer is done.
     *
     * @param string $name Timer name.
     * @return boolean
     * @throws Exception If timer is not defined
     */
    public function done($name)
    {
        if(!$this->has($name))
        {
            throw new \Exception(_('This timer does not exist!'));
        }

        return $this->arr_timers[$name] >= 0;
    }



    /**
     * Gets result for given timer.
     *
     * @param string $name Timer name.
     * @return float
     * @throws Exception If timer is not defined
     */
    public function get($name)
    {
        if(!$this->has($name))
        {
            throw new \Exception(_('This timer does not exist!'));
        }

        return $this->arr_timers[$name];
    }



    /**
     * Gets all done timers into an array indexed with their names.
     *
     * @return array
     */
    public function getAll()
    {
        $arr_out = array();

        foreach($this->arr_timers as $name => $value)
        {
            if($this->done($name))
            {
                $arr_out[$name] = $value;
            }
        }

        return $arr_out;
    }
}
