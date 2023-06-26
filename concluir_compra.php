<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conclusão da Compra</title>
    <link rel="stylesheet" href="assets/css/style2.css">
</head>
<body>
    <div class="cabecalho_concluir_compra">

        <div class="logo_concluir_compra">
        <a href="eventos.php"><img src="assets/imagens/logo_fundo_removido.png" alt="Logo EventFlow" title="Início" width="200"></a>
        </div>

        <div class="container_concluir_compra">
            <div class="informacoes_concluir_compra">

                <?php
                session_start();
                if (!isset($_SESSION['idusuario'])) {
                    header("location: login.php");
                    exit();
                }

                require_once "conexao.php";

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Obter o ID do usuário logado
                    $idusuario = $_SESSION['idusuario'];

                    // Obter o preço total do formulário
                    $preco_total = $_POST['preco_total'];

                    $query_itens_carrinho = "SELECT c.*, COALESCE(il.valor, i.valor) AS preco_item, 
                                        CASE WHEN il.iditem_loja IS NULL THEN e.nome_evento ELSE il.nome END AS nome_item, 
                                        il.descricao AS descricao_item, e.nome_evento, e.idevento, c.id_ingresso
                                        FROM carrinho c
                                        LEFT JOIN iten_loja il ON c.iditem_loja = il.iditem_loja
                                        LEFT JOIN ingresso i ON c.id_ingresso = i.id_ingresso
                                        LEFT JOIN eventos e ON il.idevento = e.idevento OR (i.idevento = e.idevento AND il.iditem_loja IS NULL)
                                        WHERE c.idusuario = $idusuario
                                        AND (il.iditem_loja IS NOT NULL OR i.id_ingresso IS NOT NULL)";

                    $resultado_itens_carrinho = mysqli_query($conexao, $query_itens_carrinho);

                    if ($resultado_itens_carrinho) {
                        // Criar um array para armazenar os dados da venda
                        $dados_venda = array();

                        while ($row_item = mysqli_fetch_assoc($resultado_itens_carrinho)) {
                            $nome_item = $row_item['nome_item'];
                            $preco_item = $row_item['preco_item'];
                            $id_evento = $row_item['idevento']; // Aqui está o ID do evento
                            $id_ingresso = $row_item['id_ingresso']; // Aqui está o ID do ingresso

                            // Adicionar os dados do item à venda
                            $dados_venda[] = array(
                                'nome_item' => $nome_item,
                                'quantidade' => 1, // A quantidade pode ser ajustada conforme necessário
                                'preco_unitario' => $preco_item,
                                'id_evento' => $id_evento,
                                'id_ingresso' => $id_ingresso // Adicionando o ID do ingresso ao array de dados
                            );
                        }

                        // Exibir os dados da compra na página
                        echo'<center>';
                        echo "<h1>Conclusão da Compra</h1><hr>";
                        echo'</center>';

                        echo "<h2>Itens Comprados:</h2>";
                foreach ($dados_venda as $dados) {
                    $nome_item = $dados['nome_item'];
                    $quantidade = $dados['quantidade'];
                    $preco_unitario = $dados['preco_unitario'];
                    $id_evento = $dados['id_evento'];
                    $id_ingresso = $dados['id_ingresso'];

                    echo "<p>Item: $nome_item</p>";
                    echo "<p>Quantidade: $quantidade</p>";
                    echo "<p>Preço Unitário: R$ $preco_unitario</p>";
                    echo "<p>ID do Evento: $id_evento</p>";
                    
                    // Verificar se o ID do Ingresso não está vazio
                    if (!empty($id_ingresso)) {
                        echo "<p>ID do Ingresso: $id_ingresso</p>";
                    }
                    
                    echo "<hr>";
                }

                echo'<center>';
                        echo "<h2>Preço Total: R$ $preco_total</h2>";

                        echo "<form action='processar_compra.php' method='POST'>";
                        echo "<input type='hidden' name='preco_total' value='$preco_total'>";

                        // Adicionar um input hidden para cada valor de id_evento
                        foreach ($dados_venda as $dados) {
                            $id_evento = $dados['id_evento'];
                            $id_ingresso = $dados['id_ingresso'];
                            echo "<input type='hidden' name='id_evento[]' value='$id_evento'>";
                            echo "<input type='hidden' name='id_ingresso[]' value='$id_ingresso'>";
                        }

                        // Verificar a quantidade disponível antes de adicionar o item ao carrinho
                        foreach ($dados_venda as $dados) {
                            if (isset($dados['id_ingresso'])) {
                                $id_ingresso = $dados['id_ingresso'];
                                if (!empty($id_ingresso)) {
                                    // Consultar a quantidade disponível do ingresso no banco de dados
                                    $query_quantidade_disponivel = "SELECT quantidade FROM ingresso WHERE id_ingresso = $id_ingresso";
                                    $resultado_quantidade = mysqli_query($conexao, $query_quantidade_disponivel);
                                    $row_quantidade = mysqli_fetch_assoc($resultado_quantidade);
                                    $quantidade_disponivel = $row_quantidade['quantidade'];

                                    // Verificar se a quantidade disponível é maior que zero
                                    if ($quantidade_disponivel > 0) {
                                        // A quantidade é maior que zero, adicionar o ingresso ao carrinho

                                        // Atualizar a quantidade no banco de dados
                                        $query_atualizar_quantidade = "UPDATE ingresso SET quantidade = quantidade - 1 WHERE id_ingresso = $id_ingresso";
                                        if (mysqli_query($conexao, $query_atualizar_quantidade)) {
                                            // A atualização foi realizada com sucesso
                                            echo "<input type='hidden' name='id_ingresso[]' value='$id_ingresso'>";
                                        } else {
                                            // Ocorreu um erro ao atualizar a quantidade de ingressos
                                            echo "Erro ao atualizar a quantidade de ingressos: " . mysqli_error($conexao);
                                        }
                                    } else {
                                        // A quantidade é igual a zero, exibir mensagem de ingresso esgotado
                                        echo "<p>O ingresso está esgotado. Não foi possível adicioná-lo ao carrinho.</p>";
                                    }
                                }
                            }
                        }
                       
                        echo "</form>";
                        echo'</center>';
                    } else {
                        echo "<p>Erro na consulta do banco de dados.</p>";
                    }
                } else {
                    header("location: carrinho.php");
                    exit();
                }
                ?>

                <form action="processar_compra.php" method="POST">
                    <input type="radio" name="opcao_pagamento" value="pix" onchange="mostrarFormulario('pix')" required> PIX<br>
                    <input type="radio" name="opcao_pagamento" value="cartao" onchange="mostrarFormulario('cartao')" required> Cartão de Crédito<br><br>
                    
                    <div id="formulario_pix" style="display: none;">
                        <p>Chave PIX: 1234567890</p>
                    </div>

                    <div class="caixa_concluir_compra" id="formulario_cartao" style="display: none;">
                        <label for="numero_cartao">Número do Cartão:</label>
                        <input type="number" id="numero_cartao" name="numero_cartao" ><br>
                        <label for="nome_titular">Nome do Titular:</label>
                        <input type="text" id="nome_titular" name="nome_titular" ><br>
                        <label for="cvv">CVV:</label>
                        <input type="number" id="cvv" name="cvv" ><br>
                        <label for="validade">Data de Validade:</label>
                        <input type="date" id="validade" name="validade" ><br>
                    </div>

                    <?php
                    echo'<div class="caixa_concluir_compra">';
                    echo "<form action='processar_compra.php' method='POST'>";
                    echo "<input type='hidden' name='preco_total' value='$preco_total'>";

                    // Adicionar um input hidden para cada valor de id_evento
                    foreach ($dados_venda as $dados) {
                        $id_evento = $dados['id_evento'];
                        $id_ingresso = $dados['id_ingresso'];
                        echo "<input type='hidden' name='id_evento[]' value='$id_evento'>";
                        echo "<input type='hidden' name='id_ingresso[]' value='$id_ingresso'>";
                    }
                    echo'<center>';
                    echo "<button type='submit' value='Concluir Compra'>Concluir Compra</button>";
                    echo "</form>";
                    echo "</div>";
                    ?>
                    
                </form>
                
                    <center>
                    <div class="caixa_concluir_compra">
                    <a href="carrinho.php"><button>Voltar</button></a>
                    </div>
                    </center>

                <div id="aviso_compra" style="display: none;">
                    <p>Compra realizada com sucesso!</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function mostrarFormulario(opcao) {
            if (opcao === 'pix') {
                document.getElementById('formulario_pix').style.display = 'block';
                document.getElementById('formulario_cartao').style.display = 'none';
                document.getElementById('btn_concluir').style.display = 'inline-block';
            } else if (opcao === 'cartao') {
                document.getElementById('formulario_pix').style.display = 'none';
                document.getElementById('formulario_cartao').style.display = 'block';
                document.getElementById('btn_concluir').style.display = 'inline-block';
            }
        }
    </script>
</body> 
</html>  