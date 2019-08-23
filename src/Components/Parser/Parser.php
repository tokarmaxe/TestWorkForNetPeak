<?php


namespace src\Components\Parser;

use src\Components\Saver\SaverCVS;
use src\Components\Saver\ISaver;

class Parser implements IParser
{
    private $arr, $domain, $hrefs, $imagesSrc, $path, $config, $saver;

    public function __construct(ISaver $saver)
    {
        $this->config = require_once('configs/config.php');
        $this->saver = $saver;
    }

    public function parse($url)
    {
        $this->domain = $this->getDomain($url);

        $this->path = $this->config['storageDir'] . $this->domain . '.csv';

        $arr = $this->getArray($url, 'img', array('src'));

        if ($arr) {
            $saver = new SaverCVS();
            $saver->save($arr, $this->path);
            $this->displayCSVPath($this->path);
        }
    }

    private function displayCSVPath($path)
    {
        print("Results are located along this path: " . realpath($path) . "\n");
    }

    private function getInfoAboutDomain($path)
    {
        return $this->saver->get($path[0]);
    }

    public function report($domain)
    {
        try {
            $this->domain = $this->getDomain($domain);
            if (glob($this->config['storageDir'] . $this->domain . "*.csv")) {
                $report = $this->getInfoAboutDomain(glob($this->config['storageDir'] . $this->domain . "*.csv"));
                $this->displayCSVPath(glob($this->config['storageDir'] . $this->domain . "*.csv")[0]);
            } else {
                throw new \Exception("You have not domain '" . $this->domain . "' in your database, at first use 'parse " . $this->domain . "'");
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
        }
    }

    private function getDomain($url)
    {
        return explode("/", $url)[2];
    }

    private function getArray($url, $tag, $attributes)
    {
        try {
            if (@file_get_contents($url)) {
                $this->hrefs = array();
                $this->imagesSrc = array();
                $html = file_get_contents($url);
                $dom = new \DOMDocument();
                @$dom->loadHTML($html);
                $dom->preserveWhiteSpace = false;
                $this->getTag($dom, $tag, $attributes);
                $this->getPageHrefs($dom);

                $this->fillArray($url, $tag, $attributes);

                foreach ($this->hrefs as $href) {
                    if (!array_key_exists($href, $this->arr)) {
                        $this->getArray($href, $tag, $attributes);
                    }
                }
                return $this->arr;
            } else {
                throw new \Exception("You entered wrong url parameter");
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
        }

    }

    private function getTag($dom, $tag, $attributes)
    {
        $items = $dom->getElementsByTagName($tag);
        $k = 0;
        foreach ($items as $item) {
            foreach ($attributes as $attribute) {
                if ($item->getAttribute($attribute)) {
                    $this->imagesSrc[$k][$attribute] = $item->getAttribute($attribute);
                }
            }
            $k++;
        };
    }

    private function getPageHrefs($dom)
    {
        $as = $dom->getElementsByTagName('a');
        foreach ($as as $a) {
            if (stripos($a->getAttribute('href'), $this->domain)) {
                if (!in_array($a->getAttribute('href'), $this->hrefs))
                    $this->hrefs[] = $a->getAttribute('href');
            }
        }
    }

    private function fillArray($url, $tag, $attributes)
    {
        $i = 0;
        foreach ($this->imagesSrc as $src) {
            $this->arr[$url][$tag . 's'][$i][] = $url;
            foreach ($attributes as $attribute) {
                $this->arr[$url][$tag . 's'][$i][] = @$src[$attribute];
            }
            $i++;
        }
    }
}