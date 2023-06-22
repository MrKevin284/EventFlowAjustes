<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico</title>
    <link rel="stylesheet" href="assets/css/style2.css">
</head>
<body>
    <div class="cabecalho_historico">

        <div class="logo_historico">
        <a href="eventos.php"><img src="assets/imagens/logo_fundo_removido.png" alt="Logo EventFlow" title="Início" width="200"></a>
        </div>

        <div class="container_historico">
            <div class="caixa_historico">

                <?php
                // Inclua o arquivo de conexão com o banco de dados
                require_once 'conexao.php';

                // Inicie a sessão
                session_start();

                // Verifique se o usuário está logado
                if (!isset($_SESSION['idusuario'])) {
                    // Redirecione para a página de login ou exiba uma mensagem de erro
                    header('Location: login.php');
                    exit();
                }

                // Obtenha a ID do usuário logado
                $idUsuario = $_SESSION['idusuario'];

                // Query para obter as compras do usuário logado na tabela 'venda'
                $query = "SELECT * FROM venda WHERE idusuario = $idUsuario";

                // Executa a query
                $resultado = mysqli_query($conexao, $query);
                echo'<div class="caixa_historio">';
                // Verifique se a consulta retornou resultados
                if (mysqli_num_rows($resultado) > 0) {
                    // Loop pelos resultados e exibição dos itens comprados
                    while ($row = mysqli_fetch_assoc($resultado)) {
                        echo "Item comprado: " . $row['nome_item'] . "<br>";
                        echo "Quantidade: " . $row['quantidade'] . "<br>";
                        echo "Preço unitário: " . $row['preco_unitario'] . "<br>","<hr>"; 
                        echo "<br>";
                    }
                    echo'<center>';
                } else {
                    echo "Nenhum item comprado encontrado.";
                    echo'<a href="eventos.php">Voltar para Eventos</a>';
                }
                echo'<a href="eventos.php">Voltar para Eventos</a>';
                echo'</center>';
                echo'</div>';
                // Feche a conexão com o banco de dados
                mysqli_close($conexao);
                ?>
            </div>
        </div>
    </div> 
</body>
</html> 