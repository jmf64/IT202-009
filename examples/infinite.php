<?php
//this just uses the "true" constant to prevent the loop from ending
while(true){
    //runs forever
}

//this creates an infinite loop by growing the array during each iteration
$a = [0];
foreach($a as $v){
    array_push($a, 0);
}

//missing components of the for loop will just make it run continuously
for (;;) {
    //runs forever
}
//there are many other ways we can fall into an infinite loop and most of the time they're by accident
?>
