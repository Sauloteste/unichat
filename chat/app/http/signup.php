<?php  

# Verifica se os campos nome, usuário e senha foram enviados
if (isset($_POST['username']) &&
    isset($_POST['password']) &&
    isset($_POST['name'])) {

    # Conexão com o banco de dados
    include '../db.conn.php';
    
    # Pega os dados do formulário
    $nome = $_POST['name'];
    $senha = $_POST['password'];
    $usuario = $_POST['username'];

    # Dados para reaproveitar na URL
    $dados = 'name='.$nome.'&username='.$usuario;

    # Validações simples
    if (empty($nome)) {
        $erro = "O nome é obrigatório.";
        header("Location: ../../signup.php?error=$erro");
        exit;
    } else if (empty($usuario)) {
        $erro = "O nome de usuário é obrigatório.";
        header("Location: ../../signup.php?error=$erro&$dados");
        exit;
    } else if (empty($senha)) {
        $erro = "A senha é obrigatória.";
        header("Location: ../../signup.php?error=$erro&$dados");
        exit;
    } else {
        # Verifica se o nome de usuário já está cadastrado
        $sql = "SELECT username FROM users WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$usuario]);

        if ($stmt->rowCount() > 0) {
            $erro = "O nome de usuário ($usuario) já está em uso.";
            header("Location: ../../signup.php?error=$erro&$dados");
            exit;
        } else {
            # Upload da foto de perfil
            if (isset($_FILES['pp'])) {
                $nome_img = $_FILES['pp']['name'];
                $tmp_img = $_FILES['pp']['tmp_name'];
                $erro_img = $_FILES['pp']['error'];

                if ($erro_img === 0) {
                    $ext_img = pathinfo($nome_img, PATHINFO_EXTENSION);
                    $ext_img = strtolower($ext_img);

                    # Extensões permitidas
                    $permitidas = array("jpg", "jpeg", "png");

                    if (in_array($ext_img, $permitidas)) {
                        $novo_nome_img = $usuario . '.' . $ext_img;
                        $caminho_upload = '../../uploads/' . $novo_nome_img;
                        move_uploaded_file($tmp_img, $caminho_upload);
                    } else {
                        $erro = "Você só pode enviar imagens JPG, JPEG ou PNG.";
                        header("Location: ../../signup.php?error=$erro&$dados");
                        exit;
                    }
                }
            }

            # Criptografar a senha
            $senha = password_hash($senha, PASSWORD_DEFAULT);

            # Inserir no banco de dados
            if (isset($novo_nome_img)) {
                $sql = "INSERT INTO users (name, username, password, p_p) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$nome, $usuario, $senha, $novo_nome_img]);
            } else {
                $sql = "INSERT INTO users (name, username, password) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$nome, $usuario, $senha]);
            }

            # Mensagem de sucesso
            $sucesso = "Conta criada com sucesso!";
            header("Location: ../../index.php?success=$sucesso");
            exit;
        }
    }

} else {
    # Redireciona se o acesso não for via POST
    header("Location: ../../signup.php");
    exit;
}
