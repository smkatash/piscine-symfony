<?php

class TemplateEngine {
    public function __construct() {
        echo "construct is called."; 
    }
    public function __destruct() {
        echo "destruct is called."; 
    }
    
    public function createFile($fileName, $templateName, $parameters) {
        $inputFile = fopen($templateName, 'r');
        if (!$inputFile) {
            return;
        }
        $outFile = fopen($fileName, "w") or die("Unable to open file!");
        
        $paramsToFill = array();
        $regexPattern = '/\{(.*?)\}/';

        while (!feof($inputFile)) {
            $line = fgets($inputFile);
            if (preg_match_all($regexPattern, $line, $matches)) {
                foreach($matches[0] as $match) {
                    $var = str_replace(['{', '}'], '', $match);
                    if (array_key_exists($var, $parameters)) {
                        $line = str_replace($match, $parameters[$var], $line);
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