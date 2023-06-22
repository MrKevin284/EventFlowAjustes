<?php
// Incluir o arquivo de conexão com o banco de dados
require_once "conexao.php";

// Verificar se o usuário está logado
session_start();
if (!isset($_SESSION['idusuario'])) {
    header("location: login.php");
    exit();
}

// Verificar se o ID do carrinho foi enviado via POST
if (isset($_POST['idcarrinho'])) {
    // Obter o ID do carrinho enviado
    $idcarrinho = $_POST['idcarrinho'];

    $query_FK = "SET FOREIGN_KEY_CHECKS=0";
    mysqli_query($conexao, $query_FK);
    
    // Remover o item do carrinho com base no ID do carrinho
    $query_remover_item = "DELETE FROM carrinho WHERE idcarrinho = $idcarrinho";

    // Executar a consulta de remoção
    $resultado_remover_item = mysqli_query($conexao, $query_remover_item);

    if ($resultado_remover_item) {
        // Item removido com sucesso, redirecionar de volta para a página do carrinho
        header("Location: carrinho.php");
        exit();
    } else {
        // Ocorreu um erro ao remover o item do carrinho
        echo "<p>Erro ao remover o item do carrinho.</p>";
    }
} else {
    // O ID do carrinho não foi enviado via POST
    echo "<p>ID do carrinho não fornecido.</p>";
}
?>