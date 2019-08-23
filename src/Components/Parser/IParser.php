<?php


namespace src\Components\Parser;


interface IParser
{
    public function parse($url, $option);

    public function report($domain);
}