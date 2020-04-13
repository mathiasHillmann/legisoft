<?php
	include("header.php");
?>
<script>
	//jquery para ficar marcado a opção ativa na sidebar
	$(function() {
		$('.sidebar a').click( function() {
	  		$(this).addClass('ativo').siblings().removeClass('ativo');
	  	});
	});
	</script>
</head>
<body>
	<div class="container-fluid px-0">
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		    <a class="navbar-brand" href="index.php"><img src="img/logo-navbar.png" /></a>
		    <ul class="navbar-nav ml-auto nav-flex-icons">
			    <li class="nav-item avatar nav-item dropdown">
			        <div class="dropdown">
			        	<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			        		<img src="img/icon.png" class="rounded-circle z-depth-0" alt="avatar image" height="35">
			        	</a>
			        	<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
			        		<p class="nomeavatar"><?php echo $_SESSION['nomevereador']?></p>
			        		<div class="dropdown-divider"></div>
				        	<a class="dropdown-item" href="logout.php">Sair</a>
				        </div>
			        </div>
			    </li>
		    </ul>
		</nav>
		<div class="sidebar">
		    <a href="projetos.php" title="Projetos" target="conteudo"><i class="fa fa-fw fa-folder-open"></i><p>Projetos</p></a>
		    <a href="sessoes.php" title="Sessões" target="conteudo"><i class="fa fa-fw fa-users"></i><p>Sessões</p></a>
		    <a href="vereadores.php" title="Vereadores" target="conteudo"><i class="fa fa-fw fa-user"></i><p>Vereadores</p></a>
		</div>
		<!-- Todo o conteudo é carregado dentro da iframe, atraves do target dentro da tag A -->
		<iframe name="conteudo">
		</iframe>
	</div>
</body>
</html>