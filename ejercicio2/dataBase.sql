-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS `clinic_appointments`;

-- Use the database
USE `clinic_appointments`;

-- Table for storing patient information
CREATE TABLE IF NOT EXISTS `patients` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `dni` VARCHAR(20) UNIQUE NOT NULL,
    `name_patient` VARCHAR(255) NOT NULL,
    `cellphone` VARCHAR(50),
    `email` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for storing appointments
CREATE TABLE IF NOT EXISTS `appointments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `patient_id` INT NOT NULL,
    `date_appointment` DATE NOT NULL,
    `hour_appointment` TIME NOT NULL,
    `type_appointment` ENUM('Primera consulta', 'Revisión') NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`) ON DELETE CASCADE,
    UNIQUE (`date_appointment`, `hour_appointment`) -- Essential to prevent double booking the same slot
);


-- Optional: Add some initial data for testing
-- INSERT INTO `patients` (`dni`, `name`, `cellphone`, `email`) VALUES
-- ('12345678A', 'Paciente Existente', '666555444', 'existente@example.com');