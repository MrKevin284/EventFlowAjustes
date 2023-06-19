<?php

session_start();

include('conexao.php');

$idusuario = $_SESSION['idusuario'];

$email = isset($_POST['email']) ? $_POST['email'] : '';
$senha = isset($_POST['nova_senha']) ? $_POST['nova_senha'] : '';

$update = "UPDATE login SET senha = '$senha' WHERE idusuario = '$idusuario'";
$query = mysqli_query($conexao, $update);

header('Location: login.php');

?>