<?php
	session_start();

	$db = mysqli_connect('ip_banco', 'nome_usuario', 'senha_usuario', 'nome_banco');
	if ($db->connect_error) {
		die("Connection failed: " . $db->connect_error);
	}

	$email = $_SESSION["email"];
	$senha = $_SESSION["senha"];

	$result = mysqli_query($db, "SELECT * FROM admins WHERE email='$email' AND senha='$senha'")or die(mysqli_error($db));
	$row = mysqli_fetch_assoc($result);

	$admins_id = $row['id'];

	$nome_obra = addslashes($_POST["nome-obra"]);
	$nome_autor = addslashes($_POST["nome-autor"]);
	$data_obra = addslashes($_POST["data-obra"]);
	$descricao_obra = addslashes($_POST["descricao-obra"]);
	$largura_imagem = $_POST["largura-imagem"];
	$altura_imagem = $_POST["altura-imagem"];

	$fragmentos_json = json_decode($_POST["fragmentos-array"], true);

	mysqli_query($db, "INSERT INTO imagens (`admins_id`, `nome`, `autor`, `data_obra`, `descricao`, `largura`, `altura`)
	VALUES ('$admins_id', '$nome_obra', '$nome_autor', '$data_obra', '$descricao_obra', '$largura_imagem', '$altura_imagem')") or die(mysqli_error($db));

	$id = $db->insert_id;

	$id_string = hash("crc32b", $id);

	$query = mysqli_query($db, "UPDATE imagens SET `id_string`='$id_string'
    WHERE `id`='$id'") or die(mysqli_error($db));

    foreach ($fragmentos_json["fragmentos"] as $fragmento) {
		
		$fragmento_numero = $fragmento["numero-fragmento"];
		$fragmento_x = $fragmento["x"];
		$fragmento_y = $fragmento["y"];
		$fragmento_largura = $fragmento["largura"];
		$fragmento_altura = $fragmento["altura"];

		mysqli_query($db, "INSERT INTO fragmentos (`imagens_id`, `numero`, `x`, `y`, `largura`, `altura`)
		VALUES ('$id', '$fragmento_numero', '$fragmento_x', '$fragmento_y', '$fragmento_largura', '$fragmento_altura')") or die(mysqli_error($db));
	}

	// diretório
	$_UP['pasta'] = '../img/';
	// tamanho máximo permitido
	$_UP['tamanho'] = 1024 * 1024 * 20; // 20MB
	// array com as extensões permitidas
	$_UP['extensoes'] = array('jpg', "jpeg");
	// verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
	if ($_FILES['file-input']['error'] != 0) {
	    echo "erro de upload"; exit;
	}
	// caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar
	// faz a verificação da extensão do arquivo
	$extensao = strtolower(end(explode('.', $_FILES['file-input']['name'])));

	if (array_search($extensao, $_UP['extensoes']) === false) {
	    echo "extensão errada!"; exit;
	}

	if ($_UP['tamanho'] < $_FILES['file-input']['size']) {
	    echo "tamanho máximo excedido!"; exit;
	}

	$nome_final = $id_string . "." . $extensao;
	if (move_uploaded_file($_FILES['file-input']['tmp_name'], $_UP['pasta'] . $nome_final)) {
	} else {
	    echo "não conseguiu transferir o arquivo!"; exit;
	}

	header("location: ../imagem.php?id=". $id_string);

?>