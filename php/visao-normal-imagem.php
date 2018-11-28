<?php
  $imagem_hash_id = $_GET["id"];

  $db = mysqli_connect('ip_banco', 'nome_usuario', 'senha_usuario', 'nome_banco');
  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }

  $result = mysqli_query($db, "SELECT * FROM imagens WHERE id_string = '$imagem_hash_id'")or die(mysqli_error($db));
  $row = mysqli_fetch_assoc($result);

  $imagens_id = $row['id'];
  $nome_obra = $row['nome'];
  $nome_autor = $row['autor'];
  $data_obra = date_create($row['data_obra']);
  $descricao_obra = $row['descricao'];
  $largura_obra = $row['largura'];
  $altura_obra = $row['altura'];

  $imagens_array = array();

  $result = mysqli_query($db, "SELECT * FROM imagens")or die(mysqli_error($db));
  while($row = mysqli_fetch_array($result)) {
    $id_string_array = $row['id_string'];
    $nome_obra_array = $row['nome'];
    $nome_autor_array = $row['autor'];

    array_push($imagens_array, array($id_string_array, $nome_obra_array, $nome_autor_array));
  }

?>
</head>

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
            <a href="#" id="fazer-login">Login</a>
          </li>
          <li>
            <a href="sobre.php">Sobre</a>
          </li>
        </ul>
      </div>
    </div>
  </header>

  <div class="container">
    
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">

          <div class="modal-header">

            <h5 class="modal-title" id="modalLabel">Login</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>

          </div>

          <div class="modal-body">

            <form action="php/login.php" method="post">

              <div class="form-group">
                <label for="email">Seu email</label>
                <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="Insira seu email">
              </div>

              <div class="form-group">
                <label for="senha">Sua senha</label>
                <input type="password" class="form-control" name="senha" id="senha" placeholder="Insira sua senha">
              </div>
            
          </div>

          <div class="modal-footer">

            <button type="submit" class="btn btn-primary" id="salvar-fragmento">Logar</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

            </form>

          </div>

        </div>
      </div>
    </div>

    <!-- modal para mostrar conteúdo de fragmento-->
    <div class="modal fade" id="modal-2" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>

          </div>

          <div class="modal-body">

              <div class="form-group">
                <div id="atualizar-texto-fragmento"></div>
              </div>

          </div>

        </div>
      </div>
    </div>

  </div>

  <div class="container" style="margin-top: 80px;  margin-bottom: 80px">

      <svg id="svg" width="100%" height="auto" viewBox="<?php echo '0 0 ' . $largura_obra . ' ' . $altura_obra; ?>" version="1.1"
         xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">

        <image id="svg_image" xlink:href="<?php echo 'img/' . $imagem_hash_id . '.jpg'; ?>" x="0" y="0" width="<?php echo $largura_obra; ?>" height="<?php echo $altura_obra; ?>"/>
      
        <?php

          $html_fragmentos = array();

          $result = mysqli_query($db, "SELECT * FROM fragmentos WHERE imagens_id = '$imagens_id'")or die(mysqli_error($db));

          while($row = mysqli_fetch_array($result)) {
            $numero_fragmento = $row['numero'];
            $x_fragmento = $row['x'];
            $y_fragmento = $row['y'];
            $largura_fragmento = $row['largura'];
            $altura_fragmento = $row['altura'];

            $html_fragmentos[$row['numero']] = $row['html'];

            echo "<rect fill='transparent' x='" . $x_fragmento . "' y='" . $y_fragmento . "' width='" . $largura_fragmento . "' height='" . $altura_fragmento . "'  id='numero-fragmento-mapa-" . $numero_fragmento . "' onclick=mostrarModal('" . $numero_fragmento . "') />";
          }

        ?>
        
      </svg>

      <div class="form-group" style="margin-top: 30px">
        <label for="nome-obra">Nome da obra</label>
        <p><?php echo $nome_obra; ?></p>
      </div>
      <div class="form-group">
        <label for="nome-autor">Nome do autor da obra</label>
        <p><?php echo $nome_autor; ?></p>
      </div>

      <div class="form-group">
        <label for="data-obra">Data da criação da obra</label>
        <p><?php echo date_format($data_obra,"d/m/Y"); ?></p>
      </div>

      <div class="form-group">
        <label for="descricao-obra">Descrição da obra</label>
        <p><?php echo $descricao_obra; ?></p>
      </div>

  </div>

  <footer class="navbar navbar-default navbar-fixed-bottom">
    <div class="container">
      <span class="navbar-text">Módulo de Imagem <?php echo date("Y"); ?> &copy; Giordanna De Gregoriis. Todos os direitos reservados.</span>
    </div>
  </footer>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

  <script type="text/javascript">

    var conteudo_html_fragmentos = <?php echo json_encode($html_fragmentos); ?>;

    function mostrarModal(numero) {

      $('#modal-2').modal('show');

      var atualizar_conteudo_fragmento = document.getElementById("atualizar-texto-fragmento");
      
      atualizar_conteudo_fragmento.innerHTML = conteudo_html_fragmentos[numero];
      
    }

    window.addEventListener('DOMContentLoaded', function () {
      var $modal = $('#modal');

      document.getElementById('fazer-login').onclick = function () {
        $modal.modal('show');
      };

    });

  </script>

</body>