<?php
require_once 'Empleado.php';
require_once 'Evaluable.php';

class Desarrollador extends Empleado implements Evaluable {
    private $lenguajePrincipal;
    private $nivelExperiencia;

    // Constructor
    public function __construct($nombre, $idEmpleado, $salarioBase, $lenguajePrincipal, $nivelExperiencia) {
        parent::__construct($nombre, $idEmpleado, $salarioBase);
        $this->lenguajePrincipal = $lenguajePrincipal;
        $this->nivelExperiencia = $nivelExperiencia;
    }

    // Getter y setter para lenguaje y experiencia
    public function getLenguajePrincipal() {
        return $this->lenguajePrincipal;
    }

    public function setLenguajePrincipal($lenguajePrincipal) {
        $this->lenguajePrincipal = $lenguajePrincipal;
    }

    public function getNivelExperiencia() {
        return $this->nivelExperiencia;
    }

    public function setNivelExperiencia($nivelExperiencia) {
        $this->nivelExperiencia = $nivelExperiencia;
    }

    // Implementación de la interfaz Evaluable
    public function evaluarDesempenio() {
        return "El desempeño del desarrollador {$this->nombre} ha sido evaluado. Lenguaje principal: {$this->lenguajePrincipal}";
    }

    // Sobrescribir obtenerInformacion
    public function obtenerInformacion() {
        return parent::obtenerInformacion() . ", Lenguaje Principal: {$this->lenguajePrincipal}, Nivel de Experiencia: {$this->nivelExperiencia}";
    }
}
?>
