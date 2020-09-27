<?php
$age = 20;//<--- Change this value and rerun it
if($age >= 21){//if this is true
    echo "You're at least 21 years old!";
}
else if($age >= 18){//otherwise if this is true
    echo "You're at least 18 years old!";
}
else{//if no other condition was met
    echo "You're under 18 years old";
}
/*
Note: The order of the logic matters, the first true condition is what's evaluated, anything else after
is not evaluated, not even to check if it's true.
If you switched lines 3 and 6 (and their respective echo statements), both 18 and 21 would trigger "You're at least 18 years old!".
Try it.
It's also important to not that this if, elseif, else order is the only order you can have these in.
elseif if else is wrong
else elseif if is wrong
elseif else if is wrong
etc
*/
?>
