<?php

// Llamada al modelo
require_once(__DIR__ . "/../models/Appointments.php");
require_once(__DIR__ . "/../models/Patients.php");
require_once(__DIR__ . "/../views/AppointmentsView.html");

class AppointmentsController{
   
    public function makeAppointment() {

        $response = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $appointment = new Appointments();
            $lastAppointment = $appointment->getLastAppointment();
            $appointmentType = $_POST['appointmentType'];
            $limitHour = '22:00:00';
            $laborDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];


            // there is no last appointment, so we set the firts hour
            if (is_null($lastAppointment)) {

                if ($appointmentType === "Revisión") {
                    $dni = $_POST['dni'];
                    $patient = new Patients();
                    $objetPatient = $patient->getPatientByDni($dni);
                    $idPatient = $objetPatient['id'];

                    $newDateTime = (new DateTime())->format('Y-m-d');
                    $newDateTime = $this->checkDate($newDateTime, $laborDays);

                    $newappointment = new Appointments();
                    $newappointment->setPatientId($idPatient);
                    $newappointment->setTypeAppointment($appointmentType);
                    $newappointment->setDateAppointment($newDateTime);
                    $newappointment->setHour('10:00:00');

                    $newappointment->createNewAppointment();
                    
                }else{
                    $newpatient = new Patients();
                    $newpatient->setName($_POST['name']);
                    $newpatient->setDni($_POST['dni']); 
                    $newpatient->setCellphone($_POST['cellphone']);
                    $newpatient->setEmail($_POST['email']);
                    
                    $responsePatient = $newpatient->createNewPatient();

                    $patient = new Patients();
                    $dni = $_POST['dni'];
                    $objetPatient = $patient->getPatientByDni($dni);
                    $idPatient = $objetPatient['id'];

                    $newappointment = new Appointments();
                    $newappointment->setPatientId($idPatient);
                    $newappointment->setTypeAppointment($appointmentType);
                    $newappointment->setDateAppointment((new DateTime())->format('Y-m-d'));
                    $newappointment->setHour('10:00:00');

                    $responseAppointment = $newappointment->createNewAppointment();

                }
                
            } else {
                // there is a last appointment, so we set the next hour
                $lastHour = $lastAppointment['hora'];
                $lastHourDateTime = new DateTime($lastHour);
                $lastHourDateTime->modify('+60 minutes');
                $newHour = $lastHourDateTime->format('H:i:s');

                $lastDate = $lastAppointment['fecha'];
                $newDateTime = new DateTime($lastDate);

               
                if ($newHour >= $limitHour) {
                    $newHour = '10:00:00';
                    $newDateTime = $this->checkDate($newDateTime, $laborDays);
                }

                if ($appointmentType === "Revisión") {

                    $dni = $_POST['dni'];
                    $patient = new Patients();
                    $objetPatient = $patient->getPatientByDni($dni);
                    $idPatient = $objetPatient['id'];

                    $newappointment = new Appointments();
                    $newappointment->setPatientId($idPatient);
                    $newappointment->setTypeAppointment($appointmentType);
                    $newappointment->setDateAppointment($newDateTime->format('Y-m-d'));
                    $newappointment->setHour($newHour);

                    $responseAppointment = $newappointment->createNewAppointment();

                }else{

                    $newpatient = new Patients();
                    $newpatient->setName($_POST['name']);
                    $newpatient->setDni($_POST['dni']); 
                    $newpatient->setCellphone($_POST['cellphone']);
                    $newpatient->setEmail($_POST['email']);
                    $responsePatient = $newpatient->createNewPatient();

                    $patient = new Patients();
                    $dni = $_POST['dni'];
                    $objetPatient = $patient->getPatientByDni($dni);
                    $idPatient = $objetPatient['id'];

                    $newappointment = new Appointments();
                    $newappointment->setPatientId($idPatient);
                    $newappointment->setTypeAppointment($appointmentType);
                    $newappointment->setDateAppointment($newDateTime->format('Y-m-d'));
                    $newappointment->setHour($newHour);

                    $responseAppointment = $newappointment->createNewAppointment();

                }


            }

        }
    }

    public function checkDate(&$date, $laborDays) {
        $dateTimeVerified = $date->modify('+1 day');
        $dateName = $dateTimeVerified->format('l');

        if (!in_array($dateName, $laborDays)) {
            if ($dateName === 'Saturday') {
                $date->modify('+2 days');
            } else {
                $date->modify('+1 day');
            }
        }
        return $date;
    }
    
}

$patientsController = new AppointmentsController();
$patientsController->makeAppointment();


?>