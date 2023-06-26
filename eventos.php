<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos</title>
    <link rel="stylesheet" href="assets/css/style2.css">
    <style>
        .star-rating {
            display: flex;
            align-items: center;
            margin-top: 10px;
            flex-direction: row-reverse;
            /* Inverte a direção das estrelas */
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            color: #aaa;
            font-size: 30px;
            padding: 2px;
            cursor: pointer;
        }

        .star-rating label:before {
            content: "\2606";
            /* Estrela vazia */
        }

        .star-rating input[type="radio"]:checked~label:before {
            content: "\2605";
            /* Estrela preenchida */
            color: #f8ce0b;
        }
    </style>
</head>

<body>
    <div class="cabecalho_eventos">

        <div class="logo_eventos">
            <img src="assets/imagens/logo_fundo_removido.png" alt="Logo EventFlow" width="200">
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
        $query_usuario = "SELECT nome, tipo_user FROM usuario WHERE idusuario = $idusuario";
        $resultado_usuario = mysqli_query($conexao, $query_usuario);
        $row_usuario = mysqli_fetch_assoc($resultado_usuario);
        $nome_usuario = $row_usuario['nome'];
        $tipo_usuario = $row_usuario['tipo_user'];
        ?>

        <nav class="botoes_eventos">
            <?php if ($tipo_usuario == 1) { ?>
                <a href="perfil.php">
                    <script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
                    <lord-icon src="https://cdn.lordicon.com/dxjqoygy.json" trigger="hover" colors="primary:white,secondary:white" style="width:65px;height:65px;top:5px;">
                    </lord-icon>
                </a>
                <a href="historico_ingressos.php"><label>Meus Ingressos</label></a>
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
                    </button></a>
            <?php } elseif ($tipo_usuario == 2) { ?>
                <a href="perfil.php">
                    <script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
                    <lord-icon src="https://cdn.lordicon.com/dxjqoygy.json" trigger="hover" colors="primary:white,secondary:white" style="width:65px;height:65px;top:5px;">
                    </lord-icon>
                </a>
                <a href="eventos_criados.php"><label>Eventos Criados</label></a>
                <a href="criar_eventos.php"><label>Criar Evento</label></a>
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
                    </button></a>
            <?php } ?>
        </nav>

        <center>
            <div class="nome_usuario_eventos">
                <h1>Bem-vindo(a) ao EventFlow, <?php echo $nome_usuario; ?>!</h1>
            </div>

            <form action="eventos.php" method="GET">
                <div class="container">
                    <input class="input" type="text" name="palavra_chave" placeholder="Categoria">
                    <button class="search__btn" type="submit" value="Pesquisar">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22">
                            <path d="M18.031 16.6168L22.3137 20.8995L20.8995 22.3137L16.6168 18.031C15.0769 19.263 13.124 20 11 20C6.032 20 2 15.968 2 11C2 6.032 6.032 2 11 2C15.968 2 20 6.032 20 11C20 13.124 19.263 15.0769 18.031 16.6168ZM16.0247 15.8748C17.2475 14.6146 18 12.8956 18 11C18 7.1325 14.8675 4 11 4C7.1325 4 4 7.1325 4 11C4 14.8675 7.1325 18 11 18C12.8956 18 14.6146 17.2475 15.8748 16.0247L16.0247 15.8748Z" fill="#efeff1"></path>
                        </svg>
                    </button>
                </div>
            </form>

            <h1 id="todos_os_eventos">Todos os Eventos</h1>
        </center>

        <div class="container_eventos_2">

            <?php
            // Verificar se foi feita uma pesquisa
            if (isset($_GET['palavra_chave'])) {
                // Capturar a palavra-chave digitada
                $palavraChave = $_GET['palavra_chave'];

                // Consultar os eventos no banco de dados com base na palavra-chave
                $consulta = "SELECT * FROM eventos WHERE nome_evento LIKE '%$palavraChave%' OR palavra_chave LIKE '%$palavraChave%'";
            } else {
                // Consultar todos os eventos no banco de dados
                $consulta = "SELECT * FROM eventos";
            }

            // Executar a consulta
            $resultado = mysqli_query($conexao, $consulta);

            // Verificar se existem eventos cadastrados
            if (mysqli_num_rows($resultado) > 0) {
                // Exibir os eventos
                while ($row = mysqli_fetch_assoc($resultado)) {
                    // Obter a data de início e fim do evento
                    echo '<div class="caixa_eventos"><a href="info_evento.php?id=' . $row["idevento"] . '" class="caixa_evento">';
                    $dataInicio = date("d/m", strtotime($row["data_inicio_evento"]));
                    $dataFim = date("d/m", strtotime($row["data_final_evento"]));

                    echo '<div class="cartao">';
                    echo '<div class="cartao_esquerdo">';
                    echo '<span>' . $dataInicio . ' - ' . $dataFim . '</span>';
                    echo '<h1>' . $row["nome_evento"] . '</h1><hr>';
                    echo '<h3>' . $row["palavra_chave"] . '</h3>';
                    echo '</div>';
                    echo '</div>';
                    echo '</a>';

                    // Star Rating
                    echo '<div class="star-rating">';
                    echo '<input type="radio" name="rating-' . $row["idevento"] . '" value="5" id="rating-' . $row["idevento"] . '-5">';
                    echo '<label for="rating-' . $row["idevento"] . '-5"></label>';
                    echo '<input type="radio" name="rating-' . $row["idevento"] . '" value="4" id="rating-' . $row["idevento"] . '-4">';
                    echo '<label for="rating-' . $row["idevento"] . '-4"></label>';
                    echo '<input type="radio" name="rating-' . $row["idevento"] . '" value="3" id="rating-' . $row["idevento"] . '-3">';
                    echo '<label for="rating-' . $row["idevento"] . '-3"></label>';
                    echo '<input type="radio" name="rating-' . $row["idevento"] . '" value="2" id="rating-' . $row["idevento"] . '-2">';
                    echo '<label for="rating-' . $row["idevento"] . '-2"></label>';
                    echo '<input type="radio" name="rating-' . $row["idevento"] . '" value="1" id="rating-' . $row["idevento"] . '-1">';
                    echo '<label for="rating-' . $row["idevento"] . '-1"></label>';
                    echo '</div>';

                    echo '</div>';
                }
            } else {
                echo '<p id="nome_nenhum_evento_encontrado">Nenhum evento encontrado.</p>';
            }

            // Fechar a conexão com o banco de dados
            mysqli_close($conexao);
            ?>

        </div>
    </div>
</body>

</html>