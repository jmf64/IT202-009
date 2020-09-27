<?php
$arr = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
//no I don't know why I chose to do days of the week

//this will look a bit backwards if you come from other language backgrounds
//note we take the array first, then we get the value "as" the next variable we declare
foreach($arr as $day){
    echo "$day <br>\n";
}

//we can also return the key/value separately for associative arrays
foreach($arr as $index=>$value){
    echo "$index => $value<br>\n";
}
?>
