<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hitórico Eventos</title>
    <link rel="stylesheet" href="assets/css/style2.css">
</head>
<body>
    <div class="cabecalho_historico_ingressos">

        <div class="logo_historico_ingressos">
        <a href="eventos.php"><img src="assets/imagens/logo_fundo_removido.png" alt="Logo EventFlow" title="Início" width="200"></a>
        </div>

        <div class="container_historico_ingressos">
            <div class="caixa_historico_ingressos">

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

                // Query para obter as compras de ingressos do usuário logado
                $query = "SELECT venda.nome_item, venda.cod_ingressos, eventos.nome_evento
                        FROM venda
                        INNER JOIN ingresso ON venda.id_ingresso = ingresso.id_ingresso
                        INNER JOIN eventos ON ingresso.idevento = eventos.idevento
                        WHERE venda.idusuario = $idUsuario";



                // Executa a query
                $resultado = mysqli_query($conexao, $query);
                
                // Verifique se a consulta retornou resultados
                if (mysqli_num_rows($resultado) > 0) {
                    // Loop pelos resultados e exibição das informações
                    while ($row = mysqli_fetch_assoc($resultado)) {
                        echo "Nome do evento: " . $row['nome_evento'] . "<br>";
                        echo "Código do ingresso: " . $row['cod_ingressos'] . "<br>","<hr>";
                        echo "<br>";
                    }
                    
                } else {
                    echo'<center>';
                    echo "Nenhuma compra de ingresso encontrada.";
                    
                }
                echo'<a href="eventos.php">Voltar para Eventos</a>';
                echo'</center>';
                // Feche a conexão com o banco de dados
                mysqli_close($conexao);
                ?>
            </div>
        </div>
    </div>
</body> 
</html> 