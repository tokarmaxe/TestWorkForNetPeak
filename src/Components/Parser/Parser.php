<?php


namespace src\Components\Parser;

class Parser implements IParser
{
    private $arr, $domain;

    public function parseUrls($url)
    {
        $imagesSrc = array();
        $hrefs = array();
        $html = file_get_contents($url);
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        $dom->preserveWhiteSpace = false;
        $images = $dom->getElementsByTagName('img');
        $as = $dom->getElementsByTagName('a');
        $k = 0;
        foreach ($images as $image) {
            if ($image->getAttribute('src')) {
                $imagesSrc[$k]['src'] = $image->getAttribute('src');
                $imagesSrc[$k]['alt'] = $image->getAttribute('alt');
            }
            $k++;
        }
        foreach ($as as $a) {
            if (stripos($a->getAttribute('href'), "spektr.webcat.com.ua")) {
                if (!in_array($a->getAttribute('href'), $hrefs))
                    $hrefs[] = $a->getAttribute('href');
            }
        }
        //echo "".count($hrefs)."\n";
        //var_dump($imagesSrc);

        $i = 0;
        foreach ($imagesSrc as $src) {
            $this->arr[$url]['images'][$i][] = $url;
            $this->arr[$url]['images'][$i][] = $src['src'];
            $this->arr[$url]['images'][$i][] = "title";
            $this->arr[$url]['images'][$i][] = $src['alt'];
            $i++;
        }

        foreach ($hrefs as $href) {
            if (!array_key_exists($href, $this->arr)) {
                $this->parseUrls($href);
            }
        }
        return $this->arr;
    }

    public function parse($url)
    {
        $this->domain = $this->getDomain($url);

        $arr = $this->parseUrls($url);

        //var_dump($arr);
        //for ($i = 0; $i < count($arr); $i++) {
        //foreach ($arr as $url) {
        //mkdir(dirname(str_replace(array("http://", "https://"),null,$arr[$i])), 0777, true);
        $fp = fopen("data/".$this->domain, 'w');

        //fputcsv($fp, array_keys(current($arr)));
        foreach ($arr as $img) {
            foreach ($img as $item) {
                foreach ($item as $src) {
                    fputcsv($fp, $src);
                }
            }
            //fputcsv($fp, $img);
        }

        fclose($fp);
        chmod("data/".$this->domain, 0777);


        //echo "$arr[$i]\n";
        //}
//        $imagesSrc = array();
//        $hrefs = array();
//        $html = file_get_contents($url);
//        $dom = new \DOMDocument();
//        @$dom->loadHTML($html);
//        $dom->preserveWhiteSpace = false;
//        $images = $dom->getElementsByTagName('img');
//        $as = $dom->getElementsByTagName('a');
//        foreach ($images as $image) {
//            if ($image->getAttribute('src')) {
//                $imagesSrc[] = $image->getAttribute('src');
//            }
//        }
//        foreach ($as as $a) {
//            if (stripos($a->getAttribute('href'), "spektr.webcat.com.ua")) {
//                if (!in_array($a->getAttribute('href'), $hrefs))
//                    $hrefs[] = $a->getAttribute('href');
//            }
//        }
//        //var_dump($imagesSrc);
//        foreach ($imagesSrc as $src) {
//            $this->arr[$url][] = $src;
//        }
//
//        foreach ($hrefs as $href) {
//            if (!array_key_exists($href, $this->arr)) {
//                $this->parse($href);
//            }
//        }
//
//        //var_dump($hrefs);
//        //var_dump($this->arr);
//        //
//        echo "Soqa";


        //chmod("google.com.csv", 0777);
    }

    public function getArr()
    {
        return $this->arr;
    }

    public function report($domain)
    {
        echo "report";
    }

    private function getDomain($url)
    {
        return explode("/", $url)[2];
    }
}