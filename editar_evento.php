<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento</title>
</head>
<body>
    <div class="logo_principal">
        <img src="assets/imagens/logo_fundo_removido.png" alt="Logo EventFlow">
    </div>

    <a href="eventos.php">Eventos</a>
    <a href="eventos_criados.php">Eventos Criados</a>
    <a href="carrinho.php">Carrinho</a>
    <a href="perfil.php">Perfil</a>
    <a href="EventFlow.php">Logout</a>

    <h1>Editar Evento</h1>

    <?php
    require_once "conexao.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Verificar se todos os campos foram preenchidos
        if (empty($_POST['nome_evento']) || empty($_POST['data_inicio']) || empty($_POST['data_termino']) ||
            empty($_POST['horario_inicio']) || empty($_POST['horario_termino']) || empty($_POST['descricao']) ||
            empty($_POST['endereco']) || empty($_POST['id_evento']) || empty($_POST['quantidade_ingressos'])) {
            echo "Por favor, preencha todos os campos.";
        } else {
            // Obter os valores dos campos do formulário
            $id_evento = $_POST['id_evento'];
            $nome_evento = $_POST['nome_evento'];
            $data_inicio = $_POST['data_inicio'];
            $data_termino = $_POST['data_termino'];
            $horario_inicio = $_POST['horario_inicio'];
            $horario_termino = $_POST['horario_termino'];
            $descricao = $_POST['descricao'];
            $endereco = $_POST['endereco'];
            $quantidade_ingressos = $_POST['quantidade_ingressos'];

            // Atualizar os dados do evento no banco de dados
            $query = "UPDATE eventos SET nome_evento = '$nome_evento', data_inicio_evento = '$data_inicio', 
                      data_final_evento = '$data_termino', horario_inicial = '$horario_inicio', 
                      horario_final = '$horario_termino', descricao = '$descricao', endereco = '$endereco' 
                      WHERE idevento = $id_evento";

            if (mysqli_query($conexao, $query)) {
                // Atualizar a quantidade de ingressos no banco de dados
                $query_ingresso = "UPDATE ingresso SET quantidade = $quantidade_ingressos WHERE idevento = $id_evento";

                if (mysqli_query($conexao, $query_ingresso)) {
                    echo "Evento e quantidade de ingressos atualizados com sucesso!";
                } else {
                    echo "Erro ao atualizar a quantidade de ingressos: " . mysqli_error($conexao);
                }
            } else {
                echo "Erro ao atualizar o evento: " . mysqli_error($conexao);
            }
        }
    } else {
        // Verificar se foi fornecido o parâmetro de ID do evento
        if (isset($_GET['id'])) {
            // Obter o ID do evento a partir do parâmetro da URL
            $id_evento = $_GET['id'];

            // Consultar o evento no banco de dados
            $query_evento = "SELECT * FROM eventos WHERE idevento = $id_evento";
            $resultado_evento = mysqli_query($conexao, $query_evento);
            $dados_evento = mysqli_fetch_assoc($resultado_evento);

            if ($dados_evento) {
                // Consultar a quantidade de ingressos do evento
                $query_ingresso = "SELECT quantidade FROM ingresso WHERE idevento = $id_evento";
                $resultado_ingresso = mysqli_query($conexao, $query_ingresso);
                $dados_ingresso = mysqli_fetch_assoc($resultado_ingresso);

                // Exibir o formulário de edição do evento com os dados preenchidos
                ?>

                <form action="editar_evento.php" method="POST">
                    <input type="hidden" name="id_evento" value="<?php echo $id_evento; ?>">

                    <label for="nome_evento">Nome do Evento:</label>
                    <input type="text" name="nome_evento" value="<?php echo $dados_evento['nome_evento']; ?>"><br><br>

                    <label for="data_inicio">Data de Início:</label>
                    <input type="date" name="data_inicio" value="<?php echo $dados_evento['data_inicio_evento']; ?>"><br><br>

                    <label for="data_termino">Data de Término:</label>
                    <input type="date" name="data_termino" value="<?php echo $dados_evento['data_final_evento']; ?>"><br><br>

                    <label for="horario_inicio">Horário de Início:</label>
                    <input type="time" name="horario_inicio" value="<?php echo $dados_evento['horario_inicial']; ?>"><br><br>

                    <label for="horario_termino">Horário de Término:</label>
                    <input type="time" name="horario_termino" value="<?php echo $dados_evento['horario_final']; ?>"><br><br>

                    <label for="descricao">Descrição:</label>
                    <textarea name="descricao"><?php echo $dados_evento['descricao']; ?></textarea><br><br>

                    <label for="endereco">Endereço:</label>
                    <input type="text" name="endereco" value="<?php echo $dados_evento['endereco']; ?>"><br><br>

                    <label for="quantidade_ingressos">Quantidade de Ingressos:</label>
                    <input type="number" name="quantidade_ingressos" value="<?php echo $dados_ingresso['quantidade']; ?>"><br><br>

                    <button type="submit">Salvar</button>
                </form>

                <?php
            } else {
                echo "Evento não encontrado.";
            }
        } else {
            echo "Evento não especificado.";
        }
    }
    ?>

</body>
</html>