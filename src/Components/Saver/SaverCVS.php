<?php


namespace src\Components\Saver;


class SaverCVS implements ISaver
{
    public function save($arr, $domain)
    {
        $file = new \SplFileObject($domain, 'w');
        foreach ($arr as $img) {
            foreach ($img as $item) {
                foreach ($item as $src) {
                    $file->fputcsv($src);
                }
            }
        }
        $file = null;
    }

    public function get($domain)
    {
        $file = new \SplFileObject($domain);
        while (!$file->eof()) {
            $arr[] = $file->fgetcsv();
        }

        return $arr;
    }
}