<?php

// Llamada al modelo
require_once(__DIR__ . "/../models/Patients.php");

class PatientsController{
    
    public function checkPatientToType($dni){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $patientObject = new Patients();
            $patient = $patientObject->getPatientByDni($dni);
            if ($patient) {
                echo json_encode(['exists' => true]);
            } else {
                echo json_encode(['exists' => false]);
            }
        } 
    }
}

$patientsController = new PatientsController();
$patientsController->checkPatientToType($_POST['dni']);

?>