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
 * You can define as many as you want. Usage is very simple, you load the
 * singleton, then you start a counter by given it a name, you can repeat this
 * last state for all your needs, you can stop them at every time. You can
 * check whether some of them is finished, you can check if a timer exists. You
 * can even compute average for all timers or just for some of them.
 *
 * Quick examples:
 *
 *     $t = TicTacTic::getInstance();
 *     $t->start('foo'); // timer foo is starting…
 *     // do some other things…
 *     $t->start('bar'); //timer bar is starting…
 *     // do other things yet…
 *     $t->finish('bar'); //stops timer 'bar'
 *     var_dump($t->has('something')); //returns false
 *     var_dump($t->has('foo')); //returns true
 *     echo $t->get('foo'); // print running time
 *
 *
 *
 * @author Michel Petit <petit.michel@gmail.com>
 * @license MIT
 */
class TicTacTic implements \Countable
{
    /**
     * Object instance of TicTacTic goes here.
     *
     * @version 1.0.0
     * @var TicTacTic
     */
    protected static $obj_instance = null;

    /**
     * Created timers go here.
     *
     * @version 1.0.0
     * @var array
     */
    protected $arr_timers = array();

    /**
     * To work like a singleton should work…
     *
     * @version 1.0.0
     * @return TicTacTic
     */
    public static function getInstance()
    {
        if (is_null(self::$obj_instance)) {
            self::$obj_instance = new self();
        }

        return self::$obj_instance;
    }

    /**
     * Checks whether timer foo is already defined.
     *
     * @param  string  $name Timer's name
     * @version 1.0.0
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
     * @version 1.0.0
     */
    public function count()
    {
        return count($this->arr_timers);
    }



    /**
     * Start a new timer giving its name.
     *
     * @param  string            $name Timer name.
     * @return void
     * @version 1.0.0
     * @throws \RuntimeException If timer is already defined
     */
    public function start($name)
    {
        if ($this->has($name)) {
            throw new \RuntimeException(_('This timer is already defined!'));
        } else {
            $this->arr_timers[$name] = -microtime(true);
        }
    }



    /**
     * Finishes given timer.
     *
     * @param  string            $name Timer name.
     * @return void
     * @version 1.0.0
     * @throws \RuntimeException If timer is not defined
     */
    public function finish($name)
    {
        if ($this->has($name)) {
            $this->arr_timers[$name] += microtime(true);
        } else {
            throw new \RuntimeException(_('This timer does not exist!'));
        }
    }




    /**
     * Finishes all available timers.
     *
     * @access public
     * @version 1.2.0
     * @return void
     */
    public function finishAll()
    {
        if (count($this->arr_timers)) {
            foreach ($this->arr_timers as $k => $t) {
                $this->finish($k);
            }
        } else {
            trigger_error(
                'No timer defined. Cannot finished not existant timers.',
                E_USER_NOTICE
            );
        }
    }


    /**
     * Checks whether the given timer is done.
     *
     * @param  string            $name Timer name.
     * @return boolean
     * @version 1.0.0
     * @throws \RuntimeException If timer is not defined
     */
    public function done($name)
    {
        if (!$this->has($name)) {
            throw new \RuntimeException(_('This timer does not exist!'));
        }

        return $this->arr_timers[$name] >= 0;
    }



    /**
     * Gets result for given timer.
     *
     * @param  string            $name Timer name.
     * @return float
     * @version 1.0.0
     * @throws \RuntimeException If timer is not defined
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            throw new \RuntimeException(_('This timer does not exist!'));
        }

        return $this->arr_timers[$name];
    }



    /**
     * Gets all done timers into an array indexed with their names.
     *
     * @version 1.0.0
     * @return array
     */
    public function getAll()
    {
        $arr_out = array();

        foreach ($this->arr_timers as $name => $value) {
            if ($this->done($name)) {
                $arr_out[$name] = $value;
            }
        }

        return $arr_out;
    }



    /**
     * Get average value.
     *
     * @param  array                     $arr An optional array of timer’s names. If not given,
     *                                        compute average with all done timers.
     * @version 1.1.0
     * @return float
     * @throws \InvalidArgumentException If at least one timer does not exist
     */
    public function average(array $arr = array())
    {
        $arr_all = array();

        if (count($arr)) {
            foreach ($arr as $name) {
                if (!$this->has($name)) {
                    throw new \InvalidArgumentException(sprintf('Timer %s does not exist!', $name));
                } else {
                    $arr_all[] = $this->get($name);
                }
            }
        } else {
            $arr_all = $this->getAll();
        }

        return array_sum($arr_all) / count($arr_all);
    }
}
