<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra Concluída</title>
    <link rel="stylesheet" href="assets/css/style2.css">
</head>
<body>
    <div class="cabecalho_compra_concluida">

        <div class="logo_compra_concluida">
        <a href="eventos.php"><img src="assets/imagens/logo_fundo_removido.png" alt="Logo EventFlow" title="Início" width="200"></a>
        </div>

        <div class="container_compra_concluida"> 
            <div class="informacoes_compra_concluida">

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

                    // Obtenha o array de id_evento
                    $id_evento_array = $_POST['id_evento'];
                    $id_ingresso_array = $_POST['id_ingresso']; // Adicionado o array de ID do ingresso

                    // Faça o processamento necessário com o array de id_evento e id_ingresso
                    foreach ($id_evento_array as $key => $id_evento) {
                        $id_ingresso = $id_ingresso_array[$key]; // Obtenha o ID do ingresso correspondente

                        // Obter o nome do evento correspondente ao ID do evento
                        $query_nome_evento = "SELECT nome_evento FROM eventos WHERE idevento = $id_evento LIMIT 1";
                        $resultado_nome_evento = mysqli_query($conexao, $query_nome_evento);

                        if ($resultado_nome_evento && mysqli_num_rows($resultado_nome_evento) > 0) {
                            $row_nome_evento = mysqli_fetch_assoc($resultado_nome_evento);
                            $nome_evento = $row_nome_evento['nome_evento'];

                            // Faça algo com o nome do evento e o ID do ingresso
                            echo "Nome do Evento: " . $nome_evento . "<br>";
                            echo "ID do Ingresso: " . $id_ingresso . "<br><hr>";
                            // ... Outras operações com o nome do evento e o ID do ingresso ...

                            // Obter o valor máximo atual da coluna cod_ingressos na tabela venda
                            $query_max_cod_ingressos = "SELECT MAX(cod_ingressos) AS max_cod_ingressos FROM venda";
                            $resultado_max_cod_ingressos = mysqli_query($conexao, $query_max_cod_ingressos);

                            // Chamar a função para gerar a chave aleatória
                $cod_ingressos = gerarChaveAleatoria(10);

                            // Usar a variável $cod_ingressos na query de inserção
                $query_insert_venda = "INSERT INTO venda (nome_item, quantidade, preco_unitario, idusuario, id_ingresso, cod_ingressos) 
                SELECT CASE WHEN il.iditem_loja IS NULL THEN e.nome_evento ELSE il.nome END AS nome_item,
                1 AS quantidade,
                COALESCE(il.valor, i.valor) AS preco_unitario,
                $idusuario AS idusuario,
                c.id_ingresso,
                '$cod_ingressos' AS cod_ingressos
                FROM carrinho c
                LEFT JOIN iten_loja il ON c.iditem_loja = il.iditem_loja
                LEFT JOIN ingresso i ON c.id_ingresso = i.id_ingresso
                LEFT JOIN eventos e ON il.idevento = e.idevento OR (i.idevento = e.idevento AND il.iditem_loja IS NULL)
                WHERE c.idusuario = $idusuario
                AND (il.iditem_loja IS NOT NULL OR i.id_ingresso IS NOT NULL)";

                            mysqli_query($conexao, $query_insert_venda);
                        } else {
                            echo "Evento não encontrado para o ID: " . $id_evento . "<br>";
                        }
                    }

                    $query_FK = "SET FOREIGN_KEY_CHECKS=0";
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
                        echo'<center>';
                        echo'<div class="caixa_processar_compra">';
                        echo "<h1>Compra Concluída</h1>";
                        echo "<p>Obrigado por sua compra, $nome_usuario!</p>";
                        echo "<p>Preço Total: R$ $preco_total</p>";

                        // Exibir formulário de avaliação
                        echo '<div class="avaliacao">';
                        echo '<h2>Avaliar o Evento</h2>';
                        echo '<form action="processar_avaliacao.php" method="post">';
                        echo '<label for="nota">Nota:</label>';
                        echo '<input type="radio" name="nota" value="5"> 5';
                        echo '<input type="radio" name="nota" value="4"> 4';
                        echo '<input type="radio" name="nota" value="3"> 3';
                        echo '<input type="radio" name="nota" value="2"> 2';
                        echo '<input type="radio" name="nota" value="1"> 1';
                        echo '</form>';
                        echo '</div>',"<br>";

                        echo "<a href='eventos.php'>Voltar para os Eventos</a>";
                    } else {
                        echo "Não foi possível obter o nome do usuário";
                        echo "<a href='eventos.php'>Voltar para os Eventos</a>";
                        echo'</div>';
                        echo'</center>';
                    }
                } else {
                    header("location: carrinho.php");
                    exit();
                }
                ?>

            </div>
        </div>
    </div>  
</body>
</html>