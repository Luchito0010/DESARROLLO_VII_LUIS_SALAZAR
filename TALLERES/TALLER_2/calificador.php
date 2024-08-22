<?php
$calificacion = 100;

if ($calificacion <= 59 ){
    echo "Tu calificacion es: F <br>";
}else 
    if ($calificacion >=60 && $calificacion <= 69){
        echo "Tu calificacion es: D <br>";
}else 
    if ($calificacion >=70 && $calificacion <= 79){
        echo "Tu calificacion es: C <br>";
}else 
    if ($calificacion >=80 && $calificacion <= 89){
        echo "Tu calificacion es: B <br>";
}else
        echo "Tu calificacion es: A <br>";


$ternario = ($calificacion>=60) ? "Aprovado <br>": "Reprobado <br>";
echo $ternario;

switch (true){
    case ($calificacion >= 90):
        echo "Excelente trabajo.<br>";
        break;
    case ($calificacion >= 80):
        echo "Buen trabajo.<br>";
        break;
    case ($calificacion >= 70):
        echo "trabajo aceptable.<br>";
        break;
    case ($calificacion >= 60):
        echo "Necesita mejorar.<br>";
        break;
    default:
        echo "Debes esfrozarte mas.<br>";
}

?>
