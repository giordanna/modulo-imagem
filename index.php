<!DOCTYPE html>
<html>
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width; initial-scale=1.0;"  />

  <title>Módulo Imagem</title>

  <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

  <link rel="stylesheet" href="js/cropperjs/cropper.css">

  <style>
    img {
      max-width: 100%;
    }

    .container-cropper {
      max-width: 100%;
      margin: 20px auto;
    }

  </style>

</head>

  <?php

    session_start();
        
    if (!isset($_SESSION['email']) and !isset($_SESSION['senha'])){

      // não fez login como admin
      unset($_SESSION['email']);
      unset($_SESSION['senha']);

      include "php/visao-normal.php";

    }
    else {
      $email = $_SESSION['email'];
      $senha = $_SESSION['senha'];

      $db = mysqli_connect('ip_banco', 'nome_usuario', 'senha_usuario', 'nome_banco');
      if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
      }

      mysqli_query($db, "SELECT * FROM admins WHERE email='$email' AND senha='$senha'")or die(mysqli_error($db));
      if (mysqli_affected_rows($db) >= 1) {
        include "php/visao-admin.php";
        
      }
      else {
        include "php/visao-normal.php";
      }

    }

  ?>

</html>