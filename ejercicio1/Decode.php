<?php
$csvFile = fopen('data.csv', 'r');

//Reading the headers
$headers = fgetcsv($csvFile);

$data = [];

while (($row = fgetcsv($csvFile)) !== false) {
    // Building the array with the data
    if (count($row) === count($headers)) {
        $rowString = str_replace(';', ',', $row[0]);
        $data[] = explode(',', $rowString);
    }
}

fclose($csvFile);

// function to decode the scores

$decodeData = decodePuntuation($data);
echo "Decoded data:<br>";

foreach ($decodeData as $row) {
    echo '- ' . $row['userName'] .' '. $row['puntuation']. '<br>';
}

/**
 * Decodes the scores from the provided data array.
 *
 * @param array $dataArray An array of data where each row contains a username, code digits, and encoded score.
 * @return array An array of decoded scores with usernames and their corresponding decoded scores.
 */
function decodePuntuation($dataArray) {
    $resultados = [];

    foreach ($dataArray as $row) { 
        $user = $row[0];
        $codeDigits = $row[1];
        $codePuntuation = $row[2];

        $puntuacionDecodificada = 0;
        $base = strlen($codeDigits);
        $exponent = 0;

        for ($i = strlen($codePuntuation) - 1; $i >= 0; $i--) {
            $digitoCodificado = $codePuntuation[$i];
            $position = strpos($codeDigits, $digitoCodificado);
            if ($position !== false) {
                $puntuacionDecodificada += $position * pow($base, $exponent);
                $exponent++;
            }
        }
        $resultados []= ['userName' => $user, 'puntuation' => $puntuacionDecodificada];
    }
    
    return $resultados;
}
?>


