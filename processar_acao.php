<?php
include('conexao.php');
session_start();
if (!isset($_SESSION['idusuario'])) {
    header("location: login.php");
    exit();
}

// Obter informações do usuário logado
$idusuario = $_SESSION['idusuario'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifique se a ação é adicionar ao carrinho
    if ($_POST["acao"] == "adicionar_carrinho") {
        // Obtenha os valores do formulário
        $id_ingresso = $_POST["id_ingresso"];
        $id_evento = $_POST["id_evento"];

        // Verifique se o ingresso existe
        $query = "SELECT id_ingresso FROM ingresso WHERE id_ingresso = '$id_ingresso'";
        $result = mysqli_query($conexao, $query);
        if (mysqli_num_rows($result) > 0) {
            // Insira os dados no carrinho
            $sql = "INSERT INTO carrinho (id_ingresso, idusuario) 
                    VALUES ('$id_ingresso', '$idusuario')";
            if (mysqli_query($conexao, $sql)) {
                echo "Item adicionado ao carrinho com sucesso!","<br>";
                echo "<a href='eventos.php'><label>Eventos</label></a>";
            } else {
                echo "Erro ao adicionar item ao carrinho: " . mysqli_error($conexao),"<br>";;
                echo "<a href='eventos.php'><label>Eventos</label></a>";
            }
        } else {
            echo "O ingresso não existe.","<br>";;
            echo "<a href='eventos.php'><label>Eventos</label></a>";
        }

        mysqli_close($conexao);
    }
}
?>
