<?php
// Incluir o arquivo de conexão com o banco de dados
require_once "conexao.php";

// Verificar se o ID do item foi fornecido
if (isset($_GET['id'])) {
    $id_item = $_GET['id'];

    // Verificar se o usuário está logado
    session_start();
    if (!isset($_SESSION['idusuario'])) {
        header("location: login.php");
        exit();
    }

    // Obter o ID do usuário logado
    $idusuario = $_SESSION['idusuario'];

    // Inserir o item no carrinho do usuário
    $query_inserir = "INSERT INTO carrinho (iditem_loja, idusuario) VALUES ($id_item, $idusuario)";
    $resultado_inserir = mysqli_query($conexao, $query_inserir);

    if ($resultado_inserir) {
        echo "<h2>Item Adicionado ao Carrinho</h2>";
        echo "<p>O item foi adicionado com sucesso ao seu carrinho.</p>";
        echo "<a href='eventos.php'>Eventos</a>";
        exit();
    } else {
        echo "<h2>Erro ao Adicionar Item</h2>";
        echo "<p>Ocorreu um erro ao adicionar o item ao carrinho. Por favor, tente novamente.</p>";
        echo "<a href='eventos.php'>Eventos</a>";
        exit();
    }
} else {
    echo "<h2>ID do item não fornecido</h2>";
    echo "<p>ID do item não foi fornecido para adicionar ao carrinho.</p>";
    echo "<a href='eventos.php'>Eventos</a>";
}
?>