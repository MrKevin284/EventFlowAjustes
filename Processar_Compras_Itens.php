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

    // Desativar verificação de chave estrangeira temporariamente
    $query_FK = "SET FOREIGN_KEY_CHECKS=0";
    mysqli_query($conexao, $query_FK);

    // Inserir os ingressos na tabela de vendas
    $query_insert_venda = "INSERT INTO venda (nome_item, quantidade, preco_unitario, idusuario, id_ingresso, cod_ingressos) 
    VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conexao->prepare($query_insert_venda);
    $stmt->bind_param("sdisis", $nome_item, $quantidade, $preco_unitario, $idusuario, $id_ingresso, $cod_ingressos);

    foreach ($id_evento_array as $key => $id_evento) {
        $id_ingresso = $id_ingresso_array[$key];

        // Obter o nome do item
        if (isset($id_ingresso) && $id_ingresso !== '') {
            // Consulta quando o id_ingresso não for nulo
            $stmt_nome_item = $conexao->prepare("SELECT COALESCE(il.nome, i.nome_ingresso) FROM iten_loja il
                                                 LEFT JOIN ingresso i ON i.id_ingresso = ?
                                                 WHERE (il.iditem_loja IS NULL AND i.id_ingresso = ?) OR (il.iditem_loja IS NOT NULL AND i.id_ingresso IS NULL) LIMIT 1");
            $stmt_nome_item->bind_param("ii", $id_ingresso, $id_ingresso);
        } else {
            // Consulta quando o id_ingresso for nulo
            $stmt_nome_item = $conexao->prepare("SELECT nome FROM iten_loja WHERE idevento = ? LIMIT 1");
            $stmt_nome_item->bind_param("i", $id_evento);
        }
        $stmt_nome_item->execute();
        $stmt_nome_item->bind_result($nome_item);
        $stmt_nome_item->fetch();
        $stmt_nome_item->close();

        $quantidade = 1;

        // Obter o preço do ingresso
        if (isset($id_ingresso) && $id_ingresso !== '') {
            $stmt_preco = $conexao->prepare("SELECT COALESCE(il.valor, i.valor) FROM iten_loja il
                                             LEFT JOIN ingresso i ON i.id_ingresso = ?
                                             WHERE (il.iditem_loja IS NULL AND i.id_ingresso = ?) OR (il.iditem_loja IS NOT NULL AND i.id_ingresso IS NULL) LIMIT 1");
            $stmt_preco->bind_param("ii", $id_ingresso, $id_ingresso);
        } else {
            $stmt_preco = $conexao->prepare("SELECT valor FROM iten_loja WHERE idevento = ? LIMIT 1");
            $stmt_preco->bind_param("i", $id_evento);
        }
        $stmt_preco->execute();
        $stmt_preco->bind_result($preco_unitario);
        $stmt_preco->fetch();
        $stmt_preco->close();

        $cod_ingressos = gerarChaveAleatoria(20);

        $stmt->execute();
    }

    $stmt->close();

    // Ativar verificação de chave estrangeira novamente
    $query_FK = "SET FOREIGN_KEY_CHECKS=1";
    mysqli_query($conexao, $query_FK);

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
s