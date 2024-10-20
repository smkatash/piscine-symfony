<?php

include('./search_by_states.php');
$results = search_by_states("Oregon, trenton, Topeka, NewJersey");
foreach ($results as $result)
{
  echo $result . "\n";
}

?>