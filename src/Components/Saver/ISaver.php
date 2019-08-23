<?php


namespace src\Components\Saver;


interface ISaver
{
    public function save($arr, $domain);

    public function get($domain);
}