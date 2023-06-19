<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="assets/css/style.css">
    
</head>
<body>
    <div class="container_login">
        <center>
            <div class="logo_login">
                <a href="EventFlow.php"><img src="assets/imagens/logo_fundo_removido.png" alt="Logo EventFlow" title="Página Inicial"></a>
            </div>

            <div class="nome_login">
                <h2>Login</h2>
            </div>
        </center>
            
        <form class="form_login" id="form" action="autenticar.php" method="POST">

            <div class="form_control_login">

                <input type="email" id="email" placeholder="Email" name="email" required>
                <i class="img-success" ><img src="assets/imagens/success-icon.svg" alt=""></i>
                <i class="img-error" ><img src="assets/imagens/error-icon.svg" alt=""></i>

                <small>Error Message</small>

            </div>

            <div class="form_control_login password-toggle">   
                <div class="senha_olho"> 
                    <input type="password" id="password" placeholder="Senha" name="senha" required>
                    
                    <span class="toggle-icon" onclick="togglePasswordVisibility()">
                        <img src="assets/imagens/mostrar_senha.png" alt="Mostrar Senha" id="password-toggle-icon">
                    </span>
                    
                    <i class="img-success" ><img src="assets/imagens/success-icon.svg" alt=""></i>
                    <i class="img-error" ><img src="assets/imagens/error-icon.svg" alt=""></i>

                    <small>Error Message</small>
                </div>
            </div>

            <button type="submit" name="enviar" value="Entrar">Entrar</button>
           
            <div class="senha_e_conta">
                <?php
                    if (isset($_SESSION['login_erro'])) {
                        echo '<p class="erro">' . $_SESSION['login_erro'] . '</p>';
                        unset($_SESSION['login_erro']);
                    }
                    echo '<a href="esqueci_senha.php"><h2>Esqueci a Senha</h2></a>';
                    echo '<a href="tipo_de_usuario.php"><h2>Criar Conta</h2></a>';
                ?>
            </div>
        </form>
        
    </div>

    <script src="assets/js/index.js"></script>
    
</body>
</html>