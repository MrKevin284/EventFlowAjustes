<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventFlow</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="cabecalho_eventflow">
        <h2 id="nome_eventflow">Bem-vindo(a) ao EventFlow!</h2>
        <nav class="botoes_eventflow">
            <a href="login.php"><label>Login</label></a>
            <a href="tipo_de_usuario.php"><label>Cadastro</label></a>
            <a href="sobre_nos.php"><label>Sobre Nós</label></a>
        </nav>

        <div class="logo_eventflow">
            <img src="assets/imagens/logo_fundo_removido.png" alt="Logo EventFlow">
        </div>

        <center>
        <div class="imagem_evento">
            <img width="820" height="400" src="assets/imagens/evento_1.jpeg" alt="Imagem do Evento">
            <!-- width="1363" height="450" -->
        </div>
        <h1 id="todos_os_eventos">Todos os Eventos:</h1>
        </center>
        
        <div class="container_eventflow">
            
            

            <?php
            // Incluir o arquivo de conexão com o banco de dados
            require_once "conexao.php";

            // Consultar os eventos no banco de dados
            $consulta = "SELECT * FROM eventos";
            $resultado = mysqli_query($conexao, $consulta);

            // Verificar se existem eventos cadastrados
            if (mysqli_num_rows($resultado) > 0) {
                // Exibir os eventos
                while ($row = mysqli_fetch_assoc($resultado)) {
                    // Obter a data de início e fim do evento
                    echo '<div class="caixa_eventos">';
                    $dataInicio = date("d/m", strtotime($row["data_inicio_evento"]));
                    $dataFim = date("d/m", strtotime($row["data_final_evento"]));
                    echo '<div class="cartao">';
                    echo '<div class="cartao_esquerdo">';
                    echo '<span>' . $dataInicio . ' - ' . $dataFim . '</span>';
                    echo '<h1>' . $row["nome_evento"] . '</h1>';
                    echo '<h3>' . $row["palavra_chave"] . '</h3>';
                    echo '</div>';
                    echo '</div>';
                    echo '</a></div>';
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