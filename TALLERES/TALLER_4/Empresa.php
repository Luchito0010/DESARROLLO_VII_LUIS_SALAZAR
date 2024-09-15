<?php
require_once 'Gerente.php';
require_once 'Desarrollador.php';

class Empresa {
    private $empleados = [];

    // Método para agregar empleados
    public function agregarEmpleado(Empleado $empleado) {
        $this->empleados[] = $empleado;
    }

    // Listar todos los empleados
    public function listarEmpleados() {
        foreach ($this->empleados as $empleado) {
            echo $empleado->obtenerInformacion() . "\n";
        }
    }

    // Calcular la nómina total
    public function calcularNominaTotal() {
        $nominaTotal = 0;
        foreach ($this->empleados as $empleado) {
            $nominaTotal += $empleado->getSalarioBase();
        }
        return $nominaTotal;
    }

    // Evaluar el desempeño de empleados evaluables
    public function evaluarEmpleados() {
        foreach ($this->empleados as $empleado) {
            if ($empleado instanceof Evaluable) {
                echo $empleado->evaluarDesempenio() . "\n";
            } else {
                echo "El empleado {$empleado->getNombre()} no es evaluable.\n";
            }
        }
    }
}
?>
