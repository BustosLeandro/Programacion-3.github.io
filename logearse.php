<?php
  session_start();
  if($_SERVER['HTTP_REFERER'] !== "http://localhost/PM/logearse.php"){
    $_SESSION['pagAnterior'] = $_SERVER['HTTP_REFERER'];
  }

  if(isset($_SESSION['Codigo'])){
    header("Location:index.php");
  }
?>
<!DOCTYPE html>
<html lang="es">
  <head>
  	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<title>Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="estilos/estilos.css">
  </head>
  <body class="bg-secondary pt-5">

    <?php
      if(isset($_POST['inputEmail'])){
        try{
          $servidor = 'localhost';
          $userName = 'root';
          $bd = 'pm';
          $email = $_POST['inputEmail'];
          $password = $_POST['inputPassword'];

          $conexion = new mysqli($servidor,$userName,"",$bd);
          if($conexion->connect_error){
            die("Error al conectarse con la base de datos");
          }

          $sqlLogIn = "SELECT u.*,t.Codigo AS TipoCodigo,Tipo FROM usuarios u, tipousuarios t WHERE Email = '$email' AND Es = t.Codigo";
          $usuario = $conexion->query($sqlLogIn);
          if($usuario && $conexion->affected_rows > 0){
            $usuario = $usuario->fetch_assoc();
            if(password_verify($password, $usuario['Password'])){
              $_SESSION['Codigo'] = $usuario['Codigo'];
              if($usuario['Tipo'] == "Admin"){
                header("Location:dashboard.php");
              }else{
                header("Location:".$_SESSION['pagAnterior']);
              }
            }else{
              echo "<script>alert(\"Nombre de usuario y/o clave incorrectos.\")</script>"; 
            }
          }else{
            echo "<script>alert(\"Nombre de usuario y/o clave incorrectos.\")</script>";
          }
        }catch(Exception $e){
          echo "<script>alert(\"Surgio una excepción del tipo: ".$e."\")</script>";
        }
      }
    ?>

    <div class="container">
      <div class="m-0 row justify-content-center align-items-center">
        <div class="col-sm-5 bg-principal text-center mt-5 pt-3 rounded-top">
          <a class="navbar-brand text-white" href="index.php"><img class="logoImg" src="iconos/logo.png" alt="Logo">CursoLandia</a>
        </div>
      </div>
      <div class="m-0 row justify-content-center align-items-center">
        <div class="col-sm-5 bg-principal px-5 py-5">
          <!--FORMULARIO-->
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="formLogIn">
            <div class="mb-3">
              <input type="email" name="inputEmail" class="form-control" id="inputEmail" placeholder="Correo electrónico">
              <div id="alertaEmail" class="form-text text-danger visually-hidden">
                El correo electrónico ingresado es inválido
                <i class="bi bi-info-circle-fill"></i>
              </div>
            </div>
            <div class="mb-3">
              <input type="password" name="inputPassword" class="form-control" id="inputPassword" placeholder="Contraseña">
              <div id="alertaPassword" class="form-text text-danger visually-hidden">
                La contraseña ingresada es inválida
                <i class="bi bi-info-circle-fill"></i>
              </div>
            </div>
            <a href="#">¿Has olvidado la contraseña?</a><br>
            <div class="text-center mt-4">
              <button type="submit" class="btn btn-primary">Iniciar sesión</button>
            </div>
          </form>
        </div>
      </div>
      <div class="m-0 row justify-content-center align-items-center text-center">
        <div class="col-sm-5 text-bg-dark px-2 py-2 rounded-bottom">
          <label>¿Eres nuevo?</label>  <a href="#">¡Regístrese y comienza a aprender!</a>
        </div>
      </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
    <script src="js/logeo.js"></script>
  </body>
</html>