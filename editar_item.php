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
    $id_item = $_GET['id'];
} else {
    header("location: loja.php");
    exit();
}

$query_item = "SELECT * FROM iten_loja WHERE iditem_loja = $id_item";
$resultado_item = mysqli_query($conexao, $query_item);
$row_item = mysqli_fetch_assoc($resultado_item);

if (!$row_item) {
    header("location: loja.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_item = $_POST['nome_item'];
    $descricao_item = $_POST['descricao_item'];
    $quantidade_item = $_POST['quantidade_item'];
    $preco_item = $_POST['preco_item'];

    $query_atualizar_item = "UPDATE iten_loja SET nome = '$nome_item', descricao = '$descricao_item',
                             quantidade = $quantidade_item, valor = $preco_item WHERE iditem_loja = $id_item";

    if (mysqli_query($conexao, $query_atualizar_item)) {
        header("location: loja.php?id=" . $row_item['idevento']);
        exit();
    } else {
        echo "Erro ao atualizar o item na loja.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Item</title>
    <link rel="stylesheet" href="assets/css/style2.css">
</head>

<body>

    <div class="cabecalho_editar_item">
        <!-- Logo -->
        <div class="logo_editar_item">
            <a href="eventos.php"><img src="assets/imagens/logo_fundo_removido.png" alt="Logo EventFlow" title="Inicio" width="200"></a>
        </div>

        <!-- Botões de Navegação -->
        <nav class="botoes_editar_item">
            <?php
            if ($tipo_usuario == 2) {
                echo '<a href="criar_eventos.php"><label>Criar Eventos</label></a>';
                echo '<a href="eventos_criados.php"><label>Eventos Criados</label></a>';
            } else {
                echo '<a href="eventos.php"><label>Eventos</label></a>';
                echo '<a href="meus_eventos.php"><label>Meus Eventos</label></a>';
            }

            echo '<a href="carrinho.php"><script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
        <lord-icon
            src="https://cdn.lordicon.com/slkvcfos.json"
            trigger="hover"
            colors="primary:white,secondary:white"
            style="width:65px;height:65px;top:5px;">
        </lord-icon></a>';
            echo '<a href="perfil.php"><label>Perfil</label></a>';
            echo '<a href="EventFlow.php"><button class="Btn">
        <div class="sign"><svg viewBox="0 0 512 512">
        <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path></svg></div>
        <div class="text">Logout</div></button></a>';
            ?>
        </nav>

        <div class="container_editar_item">

            <div class="caixa_editar_item">
                <center>
                    <h1>Editar Item</h1>
                    <hr>
                </center>
                <form method="POST" action="">
                    <label for="nome_item">Nome:</label>
                    <input type="text" name="nome_item" value="<?php echo $row_item['nome']; ?>" required><br>
                    <label for="descricao_item">Descrição:</label>
                    <textarea name="descricao_item" rows="3" required style="resize: none"><?php echo $row_item['descricao']; ?></textarea><br>
                    <label for="quantidade_item">Quantidade:</label>
                    <input type="number" name="quantidade_item" min="1" value="<?php echo $row_item['quantidade']; ?>" required><br>
                    <label for="preco_item">Preço:</label>
                    <input type="number" name="preco_item" step="0.01" value="<?php echo $row_item['valor']; ?>" required><br>
                    <button type="submit" value="Atualizar">Atualizar</button>
                </form>
                <br>
                <center>
                    <a href="loja.php?id=<?php echo $row_item['idevento']; ?>">Voltar para a Loja</a>
                </center>
            </div>
        </div>
    </div>

    <script>
        // Verificação de atualizações pendentes ao fechar a página
        window.addEventListener('beforeunload', function(e) {
            const form = document.querySelector('form');
            if (form && form.checkDirty && form.checkDirty()) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // Função para verificar se há campos alterados no formulário
        document.querySelector('form').checkDirty = function() {
            const inputs = document.querySelectorAll('form input, form textarea');
            for (let i = 0; i < inputs.length; i++) {
                if (inputs[i].value !== inputs[i].defaultValue) {
                    return true;
                }
            }
            return false;
        };
    </script>

</body>

</html>