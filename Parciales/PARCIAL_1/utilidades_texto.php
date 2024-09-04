<?php

$cadena = ["hola", "Mundo"];
$contar_palabras = count($cadena);
echo "la cadena tiene: ".$contar_palabras." de caracteres <br>";
echo "la cadena es Hola Mundo<br>";



$frutas = "manzana";

for ($i = 0; $i < strlen($frutas); $i++) {
    if($frutas[$i]=="a"){
        count($num);
        $num=$frutas[$i];
        echo $frutas[$i]."".($i+1);
    } else if($frutas[$i]=="e"){
        echo $frutas[$i]."".($i+1);
    }else if($frutas[$i]=="i"){
        echo $frutas[$i]."".($i+1);
    } else if($frutas[$i]=="o"){
        echo $frutas[$i]."".($i+1);
    } else if($frutas[$i]=="u"){
        echo $frutas[$i]."".($i+1);
    }
}



?>