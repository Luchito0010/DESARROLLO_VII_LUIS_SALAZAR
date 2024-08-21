<?php
$nombre ="Luis Andres Salazar Molina";
$edad = "22 años";
$correo = "luis102624@gmail.com";
$telefono = "64847780";

define("Ocupacion","Estudiante");

echo "Mi nombre es: ".$nombre. " y tengo: ". $edad. "<br>";
print "Actualmente soy ". Ocupacion ." en la UTP, mi correo es: " .$correo. "<br>" ;
printf("mi celulara es %s", $telefono);


echo "<br>Información de debugging:<br>";
var_dump($nombre);
echo "<br>";
var_dump($edad);
echo "<br>";
var_dump($correo);
echo "<br>";
var_dump($telefono);
echo "<br>";
var_dump(Ocupacion);
echo "<br>";

?>