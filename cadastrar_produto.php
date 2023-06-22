<?php
require_once "conexao.php";

session_start();
if (!isset($_SESSION['idusuario'])) {
    header("location: login.php");
    exit();
}

$idusuario = $_SESSION['idusuario'];
$query_usuario = "SELECT tipo_user FROM usuario WHERE idusuario = $idusuario";
$resultado_usuario = mysqli_query($conexao, $query_usuario);
$row_usuario = mysqli_fetch_assoc($resultado_usuario);
$tipo_usuario = $row_usuario['tipo_user'];

if ($tipo_usuario != 2) {
    header("location: loja.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_evento = $_GET['id'];
} else {
    header("location: loja.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_item = $_POST['nome_item'];
    $descricao_item = $_POST['descricao_item'];
    $quantidade_item = $_POST['quantidade_item'];
    $preco_item = $_POST['preco_item'];

    $query_cadastrar_item = "INSERT INTO iten_loja (nome, descricao, quantidade, valor, idevento)
                            VALUES ('$nome_item', '$descricao_item', $quantidade_item, $preco_item, $id_evento)";

    if (mysqli_query($conexao, $query_cadastrar_item)) {
        header("location: loja.php?id=$id_evento");
        exit();
    } else {
        echo "Erro ao cadastrar o item na loja.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto</title>
    <link rel="stylesheet" href="assets/css/style2.css">
</head>
<body>
    <div class="cabecalho_cadastrar_produto">
            <!-- Logo -->
                <div class="logo_cadastrar_produto">
                <a href="eventos.php"><img src="assets/imagens/logo_fundo_removido.png" alt="Logo EventFlow" title="Início" width="200"></a>
                </div>

            <!-- Botões de Navegação -->
            <nav class="botoes_cadastrar_produto">
                <?php
                if ($tipo_usuario == 2) {
                    echo '<a href="criar_eventos.php"><label>Criar Eventos</label></a>';
                    echo '<a href="eventos_criados.php"><label>Eventos Criados</label></a>';
                } else {
                    echo '<a href="eventos.php"><label>Eventos</label></a>';
                    echo '<a href="meus_eventos.php"><label>Meus Eventos</label></a>';
                }

                echo '<a href="carrinho.php">Carrinho</a>';
                echo '<a href="perfil.php"><label>Perfil</label></a>';
                echo '<a href="EventFlow.php"><label>Logout</label></a>';
                ?>
            </nav>

            <!-- Conteúdo da Página -->
            <div class="container_cadastrar_produto">
                <div class="caixa_cadastrar_produto">
                    <center>
                    <h1 id="nome_cadastrar_produto">Cadastrar Produto</h1><hr>
                    </center>
                    <form action="" method="POST">
                        <label for="nome_item">Nome do Item:</label>
                        <input type="text" name="nome_item" required>
                        <br>
                        <label for="descricao_item">Descrição do Item:</label>
                        <textarea name="descricao_item" rows="4" required style="resize: none"></textarea>
                        <br>
                        <label for="quantidade_item">Quantidade do Item:</label>
                        <input type="number" name="quantidade_item" min="1" required>
                        <br>
                        <label for="preco_item">Preço do Item:</label>
                        <input type="number" name="preco_item" step="0.01" required>
                        <br>
                        <button type="submit">Cadastrar</button>
                    </form>
                    <br>
                    <a href="loja.php?id=<?php echo $id_evento; ?>">Voltar para a Loja</a>
                </div>
            </div>
    </div>       

</body>
</html>