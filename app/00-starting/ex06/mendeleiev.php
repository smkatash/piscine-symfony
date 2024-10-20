<?php

function parse_line($line)
{
    $element_arr = array();
    $words = explode(",", $line);
    $elements = explode(" ", $words[0]);
    $element_name = $elements[0];
    $position = str_replace("position:", "", $elements[2]);
    $number = str_replace("number:", "", trim($words[1]));
    $sign = str_replace("small:", "", trim($words[2]));
    $molar = str_replace("molar:", "", trim($words[3]));
    $electron = str_replace("electron:", "", trim($words[4]));
    $element_arr = [
        'Element' => $element_name,
        'Position' => $position,
        'Number' => $number,
        'Sign' => $sign,
        'Molar' => $molar,
        'Electron' => $electron,
    ];

    return $element_arr;
}

function generate_table($elements)
{
    $html = '<table border="1" style="border-collapse: collapse;">';
    $var = 0;
    foreach ($elements as $element) {
        if ($var == 0) {
            $html .= '<tr>';
        }
        $html .= '<td style="border: 1px solid black; padding: 10px;">';
        $html .= '<h4>' . htmlspecialchars($element['Element']) . '</h4>';
        $html .= '<ul>';
        $html .= '<li>No ' . htmlspecialchars($element['Number']) . '</li>';
        $html .= '<li>' . htmlspecialchars($element['Sign']) . '</li>';
        $html .= '<li> ' . htmlspecialchars($element['Molar']) . ' </li>';
        $html .= '<li>' . htmlspecialchars($element['Electron']) . ' electron </li>';
        $html .= '</ul>';
        $html .= '</td>';
        $var++;
        if ($var == 17) {
            $html .= '</tr>';
            $var = 0;
        }
    }

    $html .= '</table>';
    return $html;
}

function empty_element() {
    $element_arr = [
        'Element' => " ",
        'Position' => " ",
        'Number' => " ",
        'Sign' => " ",
        'Molar' => " ",
        'Electron' => " ",
    ];
    return $element_arr;
}

function prepare_elements($elements) {
    $original_count = count($elements);
    for ($i = 0; $i < count($elements); $i++) {
        if ($i != 0 && $i < 17) {
            array_push($elements, empty_element());
        }
        if ($i > 19 && $i < 30) {
            array_push($elements, empty_element());
        }
        if ($i > 37 && $i < 48) {
            array_push($elements, empty_element());
        }
    }
    return $elements;
}

function execute($filepath)
{
    $all_elements = array();
    $input = fopen($filepath, "r");
    while(! feof($input)) {
        $line = fgets($input);
        $trline = rtrim($line);
        if (!empty($trline)) {
            $element = parse_line($trline);
            array_push($all_elements, $element);
        }
    }
    fclose($input);

    $table_html = generate_table(prepare_elements($all_elements));
    echo $table_html;
}

execute("ex06.txt")

?>