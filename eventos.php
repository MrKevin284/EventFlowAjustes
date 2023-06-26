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
        flex-direction: row-reverse; /* Inverte a direção das estrelas */
    }

    .star-rating input[type="radio"] {
        display: none;
    }

    .star-rating label {
        color: #aaa;
        font-size: 20px;
        padding: 2px;
        cursor: pointer;
    }

    .star-rating label:before {
        content: "\2606"; /* Estrela vazia */
    }

    .star-rating input[type="radio"]:checked ~ label:before {
        content: "\2605"; /* Estrela preenchida */
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
                <a href="perfil.php"><label>Perfil</label></a>
                <a href="historico_ingressos.php"><label>Meus Ingressos</label></a>
                <a href="carrinho.php"><label>Carrinho</label></a>
                <a href="EventFlow.php"><label>Logout</label></a>
            <?php } elseif ($tipo_usuario == 2) { ?>
                <a href="perfil.php"><label>Perfil</label></a>
                <a href="eventos_criados.php"><label>Eventos Criados</label></a>
                <a href="criar_eventos.php"><label>Criar Evento</label></a>
                <a href="carrinho.php">Carrinho</a>
                <a href="EventFlow.php"><label>Logout</label></a>
            <?php } ?>
        </nav>
        
        <center>
            <div class="nome_usuario_eventos">
                <h1>Bem-vindo(a) ao EventFlow, <?php echo $nome_usuario; ?>!</h1> 
            </div>
            <h1 id="todos_os_eventos">Todos os Eventos</h1>
        </center>

        <div class="container_eventos_2">
            <form action="eventos.php" method="GET" class="pesquisa_eventos">
                <input type="text" name="palavra_chave" placeholder="Digite a palavra-chave">
                <input type="submit" value="Pesquisar">
            </form>
        </div>

        <?php
        // Verificar se foi feita uma pesquisa
        if (isset($_GET['palavra_chave'])) {
            // Capturar a palavra-chave digitada
            $palavraChave = $_GET['palavra_chave'];

            // Consultar os eventos no banco de dados com base na palavra-chave
            $consulta = "SELECT * FROM eventos WHERE palavra_chave LIKE '%$palavraChave%'"; 
        } else {
            // Consultar todos os eventos no banco de dados
            $consulta = "SELECT * FROM eventos";
        };

        // Executar a consulta
        $resultado = mysqli_query($conexao, $consulta);

        // Verificar se existem eventos cadastrados
        if (mysqli_num_rows($resultado) > 0) {
            // Exibir os eventos
            echo '<div class="container_eventos">';
            while ($row = mysqli_fetch_assoc($resultado)) {
                // Obter a data de início e fim do evento
                $dataInicio = date("d/m", strtotime($row["data_inicio_evento"]));
                $dataFim = date("d/m", strtotime($row["data_final_evento"]));

                echo '<div class="caixa_eventos">';
                echo '<a href="info_evento.php?id=' . $row["idevento"] . '" class="caixa_evento">';
                echo '<div class="cartao">';
                echo '<div class="cartao_esquerdo">';
                echo '<span>' . $dataInicio . ' - ' . $dataFim . '</span>';
                echo '<h1>' . $row["nome_evento"] . '</h1>';
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
            echo '</div>';
        } else {
            echo '<p id="nome_nenhum_evento_encontrado">Nenhum evento encontrado.</p>';
        }

        // Fechar a conexão com o banco de dados
        mysqli_close($conexao);
        ?>
    </div>
</body>
</html>