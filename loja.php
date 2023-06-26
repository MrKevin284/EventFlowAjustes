<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja</title>
    <link rel="stylesheet" href="assets/css/style2.css">
</head>

<body>
    <div class="cabecalho_loja">

        <div class="logo_loja">
            <a href="eventos.php"><img src="assets/imagens/logo_fundo_removido.png" alt="Logo EventFlow" title="Início" width="200"></a>
        </div>

        <?php
        // Incluir o arquivo de conexão com o banco de dados
        require_once "conexao.php";

        // Função para verificar se o usuário é o criador do evento
        function verificarCriadorEvento($id_evento, $idusuario, $conexao)
        {
            // Consultar o banco de dados para verificar se o usuário é o criador do evento
            $query = "SELECT idevento FROM eventos WHERE idevento = $id_evento AND idusuario = $idusuario";
            $resultado = mysqli_query($conexao, $query);

            return (mysqli_num_rows($resultado) > 0);
        }

        // Verificar se o usuário está logado
        session_start();
        if (!isset($_SESSION['idusuario'])) {
            header("location: login.php");
            exit();
        }

        // Obter informações do usuário logado
        $idusuario = $_SESSION['idusuario'];
        $query_usuario = "SELECT nome, tipo_user FROM usuario WHERE idusuario = $idusuario";
        $resultado_usuario = mysqli_query($conexao, $query_usuario);
        $row_usuario = mysqli_fetch_assoc($resultado_usuario);
        $nome_usuario = $row_usuario['nome'];
        $tipo_usuario = $row_usuario['tipo_user'];
        ?>

        <nav class="botoes_loja">
            <?php if ($tipo_usuario == 2) : // Cadastro Empresarial 
            ?>
                <a href="criar_eventos.php"><label>Criar Eventos</label></a>
                <a href="eventos_criados.php"><label>Eventos Criados</label></a>
            <?php else : // Cadastro Pessoal 
            ?>
                <a href="eventos.php"><label>Eventos</label></a>
                <a href="meus_eventos.php"><label>Meus Eventos</label></a>
            <?php endif; ?>
            <a href="carrinho.php">
                <script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
                <lord-icon src="https://cdn.lordicon.com/slkvcfos.json" trigger="hover" colors="primary:white,secondary:white" style="width:65px;height:65px;top:5px;">
                </lord-icon>
            </a>
            <a href="perfil.php">
                <script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
                <lord-icon src="https://cdn.lordicon.com/dxjqoygy.json" trigger="hover" colors="primary:white,secondary:white" style="width:65px;height:65px;top:5px;">
                </lord-icon>
            </a>
            <a href="EventFlow.php"><button class="Btn">
                    <div class="sign"><svg viewBox="0 0 512 512">
                            <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path>
                        </svg></div>
                    <div class="text">Logout</div>
                </button></a>
        </nav>


        <div class="container_loja">
            <div class="caixa_loja">

                <center>
                    <h1>Loja</h1>
                    <hr>
                </center>

                <?php
                // Verificar se foi fornecido o parâmetro de ID do evento
                if (isset($_GET['id'])) {
                    // Obter o ID do evento a partir do parâmetro da URL
                    $id_evento = $_GET['id'];

                    // Consultar o evento no banco de dados
                    $query_evento = "SELECT * FROM eventos WHERE idevento = $id_evento";
                    $resultado_evento = mysqli_query($conexao, $query_evento);
                    $row_evento = mysqli_fetch_assoc($resultado_evento);

                    // Verificar se o evento existe e se está ativo
                    if ($row_evento) {
                        $nome_evento = $row_evento['nome_evento'];

                        // Exibir informações básicas do evento
                        echo "<h2>$nome_evento</h2>";

                        // Verificar se o usuário é o criador do evento
                        $eh_criador_evento = verificarCriadorEvento($id_evento, $idusuario, $conexao);

                        if ($eh_criador_evento) {
                            echo "<p></p>";
                            echo '<a href="cadastrar_produto.php?id=' . $id_evento . '">Cadastrar Produto</a>';
                        }
                    } else {
                        echo "<p>O evento não existe ou está inativo.</p>";
                    }

                    // Consultar os itens da loja relacionados ao evento
                    $query_itens_loja = "SELECT * FROM iten_loja WHERE idevento = $id_evento";
                    $resultado_itens_loja = mysqli_query($conexao, $query_itens_loja);

                    // Verificar se a consulta foi bem-sucedida
                    if ($resultado_itens_loja) {
                        // Verificar o número de linhas retornadas
                        $num_itens_loja = mysqli_num_rows($resultado_itens_loja);

                        if ($num_itens_loja > 0) {
                            // Exibir os itens da loja
                            echo "<h3>Itens disponíveis na loja do evento:</h3>", "<br>";
                            echo "<div class='itens_loja'>";
                            while ($row_item = mysqli_fetch_assoc($resultado_itens_loja)) {
                                $id_item = $row_item['iditem_loja'];
                                $nome_item = $row_item['nome'];
                                $descricao_item = $row_item['descricao'];
                                $preco_item = $row_item['valor'];

                                echo "<div class='item_loja'>";
                                echo "<h4>$nome_item</h4>";
                                echo "<p>Preço: R$ $preco_item</p>";
                                echo "<p>Descrição: $descricao_item</p>";


                                // Exibir botão "Adicionar ao Carrinho" apenas para não criadores de evento
                                if (!$eh_criador_evento) {
                                    echo "<a href='adicionar_carrinho.php?id=$id_item'>Adicionar ao Carrinho</a>";
                                }

                                // Exibir botão "Excluir" apenas para o criador do evento
                                if ($eh_criador_evento) {
                                    echo "<a href='editar_item.php?id=$id_item'>Editar</a>";
                                    echo "<span onclick=\"excluirItem($id_item, $id_evento)\"><button>Excluir</button></span>", "<hr>";
                                }

                                echo "</div>";
                            }
                            echo "</div>";
                        } else {
                            echo "<p>Nenhum item disponível na loja.</p>";
                        }
                    } else {
                        echo "<p>Erro na consulta do banco de dados.</p>";
                    }
                } else {
                    echo "<p>ID do evento não fornecido.</p>";
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        function excluirItem(id_item, id_evento) {
            if (confirm("Deseja excluir este item?")) {
                // Enviar uma solicitação AJAX para excluir o item
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "excluir_item.php?id=" + id_item + "&evento_id=" + id_evento, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Atualizar a página após a exclusão
                        window.location.reload();
                    }
                };
                xhr.send();
            }
        }
    </script>

</body>

</html>