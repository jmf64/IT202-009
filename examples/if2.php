<?php
$value = null;
if($value){
    echo "value is true";
    echo "<br>\n";
}
echo "If you just see this message, value wasn't truthy";
echo "<br>\n";
$a = 2;
if($a){
    echo "a is truthy";
    echo "<br>\n";
}
echo "Like before, if you see only this message, a wasn't truthy. Some languages convert 0 to false and 1 or >0 to true.";
?>
