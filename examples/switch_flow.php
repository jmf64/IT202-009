<?php
//ignore this, it's just for output formatting
function newline(){
    //attempt to create newline for command line or browser, can ignore
    echo "<br>\n";
}
$age = 21;
switch($age){
    case 21:
        echo "You have all the priviledges given at the legal age of 21";
        newline();
    //note the missing "break"
    case 18:
        echo "You have all the priviledges given at the legal age of 18";
        newline();
        break;
    default:
        echo "Sorry, the data was a poor example so we can only accept 18 or 21 :)";
        newline();
        break;
}
//here's the same but with our if/elseif/else
// the || is the OR symbol, the AND equivalent is &&
//if age is 18 or age is 21
if($age == 18 || $age == 21){
    //We don't know which is which here
    echo "You are 18....or maybe 21?...you're one of the two so I'm close enough :)";
    newline();
}
//let's try again
//this is slightly better and we can reorange the order in which things trigger, but it's a bit messier
//Practice: see what happens if you change lines 30 and 34 to == instead
if($age >= 18){
    echo "You have all the priviledges given at the legal age of 18";
    newline();
}
if($age >= 21){
    echo "You have all the priviledges given at the legal age of 21";
    newline();
}
?>
