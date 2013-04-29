<?php

namespace Malenki;

class TicTacTic
{
    protected $arr_timers = array();



    public function has($name)
    {
        return array_key_exists($name, $this->arr_timers);
    }



    public function count()
    {
        return count($this->arr_timers);
    }



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



    public function done($name)
    {
        if(!$this->has($name))
        {
            throw new \Exception(_('This timer does not exist!'));
        }

        return $this->arr_timers[$name] >= 0;
    }
}
