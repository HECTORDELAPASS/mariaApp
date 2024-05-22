<?php
$serverName = "DESKTOP-3AGDMLR\SQLEXPRESS"; 
$connectionOptions = array(
    "Database" => "Peliculas_Animacion",
    "CharacterSet" => "UTF-8" 
);


$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn) {
    //Conexion Exitosa
} else {
    echo "Error al conectar<br>";
    die(print_r(sqlsrv_errors(), true)); 
}
?>
