<?php
session_start();
if (!isset($_SESSION['idusuario'])) {
    header("location: login.php");
    exit();
}

function gerarChaveAleatoria($tamanho = 10) {
    $chave = bin2hex(random_bytes($tamanho));
    return substr($chave, 0, $tamanho);
}
require_once "conexao.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter o ID do usuário logado
    $idusuario = $_SESSION['idusuario'];

    // Obter o preço total do formulário
    $preco_total = $_POST['preco_total'];

    // Obtenha os arrays de id_evento e id_ingresso
    $id_evento_array = $_POST['id_evento'];
    $id_ingresso_array = $_POST['id_ingresso'];

    // Verificar se os arrays têm o mesmo tamanho
    if (count($id_evento_array) !== count($id_ingresso_array)) {
        echo "Erro: número de eventos e ingressos não corresponde.";
        exit();
    }

    // Preparar os valores para o INSERT
    $valores = [];
    foreach ($id_evento_array as $key => $id_evento) {
        $id_ingresso = $id_ingresso_array[$key];

        $valores[] = "(
            (SELECT CASE WHEN il.iditem_loja IS NULL THEN e.nome_evento ELSE il.nome END FROM eventos e
             LEFT JOIN iten_loja il ON il.idevento = e.idevento AND il.iditem_loja IS NULL
             WHERE e.idevento = $id_evento LIMIT 1),
            1,
            (SELECT COALESCE(il.valor, i.valor) FROM iten_loja il
             LEFT JOIN ingresso i ON i.id_ingresso = $id_ingresso
             WHERE il.iditem_loja IS NULL AND i.id_ingresso = $id_ingresso LIMIT 1),
            $idusuario,
            $id_ingresso,
            '" . gerarChaveAleatoria(10) . "'
        )";
    }

    $query_FK = "SET FOREIGN_KEY_CHECKS=0";
    mysqli_query($conexao, $query_FK);

        // Inserir os ingressos na tabela de vendas
        $query_insert_venda = "INSERT INTO venda (nome_item, quantidade, preco_unitario, idusuario, id_ingresso, cod_ingressos) VALUES " . implode(", ", $valores);
        mysqli_query($conexao, $query_insert_venda);
        
        // Limpar o carrinho do usuário
        $query_limpar_carrinho = "DELETE FROM carrinho WHERE idusuario = '$idusuario'";
        mysqli_query($conexao, $query_limpar_carrinho);

    // Obter o nome do usuário logado
    $query_nome_usuario = "SELECT nome FROM usuario WHERE idusuario = $idusuario";
    $resultado_nome_usuario = mysqli_query($conexao, $query_nome_usuario);

    if ($resultado_nome_usuario && mysqli_num_rows($resultado_nome_usuario) > 0) {
        $row_nome_usuario = mysqli_fetch_assoc($resultado_nome_usuario);
        $nome_usuario = $row_nome_usuario['nome'];

        // Exibir mensagem de sucesso
        echo "<h1>Compra Concluída</h1>";
        echo "<p>Obrigado por sua compra, $nome_usuario!</p>";
        echo "<p>Preço Total: R$ $preco_total</p>";
        echo "<a href='eventos.php'>Eventos</a>";
    } else {
        echo "Não foi possível obter o nome do usuário";
        echo "<a href='eventos.php'>Eventos</a>";
    }
} else {
    header("location: carrinho.php");
    exit();
}
?>