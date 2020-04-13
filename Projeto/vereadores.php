<?php
	include("header.php");

	#O uso de elseif nesse if faz com que o iframe fique em branco após rodar o post, então separei em três if.
	if(isset($_POST['criar'])) { 
		$db = mysqli_connect(DB_SERVER,DB_USUARIO,DB_SENHA,DB_DATABASE);
        $nome = mysqli_real_escape_string($db,$_POST['nome']);
        $partido = mysqli_real_escape_string($db,$_POST['partido']); 
        $usuario = mysqli_real_escape_string($db,$_POST['usuario']);
        $idusuarioquery = mysqli_query($db,"SELECT idusuario FROM usuarios WHERE usuario = '$usuario'");
        $coluna = mysqli_fetch_assoc($idusuarioquery);
        $idusuario = current($coluna);
      
        $sql = "INSERT INTO vereadores (idvereador, nome, partido, idusuario) VALUES (null, '$nome', '$partido', '$idusuario')";
        $resultado = mysqli_query($db,$sql);

    } 
    if (isset($_POST['alterar'])) {
    	$db = mysqli_connect(DB_SERVER,DB_USUARIO,DB_SENHA,DB_DATABASE);
    	$codigo = mysqli_real_escape_string($db,$_POST['codigo']);
    	$nome = mysqli_real_escape_string($db,$_POST['nome']);
        $partido = mysqli_real_escape_string($db,$_POST['partido']);

        $sql = "UPDATE vereadores SET nome='$nome',partido='$partido' WHERE idvereador='$codigo'";
        $resultado = mysqli_query($db,$sql);

    } 
    if (isset($_POST['excluir'])) {
    	$db = mysqli_connect(DB_SERVER,DB_USUARIO,DB_SENHA,DB_DATABASE);
    	$codigo = mysqli_real_escape_string($db,$_POST['codigo']);

        $sql = "DELETE FROM vereadores WHERE idvereador='$codigo'";
        $resultado = mysqli_query($db,$sql);
    }
?>
</head>
<body>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
	    	<li class="breadcrumb-item"><a href="index.php" target="_parent">Início</a></li>
	    	<li class="breadcrumb-item active" aria-current="page">Vereadores</li>
		</ol>
	</nav>
	<div class="controle">
		<button type="button" class="btn btn-primary" data-toggle='modal' data-target='#novo'>Novo</button>
	</div>
	<!-- Carregar a tabela através de PHP -->
	<?php
		echo "<table class='table table-hover sortable'>
				<thead>
					<tr>
						<th scope='col'>#</th>
						<th scope='col'>Vereador</th>
						<th scope='col'>Partido</th>
						<th scope='col'></th>
					</tr>
				</thead>
				<tbody>";
		$resultado = mysqli_query($db,"SELECT idvereador, nome, partido  FROM vereadores");
		while($coluna = mysqli_fetch_array($resultado)) {
			echo "<tr>";
			echo "<td>" . $coluna['idvereador'] . "</td>";
			echo "<td>" . $coluna['nome'] . "</td>";
			echo "<td>" . $coluna['partido'] . "</td>";
			# Alimentando o código, nome e partido dentro da tag A para depois alimentar via JavaScript o modal de alterar e deletar
			# IF para aparecer o campo editar e excluir para o próprio usuário.
			if($_SESSION['idvereador'] == $coluna['idvereador']) {
				echo "<td>
				<a class='fa fa-fw fa-pencil' data-toggle='modal' data-target='#alterar' data-id='".$coluna['idvereador']."' data-nome='".$coluna['nome']."' data-partido='".$coluna['partido']."'onclick='vereadorAlterar(this)'></a>
				<a class='fa fa-fw fa-trash' data-toggle='modal' data-target='#excluir' data-id='".$coluna['idvereador']."' data-nome='".$coluna['nome']."' data-partido='".$coluna['partido']."' onclick='vereadorExcluir(this)'></a>
				</td>";
				echo "</tr>";
			} else {
				echo "</tr>";
			}
		}
		echo "</tbody>
		</table>";
		mysqli_close($db);
	?>
	<div class="modal fade" id="novo" tabindex="-1" role="dialog" aria-labelledby="novo registro" aria-hidden="true">
	    <div class="modal-dialog" role="document">
		    <div class="modal-content">
		    	<form action="" method="post">
			        <div class="modal-header">
			            <h5 class="modal-title" id="novo">Novo Registro</h5>
			            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			            	<span aria-hidden="true">&times;</span>
			            </button>
			        </div>
			        <div class="modal-body">
			        	<div class="form-group">
			                <label for="nome">Nome Completo</label>
			                <input type="text" name="nome" id="nomecriar" class="form-control" required/>
			            </div>
			            <div class="form-group">
			                <label for="partido">Partido</label>
			                <input type="text" name="partido" id="partidocriar" class="form-control" required/>
			            </div>
			            <div class="usuario">
			                <label for="usuario">Usuario</label>
			                <input list="usuarios" name="usuario" id="usuariocriar" class="form-control" required/>
			                <datalist id="usuarios">
			                	<?php
			                		# Iniciando outra conexão devido a conexão que vem do header.php foi fechada ao carregar a tabela
			                		$db = mysqli_connect(DB_SERVER,DB_USUARIO,DB_SENHA,DB_DATABASE);
			                		$resultado = mysqli_query($db,"SELECT usuario FROM usuarios");
									while($row = mysqli_fetch_array($resultado)) {
										echo "<option value='" . $row['usuario'] . "'>";
									}
									mysqli_close($db);
			                	?>
			                </datalist>
			            </div>
			        </div>
			        <div class="modal-footer">
			            <button type="button" class="btn btn-secondary" data-dismiss="modal">Sair</button>
			            <button type="submit" name="criar" class="btn btn-primary">Criar</button>
			        </div>
		        </form>
		    </div>
	    </div>
	</div>
	<div class="modal fade" id="alterar" tabindex="-1" role="dialog" aria-labelledby="alterar registro" aria-hidden="true">
	    <div class="modal-dialog" role="document">
		    <div class="modal-content">
		        <form action="" method="post">
			        <div class="modal-header">
			            <h5 class="modal-title" id="alterar">Alterar Registro</h5>
			            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			            	<span aria-hidden="true">&times;</span>
			            </button>
			        </div>
			        <div class="modal-body">
			        	<div class="form-group">
							<label for="codigo">Código</label>
							<input type="text" name="codigo" class="form-control" id="codigoalterar" readonly/>
						</div>
						<div class="form-group">
							<label for="nome">Nome Completo</label>
							<input type="text" name="nome" class="form-control" id="nomealterar" required/>
						</div>
						<div class="form-group">
							<label for="partido">Partido</label>
							<input type="text" name="partido" class="form-control" id="partidoalterar" required/>
						</div>
			        </div>
			        <div class="modal-footer">
			            <button type="button" class="btn btn-secondary" data-dismiss="modal">Sair</button>
			            <button type="submit" name="alterar" class="btn btn-primary">Alterar</button>
			        </div>
		    	</form>
		    </div>
	    </div>
	</div>
	<div class="modal fade" id="excluir" tabindex="-1" role="dialog" aria-labelledby="excluir registro" aria-hidden="true">
	    <div class="modal-dialog" role="document">
		    <div class="modal-content">
		        <form action="" method="post">
			        <div class="modal-header">
			            <h5 class="modal-title" id="excluir">Excluir Registro</h5>
			            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			            	<span aria-hidden="true">&times;</span>
			            </button>
			        </div>
			        <div class="modal-body">
			        	<div class="form-group">
							<label for="codigo">Código</label>
							<input type="text" name="codigo" class="form-control" id="codigoexcluir" readonly/>
						</div>
						<div class="form-group">
							<label for="nome">Nome Completo</label>
							<input type="text" name="nome" class="form-control" id="nomeexcluir" readonly/>
						</div>
						<div class="form-group">
							<label for="partido">Partido</label>
							<input type="text" name="partido" class="form-control" id="partidoexcluir" readonly/>
						</div>
			        </div>
			        <div class="modal-footer">
			            <button type="button" class="btn btn-secondary" data-dismiss="modal">Sair</button>
			            <button type="submit" name="excluir" class="btn btn-danger">Excluir</button>
			        </div>
		    	</form>
		    </div>
	    </div>
	</div>
</body>
</html>