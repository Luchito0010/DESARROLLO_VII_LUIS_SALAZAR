<?php
require_once 'Empresa.php';

// Crear empleados
$gerente = new Gerente("Carlos Pérez", 101, 5000, "Recursos Humanos", 1000);
$desarrollador = new Desarrollador("Ana López", 102, 3000, "PHP", "Senior");
$empleado = new Empleado("Luis Gómez", 103, 2000);

// Crear empresa y agregar empleados
$empresa = new Empresa();
$empresa->agregarEmpleado($gerente);
$empresa->agregarEmpleado($desarrollador);
$empresa->agregarEmpleado($empleado);

// Listar empleados
echo "Listado de empleados:\n";
$empresa->listarEmpleados();

// Calcular nómina total
echo "\nNómina total: " . $empresa->calcularNominaTotal() . "\n";

// Evaluar desempeño
echo "\nEvaluación de empleados:\n";
$empresa->evaluarEmpleados();
?>
