<?php

class TemplateEngine {
    public function __construct() {
        echo "construct is called.\n"; 
    }
    public function __destruct() {
        echo "destruct is called.\n"; 
    }
    
    public function createFile($fileName, $text) {
        $outFile = fopen($fileName, "w") or die("Unable to open file!");
        $htmlObj = '<!DOCTYPE html>
        <html>
            <head>
            </head>
            <body>
                '. $text->readData() . 
        '   </body>
        </html>';
        fwrite($outFile, $htmlObj);
        fclose($outFile);
    }
}

?>