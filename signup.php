<?php 
  session_start();

  if (!isset($_SESSION['username'])) {
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Aplicativo de Chat - Cadastro</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/style.css">
	<link rel="icon" href="img/logo.png">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
	 <div class="w-400 p-5 shadow rounded">
	 	<form method="post" action="app/http/signup.php" enctype="multipart/form-data">
	 		<div class="d-flex justify-content-center align-items-center flex-column">
	 			<i
