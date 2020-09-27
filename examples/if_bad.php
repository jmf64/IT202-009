<?php
//a world without elseif and else
//this is bad in most cases, use the appropriate flow control
$a = "test";
if($a == "test"){
    echo "A matches what we expect";
}
//remember "!" negates (translates to 'not equal to')
if($a != "test"){
    echo "A doesn't match what we expect";
}
//note: each condition gets evaluated
?>
