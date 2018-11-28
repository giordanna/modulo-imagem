<?php

	$db = mysqli_connect('ip_banco', 'nome_usuario', 'senha_usuario', 'nome_banco');
	if ($db->connect_error) {
		die("Connection failed: " . $db->connect_error);
	}

	$imagem_id = addslashes($_POST["id"]);
	$nome_obra = addslashes($_POST["nome-obra"]);
	$nome_autor = addslashes($_POST["nome-autor"]);
	$data_obra = addslashes($_POST["data-obra"]);
	$descricao_obra = addslashes($_POST["descricao-obra"]);

	$fragmentos_json = json_decode($_POST["html_fragmentos"], true);

	mysqli_query($db, "UPDATE imagens SET
	    `nome` = '$nome_obra',
	    `autor` = '$nome_autor',
	    `data_obra` = '$data_obra',
	    `descricao` = '$descricao_obra'
	WHERE `id`='$imagem_id'") or die(mysqli_error($db));

	$id_string = hash("crc32b", $imagem_id);

	$result = mysqli_query($db, "SELECT * FROM fragmentos WHERE imagens_id = '$imagem_id'")or die(mysqli_error($db));
	while($row = mysqli_fetch_array($result)) {

		$id_fragmento = $row['id'];
		$numero_fragmento = $row['numero'];

		foreach ($fragmentos_json["fragmentos"] as $fragmento) {
		
			if ($fragmento["numero-fragmento"] == $numero_fragmento) {

				$html_fragmento = addslashes($fragmento["html"]);

				mysqli_query($db, "UPDATE fragmentos SET
				    `html` = '$html_fragmento'
				WHERE `id`='$id_fragmento' AND `numero`='$numero_fragmento'") or die(mysqli_error($db));

				break;

			}

		}	

	} 

	header("location: ../imagem.php?id=" . $id_string . "&msg=1");

?>