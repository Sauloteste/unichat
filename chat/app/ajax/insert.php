<?php 

session_start();

// Verifica se o usuário está logado
if (isset($_SESSION['username'])) {

    // Verifica se a mensagem e o id do destinatário foram enviados via POST
    if (isset($_POST['message']) && isset($_POST['to_id'])) {
	
        // Inclui o arquivo de conexão com o banco de dados
        include '../db.conn.php';

        // Recebe a mensagem enviada e o id do destinatário
        $message = $_POST['message'];
        $to_id = $_POST['to_id'];

        // Pega o id do usuário logado da sessão (remetente)
        $from_id = $_SESSION['user_id'];

        // Insere a nova mensagem na tabela 'chats'
        $sql = "INSERT INTO chats (from_id, to_id, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $res  = $stmt->execute([$from_id, $to_id, $message]);
        
        // Se a mensagem foi inserida com sucesso
        if ($res) {
            /**
             * Verifica se já existe uma conversa entre esses dois usuários
             */
            $sql2 = "SELECT * FROM conversations
                     WHERE (user_1=? AND user_2=?)
                     OR    (user_2=? AND user_1=?)";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->execute([$from_id, $to_id, $from_id, $to_id]);

            // Define o fuso horário para o horário de Brasília (Brasil)
            define('TIMEZONE', 'America/Sao_Paulo');
            date_default_timezone_set(TIMEZONE);

            // Pega o horário atual formatado
            $time = date("h:i:s a");

            // Se não existir conversa, cria um novo registro na tabela 'conversations'
            if ($stmt2->rowCount() == 0 ) {
                $sql3 = "INSERT INTO conversations(user_1, user_2) VALUES (?, ?)";
                $stmt3 = $conn->prepare($sql3); 
                $stmt3->execute([$from_id, $to_id]);
            }
            ?>

            <!-- Exibe a mensagem enviada formatada em HTML -->
            <p class="rtext align-self-end border rounded p-2 mb-1">
                <?=$message?>  
                <small class="d-block"><?=$time?></small>      	
            </p>

        <?php 
        }
    }
} else {
    // Se o usuário não estiver logado, redireciona para a página de login
    header("Location: ../../index.php");
    exit;
}
