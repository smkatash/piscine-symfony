<?php

$states = [
    'Oregon' => 'OR',
    'Alabama' => 'AL',
    'New Jersey' => 'NJ',
    'Colorado' => 'CO',
];

$capitals = [
    'OR' => 'Salem',
    'AL' =>  'Montgomery',
    'NJ' => 'trenton',
    'KS' => 'Topeka',
];

function capital_city_from($state) 
{
    if (!empty($state)) {
        global $states;
        if (array_key_exists($state, $states)) {
            $stateabbr = $states[$state];
            if (!empty($stateabbr)) {
                global $capitals;
                if (array_key_exists($stateabbr, $capitals)) {
                    return $capitals[$stateabbr];
                }
            }
        }
    }
    return null;
}

function state_from_capital_city($city) 
{
    if (!empty($city)) {
        global $capitals;
        if (in_array($city, $capitals)) {
            $stateabbr = array_search($city, $capitals);
            if (!empty($stateabbr)) {
                global $states;
                if (in_array($stateabbr, $states)) {
                    $state = array_search($stateabbr, $states);
                    return $state;
                }
            }
        }
    }
    return null;
}

function search_by_states($params)
{
    $states = explode(",", $params);
    $results = array();
    foreach ($states as $state) {
        $statetr = trim($state);
        $capital = capital_city_from($statetr);
        if (!empty($capital)) {
            array_push($results, $capital . " is the capital of " . $statetr . ".");
            continue ;
        } else {
            $state_from_capital = state_from_capital_city($statetr);
            if (empty($state_from_capital)) {
                array_push($results, $statetr .  " neither a capital nor a state.");
                continue ;
            }
            array_push($results, $statetr . " is the capital of " . $state_from_capital . ".");
        }
    }
    return $results;
}

?>