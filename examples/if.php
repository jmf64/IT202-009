<?php
echo "Script started<br>\n";
//this isn't very useful, since we hard code to true it's the same as if we didn't have the if block, but baby steps right?
if(true){
    echo "Hello!";
}
echo "<br>\n";
//here's a better example
$age = 20;
if($age >= 18){
    echo "You meet the legal age to be considered an adult";
}
//but what if we're not?....
?>
