<?php
include 'conexao.php';

session_start();
if (!isset($_SESSION['idusuario'])) {
    header('Location: login.php');
    exit();
}

$idUsuario = $_SESSION['idusuario'];

$query = "SELECT u.nome, u.cpf_cnpj, u.telefone, tu.id_funcao, u.empresa, l.email FROM usuario u
          INNER JOIN tipo_usuario tu ON u.tipo_user = tu.id_funcao
          INNER JOIN login l ON u.idusuario = l.idusuario
          WHERE u.idusuario = ?";
$stmt = $conexao->prepare($query);
$stmt->bind_param('i', $idUsuario);
$stmt->execute();
$resultado = $stmt->get_result();
$dadosUsuario = $resultado->fetch_assoc();

if (!$dadosUsuario) {
    header('Location: login.php');
    exit();
}

$nomeUsuario = $dadosUsuario['nome'];
$tipoUsuario = $dadosUsuario['id_funcao'];
$cpfCnpjUsuario = $dadosUsuario['cpf_cnpj'];
$telefoneUsuario = $dadosUsuario['telefone'];
$empresaUsuario = $dadosUsuario['empresa'];
$emailUsuario = $dadosUsuario['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receber os dados do formulário
    $nome = $_POST['nome'];
    $cpfCnpj = $_POST['cpf_cnpj'];
    $telefone = $_POST['telefone'];
    $empresa = $_POST['empresa'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Atualizar os dados do usuário no banco de dados
    $query = "UPDATE usuario SET nome = ?, cpf_cnpj = ?, telefone = ?, empresa = ? WHERE idusuario = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param('ssssi', $nome, $cpfCnpj, $telefone, $empresa, $idUsuario);
    $stmt->execute();

    // Atualizar o email e a senha do usuário na tabela de login
    $query = "UPDATE login SET email = ?, senha = ? WHERE idusuario = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param('ssi', $email, $senha, $idUsuario);
    $stmt->execute();

    // Redirecionar para a página de perfil após a atualização
    header('Location: perfil.php');
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="assets/css/style2.css">
</head>

<body>
    <div class="container_editar_perfil">
        <div class="cabecalho">

            <div class="logo_perfil">
                <a href="eventos.php"><img src="assets/imagens/logo_fundo_removido.png" alt="Logo EventFlow" title="Início" width="200"></a>
            </div>

            <nav class="botoes_editar_perfil">
                <?php if ($tipoUsuario == 1) { ?>
                    <a href="eventos.php"><label>Eventos</label></a>
                    <a href="eventos_criados.php"><label>Meus Ingressos</label></a>
                    <a href="carrinho.php"><script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
                    <lord-icon
                        src="https://cdn.lordicon.com/slkvcfos.json"
                        trigger="hover"
                        colors="primary:white,secondary:white"
                        style="width:65px;height:65px;top:5px;">
                    </lord-icon></a>
                    <a href="EventFlow.php"><button class="Btn">
                            <div class="sign"><svg viewBox="0 0 512 512">
                                    <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path>
                                </svg></div>
                            <div class="text">Logout</div>
                        </button></a>
                <?php } elseif ($tipoUsuario == 2) { ?>
                    <a href="eventos.php"><label>Eventos</label></a>
                    <a href="eventos_criados.php"><label>Eventos Criados</label></a>
                    <a href="criar_eventos.php"><label>Criar Evento</label></a>
                    <a href="carrinho.php">
                        <script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
                        <lord-icon src="https://cdn.lordicon.com/slkvcfos.json" trigger="hover" colors="primary:white,secondary:white" style="width:65px;height:65px;top:5px;">
                        </lord-icon>
                    </a>
                    <a href="EventFlow.php"><button class="Btn">
                            <div class="sign"><svg viewBox="0 0 512 512">
                                    <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path>
                                </svg></div>
                            <div class="text">Logout</div>
                        </button></a>
                <?php } ?>
            </nav>
        </div>

        <div class="container_editar_2">
            <div class="caixa_editar_perfil">
                <center>
                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

                        <div class="editar_informacoes_perfil">

                            <h2 id="nome_editar_informacoes_perfil">Editar Informações do Perfil</h2>
                            <hr>
                            <label>Nome:</label>
                            <input type="text" name="nome" value="<?php echo $nomeUsuario; ?>" required><br>
                            <label>
                                <?php
                                if ($tipoUsuario == 1) {
                                    echo "CPF:";
                                } elseif ($tipoUsuario == 2) {
                                    echo "CNPJ:";
                                }
                                ?>
                            </label>
                            <input type="number" name="cpf_cnpj" value="<?php echo $cpfCnpjUsuario; ?>" required><br>
                            <label>Telefone:</label>
                            <input type="number" name="telefone" value="<?php echo $telefoneUsuario; ?>" required><br>
                            <?php if ($tipoUsuario == 2) { ?>
                                <label>Empresa:</label>
                                <input type="text" name="empresa" value="<?php echo $empresaUsuario; ?>" required><br>
                            <?php } ?>
                            <label>Email:</label>
                            <input type="email" name="email" value="<?php echo $emailUsuario; ?>" required><br>
                            <label>Senha:</label>
                            <input type="password" name="senha" required><br>
                        </div>

                        <div class="salvar_voltar">
                            <button type="submit">Salvar</button>
                            <a href="perfil.php">
                                <button type="button">Voltar</button>
                            </a>
                        </div>

                    </form>
                </center>

            </div>
        </div>

    </div>
</body>

</html>