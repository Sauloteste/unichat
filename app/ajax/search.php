<?php

session_start();

// Verifica se o usuário está logado
if (isset($_SESSION['username'])) {
    // Verifica se a chave de busca foi enviada via POST
    if(isset($_POST['key'])){
       // Inclui arquivo de conexão com banco de dados
	   include '../db.conn.php';

       // Monta o termo para pesquisa LIKE, adicionando % antes e depois da chave
	   $key = "%{$_POST['key']}%";
		
	   // Consulta SQL para buscar usuários

	   $sql = "SELECT * FROM users
	           WHERE username
	           LIKE ? OR name LIKE ?";
       $stmt = $conn->prepare($sql);
       $stmt->execute([$key, $key]);

       if($stmt->rowCount() > 0){ 
         $users = $stmt->fetchAll();

         foreach ($users as $user) {
         	if ($user['user_id'] == $_SESSION['user_id']) continue;
       ?>
       <li class="list-group-item">
		<a href="chat.php?user=<?=$user['username']?>"
		   class="d-flex
		          justify-content-between
		          align-items-center p-2">
			<div class="d-flex
			            align-items-center">

			    <img src="uploads/<?=$user['p_p']?>"
			         class="w-10 rounded-circle">

			    <h3 class="fs-xs m-2">
			    	<?=$user['name']?>
			    </h3>            	
			</div>
		 </a>
	   </li>
       <?php } }else { ?>
         <div class="alert alert-info 
    				 text-center">
		   <i class="fa fa-user-times d-block fs-big"></i>
           O usuário "<?=htmlspecialchars($_POST['key'])?>" não foi encontrado.

		</div>
    <?php }
    }

}else {
	header("Location: ../../index.php");
	exit;
}