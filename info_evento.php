<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informações do Evento</title>
    <link rel="stylesheet" href="assets/css/style2.css">
</head>

<body>
    <div class="container_info_evento">
        <div class="cabecalho_info_evento">

            <div class="logo_info_evento">
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

            <nav class="botoes_info_evento">
                <?php if ($tipo_usuario == 2) : // Cadastro Empresarial 
                ?>
                    <a href="criar_eventos.php"><label>Criar Eventos</label></a>
                    <a href="eventos_criados.php"><label>Eventos Criados</label></a>
                <?php else : // Cadastro Pessoal 
                ?>
                    <a href="eventos.php"><label>Eventos</label></a>
                    <a href="eventos_criados.php"><label>Meus Eventos</label></a>
                <?php endif; ?>
                <a href="carrinho.php">
                    <script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
                    <lord-icon src="https://cdn.lordicon.com/slkvcfos.json" trigger="hover" colors="primary:white,secondary:white" style="width:65px;height:65px;top:5px;">
                    </lord-icon>
                </a>
                <a href="perfil.php"><label>Perfil</label></a>
                <a href="EventFlow.php"><button class="Btn">
                        <div class="sign"><svg viewBox="0 0 512 512">
                                <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path>
                            </svg></div>
                        <div class="text">Logout</div>
                    </button></a>
            </nav>
        </div>

        <div class="container_info_evento_2">
            <div class="caixa_info_evento">

                <?php
                // Verificar se foi fornecido o parâmetro de ID do evento
                if (isset($_GET['id'])) {
                    // Obter o ID do evento a partir do parâmetro da URL
                    $id_evento = $_GET['id'];

                    // Consultar o evento no banco de dados
                    $query_evento = "SELECT * FROM eventos WHERE idevento = $id_evento";
                    $resultado_evento = mysqli_query($conexao, $query_evento);
                    $dados_evento = mysqli_fetch_assoc($resultado_evento);

                    if ($dados_evento) {
                        echo '<h1>' . $dados_evento['nome_evento'] . '</h1><hr>';

                        // Formatando a data do evento para o padrão brasileiro
                        $data_inicio_evento = date('d/m/Y', strtotime($dados_evento['data_inicio_evento']));
                        $data_final_evento = date('d/m/Y', strtotime($dados_evento['data_final_evento']));
                        echo '<p>Data de Início do Evento: ' . $data_inicio_evento . '</p>';
                        echo '<p>Data de Término do Evento: ' . $data_final_evento . '</p>';

                        $horario_inicial = date('H:i', strtotime($dados_evento['horario_inicial']));
                        $horario_final = date('H:i', strtotime($dados_evento['horario_final']));
                        echo '<p>Horário de Início do Evento: ' . $horario_inicial . '</p>';
                        echo '<p>Horário de Término do Evento: ' . $horario_final . '</p>';

                        echo '<p>Descrição: ' . $dados_evento['descricao'] . '</p>';
                        echo '<p>Local: ' . $dados_evento['endereco'] . '</p>';

                        echo '<h2>Ingressos:</h2>';

                        // Consultar os ingressos relacionados ao evento
                        $query_ingressos = "SELECT i.*, t.descricao AS tipo_ingresso FROM ingresso i INNER JOIN tipo_ingresso t ON i.id_tipoingresso = t.id_tipoingresso WHERE idevento = $id_evento";
                        $resultado_ingressos = mysqli_query($conexao, $query_ingressos);

                        if (mysqli_num_rows($resultado_ingressos) > 0) {
                            while ($dados_ingresso = mysqli_fetch_assoc($resultado_ingressos)) {
                                echo '<p>';
                                if ($dados_ingresso['tipo_ingresso'] == 'entrada inteira') {
                                    echo 'Tipo: Entrada Inteira<br>';
                                } elseif ($dados_ingresso['tipo_ingresso'] == 'entrada estudante') {
                                    echo 'Tipo: Entrada Estudante<br>';
                                }
                                echo 'Preço: R$ ' . $dados_ingresso['valor'] . '<br>';
                                echo 'Quantidade: ' . $dados_ingresso['quantidade'] . '<br>';

                                // Verificar se o usuário é o criador do evento
                                $isCriadorEvento = verificarCriadorEvento($id_evento, $idusuario, $conexao);

                                if (!$isCriadorEvento) {
                                    echo '<form class="informacoes_info_evento" action="processar_acao.php" method="POST">';
                                    echo '<input type="hidden" name="id_ingresso" value="' . $dados_ingresso['id_ingresso'] . '">';
                                    echo '<input type="hidden" name="id_evento" value="' . $id_evento . '">';
                                    echo '<div class="botoes_caixa_info_evento"><button type="submit" name="acao" value="adicionar_carrinho">Adicionar ao Carrinho</button></div>';
                                    echo '</form>';
                                }

                                echo '</p>';
                            }
                        } else {
                            echo '<p>Nenhum ingresso disponível para este evento.</p>';
                        }
                        echo '</div>';

                        echo '<center><div class="caixa_info_evento_2">';

                        if ($tipo_usuario == 2 && $isCriadorEvento) { // Cadastro Empresarial
                            echo '<div class="botoes_caixa_info_evento"><a href="editar_evento.php?id=' . $id_evento . '">Editar Evento</a></div>';
                            echo '<div class="botoes_caixa_info_evento"><a href="remover_evento.php?id=' . $id_evento . '">Excluir Evento</a></div>';
                        }
                        echo '<div class="botoes_caixa_info_evento"><a href="loja.php?id=' . $id_evento . '">Loja</a></div>';
                        echo '<div class="botoes_caixa_info_evento"><a href="eventos.php">Voltar para a lista de eventos</a></div>';
                    } else {
                        echo '<p>Evento não encontrado.</p>';
                    }
                } else {
                    echo '<p>Evento não especificado.</p>';
                }
                echo '</div></center>';
                ?>
            </div>
        </div>
    </div>
</body>

</html>