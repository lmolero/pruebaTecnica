<?php

// Llamada al modelo
require_once(__DIR__ . "/../models/Appointments.php");
require_once(__DIR__ . "/../models/Patients.php");

class AppointmentsController{
   
    public function makeAppointment() {

        $response = [];

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

                $newDateTime = new DateTime();
                $newDateTime = $this->checkDate($newDateTime, $laborDays);
                $newHour = '10:00:00';

                $newappointment = new Appointments();
                $newappointment->setPatientId($idPatient);
                $newappointment->setTypeAppointment($appointmentType);
                $newappointment->setDateAppointment($newDateTime->format('Y-m-d'));
                $newappointment->setHour('$newHour');

                $responseAppointment = $newappointment->createNewAppointment();


            }else{
                $newpatient = new Patients();
                $newpatient->setName($_POST['name']);
                $newpatient->setDni($_POST['dni']); 
                $newpatient->setCellphone($_POST['cellphone']);
                $newpatient->setEmail($_POST['email']);
                
                $newpatient->createNewPatient();

                $patient = new Patients();
                $dni = $_POST['dni'];
                $objetPatient = $patient->getPatientByDni($dni);
                $idPatient = $objetPatient['id'];

                $newDateTime = new DateTime();
                $newDateTime = $this->checkDate($newDateTime, $laborDays);
                $newHour = '10:00:00';

                $newappointment = new Appointments();
                $newappointment->setPatientId($idPatient);
                $newappointment->setTypeAppointment($appointmentType);
                $newappointment->setDateAppointment($newDateTime->format('Y-m-d'));
                $newappointment->setHour($newHour);

                $responseAppointment = $newappointment->createNewAppointment();

            }
            
        } else {
            // there is a last appointment, so we set the next hour
            $lastHour = $lastAppointment['hour_appointment'];
            $lastHourDateTime = new DateTime($lastHour);
            $lastHourDateTime->modify('+60 minutes');
            $newHour = $lastHourDateTime->format('H:i:s');

            $lastDate = $lastAppointment['date_appointment'];
            $newDateTime = new DateTime($lastDate);
  
            if ($newHour > $limitHour) {
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
                $newpatient->createNewPatient();

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
        if ($responseAppointment) {
            $response['status'] = 'success';
            $response['message'] = 'Cita creada correctamente.';
            $response['appointment'] = [
                'date' => $newDateTime->format('Y-m-d'),
                'hour' => $newHour,
            ];
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error al crear la cita.';
        }
        return $response;
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

$controller = new AppointmentsController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dataAppointmentDone = $controller->makeAppointment();
    require_once(__DIR__ . "/../views/AppointmentsView.html");
} else {
    // Si es GET, cargamos la vista del formulario
    require_once(__DIR__ . "/../views/AppointmentsView.html");
}


?>