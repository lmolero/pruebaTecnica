<?php

require_once(__DIR__ . '/../db/db.php');

class Patients{
    private $conexionBBDD;
    private $id;
    private $dni;
    private $name;
    private $cellphone;
    private $email;
 
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

    public function getDni(){
        return $this->dni;
    }
    public function setDni($dni){
        $this->dni=$dni;
    }
    public function getName(){
        return $this->name;
    }
    public function setName($name){
        $this->name=$name;
    }
    public function getCellphone(){
        return $this->cellphone;
    }
    public function setCellphone($cellphone){
        $this->cellphone=$cellphone;
    }
    public function getEmail(){
        return $this->email;
    }
    public function setEmail($email){
        $this->email=$email;
    }

    // methods
    public function getAllPatients(){
        $query = "SELECT * FROM patients";
        $result = $this->conexionBBDD->query($query);
        $patients = array();
        if ($result && $result->num_rows > 0) {
            $patients = $result->fetch_all(MYSQLI_ASSOC);
        }
        return $patients;
    }

    public function getPatientByDni($dni){
        $query = "SELECT * FROM patients WHERE dni LIKE ?";

        $stmt = $this->conexionBBDD->prepare($query);
        $dniLike = '%' . $dni . '%';
        $stmt->bind_param("s", $dniLike);
        
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public function createNewPatient(){
        try {
            $sql = "INSERT INTO patients (dni, name_patient, cellphone, email) VALUES ('".$this->dni."', '".$this->name."' , '".$this->cellphone."' , '".$this->email."');";

            $stmt = $this->conexionBBDD->query($sql);

            if ($stmt) {
                return true;
            } else {
                throw new Exception("Error to create appointment: " . $this->conexionBBDD->error);
                
            }
        } catch (\Throwable $th) {
            error_log($th->getMessage());
            return false;
        }   
    }

}
?>