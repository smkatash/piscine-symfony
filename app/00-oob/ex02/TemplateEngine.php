<?php
require_once "HotBeverage.php";

class TemplateEngine {
    public function __construct() {}
    public function __destruct() {}
    
    public function createFile($fileName, HotBeverage $text) {
        $inputFile = fopen('template.html', 'r');
        if (!$inputFile) {
            return;
        }
        $outFile = fopen($fileName, "w") or die("Unable to open file!");
        
        $reflection = new ReflectionClass($text);


        $paramsToFill = array();
        $regexPattern = '/\{(.*?)\}/';

        $val = '';
        while (!feof($inputFile)) {
            $line = fgets($inputFile);
            if (preg_match_all($regexPattern, $line, $matches)) {
                foreach($matches[0] as $match) {
                    $var = str_replace(['{', '}'], '', $match);
                    switch ($var) {
                        case 'name':
                            $val = $reflection->getMethod('getName')->invoke($text);
                            break;
                        case 'price':
                            $val = $reflection->getMethod('getPrice')->invoke($text);
                            break;
                        case 'resistance':
                            $val = $reflection->getMethod('getResistence')->invoke($text);
                            break;
                        case 'description':
                            $val = $reflection->getMethod('getDescription')->invoke($text);
                            break;
                        case 'comment':
                            $val = $reflection->getMethod('getComment')->invoke($text);
                            break;
                        default:
                            break;
                    }
                    if (!empty($val)) {
                        $line = str_replace($match, $val, $line);
                        $val = null;
                    }
                }
            }
            fwrite($outFile, $line);
        }
        fclose($inputFile);
        fclose($outFile);
    }
}

?>