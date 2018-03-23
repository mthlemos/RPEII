<?php
// This could be supplied by a user, for example

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

// Formulate Query
// This is the best way to perform a SQL query
// For more examples, see mysql_real_escape_string()
$query = sprintf("SELECT nome, vencimento FROM Produtos");

// Perform Query
$result = mysqli_query($conn, $query);

// Check result
// This shows the actual query sent to MySQL, and the error. Useful for debugging.
if (!$result) {
    $message  = 'Invalid query: ' . mysqli_error() . "\n";
    $message .= 'Whole query: ' . $query;
    die($message);
}

// Use result
// Attempting to print $result won't allow access to information in the resource
// One of the mysql result functions must be used
// See also mysql_result(), mysql_fetch_array(), mysql_fetch_row(), etc.

$i = 1;

	echo "<header>";
	echo '<link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">';
	echo "</header>";
	
while ($row = mysqli_fetch_assoc($result)) {
	
	echo "Produto $i:<br/>";
	echo "Nome: ".$row['nome']."<br/>";
	
	//Deixa a data no formato certo
	$dataCerta = explode('-', $row['vencimento']);
	$dataCerta = $dataCerta[2].'/'.$dataCerta[1].'/'.$dataCerta[0];
	
	echo "Vencimento: ".$dataCerta."<br/>";
	echo ''.'<a href="'."delete.php?nome=".$row['nome']."&vencimento=".$row['vencimento']."&auth=Aa7656712".'"><i class="fa fa-times"></i> DELETAR PRODUTO</a><br/><br/>';
	$i++;
}

// Free the resources associated with the result set
// This is done automatically at the end of the script
mysqli_free_result($result);
?>