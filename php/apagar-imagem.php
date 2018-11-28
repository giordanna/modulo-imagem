<?php
	$db = mysqli_connect('ip_banco', 'nome_usuario', 'senha_usuario', 'nome_banco');
	if ($db->connect_error) {
		die("Connection failed: " . $db->connect_error);
	}

	$imagem_id = $_POST["id"];

	$query = mysqli_query($db, "SELECT * FROM imagens
    WHERE `id`='$imagem_id'") or die(mysqli_error($db));

	if (mysqli_affected_rows($db) >= 1) {

		$query = mysqli_query($db, "DELETE FROM fragmentos
		WHERE imagens_id = '$imagem_id'")or die(mysqli_error($db));

		$query = mysqli_query($db, "DELETE FROM imagens
		WHERE `id`='$imagem_id'") or die(mysqli_error($db));

	}

    header("location:../");
?>