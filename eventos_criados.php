<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos Criados</title>
    <link rel="stylesheet" href="assets/css/style2.css">
</head>

<body>
    <div class="cabecalho_eventos_criados">

        <div class="logo_eventos_criados">
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

        // Obter informações do usuário logado
        $idusuario = $_SESSION['idusuario'];
        $query_usuario = "SELECT nome FROM usuario WHERE idusuario = $idusuario";
        $resultado_usuario = mysqli_query($conexao, $query_usuario);
        $row_usuario = mysqli_fetch_assoc($resultado_usuario);
        $nome_usuario = $row_usuario['nome'];

        // Obter informações do usuário logado
        $idusuario = $_SESSION['idusuario'];
        $query_usuario = "SELECT tipo_user FROM usuario WHERE idusuario = $idusuario";
        $resultado_usuario = mysqli_query($conexao, $query_usuario);
        $row_usuario = mysqli_fetch_assoc($resultado_usuario);

        $tipo_usuario = $row_usuario['tipo_user'];

        ?>

        <nav class="botoes_eventos_criados">
            <a href="eventos.php"><label>Eventos</label></a>
            <?php
            if ($tipo_usuario == 2) {
                echo "<a href='criar_eventos.php'><label>Criar Evento</label></a>";
            }
            ?>
            <a href="perfil.php">
                <script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
                <lord-icon src="https://cdn.lordicon.com/dxjqoygy.json" trigger="hover" colors="primary:white,secondary:white" style="width:65px;height:65px;top:5px;">
                </lord-icon>
            </a>
            <a href="carrinho.php">
                <script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
                <lord-icon src="https://cdn.lordicon.com/slkvcfos.json" trigger="hover" colors="primary:white,secondary:white" style="width:65px;height:65px;top:5px;">
                </lord-icon>
            </a>
            <a href="EventFlow.php"><button class="Btn">
                    <div class="sign"><svg viewBox="0 0 512 512">
                            <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path>
                        </svg></div>
                    <div class="text">Logout</div>
                </button>
            </a>
        </nav>

        <center>
            <div class="nome_usuario_eventos">
                <?php
                if ($tipo_usuario == 1) {
                    echo "<h1 id='nome_eventos_criados'>Ingressos Comprados:</h1>";
                } elseif ($tipo_usuario == 2) {
                    echo "<h1 id='nome_eventos_criados'>Eventos Criados:</h1>";
                    echo '<a href="relatorio.php"><button>Relatório dos Eventos</button></a>';
                }
                ?>
            </div>
        </center>

        <div class="container_eventos_criados">

            <?php
            if ($tipo_usuario == 2) {
                // Consultar os eventos criados pelo usuário
                $query_eventos_criados = "SELECT * FROM eventos WHERE idusuario = $idusuario";
                $resultado_eventos_criados = mysqli_query($conexao, $query_eventos_criados);

                if (mysqli_num_rows($resultado_eventos_criados) > 0) {
                    while ($dados_evento = mysqli_fetch_assoc($resultado_eventos_criados)) {
                        echo '<div class="caixa_eventos_criados"><a href="info_evento.php?id=' . $dados_evento["idevento"] . '" class="caixa_evento">';
                        echo '<div class="cartao">';
                        echo '<div class="cartao_esquerdo">';
                        echo '<span>' . $dados_evento["nome_evento"] . '</span>';
                        echo '<h1>' . $dados_evento["nome_evento"] . '</h1><hr>';
                        echo '<h3>' . $dados_evento["descricao"] . '</h3>';
                        echo '</div>';
                        echo '</div>';
                        echo '</a></div>';
                    }
                } else {
                    echo '<p id="nome_nenhum_evento_encontrado">Nenhum evento encontrado.</p>';
                }
            } elseif ($tipo_usuario == 1) {
                echo '<p id="nome_nenhum_evento_encontrado">Nenhum ingresso comprado</p>';
            }
            ?>

        </div>

    </div>
</body>

</html>