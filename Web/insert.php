<?php
$codigo = $_REQUEST['codigo'];
$nome = $_REQUEST['nome'];
$vencimento = $_REQUEST['vencimento'];
$auth = $_REQUEST['auth'];

$dataP = explode('/', $vencimento);
$dataNoFormatoCerto = '"'.$dataP[2].'-'.$dataP[1].'-'.$dataP[0].'"';
if($auth === 'Aa7656712'){
$servername = "localhost";
$username = "id1636622_kitchendb";
$password = ";a2^fR>6N";
$dbname = "id1636622_kitchendb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "INSERT INTO Produtos (codigo, nome, vencimento)
VALUES ($codigo, '$nome', $dataNoFormatoCerto)";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
}
else {
	echo "Autenticacao invalida.";
}



?>