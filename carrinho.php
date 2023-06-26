<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho</title>
    <link rel="stylesheet" href="assets/css/style2.css">
</head>
<body>
    <div class="cabecalho_carrinho">

        <div class="logo_carrinho">
        <a href="eventos.php"><img src="assets/imagens/logo_fundo_removido.png" alt="Logo EventFlow" title="Início" width="200"></a>
        </div>

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
                        echo '<h1 id="nome_carrinho">Carrinho</h1>';
                        echo "<style>";
                        echo ".logo_info_evento { text-align: center; width: 400px; }";
                        echo ".botoes { text-align: center; margin-top: 20px; width: 400px; }";
                        echo ".botoes a { margin-right: 10px; }";
                        echo ".itens_carrinho { margin-top: 20px; width: 400px; }";
                        echo ".item_carrinho { border: 5px solid #50C7F6; padding: 10px; margin-bottom: 10px; width: 400px; }";
                        echo "h1, h2 { text-align: center; }";
                        echo "</style>";
                        echo "</head>";
                        echo "<body>";
                        

                        if ($num_itens_carrinho > 0) {
                            echo "<div class='logo_carrinho'>";
                            echo '<a href="eventos.php"><img src="assets/imagens/logo_fundo_removido.png" alt="Logo EventFlow" title="Início" width="200"></a>';
                            echo "</div>";
                            
                            echo "<div class='botoes_carrinho'>";
                            if (isset($tipo_usuario) && $tipo_usuario == 2) { // Cadastro Empresarial
                                echo "<a href='criar_eventos.php'><label>Criar Eventos</label></a>";
                                echo "<a href='eventos_criados.php'><label>Eventos Criados</label></a>";
                            } else { // Cadastro Pessoal
                                echo "<a href='eventos.php'><label>Eventos</label></a>";
                                echo "<a href='eventos_criados.php'><label>Meus Eventos</label></a>";
                            }
                            echo "<a href='perfil.php'><label>Perfil</label></a>";
                            echo '<a href="EventFlow.php"><button class="Btn">
                            <div class="sign"><svg viewBox="0 0 512 512">
                            <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path></svg></div>
                            <div class="text">Logout</div></button></a>';
                            echo "</div>";

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
                                echo "<button type='submit' value='Remover'>Remover</button>";
                                echo "</form>";
                                
                                echo "</div>";
                            }
                            echo'<center>';
                            echo "<h2>Preço Total: R$ $preco_total</h2>";
                            echo "<form action='concluir_compra.php' method='POST'>";
                            echo "<input type='hidden' name='id_evento' value='" . $id_evento . "'>";
                            echo "<input type='hidden' name='preco_total' value='$preco_total'>";
                            echo "<button type='submit' value='Comprar'>Comprar</button>";
                            echo "<a href='eventos.php'>Voltar</a>"; 
                            echo "</form>";
                                 
                            echo "</div>";
                        } else {
                            echo'<div class="itens_carrinho">';
                            echo'<center>';
                            echo "<p>O carrinho está vazio.</p>";
                            echo "<a href='eventos.php'><label>Eventos</label></a>";
                            echo'</center>';
                            echo'</div>';
                        }

                        echo "</body>";
                        echo "</html>";
                    } else {
                        echo "<p>Erro na consulta do banco de dados.</p>";
                    }
                    echo'</center>';
                    ?>
        
    </div> 
</body>
</html>