<?php
// Incluir o arquivo de conexão com o banco de dados
require_once "conexao.php";

// Verificar se o usuário está logado
session_start();
if (!isset($_SESSION['idusuario'])) {
    header("location: login.php");
    exit();
}

// Obter o ID do usuário logado
$idusuario = $_SESSION['idusuario'];

$query_itens_carrinho = "SELECT c.*, COALESCE(il.valor, i.valor) AS preco_item, 
                        CASE WHEN il.iditem_loja IS NULL THEN e.nome_evento ELSE il.nome END AS nome_item, 
                        il.descricao AS descricao_item, e.nome_evento, e.idevento
                        FROM carrinho c
                        LEFT JOIN iten_loja il ON c.iditem_loja = il.iditem_loja
                        LEFT JOIN ingresso i ON c.id_ingresso = i.id_ingresso
                        LEFT JOIN eventos e ON il.idevento = e.idevento OR (i.idevento = e.idevento AND il.iditem_loja IS NULL)
                        WHERE c.idusuario = $idusuario
                        AND (il.iditem_loja IS NOT NULL OR i.id_ingresso IS NOT NULL)";

$resultado_itens_carrinho = mysqli_query($conexao, $query_itens_carrinho);

// Verificar se a consulta foi bem-sucedida
if ($resultado_itens_carrinho) {
    // Verificar o número de linhas retornadas
    $num_itens_carrinho = mysqli_num_rows($resultado_itens_carrinho);

    echo "<html>";
    echo "<head>";
    echo "<title>Carrinho</title>";
    echo "<style>";
    echo ".logo_info_evento { text-align: center; }";
    echo ".botoes { text-align: center; margin-top: 20px; }";
    echo ".botoes a { margin-right: 10px; }";
    echo ".itens_carrinho { margin-top: 20px; }";
    echo ".item_carrinho { border: 1px solid black; padding: 10px; margin-bottom: 10px; }";
    echo "h1, h2 { text-align: center; }";
    echo "</style>";
    echo "</head>";
    echo "<body>";

    if ($num_itens_carrinho > 0) {
        echo "<div class='logo_info_evento'>";
        echo "<img src='assets/imagens/logo_fundo_removido.png' alt='Logo EventFlow'>";
        echo "</div>";
        
        echo "<div class='botoes'>";
        if (isset($tipo_usuario) && $tipo_usuario == 2) { // Cadastro Empresarial
            echo "<a href='criar_eventos.php'><label>Criar Eventos</label></a>";
            echo "<a href='eventos_criados.php'><label>Eventos Criados</label></a>";
        } else { // Cadastro Pessoal
            echo "<a href='eventos.php'><label>Eventos</label></a>";
            echo "<a href='meus_eventos.php'><label>Meus Eventos</label></a>";
        }
        echo "<a href='perfil.php'><label>Perfil</label></a>";
        echo "<a href='EventFlow.php'><label>Logout</label></a>";
        echo "</div>";

        echo "<h1>Carrinho</h1>";
        
        echo "<div class='itens_carrinho'>";
        $preco_total = 0;
        while ($row_item = mysqli_fetch_assoc($resultado_itens_carrinho)) {
            $nome_item = $row_item['nome_item'];
            $descricao_item = $row_item['descricao_item'];
            $preco_item = $row_item['preco_item'];
            $preco_total += $preco_item;
            $id_evento = $row_item['idevento']; // Aqui está o ID do evento
        

            echo "<div class='item_carrinho'>";
            echo "<h4>$nome_item</h4>";
            echo "<p>Preço: R$ $preco_item</p>";
            echo $id_evento;
            if (!empty($descricao_item)) {
                echo "<p>Descrição: $descricao_item</p>";
            }

            // Botão "Remover" para excluir o item do carrinho
            echo "<form action='remover_item_carrinho.php' method='POST'>";
            echo "<input type='hidden' name='idcarrinho' value='" . $row_item['idcarrinho'] . "'>";
            echo "<input type='submit' value='Remover'>";
            echo "</form>";

            echo "</div>";
        }
        echo "<h2>Preço Total: R$ $preco_total</h2>";
        echo "<form action='concluir_compra.php' method='POST'>";
        echo "<input type='hidden' name='id_evento' value='" . $id_evento . "'>";
        echo "<input type='hidden' name='preco_total' value='$preco_total'>";
        echo "<input type='submit' value='Comprar'>";
        echo "</form>";        
        echo "</div>";
    } else {
        echo "<p>O carrinho está vazio.</p>";
        echo "<a href='eventos.php'><label>Eventos</label></a>";
    }

    echo "</body>";
    echo "</html>";
} else {
    echo "<p>Erro na consulta do banco de dados.</p>";
}
?>