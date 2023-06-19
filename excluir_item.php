<?php
// Incluir o arquivo de conexão com o banco de dados
require_once "conexao.php";

// Verificar se o ID do item foi fornecido
if (isset($_GET['id'])) {
    $id_item = $_GET['id'];

    // Verificar se o ID do evento foi fornecido
    if (isset($_GET['evento_id'])) {
        $evento_id = $_GET['evento_id'];
    } else {
        echo "<h2>ID do evento não fornecido</h2>";
        echo "<p>ID do evento não foi fornecido para voltar à loja.</p>";
        echo "<a href='loja.php'>Voltar para a Loja</a>";
        exit(); // Terminar a execução do script
    }

    // Excluir o item do banco de dados
    $query_excluir = "DELETE FROM iten_loja WHERE iditem_loja = $id_item";
    $resultado_excluir = mysqli_query($conexao, $query_excluir);

    if ($resultado_excluir) {
        echo "<h2>Item Excluído</h2>";
        echo "<p>O item foi excluído com sucesso.</p>";
        exit(); // Terminar a execução do script após a exclusão
    } else {
        echo "<h2>Erro ao Excluir</h2>";
        echo "<p>Ocorreu um erro ao excluir o item. Por favor, tente novamente.</p>";
        exit(); // Terminar a execução do script em caso de erro
    }
} else {
    echo "<h2>ID do item não fornecido</h2>";
    echo "<p>ID do item não foi fornecido para exclusão.</p>";
}
?>