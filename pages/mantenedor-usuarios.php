<?php
session_start();
require("../src/dao/UserDao.php");

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["user_name"]) && !isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit();
}
$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user_name"];
$listUsuarios = [];
$listUsuarios = getAllUsers();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mantenedor de Usuarios</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {
      padding: 40px;
    }

    .menu-link {
      color: white;
    }

    .menu-link-dark {
      color: black;
    }

    .navbar-nav {
      margin-left: auto;
    }
  </style>
</head>

<body class="p-3 m-0 border-0 bd-example">
  <nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark " data-bs-theme="dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">PRO301</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active menu-link" aria-current="page" href="home.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link menu-link" href="mantenedor-areas.php">Área</a>
          </li>
          <li class="nav-item">
            <a class="nav-link menu-link" href="mantenedor-usuarios.php">Usuarios</a>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle menu-link btn btn-primary" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?php echo $user_name ?>
            </a>
            <ul class="dropdown-menu ">
              <li><a class="btn btn-danger dropdown-item menu-link-dark btn btn-danger" href="logout.php">Cerrar sesión</a></li>
            </ul>
          </li>


        </ul>
      </div>
    </div>
  </nav>
  <section>
    <div class="container text-center">
      <div class="row p-3"></div>
      <div class="row">
        <div class="col-12 p-3">
          <h1>Mantenedor de Usuarios</h1>
        </div>
      </div>
    </div>
    <!-- BOTON NUEVO REGISTRO -->
    <div class="container">
      <div class="row p-3">
        <div class="col-4">
          <a href="form-usuarios.php?act=new" class="btn btn-primary">Nuevo Registro</a>
        </div>
        <div class="col-4">

        </div>
        <div class="col-4">

        </div>
      </div>
    </div>
    <!-- TABLA USUARIOS -->
    <div class="container">
      <div class="row">
        <div class="col-12">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Usuario</th>
                <th scope="col">Nombre</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              foreach ($listUsuarios as $usuario){
                echo "<tr>";
                echo '<td scope="row">'.$usuario->getId().'</td>';
                echo '<td>'.$usuario->getName().'</td>';
                echo '<td>'.$usuario->getFullName().'</td>';
                echo '<td>'.($usuario->getStatus() == 0 ? "N" : "S").'</td>';
                echo '<td><a class="btn btn-success mx-3" href="form-usuarios.php?act=edit&id=' . $usuario->getId() . '">Editar</a>'.
                '<a class="btn btn-danger mx-3" href="form-usuarios.php?act=del&id=' . $usuario->getId() . '">Eliminar</a>'.
                '</td>';
                echo "</tr>";
              }

              
              ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </section>

  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>