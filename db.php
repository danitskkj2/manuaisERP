<?php
$host = "ip"; 
$dbname = "dbname"; 
$user = "user"; 
$password = "password";

$conn = pg_connect("host=$host dbname=$dbname user=$user password=$password");

if (!$conn) {
    echo "Erro de conexão com o banco de dados.";
    exit();
}
?>
