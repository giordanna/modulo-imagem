<?php
  $imagem_hash_id = $_GET["id"];
  if (!empty($_GET["msg"])) {
    $mensagem = $_GET["msg"];
  }
  else {
    $mensagem = -1;
  }

  $db = mysqli_connect('ip_banco', 'nome_usuario', 'senha_usuario', 'nome_banco');
  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }

  $result = mysqli_query($db, "SELECT * FROM imagens WHERE id_string = '$imagem_hash_id'")or die(mysqli_error($db));
  $row = mysqli_fetch_assoc($result);

  $imagens_id = $row['id'];
  $nome_obra = $row['nome'];
  $nome_autor = $row['autor'];
  $data_obra = $row['data_obra'];
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

  <link rel="stylesheet" href="js/sceditor/minified/themes/modern.min.css" id="theme-style" />
      
  <script src="js/sceditor/minified/sceditor.min.js"></script>
  <script src="js/sceditor/minified/icons/monocons.js"></script>
  <script src="js/sceditor/minified/formats/bbcode.js"></script>
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

  <div class="container" style="margin-top: 80px">

    <div id="alerta">
      <?php
        if ($mensagem != -1) {
          echo "
          <div class='alert alert-success alert-dismissible fade in'>
            <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
            <strong>Sucesso!</strong> Suas alterações foram salvas!
          </div>
          ";
        }
      ?>
    </div>

  <!-- local da imagem -->

    <div class="containter-cropper">

      <svg id="svg" width="100%" height="auto" viewBox="<?php echo '0 0 ' . $largura_obra . ' ' . $altura_obra; ?>" version="1.1"
         xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">

        <image id="svg_image" xlink:href="<?php echo 'img/' . $imagem_hash_id . '.jpg'; ?>" x="0" y="0" width="<?php echo $largura_obra; ?>" height="<?php echo $altura_obra; ?>"/>
      
        <?php

          $result = mysqli_query($db, "SELECT * FROM fragmentos WHERE imagens_id = '$imagens_id'")or die(mysqli_error($db));

          while($row = mysqli_fetch_array($result)) {
            $numero_fragmento = $row['numero'];
            $x_fragmento = $row['x'];
            $y_fragmento = $row['y'];
            $largura_fragmento = $row['largura'];
            $altura_fragmento = $row['altura'];

            echo "<rect stroke='red' stroke-width='2' fill='transparent' x='" . $x_fragmento . "' y='" . $y_fragmento . "' width='" . $largura_fragmento . "' height='" . $altura_fragmento . "'  id='numero-fragmento-mapa-" . $numero_fragmento . "' onclick=mostrarModal('" . $numero_fragmento . "') />";
          }

        ?>
        
      </svg>

    </div>

    <form action="php/salvar-alteracoes.php" method="post">

      <input type="hidden" name="id" id="id" value="<?php echo $imagens_id; ?>" />

      <div class="form-group" style="margin-top: 30px">
        <label for="nome-obra">Nome da obra *</label>
        <input type="text" class="form-control" required name="nome-obra" id="nome-obra" placeholder="Insira o título da obra" value="<?php echo $nome_obra; ?>" />
      </div>

      <div class="form-group">
        <label for="nome-autor">Nome do autor da obra *</label>
        <input type="text" class="form-control" required name="nome-autor" id="nome-autor" placeholder="Insira o nome do autor da obra" value="<?php echo $nome_autor; ?>" />
      </div>

      <div class="form-group">
        <label for="data-obra">Data da criação da obra</label>
        <input type="date" class="form-control" name="data-obra" id="data-obra" value="<?php echo $data_obra; ?>" />
      </div>

      <div class="form-group">
        <label for="descricao-obra">Descrição da obra</label>
        <textarea class="form-control" id="descricao-obra" name="descricao-obra" rows="3" placeholder="Insira uma descrição sobre a obra"><?php echo $descricao_obra; ?></textarea>
      </div>
      
      <textarea name="html_fragmentos" id="html_fragmentos" class="sr-only" style="display:none">
        <?php
          echo '
          {
            "fragmentos": [
          ';

          $result = mysqli_query($db, "SELECT * FROM fragmentos WHERE imagens_id = '$imagens_id'")or die(mysqli_error($db));
          $primeiro = true;

          while($row = mysqli_fetch_array($result)) {
            $numero_fragmento = $row["numero"];
            $html = $row["html"];

            if ($primeiro) {
              echo '{ "numero-fragmento":"' . $numero_fragmento . '", "html": "' . $html . '" }';
              $primeiro = false;
            }
            else {
              echo ',{ "numero-fragmento":"' . $numero_fragmento . '", "html": "' . $html . '" }';
            }

            
          }

          echo '
            ]
          }
          ';
          
        ?>
      </textarea>

      <input type="hidden" name="id" id="id" value="<?php echo $imagens_id; ?>" />

      <div class="form-group" style="width: 150px; float: left;">
        <button type="submit" id="botao-salvar-alteracoes" class="btn btn-primary"> Salvar alterações
        </button>
      </div>

    </form>

      <div class="form-group" style="float: right;">
        <button type="bytton" id="botao-apagar-imagem" class="btn btn-danger"> Apagar imagem
        </button>
      </div>

  </div>

  <!-- modal para editar conteúdo de fragmento-->
  <div class="container">

    <hr /> 
    
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Editar conteúdo de fragmento #1</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            
            <div class="form-group">
              <label for="email">Conteúdo do fragmento</label>
              <textarea id="editor-texto" width="100%" height="400"></textarea>
            </div>
            <input type="hidden" name="numero" id="numero" />
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="salvar-conteudo-fragmento">Salvar</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Cancelar
            </button>
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

    <!-- modal apagar imagem-->
    <div class="modal fade" id="modal-3" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="form-group">
                <h2 align="center">Aviso</h2>
                <p>Tem certeza que deseja apagar a imagem?</p>
              </div>
          </div>
          <div class="modal-footer">
            <form action="php/apagar-imagem.php" method="post">

              <input type="hidden" name="id" id="id" value="<?php echo $imagens_id; ?>" />
              <button type="submit" class="btn btn-danger" id="apagar-imagem">Sim</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
              
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>

  <div class="container" style="margin-bottom: 80px">

    <!-- aqui ficaram os fragmentos salvos -->

    <div id="fragmentos-container">

      <?php

        $result = mysqli_query($db, "SELECT * FROM fragmentos WHERE imagens_id = '$imagens_id'")or die(mysqli_error($db));

        while($row = mysqli_fetch_array($result)) {
          $numero_fragmento = $row['numero'];
          $x_fragmento = $row['x'];
          $y_fragmento = $row['y'];
          $largura_fragmento = $row['largura'];
          $altura_fragmento = $row['altura'];
          $html = $row['html'];

          echo
          "<div id='container-fragmento-" . $numero_fragmento . "' class='container-fragmento jumbotron jumbotron-fluid'>
            <div class='container'>
              <button onclick=apagarContainer(" . "'" . "container-fragmento-" .  $numero_fragmento . "'" . ") type='button' class='close' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
              </button>
              <div class='row'>
                <div class='col-md-6'>
                  <h1 class='display-4'>Fragmento #" . $numero_fragmento . "</h1>
                  <table class='table table-bordered table-striped'>
                    <tr>
                      <td style='width: 50%'><b>Largura da seleção</b></td>
                      <td>" . $largura_fragmento . "</td>
                    </tr>
                    <tr>
                      <td><b>Altura da seleção</b></td>
                      <td>" . $altura_fragmento . "</td>
                    </tr>
                    <tr>
                      <td><b>X da seleção</b></td>
                      <td>" .  $x_fragmento . "</td>
                    </tr>
                    <tr>
                      <td><b>Y da seleção</b></td>
                      <td>" . $y_fragmento . "</td>
                    </tr>
                  </table>
                </div>
                <div class='col-md-6' align='center'>

                    <svg width='300' height='auto' viewBox='" . $x_fragmento  .  " " . $y_fragmento .  " " . $largura_fragmento . " " . $altura_fragmento . "' version='1.1'
                    xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'>

                      <image xlink:href='img/" . $imagem_hash_id . ".jpg' x='0' y='0' width='" . $largura_obra . "' height='" . $altura_obra . "' />
        
                    </svg>

                </div>
              </div>
              <h2>Conteúdo sobre o fragmento:</h2>
              <p>
                <div id='conteudo-fragmento-" . $numero_fragmento . "'>"
                  . $html .
                "</div>
              </p>
              <div align='center'>
                <button type='button' class='btn btn-default' data-target='#modal' data-toggle='modal' onclick=atualizarNumeroModal(" . "'" . $numero_fragmento . "'" . ")>Editar conteúdo</button>
              </div>
            </div>
          </div>";
        }

      ?>
    </div>

  </div>

  <footer class="navbar navbar-default navbar-fixed-bottom">
    <div class="container">
      <span class="navbar-text">Módulo de Imagem <?php echo date("Y"); ?> &copy; Giordanna De Gregoriis. Todos os direitos reservados.</span>
    </div>
  </footer>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

  <script type="text/javascript">

    var numero_fragmento;
    var atualizar_texto;

    $(document).ready(function() {
      $('map').imageMapResize();
    });

    function mostrarModal(numero) {
      $('#modal-2').modal('show');
      
      atualizar_texto = document.getElementById("conteudo-fragmento-" + numero);
      var atualizar_conteudo_fragmento = document.getElementById("atualizar-texto-fragmento");
      atualizar_conteudo_fragmento.innerHTML = atualizar_texto.innerHTML;
    }

    var textarea = document.getElementById('editor-texto');
    sceditor.create(textarea, {
      format: 'xhtml',
      icons: 'monocons',
      style: 'js/sceditor/minified/themes/content/modern.min.css',
      emoticonsEnabled: false,
      toolbarExclude: 'emoticon'
    });

    function atualizarNumeroModal(numero) {
      numero_fragmento = numero;

      var modalLabel = document.getElementById("modalLabel");
      modalLabel.innerHTML = "Editar conteúdo de fragmento #" + numero;

      atualizar_texto = document.getElementById("conteudo-fragmento-" + numero_fragmento);
      sceditor.instance(textarea).val(atualizar_texto.innerHTML);


    };

    $('#salvar-conteudo-fragmento').on('click', function () {

      atualizar_texto = document.getElementById("conteudo-fragmento-" + numero_fragmento);
      atualizar_texto.innerHTML = sceditor.instance(textarea).val().replace(/(\r\n\t|\n|\r\t)/gm,"").replace(/"/g, "'");

      /* atualiza JSON */

      var json_fragmentos;

      var fragmentos_array = document.getElementById('html_fragmentos');

      json_fragmentos = JSON.parse(fragmentos_array.value);
      

      for (var i = 0 ; i < Object.keys(json_fragmentos.fragmentos).length ; i++) {

        if (json_fragmentos.fragmentos[i]["numero-fragmento"] == numero_fragmento) {

          json_fragmentos.fragmentos[i]["html"] = sceditor.instance(textarea).val().replace(/(\r\n\t|\n|\r\t)/gm,"").replace(/"/g, "'");
          break;
          
        }
      }

      fragmentos_array.value = JSON.stringify(json_fragmentos);

     $('#modal').modal('hide');
          
    });

    $('#botao-apagar-imagem').on('click', function () {

     $('#modal-3').modal('show');
          
    });

  </script>

</body>