<?php


namespace src\Components\Parser;


interface IParser
{
    public function parse($url);

    public function report($domain);
}