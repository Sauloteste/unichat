<?php 

function getConversation($user_id, $conn){
    /**
     * Obtém todas as conversas 
     * do usuário logado
    **/
    $sql = "SELECT * FROM conversations
            WHERE user_1=? OR user_2=?
            ORDER BY conversation_id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $user_id]);

    if($stmt->rowCount() > 0){
        $conversas = $stmt->fetchAll();

        /**
         * Cria um array vazio para 
         * armazenar os dados dos usuários parceiros
        **/
        $dados_usuarios = [];
        
        // Percorre as conversas
        foreach($conversas as $conversa){
            // Verifica se user_1 é o usuário logado
            if ($conversa['user_1'] == $user_id) {
            	$sql2  = "SELECT *
            	          FROM users WHERE user_id=?";
            	$stmt2 = $conn->prepare($sql2);
            	$stmt2->execute([$conversa['user_2']]);
            }else {
            	$sql2  = "SELECT *
            	          FROM users WHERE user_id=?";
            	$stmt2 = $conn->prepare($sql2);
            	$stmt2->execute([$conversa['user_1']]);
            }

            $todosDados = $stmt2->fetchAll();

            // Adiciona o primeiro usuário encontrado no array
            array_push($dados_usuarios, $todosDados[0]);
        }

        return $dados_usuarios;

    }else {
    	// Retorna array vazio se não encontrar conversas
    	return [];
    }  

}
