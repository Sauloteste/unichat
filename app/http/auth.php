<?php  
session_start();

# Verifica se o usuário e a senha foram enviados
if (isset($_POST['username']) && isset($_POST['password'])) {

    # Arquivo de conexão com o banco de dados
    include '../db.conn.php';
    
    # Pega os dados do formulário
    $senha = $_POST['password'];
    $usuario = $_POST['username'];
    
    # Validação simples do formulário
    if (empty($usuario)) {
        # Mensagem de erro
        $erro = "O nome de usuário é obrigatório.";

        # Redireciona para 'index.php' passando a mensagem de erro
        header("Location: ../../index.php?error=$erro");
        exit;
    } else if (empty($senha)) {
        # Mensagem de erro
        $erro = "A senha é obrigatória.";

        # Redireciona para 'index.php' passando a mensagem de erro
        header("Location: ../../index.php?error=$erro");
        exit;
    } else {
        # Consulta no banco para verificar se o usuário existe
        $sql  = "SELECT * FROM users WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$usuario]);

        # Se o usuário existir
        if ($stmt->rowCount() === 1) {
            # Busca os dados do usuário
            $user = $stmt->fetch();

            # Confere se o usuário bate exatamente
            if ($user['username'] === $usuario) {
                # Verifica a senha criptografada
                if (password_verify($senha, $user['password'])) {

                    # Login com sucesso - cria as sessões
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['user_id'] = $user['user_id'];

                    # Redireciona para a página principal
                    header("Location: ../../home.php");
                    exit;

                } else {
                    # Erro: usuário ou senha incorretos
                    $erro = "Usuário ou senha incorretos.";

                    # Redireciona para a página de login com o erro
                    header("Location: ../../index.php?error=$erro");
                    exit;
                }
            } else {
                # Erro: usuário ou senha incorretos
                $erro = "Usuário ou senha incorretos.";

                # Redireciona para a página de login com o erro
                header("Location: ../../index.php?error=$erro");
                exit;
            }
        } else {
            # Caso o usuário não exista, redireciona com erro
            $erro = "Usuário ou senha incorretos.";
            header("Location: ../../index.php?error=$erro");
            exit;
        }
    }
} else {
    # Se a requisição não veio via POST, redireciona para login
    header("Location: ../../index.php");
    exit;
}
