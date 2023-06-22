<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Empresarial</title>
    <link rel="stylesheet" href="assets/css/style.css">
    
</head>
<body>
    
    <div class="container_empresarial">
        <div class="voltar_empresarial">
            <?php
            echo '<a href="tipo_de_usuario.php"><img src="assets/imagens/seta-voltar.png"></a>';
            ?>
        </div>
        <center>

            <div class="nome_empresarial">
                <h2>Usuário Empresarial</h2><hr>
            </div>
        
        <form class="form_empresarial" id="form" action="processa_cadastro.php" method="POST">

            <div class="form_control_empresarial">

                <input type="text" placeholder="Nome" name="nome" required>
                <input type="number" placeholder="CNPJ" name="cpf_cnpj" required>
                <input type="number" placeholder="Telefone" name="telefone" required>
                <input type="text" placeholder="Empresa" name="empresa" required>
                <input type="email" placeholder="Email" name="email" required>
                <input type="text" placeholder="Senha" name="senha" required>
                <input type="hidden" name="tipo_user" value="2"><br>
                
            </div>

            <button type="submit" name="enviar" value="Entrar">Concluir</button>
        </form>
        </center>
    </div>

</body>
</html>