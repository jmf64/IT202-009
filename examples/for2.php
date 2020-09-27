<?php
$arr = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
//no I don't know why I chose to do days of the week

$count = count($arr);
echo "The array has $count elements <br>\n";
for($i = 0; $i < $count; $i++){
    echo "$arr[$i] <br>\n";
}
?>
