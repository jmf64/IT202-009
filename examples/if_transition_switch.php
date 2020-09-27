<?php
//Here's what we'd need before switch
echo "if..elseif..else<br>\n";
$answer = 0;//
if($answer == 0){
    echo "Do something for answer equals 0";
}
else if($answer == 1){
    echo "Do something for answer equals 1";
}
else if($answer == 2){
    echo "Do something for answer equals 2";
}
else if($answer == 3){
    echo "Do something for answer equals 3";
}
else if($answer == 4){
    echo "Do something for answer equals 4";
}
else{
    echo "Unhandled answer";
}
echo "<br>\n";
echo "Switch<br>\n";
//here's the same using switch
switch($answer){
    case 0:
        echo "Do something for answer equals 0";
        break;//this is important (see the end note on when to try commenting this line out)
    case 1:
        echo "Do something for answer equals 1";
        break;
    case 2:
        echo "Do something for answer equals 2";
        break;
    case 3:
        echo "Do something for answer equals 3";
        break;
    case 4:
        echo "Do something for answer equals 4";
        break;
    default:
        echo "Unhandled answer";
        break;
}
//assuming you didn't change $answer, go back to the switch and comment out the line mentioned.
//then rerun the script
?>
