<?php
require_once(__DIR__ . '/../db/db.php');

class Appointments{
    private $conexionBBDD;
    private $id;
    private $patientId;
    private $dateAppointment;
    private $hour;
    private $typeAppointment;
 
    public function __construct(){
        $connect = new Connect();
        $this->conexionBBDD = $connect->getConnection();
    }

    // setters and getters
    public function getId(){
        return $this->id;
    }
    public function setId($id){
        $this->id=$id;
    }
    public function getPatientId(){
        return $this->patientId;
    }
    public function setPatientId($patientId){
        $this->patientId = $patientId;
    }
    public function getDateAppointment(){
        return $this->dateAppointment;
    }
    public function setDateAppointment($dateAppointment){
        $this->dateAppointment=$dateAppointment;
    }
    public function getHour(){
        return $this->hour;
    }
    public function setHour($hour){
        $this->hour=$hour;
    }
    public function getTypeAppointment(){
        return $this->typeAppointment;
    }
    public function setTypeAppointment($TypeAppointment){
        $this->typeAppointment=$TypeAppointment;
    }

    public function getLastAppointment(){
        $query = "SELECT * FROM appointments ORDER BY id DESC LIMIT 1";
        $result = $this->conexionBBDD->query($query);
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public function createNewAppointment(){

        try {
            $sql = "INSERT INTO appointments (patient_id, fecha, hora, tipo_cita) VALUES (?, ?, ?, ?)";
            $stmt = $this->conexionBBDD->prepare($sql);
            $stmt->bind_param("isss", $this->patientId, $this->dateAppointment, $this->hour, $this->typeAppointment);
            $stmt->execute();

            return true;

        } catch (\Throwable $th) {
            error_log($th->getMessage());
            return false; 
        }
       
    }
}
?>