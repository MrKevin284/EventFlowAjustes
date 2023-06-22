<?php

//LOCAL
$conexao = mysqli_connect('localhost:3307', 'root', '', 'eventflow');
if (!$conexao) {
    die('Não foi possível conectar: ' . mysqli_connect_error());
}

?>