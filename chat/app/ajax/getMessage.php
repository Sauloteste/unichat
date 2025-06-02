<?php 

session_start();

// Verifica se o usuário está logado
if (isset($_SESSION['username'])) {

    // Verifica se o id do usuário parceiro foi enviado via POST
	if (isset($_POST['id_2'])) {
	
	    // Inclui arquivo de conexão com o banco de dados
	    include '../db.conn.php';

	    // ID do usuário logado (remetente)
	    $id_1  = $_SESSION['user_id'];
	    // ID do usuário destinatário (parceiro da conversa)
	    $id_2  = $_POST['id_2'];

	    // Query para pegar as mensagens enviadas para o usuário logado vindas do usuário parceiro
	    $sql = "SELECT * FROM chats
	            WHERE to_id=?
	            AND   from_id= ?
	            ORDER BY chat_id ASC";
	    $stmt = $conn->prepare($sql);
	    $stmt->execute([$id_1, $id_2]);

	    if ($stmt->rowCount() > 0) {
	        $chats = $stmt->fetchAll();

	        // Loop para percorrer as mensagens encontradas
	        foreach ($chats as $chat) {
	            // Se a mensagem ainda não foi marcada como 'aberta'
	            if ($chat['opened'] == 0) {
	            	
	            	$opened = 1;
	            	$chat_id = $chat['chat_id'];

	            	// Atualiza o status da mensagem para 'aberta'
	            	$sql2 = "UPDATE chats
	            	         SET opened = ?
	            	         WHERE chat_id = ?";
	            	$stmt2 = $conn->prepare($sql2);
	                $stmt2->execute([$opened, $chat_id]); 

	                // Exibe a mensagem
	                ?>
                    <p class="ltext border 
            		        rounded p-2 mb-1">
            		        <?=$chat['message']?> 
            		        <small class="d-block">
            		        	<?=$chat['created_at']?>
            		        </small>      	
            	    </p>        
	                <?php
	            }
	        }
	    }

	}

} else {
	// Se não estiver logado, redireciona para a página de login
	header("Location: ../../index.php");
	exit;
}
