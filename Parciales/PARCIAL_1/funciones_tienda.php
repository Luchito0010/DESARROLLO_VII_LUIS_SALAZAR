<?php
$total_compra =1100;
$subtotal=0;
$descuento=0;


echo"valor de la compra es de: $".$total_compra."<br>";
echo calcular_descuento($total_compra);
function calcular_descuento($total_compra){
    if($total_compra<=100){
        echo "No hay Desceunto.<br>";
    } else if($total_compra>=100 && $total_compra<= 500){
        echo "Desceunto del 5%.<br>";
        $total=0.05;
        $descuento=$total*$total_compra;
        $descuento=$total_compra-$descuento;
        echo "total con el descuento aplicado: $".$descuento."<br>";
    } else if($total_compra>=501 && $total_compra<= 1000){
        echo "Desceunto del 10%.<br>";
        $total=0.10;
        $descuento=$total*$total_compra;
        $descuento=$total_compra-$descuento;
        echo "total con el descuento aplicado: $".$descuento."<br>";
    }if($total_compra>1000){
        echo "Desceunto del 15%.<br>";
        $total=0.15;
        $descuento=$total*$total_compra;
        $descuento=$total_compra-$descuento;
        echo "total con el descuento aplicado: $".$descuento."<br>";
    } 
}
echo aplicar_impuesto($total_compra);
function aplicar_impuesto($total_compra){
$total_impuesto = 0.07;
$subtotal= $total_impuesto*$total_compra;
echo "el impuesto a pagar es de :$".$subtotal."<br>";
}

echo calcular_total($total_compra, $descuento, $subtotal);
function calcular_total($total_compra, $descuento, $subtotal){
$total= $total_compra*0.07;
echo $total;
    
}
?>