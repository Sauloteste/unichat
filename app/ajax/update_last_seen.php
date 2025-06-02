<?php  

session_start();

// Verifica se o usuário está logado
if (isset($_SESSION['username'])) {
	
	// Inclui arquivo de conexão com o banco de dados
	include '../db.conn.php';

	// Pega o ID do usuário logado da sessão
	$id = $_SESSION['user_id'];

	// Atualiza o campo last_seen com o horário atual
	$sql = "UPDATE users
	        SET last_seen = NOW() 
	        WHERE user_id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

} else {
	// Redireciona para a página de login se não estiver logado
	header("Location: ../../index.php");
	exit;
}
