<?php

require_once 'vendor/autoload.php';

use src\Components\Parser\IParser;
use src\Components\Parser\Parser;

class Program
{
    private $parser, $params, $config;

    public function __construct(IParser $parser, $params)
    {
        $this->parser = $parser;
        $this->params = $params;
        $this->config = require_once('configs/config.php');
    }

    public function execute()
    {
        if (count($this->params) == 3) {
            $this->prepareUrl();
            switch ($this->params[1]) {
                case "parse":
                    //var_dump($this->params[2]);
                    $this->parser->parse($this->params[2]);
                    //$this->parser->getArr();
                    break;
                case "report":
                    $this->parser->report($this->params[2]);
                    break;
                case "help":
                    echo "Instruction: \n";
                    echo "parse {url} - parse site page with this param url \n";
                    echo "report {domain} - display results of parsing with this param domain \n";
                    break;
            }
        } else {
            echo "Sorry, but u entered wrong options. Please, try again, or call 'php index.php help' for more ditails\n";
        }

    }

    private function prepareUrl()
    {
        $url = str_replace(array('http://', 'https://'),null, $this->params[2]);
        $this->params[2] = 'http://'.$url;
    }
}

$program = new Program(new Parser(), $argv);
$program->execute();