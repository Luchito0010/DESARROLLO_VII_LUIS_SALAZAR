<?php

for($x=0; $x<=5;$x++){
    echo "<br>";
    for($y=0;$y<$x;$y++){
        echo "*";
    }
}
echo "<br><br>";

$num = 1;
while($num <=20){
    echo "$num <br>";
    $num++;
    $num++;
    
}
echo "<br><br>";


$cont = 10;
do{
    if($cont==5){
        $cont--;
    }
    echo "$cont";
    $cont--;
}while($cont>=1);
echo "<br><br>";




   


?>