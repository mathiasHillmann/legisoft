<?php
	include("header.php");

	#O uso de elseif nesse if faz com que o iframe fique em branco após rodar o post, então separei em três if.
	if(isset($_POST['criar'])) { 
		$db = mysqli_connect(DB_SERVER,DB_USUARIO,DB_SENHA,DB_DATABASE);
        $tipo = mysqli_real_escape_string($db,$_POST['tipo']); 
        $ano = mysqli_real_escape_string($db,$_POST['ano']);
        $numero = mysqli_real_escape_string($db,$_POST['numero']);
      
        $sql = "INSERT INTO projetos (idprojeto, idvereador, numero ,tipo, ano) VALUES (null, '".$_SESSION['idvereador']."', '$numero', '$tipo', '$ano' )";
        $resultado = mysqli_query($db,$sql);

    } 
    if (isset($_POST['alterar'])) {
    	$db = mysqli_connect(DB_SERVER,DB_USUARIO,DB_SENHA,DB_DATABASE);
    	$codigo = mysqli_real_escape_string($db,$_POST['codigo']);
    	$tipo = mysqli_real_escape_string($db,$_POST['tipo']); 
        $ano = mysqli_real_escape_string($db,$_POST['ano']);
        $autor = mysqli_real_escape_string($db,$_POST['autor']);
        $numero = mysqli_real_escape_string($db,$_POST['numero']);

        $idvereadorquery = mysqli_query($db,"SELECT idvereador FROM vereadores WHERE nome = '$autor'");
        $coluna = mysqli_fetch_assoc($idvereadorquery);
        $idvereador = current($coluna);

        $sql = "UPDATE projetos SET idvereador='$idvereador',  numero='$numero', tipo='$tipo', ano='$ano' WHERE idprojeto='$codigo'";
        $resultado = mysqli_query($db,$sql);

    } 
    if (isset($_POST['excluir'])) {
    	$db = mysqli_connect(DB_SERVER,DB_USUARIO,DB_SENHA,DB_DATABASE);
    	$codigo = mysqli_real_escape_string($db,$_POST['codigo']);

        $sql = "DELETE FROM projetos WHERE idprojeto='$codigo'";
        $resultado = mysqli_query($db,$sql);
    }
?>
</head>
<body>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
	    	<li class="breadcrumb-item"><a href="index.php" target="_parent">Início</a></li>
	    	<li class="breadcrumb-item active" aria-current="page">Projetos</li>
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
						<th scope='col'>Número</th>
						<th scope='col'>Tipo</th>
						<th scope='col'>Autor</th>
						<th scope='col'>Ano</th>
						<th scope='col'></th>
					</tr>
				</thead>
				<tbody>";
		$resultado = mysqli_query($db,"SELECT pj.idprojeto as idprojeto, pj.numero as numero, pj.tipo as tipo, pj.idvereador as idvereador, vd.nome as nome, pj.ano as ano FROM projetos pj join vereadores vd on vd.idvereador = pj.idvereador WHERE pj.idvereador=".$_SESSION['idvereador']."");
		echo mysqli_error($db);
		while($coluna = mysqli_fetch_array($resultado)) {
			echo "<tr>";
			echo "<td>" . $coluna['idprojeto'] . "</td>";
			echo "<td>" . $coluna['numero'] . "</td>";
			echo "<td>" . $coluna['tipo'] . "</td>";
			echo "<td>" . $coluna['nome'] . "</td>";
			echo "<td>" . $coluna['ano'] . "</td>";
			# Alimentando o código, nome e partido dentro da tag A para depois alimentar via JavaScript o modal de alterar e deletar
			echo "<td>
			<a class='fa fa-fw fa-pencil' data-toggle='modal' data-target='#alterar' data-id='".$coluna['idprojeto']."' data-numero='".$coluna['numero']."' data-tipo='".$coluna['tipo']."'  data-ano='".$coluna['ano']."' onclick='projetoAlterar(this)'></a>
			<a class='fa fa-fw fa-trash' data-toggle='modal' data-target='#excluir' data-id='".$coluna['idprojeto']."' data-numero='".$coluna['numero']."' data-tipo='".$coluna['tipo']."'  data-ano='".$coluna['ano']."' onclick='projetoExcluir(this)'></a>
			</td>";
			echo "</tr>";
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
			                <label for="numero">Número</label>
			                <input type="text" name="numero" id="numerocriar" class="form-control" required/>
			            </div>
			          	<div class="form-group">
			                <label for="tipo">Tipo</label>
			                <input type="text" name="tipo" id="tipocriar" class="form-control" required/>
			            </div>
			            <div class="form-group">
			                <label for="autor">Autor</label>
			                <input type="text" name="autor" id="autorcriar" class="form-control" <?php echo "value='".$_SESSION['nomevereador']."'" ?>readonly/>
			            </div>
			            <div class="form-group">
			                <label for="ano">Ano</label>
			                <input type="text" name="ano" id="anocriar" class="form-control" required/>
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
			                <input type="text" name="codigo" id="codigoalterar" class="form-control" readonly/>
			            </div>
			        	<div class="form-group">
			                <label for="numero">Número</label>
			                <input type="text" name="numero" id="numeroalterar" class="form-control" required/>
			            </div>
			          	<div class="form-group">
			                <label for="tipo">Tipo</label>
			                <input type="text" name="tipo" id="tipoalterar" class="form-control" required/>
			            </div>
			            <div class="form-group">
				            <label for="autor">Autor</label>
				            <input list="vereadores" name="autor" id="autoralterar" class="form-control" required/>
				            <datalist id="vereadores">
				             	<?php
				               		# Iniciando outra conexão devido a conexão que vem do header.php foi fechada ao carregar a tabela
				               		$db = mysqli_connect(DB_SERVER,DB_USUARIO,DB_SENHA,DB_DATABASE);
				               		$resultado = mysqli_query($db,"SELECT nome FROM vereadores");
									while($row = mysqli_fetch_array($resultado)) {
										echo "<option value='" . $row['nome'] . "'>";
									}
									mysqli_close($db);
				               	?>
				            </datalist>
			            </div>
			            <div class="form-group">
			                <label for="ano">Ano</label>
			                <input type="text" name="ano" id="anoalterar" class="form-control" required/>
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
			                <input type="text" name="codigo" id="codigoexcluir" class="form-control" readonly/>
			            </div>
			        	<div class="form-group">
			                <label for="numero">Número</label>
			                <input type="text" name="numero" id="numeroexcluir" class="form-control" readonly/>
			            </div>
			          	<div class="form-group">
			                <label for="tipo">Tipo</label>
			                <input type="text" name="tipo" id="tipoexcluir" class="form-control" readonly/>
			            </div>
			            <div class="form-group">
			                <label for="autor">Autor</label>
			                <input type="text" name="autor" id="autorexcluir" class="form-control" <?php echo "value='".$_SESSION['nomevereador']."'" ?>readonly/>
			            </div>
			            <div class="form-group">
			                <label for="ano">Ano</label>
			                <input type="text" name="ano" id="anoexcluir" class="form-control" readonly/>
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