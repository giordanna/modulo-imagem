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

  <section class="container" style="margin-top: 80px">
    <!--<input id="file-input" type="file">-->

    <button id="botao-enviar" class="btn btn-primary">Envie sua imagem aqui (Apenas JPG e até 20MB)</button>

<form method="post" action="php/enviar-imagem.php" enctype="multipart/form-data">

    <input type="file" class="sr-only" id="file-input" name="file-input" accept=".jpg">

  </section>

  <!-- local da imagem -->
  <div class="container" style="margin-top: 30px">

    <div class="containter-cropper"><img id="imagem-upload" width="100%"/></div>

    <div style="display:none" id="preencher-form">

      <hr />

      <div class="form-group">
        <label for="nome-obra">Nome da obra *</label>
        <input type="text" class="form-control" required name="nome-obra" id="nome-obra" placeholder="Insira o título da obra">
      </div>
      <div class="form-group">
        <label for="nome-obra">Nome do autor da obra *</label>
        <input type="text" class="form-control" required name="nome-autor" id="nome-autor" placeholder="Insira o nome do autor da obra">
      </div>

      <div class="form-group">
        <label for="nome-obra">Data da criação da obra</label>
        <input type="date" class="form-control" name="data-obra" id="data-obra">
      </div>

      <div class="form-group">
        <label for="nome-obra">Descrição da obra</label>
        <textarea class="form-control" id="descricao-obra" name="descricao-obra" rows="3" placeholder="Insira uma descrição sobre a obra"></textarea>
      </div>

        <input type="hidden" class="form-control" name="fragmentos-array" id="fragmentos-array">

        <input type="hidden" class="form-control" name="largura-imagem" id="largura-imagem">
        <input type="hidden" class="form-control" name="altura-imagem" id="altura-imagem">

      <hr />

    </div>

    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Esta seleção está boa?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="img-container">
              <p align="center"><img id="cortado"></p>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="salvar-fragmento">Salvar fragmento</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
    

  </div>

  <div class="container" id="funcoes-fragmentos" style="display:none">
    <div class="row">

      <div class="col-xs-12 col-md-6">

        <table class="table table-bordered table-striped">
          <tr>
            <td style="width: 50%"><b>Largura da seleção</b></td>
            <td id="scaled-width"></td>
          </tr>
          <tr>
            <td><b>Altura da seleção</b></td>
            <td id="scaled-height"></td>
          </tr>
          <tr>
            <td><b>X da seleção</b></td>
            <td id="scaled-x"></td>
          </tr>
          <tr>
            <td><b>Y da seleção</b></td>
            <td id="scaled-y"></td>
          </tr>
        </table>

        <hr/>

      </div>

      <div class="col-xs-12 col-md-6">

        <div class="clearfix">

          <div class="form-group" style="width: 160px; float: left;">
            <div>
              <button id="cortar" class="btn btn-default" type="button">Adicionar fragmento</button>
            </div>
          </div>
  
          <div class="form-group" style="width: 160px; float: left;">
            <div>
              <button id="salvar-btn" class="btn btn-primary" type="submit">Salvar</button>
            </div>
          </div>

        </div>

        <div class="clearfix">

          <div class="form-group" style="width: 135px; float: left;">
            <div>
              <button id="resetar" class="btn btn-default" type="button">Resetar posição</button>
            </div>
          </div>

        </div>
      
      <hr/>

      </div>

    </div>

  </div>

</form>

  <div class="container" style="margin-bottom: 80px">

    <!-- aqui ficaram os fragmentos salvos -->

    <div id="fragmentos-container">
    </div>

  </div>

  <footer class="navbar navbar-default navbar-fixed-bottom">
    <div class="container">
      <span class="navbar-text">Módulo de Imagem <?php echo date("Y"); ?> &copy; Giordanna De Gregoriis. Todos os direitos reservados.</span>
    </div>
  </footer>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  <script src="js/cropperjs/cropper.js"></script>

  <script>
    var json_fragmentos;

    function apagarContainer(nome) {
      var elemento = document.getElementById(nome);
      elemento.parentElement.removeChild(elemento);

      /* atualiza JSON eliminando o container do fragmento */
      var numero_container = parseInt(nome.substring(20));

      for (var i = 0 ; i < Object.keys(json_fragmentos.fragmentos).length ; i++) {

        if (json_fragmentos.fragmentos[i]["numero-fragmento"] == numero_container) {

          json_fragmentos.fragmentos.splice(i, 1); 
          break;
          
        }
      }

      var fragmentos_array = document.getElementById('fragmentos-array');
      fragmentos_array.value = JSON.stringify(json_fragmentos);

    };

    var contador = 0;
    window.addEventListener('DOMContentLoaded', function () {
      var image;
      var cortado = document.getElementById('cortado');
      var $modal = $('#modal');
      var cropper;

      $('#botao-enviar').on("click", function() {
        $('#file-input').click();
      });

      $('#file-input').on("change", function() {
        if (this.files && this.files[0]) {

          if (this.files[0].size > 1024 * 1024 * 20) {

            alert("Arquivo com tamanho maior que o permitido! Favor enviar arquivo de no máximo 2MB.");
            this.value = "";
          }
          else {

            var types = [".jpg", "image/jpg", "image/jpeg"];
        
            var tipoCerto = false;
            for (var i = 0 ; i < types.length ; i++) {
              if (this.files[0].type == types[i]) {
                tipoCerto = true;
                break;
              }
            }

            if (!tipoCerto) {
              alert("Tipo de arquivo inválido! Deve ser apenas JPG!");
              this.value = "";
            }
            else {

              var reader = new FileReader();

              reader.onload = function (e) {
                $('#imagem-upload').attr('src', e.target.result);

                $('#preencher-form').css("display", "block");
                $('#funcoes-fragmentos').css("display", "block");

                image = document.querySelector('#imagem-upload');
                cropper = new Cropper(image, {
                  movable: false,
                  zoomable: true,
                  rotatable: false,
                  scalable: false
                });

                image.addEventListener('crop', function (event) {

                  $('#scaled-width').html(cropper.getData(true).width);
                  $('#scaled-height').html(cropper.getData(true).height);
                  $('#scaled-x').html(cropper.getData(true).x);
                  $('#scaled-y').html(cropper.getData(true).y);

                });

                $('#botao-enviar').css("display", "none");

                var img = new Image();
              
                img.onload = function () {

                  $("#largura-imagem").val(this.width);
                  $("#altura-imagem").val(this.height);

                };

                img.src = e.target.result;

              }

            };

            reader.readAsDataURL(this.files[0]);

            $('#nome-obra').val(this.files[0].name);
   
          }
        }

      });


      document.getElementById('cortar').onclick = function () {
        $modal.modal('show');

        var canvas;

        if (cropper) {
          canvas = cropper.getCroppedCanvas();

          cortado.src = canvas.toDataURL();
        }

      };

      document.getElementById('resetar').onclick = function () {

          image.cropper.reset();

      };

      document.getElementById('salvar-fragmento').onclick = function () {

        contador += 1;

        var container_fragmento = document.getElementById('fragmentos-container');

        if (!json_fragmentos) {
          json_fragmentos = {
            "fragmentos": [
              { "numero-fragmento": contador, "x" : cropper.getData(true).x , "y" : cropper.getData(true).y, "largura" : cropper.getData(true).width , "altura" : cropper.getData(true).height }
            ]

          };
        }
        else {

          json_fragmentos["fragmentos"].push({ "numero-fragmento": contador, "x" : cropper.getData(true).x , "y" : cropper.getData(true).y, "largura" : cropper.getData(true).width , "altura" : cropper.getData(true).height });

        }

        var fragmentos_array = document.getElementById('fragmentos-array');

        fragmentos_array.value = JSON.stringify(json_fragmentos);

        container_fragmento.innerHTML += "<div id='container-fragmento-" + contador + "' class='container-fragmento jumbotron jumbotron-fluid'><div class='container'><button onclick=apagarContainer(" + "'" + "container-fragmento-" + contador + "'" + ") type='button' class='close' aria-label='Close'><span aria-hidden='true'>&times;</span></button><div class='row'><div class='col-md-6'><h1 class='display-4'>Fragmento #" + contador + "</h1><table class='table table-bordered table-striped'><tr><td style='width: 50%'><b>Largura da seleção</b></td><td>" + cropper.getData(true).width + "</td></tr><tr><td><b>Altura da seleção</b></td><td>" + cropper.getData(true).height + "</td></tr><tr><td><b>X da seleção</b></td><td>" + cropper.getData(true).x + "</td></tr><tr><td><b>Y da seleção</b></td><td>" + cropper.getData(true).y + "</td></tr></table></div><div class='col-md-6' align='center'><img id='fragmento-" + contador + "' src='" + cortado.src + "' width='300' height='auto' /></div></div></div></div>";

        

        $modal.modal('hide');

      };

    });

  </script>

</body>