<?php
//ignore this, it's just for output formatting
function newline(){
    //attempt to create newline for command line or browser, can ignore
    echo "<br>\n";
}

//don't do this
echo "Begin code block I need to duplicate";
newline();
$name = "John";
echo "Hello, $name";
newline();
echo "End code block I need to duplicate";
newline();

echo "Begin code block I need to duplicate";
newline();
$name = "John";
echo "Hello, $name";
newline();
echo "End code block I need to duplicate";
newline();

echo "Begin code block I need to duplicate";
newline();
$name = "John";
echo "Hello, $name";
newline();
echo "End code block I need to duplicate";
newline();
