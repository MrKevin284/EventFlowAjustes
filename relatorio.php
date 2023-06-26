<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório</title>
    <link rel="stylesheet" href="assets/css/style2.css">
</head>

<body>
    <div class="cabecalho_relatorio">

        <div class="logo_relatorio">
            <a href="eventos.php"><img src="assets/imagens/logo_fundo_removido.png" alt="Logo EventFlow" title="Início" width="200"></a>
        </div>

        <div class="container_relatorio">
            <div class="caixa_relatorio">

                <?php
                require_once "conexao.php";

                // Identificar o ID do usuário
                session_start();
                if (!isset($_SESSION["idusuario"])) {
                    die("Usuário não autenticado.");
                }

                $idusuario = $_SESSION["idusuario"];

                // Verificar se é um usuário empresarial
                $sql = "SELECT tipo_user FROM usuario WHERE idusuario = $idusuario";
                $result = $conexao->query($sql);

                if ($result->num_rows == 1) {
                    $row = $result->fetch_assoc();
                    $tipo_usuario = $row["tipo_user"];

                    if ($tipo_usuario != 2) {
                        die("Acesso não autorizado. Somente usuários empresariais podem acessar este relatório.");
                    }
                } else {
                    die("Usuário não encontrado.");
                }

                // Obter os eventos criados pelo usuário
                $sql = "SELECT idevento, nome_evento FROM eventos WHERE idusuario = $idusuario";
                $result = $conexao->query($sql);

                if ($result->num_rows == 0) {
                    echo "Nenhum evento criado.";
                } else {
                    // Exibir os eventos
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="relatorio_info">';
                        $idevento = $row["idevento"];
                        $nome_evento = $row["nome_evento"];
                        echo "<a href='relatorio_evento.php?idevento=$idevento'>$nome_evento</a><br><hr>";
                        echo '</div>';
                    }
                }
                echo '<div class="relatorio_voltar">';
                echo '<center>';
                echo '<a href="eventos_criados.php"><button>Voltar</button></a>';
                echo '</center>';
                echo '</div>';
                ?>
            </div>
        </div>
    </div>
</body>

</html>