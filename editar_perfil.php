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
                <img src="assets/imagens/logo_fundo_removido.png" alt="Logo EventFlow">
            </div>
                <nav class="botoes_editar_perfil">
                    <?php if ($tipoUsuario == 1) { ?>
                        <a href="eventos.php"><label>Eventos</label></a>
                        <a href="eventos_criados.php"><label>Meus Eventos</label></a>
                        <a href="carrinho.php"><label>Carrinho</label></a>
                        <a href="EventFlow.php"><label>Logout</label></a>
                    <?php } elseif ($tipoUsuario == 2) { ?>
                        <a href="eventos.php"><label>Eventos</label></a>
                        <a href="eventos_criados.php"><label>Eventos Criados</label></a>
                        <a href="carrinho.php">Carrinho</a>
                        <a href="criar_eventos.php"><label>Criar Evento</label></a>
                        <a href="EventFlow.php"><label>Logout</label></a>
                    <?php } ?>
                </nav>
            </div>

            <div class="container_editar_2">
                <div class="caixa_editar_perfil">
                    <center>
                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    
                        <div class="editar_informacoes_perfil">

                            <h2 id="nome_editar_informacoes_perfil">Editar Informações do Perfil</h2>
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


