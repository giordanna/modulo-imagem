<?php
session_start();

// admin já tá logado
if(isset($_SESSION['email'], $_SESSION['senha'])){
header('location:../');
}

//Salvando os valores em variaveis atraves de POST
$email = $_POST["email"];
$senha = md5($_POST["senha"]);

$db = mysqli_connect('ip_banco', 'nome_usuario', 'senha_usuario', 'nome_banco');

if ($db->connect_error) {
	die("Connection failed: " . $db->connect_error);
}

mysqli_query($db, "SELECT * FROM admins WHERE email='$email' AND senha='$senha'")or die(mysqli_error());

if (mysqli_affected_rows($db) >= 1) {
	$_SESSION['email'] = $email;
	$_SESSION['senha'] = $senha;
}
else {
	unset ($_SESSION['email']);
	unset ($_SESSION['senha']);
}

header('location:../');

?>