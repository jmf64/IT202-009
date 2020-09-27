<?php
//ignore this, it's just for output formatting
function newline(){
    //attempt to create newline for command line or browser, can ignore
    echo "<br>\n";
}

$age = 22;//note the value that doesn't have a matching case
switch($age){
    case 21:
        echo "You have all the priviledges given at the legal age of 21";
        newline();
    case 18:
        echo "You have all the priviledges given at the legal age of 18";
        newline();
        break;
    //note the missing default
}
echo "PHP seems ok with not having a case for $age";
?>
