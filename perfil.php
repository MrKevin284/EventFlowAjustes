<?php
include 'conexao.php';

session_start();
if (!isset($_SESSION['idusuario'])) {
    header('Location: login.php');
    exit();
}

$idUsuario = $_SESSION['idusuario'];

$query = "SELECT u.nome, u.cpf_cnpj, u.telefone, l.email, tu.id_funcao, u.empresa FROM usuario u
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
$emailUsuario = $dadosUsuario['email'];
$empresaUsuario = $dadosUsuario['empresa'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Perfil</title>
    <link rel="stylesheet" href="assets/css/style2.css">
</head>
<body>
    <div class="container_perfil">
        <div class="cabecalho">

        <div class="logo_perfil">
        <a href="eventos.php"><img src="assets/imagens/logo_fundo_removido.png" alt="Logo EventFlow" title="Início" width="200"></a>
        </div>
            
            <nav class="botoes_perfil">
            <?php if ($tipoUsuario == 1) { ?>
                    <a href="eventos.php"><label>Eventos</label></a>
                    <a href="eventos_criados.php"><label>Meus Ingressos</label></a>
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
        

        <center>
        <div class="container_perfil_2">
            <div class="informacoes_perfil">
                <h2 id="nome_informacoes_do_perfil">Informações do Perfil</h2><hr>
            
                <div class="dados_perfil">
                    <label>Nome: </label>
                    <span><?php echo $nomeUsuario; ?></span><br>
                    <label>
                        <?php
                        if ($tipoUsuario == 1) {
                            echo "CPF:";
                        } elseif ($tipoUsuario == 2) {
                            echo "CNPJ:";
                        }
                        ?>
                    </label>
                    <span><?php echo $cpfCnpjUsuario; ?></span><br>

                    <label>Telefone: </label>
                    <span><?php echo $telefoneUsuario; ?></span><br>

                    <label>Email: </label>
                    <span><?php echo $emailUsuario; ?></span><br>

                    <?php if ($tipoUsuario == 2) { ?>
                        <label>Empresa: </label>
                    <span><?php echo $empresaUsuario; ?></span>
                    <?php } ?>

                    <div class="editar_informações">
                        <a href="editar_perfil.php">Editar Informações</a>
                        <a href="historico.php">Histórico</a>
                    </div>

                </div>
            </div>
        </div>

    </div>            
        </center>
        
</body>
</html>