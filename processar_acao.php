<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processar</title>
    <link rel="stylesheet" href="assets/css/style2.css">
</head>
<body>
    <div class="cabecalho_processar_acao">

        <div class="logo_processar_acao">
        <a href="eventos.php"><img src="assets/imagens/logo_fundo_removido.png" alt="Logo EventFlow" title="Inicio" width="200"></a>
        </div>

        <div class="container_processar_acao">
            <div class="caixa_processar_acao">

                <?php
                include('conexao.php');
                session_start();
                if (!isset($_SESSION['idusuario'])) {
                    header("location: login.php");
                    exit();
                }

                // Obter informações do usuário logado
                $idusuario = $_SESSION['idusuario'];
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Verifique se a ação é adicionar ao carrinho
                    if ($_POST["acao"] == "adicionar_carrinho") {
                        // Obtenha os valores do formulário
                        $id_ingresso = $_POST["id_ingresso"];
                        $id_evento = $_POST["id_evento"];

                        // Verifique se o ingresso existe
                        $query = "SELECT id_ingresso FROM ingresso WHERE id_ingresso = '$id_ingresso'";
                        $result = mysqli_query($conexao, $query);
                        if (mysqli_num_rows($result) > 0) {
                            // Insira os dados no carrinho
                            $sql = "INSERT INTO carrinho (id_ingresso, idusuario) 
                                    VALUES ('$id_ingresso', '$idusuario')";
                            if (mysqli_query($conexao, $sql)) {
                                echo'<center>';
                                echo "Item adicionado ao carrinho com sucesso!","<br>";
                                echo "<a href='eventos.php'><label>Voltar para Eventos</label></a>";
                            } else {
                                echo "Erro ao adicionar item ao carrinho: " . mysqli_error($conexao),"<br>";;
                                echo "<a href='eventos.php'><label>Eventos</label></a>";
                            }
                        } else {
                            echo "O ingresso não existe.","<br>";;
                            echo "<a href='eventos.php'><label>Eventos</label></a>";
                            echo'</center>';
                        }
                        mysqli_close($conexao);
                    }
                }
                ?>
                
            </div> 
        </div>
    </div>
</body>
</html>