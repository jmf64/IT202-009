<?php
$number = 0;
while($number < 20){
    $number++;
    if($number == 5){
        continue;
    }
    //see what happens if we move $number++; here (don't forget to comment out line 4 before trying)
    //it's best to try it on w3schools try it editor
    echo "Number: $number<br>\n";
}
?>
