<?php
    include("config.php");
    session_start();
    $erro = "";

	if(isset($_SESSION["logado"]) && $_SESSION["logado"] === true){
	    header("location: index.php");
	    exit;
	}


   if($_SERVER["REQUEST_METHOD"] == "POST") { 
      $usuario = mysqli_real_escape_string($db,$_POST['usuario']);
      $senha = mysqli_real_escape_string($db,$_POST['senha']); 
      
      $sql = "SELECT idusuario FROM usuarios WHERE usuario = '$usuario' and senha = '$senha'";
      $resultado = mysqli_query($db,$sql);
      $coluna = mysqli_fetch_assoc($resultado);
      $idusuario = current($coluna);

      $count = mysqli_num_rows($resultado);
      if($count == 1) {
      	$_SESSION["logado"] = true;
        $_SESSION["idusuario"] = $idusuario;
        $_SESSION['usuario'] = $usuario;

        $db = mysqli_connect(DB_SERVER,DB_USUARIO,DB_SENHA,DB_DATABASE);
			$resultado = mysqli_query($db,"SELECT idvereador, nome FROM vereadores where idusuario='$idusuario'");
			while($row = mysqli_fetch_array($resultado)) {
				$_SESSION["idvereador"] = $row['idvereador'];
				$_SESSION["nomevereador"] = $row['nome'];
			}
			mysqli_close($db);
        
        header("location: index.php");
      }else {
        $erro = "Usuario ou Senha inválido.";
      }
      
   }
?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<meta name="author" content="Mathias Hillmann">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="/web/projeto/css/estilo.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<title>Teste Prático</title>
	<style type="text/css">
		.container-fluid {
		    height: 100%;
		    overflow-y: hidden;
		}
		form label {
			font-family: tahoma;
		}
	</style>
</head>
<body>
	<div class="container-fluid" style="background-color: GhostWhite;">
		<div class="container h-100 d-flex justify-content-center">
			<div class="jumbotron my-auto" style="background-color: White;">
				<form action="" method="post">
					<img src="img/nova-logo.png" class="img-fluid rounded mx-auto d-block"/><br />
					<div class="form-group">
		                <label for="usuario">Usuario</label>
		                <input type="text" name="usuario" class="form-control" required/>
		            </div>
		            <div class="form-group">
		                <label for="senha">Senha</label>
		                <input type="password" name="senha" class="form-control" required/>
		            </div> 
		            <p><button type="submit" class="btn btn-primary">Entrar</button></p>
		            <div style="font-size:11px; color:#cc0000; margin-top:10px"><?php echo $erro?></div>
	            </form>
        	</div>
		</div>	
	</div>
</body>
<html>