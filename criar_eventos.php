<?php
// Verificar se o usuário está logado e é um usuário empresarial
session_start();
if (!isset($_SESSION['idusuario'])) {
    header("location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Evento</title>
    <link rel="stylesheet" href="assets/css/style2.css">
</head>

<body>
    <div class="cabecalho_criar_eventos">

        <div class="logo_criar_eventos">
            <a href="eventos.php"><img src="assets/imagens/logo_fundo_removido.png" alt="Logo EventFlow" title="Início" width="200"></a>
        </div>

        <nav class="botoes_criar_evento">
            <a href="eventos.php">Eventos</a>
            <a href="eventos_criados.php">Eventos Criados</a>
            <a href="carrinho.php">
                <script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
                <lord-icon src="https://cdn.lordicon.com/slkvcfos.json" trigger="hover" colors="primary:white,secondary:white" style="width:65px;height:65px;top:5px;">
                </lord-icon>
            </a>
            <a href="perfil.php">Perfil</a>
            <a href="EventFlow.php"><button class="Btn">
                    <div class="sign"><svg viewBox="0 0 512 512">
                            <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path>
                        </svg></div>
                    <div class="text">Logout</div>
                </button></a>
        </nav>

        <div class="cabecalho_criar_eventos_2">
            <div class="caixa_criar_eventos">
                <center>
                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div class="informacoes_criar_eventos">
                            <center>
                                <h1 id="nome_criar_evento">Criar Evento</h1>
                                <hr>
                            </center>
                            <label for="nome_evento">Nome do Evento:</label>
                            <input type="text" id="nome_evento" name="nome_evento" required><br>

                            <label for="endereco">Endereço:</label>
                            <input type="text" id="endereco" name="endereco" required><br>

                            <label for="palavra_chave">Palavra-chave:</label>
                            <input type="text" id="palavra_chave" name="palavra_chave" required><br>

                            <label for="descricao">Descrição:</label>
                            <textarea id="descricao" name="descricao" required style="resize: none"></textarea><br>

                            <label for="data_inicio_evento">Data de Início do Evento:</label>
                            <input type="date" id="data_inicio_evento" name="data_inicio_evento" required><br>

                            <label for="data_final_evento">Data de Término do Evento:</label>
                            <input type="date" id="data_final_evento" name="data_final_evento" required><br>

                            <label for="horario_inicial">Horário de Início do Evento:</label>
                            <input type="time" id="horario_inicial" name="horario_inicial" required><br>

                            <label for="horario_final">Horário de Término do Evento:</label>
                            <input type="time" id="horario_final" name="horario_final" required><br>

                            <label for="quantidade_ingressos">Quantidade de Entradas Inteiras Disponíveis:</label>
                            <input type="number" id="quantidade_ingressos" name="quantidade_ingressos" required><br>

                            <label for="quantidade_ingressos_estudante">Quantidade de Entradas Estudantes Disponíveis:</label>
                            <input type="number" id="quantidade_ingressos_estudante" name="quantidade_ingressos_estudante" required><br>

                            <label for="preco_inteira">Preço da Entrada Inteira:</label>
                            <input type="number" id="preco_inteira" name="preco_inteira" step="0.01" required><br>

                        </div>

                        <div class="botao_criar_evento">
                            <button type="submit" value="Criar Evento">Criar Evento</button>
                        </div>
                    </form>

                    <?php

                    $idusuario = $_SESSION['idusuario'];
                    require_once "conexao.php";

                    $query_usuario = "SELECT tipo_user FROM usuario WHERE idusuario = $idusuario";
                    $resultado_usuario = mysqli_query($conexao, $query_usuario);
                    $row_usuario = mysqli_fetch_assoc($resultado_usuario);
                    $tipo_usuario = $row_usuario['tipo_user'];

                    if ($tipo_usuario != 2) {
                        echo "Acesso negado. Esta página é restrita para usuários empresariais.";
                        exit();
                    }

                    // Verificar se o formulário foi submetido
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        // Obter os dados do formulário
                        $nome_evento = $_POST['nome_evento'];
                        $endereco = $_POST['endereco'];
                        $palavra_chave = $_POST['palavra_chave'];
                        $descricao = $_POST['descricao'];
                        $data_inicio_evento = $_POST['data_inicio_evento'];
                        $data_final_evento = $_POST['data_final_evento'];
                        $horario_inicial = $_POST['horario_inicial'];
                        $horario_final = $_POST['horario_final'];
                        $quantidade_ingressos = $_POST['quantidade_ingressos'];
                        $preco_inteira = number_format($_POST['preco_inteira'], 2, '.', '');
                        $quantidade_ingressos_estudante = $_POST['quantidade_ingressos_estudante'];

                        // Inserir o evento no banco de dados
                        $inserir_evento = "INSERT INTO eventos (nome_evento, endereco, palavra_chave, descricao, data_inicio_evento, data_final_evento, horario_inicial, horario_final, idusuario) VALUES ('$nome_evento', '$endereco', '$palavra_chave', '$descricao', '$data_inicio_evento', '$data_final_evento', '$horario_inicial', '$horario_final', $idusuario)";

                        if (mysqli_query($conexao, $inserir_evento)) {
                            $id_evento = mysqli_insert_id($conexao);

                            // Inserir os ingressos disponíveis
                            $inserir_ingressos = "INSERT INTO ingresso (quantidade, valor, id_tipoingresso, idevento) VALUES ($quantidade_ingressos, $preco_inteira, 1, $id_evento)";
                            mysqli_query($conexao, $inserir_ingressos);

                            // Calcular e inserir o preço e a quantidade de ingressos da entrada estudante
                            $preco_estudante = $preco_inteira * 0.5;
                            $inserir_ingresso_estudante = "INSERT INTO ingresso (quantidade, valor, id_tipoingresso, idevento) VALUES ($quantidade_ingressos_estudante, $preco_estudante, 2, $id_evento)";
                            mysqli_query($conexao, $inserir_ingresso_estudante);

                            echo '<p id="nome_evento_criado">Evento criado com sucesso.</p>';

                            echo '<div class="voltar_criar_eventos"><a href="eventos.php">Voltar para a página de eventos</a></div>';
                        } else {
                            echo "Erro ao criar o evento: " . mysqli_error($conexao);
                        }

                        // Fechar a conexão com o banco de dados
                        mysqli_close($conexao);
                    }
                    ?>

                </center>
            </div>
        </div>
    </div>
</body>

</html>