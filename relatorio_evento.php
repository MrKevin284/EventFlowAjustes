<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório do Evento</title>
    <link rel="stylesheet" href="assets/css/style2.css">
</head>

<body>
    <div class="cabecalho_relatorio_evento">

        <div class="logo_relatorio_evento">
            <a href="eventos.php"><img src="assets/imagens/logo_fundo_removido.png" alt="Logo EventFlow" title="Início" width="200"></a>
        </div>

        <div class="container_relatorio_evento">
            <div class="caixa_relatorio_evento">

                <?php
                require_once "conexao.php";

                // Verificar se o ID do evento foi passado via parâmetro
                if (!isset($_GET["idevento"])) {
                    die("Evento não especificado.");
                }

                $idevento = $_GET["idevento"];

                // Obter a quantidade de ingressos vendidos (inteira e meia entrada)
                $sql = "SELECT tipo_ingresso.descricao, IFNULL(COUNT(venda.id_venda), 0) AS total, ingresso.valor
        FROM tipo_ingresso
        LEFT JOIN ingresso ON tipo_ingresso.id_tipoingresso = ingresso.id_tipoingresso
        LEFT JOIN venda ON ingresso.id_ingresso = venda.id_ingresso
        WHERE ingresso.idevento = $idevento
        GROUP BY tipo_ingresso.descricao, ingresso.valor";
                $result = $conexao->query($sql);

                echo"<h2>Relatório de Ingressos Vendidos:</h2>";
                while ($row = $result->fetch_assoc()) {
                    $tipo_ingresso = $row["descricao"];
                    $total = $row["total"];
                    $valor_unitario = $row["valor"];
                    $valor_total = $total * $valor_unitario;
                    echo "Tipo de Ingresso: $tipo_ingresso - Quantidade: $total - Valor Unitário: $valor_unitario - Valor Total: $valor_total<br>";
                }
                echo'<hr>';
                // Obter os produtos da loja, a quantidade vendida de cada um e o valor total
                $sql = "SELECT iten_loja.nome, iten_loja.valor, IFNULL(SUM(venda.quantidade), 0) AS total, IFNULL(SUM(venda.quantidade * iten_loja.valor), 0) AS total_valor
        FROM iten_loja
        LEFT JOIN venda ON iten_loja.nome = venda.nome_item
        WHERE iten_loja.idevento = $idevento
        GROUP BY iten_loja.nome, iten_loja.valor";
                $result = $conexao->query($sql);

                echo "<h2>Relatório de Produtos da Loja:</h2>";
                $valor_total_venda_loja = 0; // Variável para armazenar o valor total arrecadado com as vendas da loja
                while ($row = $result->fetch_assoc()) {
                    $nome_item = $row["nome"];
                    $total = $row["total"];
                    $valor_unitario = $row["valor"];
                    $valor_total = $row["total_valor"];
                    echo "Nome do Item: $nome_item - Quantidade: $total - Valor Unitário: $valor_unitario - Valor Total: $valor_total<br>";
                    $valor_total_venda_loja += $valor_total; // Somando o valor total ao valor total da venda da loja
                }

                // Obter o valor total arrecadado com as vendas de ingresso
                $sql = "SELECT IFNULL(SUM(venda.quantidade * ingresso.valor), 0) AS valor_total_arrecadado
        FROM ingresso
        LEFT JOIN venda ON ingresso.id_ingresso = venda.id_ingresso
        WHERE ingresso.idevento = $idevento";
                $result = $conexao->query($sql);

                $row = $result->fetch_assoc();
                $valor_total_arrecadado = $row["valor_total_arrecadado"];

                echo "<br>Valor Total Arrecadado com as Vendas de Ingresso: $valor_total_arrecadado<br>";
                echo "<br>Valor Total Arrecadado com as Vendas da Loja: $valor_total_venda_loja<br><hr>";

                echo '<div class="relatorio_evento_voltar">';
                echo '<center>';
                echo '<a href="relatorio.php"><button>Voltar</button></a>';
                echo '</center>';
                echo '</div>';
                ?>
            </div>
        </div>
    </div>
</body>

</html>