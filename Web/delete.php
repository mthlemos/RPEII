<?php
$nome = $_REQUEST['nome'];
$vencimento = $_REQUEST['vencimento'];
$auth = $_REQUEST['auth'];

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

$sql = 'DELETE FROM Produtos WHERE nome ="'.$nome.'" and vencimento = "'.$vencimento.'"';

header( "refresh:2;url=consulta.php" );

if ($conn->query($sql) === TRUE) {
	echo "Produto deletado com sucesso!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
}
else {
	echo "Autenticacao invalida.";
}



?>