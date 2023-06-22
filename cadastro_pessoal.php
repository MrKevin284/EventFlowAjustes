<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Pessoal</title>
    <link rel="stylesheet" href="assets/css/style.css">
    
</head>
<body>
    
    <div class="container_pessoal">
        <div class="voltar_pessoal">
            <?php
            echo '<a href="tipo_de_usuario.php"><img src="assets/imagens/seta-voltar.png"></a>';
            ?>
        </div>
        <center>

            <div class="nome_pessoal">
                <h2>Usuário Pessoal</h2><hr>
            </div>
        
        <form class="form_pessoal" id="form" action="processa_cadastro.php" method="POST">

            <div class="form_control_pessoal">

                <input type="text" placeholder="Nome" name="nome" required><br>
                <input type="number" placeholder="Telefone" name="telefone" required><br>
                <input type="number" placeholder="CPF" name="cpf_cnpj" required><br>
                <input type="email" placeholder="Email" name="email" required><br>
                <input type="text" placeholder="Senha" name="senha" required><br>
                <input type="hidden" name="tipo_user" value="1">
                
            </div>

            <button type="submit" name="enviar" value="Entrar">Concluir</button>
        </form>
        </center>
    </div>
  
</body>
</html>