<?php
  $email = $_SESSION['email'];

  $db = mysqli_connect('ip_banco', 'nome_usuario', 'senha_usuario', 'nome_banco');
  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }

  $imagens_array = array();

  $result = mysqli_query($db, "SELECT * FROM imagens")or die(mysqli_error($db));
  while($row = mysqli_fetch_array($result)) {
    $id_string_array = $row['id_string'];
    $nome_obra_array = $row['nome'];
    $nome_autor_array = $row['autor'];

    array_push($imagens_array, array($id_string_array, $nome_obra_array, $nome_autor_array));
  }
?>
<body>

  <header class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <a href="/" class="navbar-brand">Módulo de Imagem</a>
        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>
      <div class="navbar-collapse collapse" id="navbar-main">
        <ul class="nav navbar-nav">
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="download">Imagens registradas <span class="caret"></span></a>
            <ul class="dropdown-menu" aria-labelledby="download">
              <?php
                foreach ($imagens_array as $imagem) {
                  echo "<li><a href='imagem.php?id=$imagem[0]'>$imagem[1] por $imagem[2]</a></li>";
                }
              ?>
            </ul>
          </li>
          <li>
            <a>Olá, <?php echo $email; ?></a>
          </li>
          <li>
            <a href="php/sair.php">Sair</a>
          </li>
          <li>
            <a href="sobre.php">Sobre</a>
          </li>
        </ul>
      </div>
    </div>
  </header>

  <section class="container" style="margin-top: 80px; margin-bottom: 80px">

    <h1>Sobre o Módulo de Imagem</h1>
    <p>Utiliza Bootstrap, Cropper.JS, image-map-resizer e SCEditor</p>

  </section>

  <footer class="navbar navbar-default navbar-fixed-bottom">
    <div class="container">
      <span class="navbar-text">Módulo de Imagem <?php echo date("Y"); ?> &copy; Giordanna De Gregoriis. Todos os direitos reservados.</span>
    </div>
  </footer>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</body>