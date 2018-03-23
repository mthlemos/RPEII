<?php
// This could be supplied by a user, for example

$servername = "localhost";
$username = "id1636622_kitchendb";
$password = ";a2^fR>6N";
$dbname = "id1636622_kitchendb";

//CRIA CONEXAO curl

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, '../onesignaltest.php?titulo=PRODUTO VENCE AMANHA&mensagem=mensagem');


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


//DEFINIR FUSO HORARIO E TALZ

date_default_timezone_set('America/Sao_Paulo');
$date = date('Y-m-d');
$amanha = date('Y-m-d',strtotime($date . "+3 days"));


// Use result
// Attempting to print $result won't allow access to information in the resource
// One of the mysql result functions must be used
// See also mysql_result(), mysql_fetch_array(), mysql_fetch_row(), etc.

while ($row = mysqli_fetch_assoc($result)) {
	
	if($amanha === $row['vencimento']) {
		
		$nomeUP = strtoupper($row['nome']);
		
		$fields = array(
		'titulo' => 'PRODUTO VENCE EM 3 DIAS',
		'mensagem' => $nomeUP.' VENCE EM 3 DIAS!'
		);
		$fields_string;
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');
		
		curl_setopt($ch, CURLOPT_URL, 'http://rpesite.000webhostapp.com/onesignaltest.php');
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_exec($ch);
	}
	
}
mysqli_free_result($result);
curl_close($ch);
// Free the resources associated with the result set
// This is done automatically at the end of the script

?>