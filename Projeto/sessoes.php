<?php
	include("header.php");

	#O uso de elseif nesse if faz com que o iframe fique em branco após rodar o post, então separei em três if.
	if(isset($_POST['criar'])) { 
		$db = mysqli_connect(DB_SERVER,DB_USUARIO,DB_SENHA,DB_DATABASE);
        $titulo = mysqli_real_escape_string($db,$_POST['titulo']); 
        $data = mysqli_real_escape_string($db,$_POST['data']);
        $projeto = mysqli_real_escape_string($db,$_POST['projeto']);

        # Como é array eu estou primeiramente guardando ela suja com caracteres especiais para depois dar escape.
        $vereadorsujo = $_POST['vereador'];
        $vereador = array_map(array($db, 'real_escape_string'), $vereadorsujo);

        $idprojetoquery = mysqli_query($db,"SELECT idprojeto FROM projetos WHERE numero = '$projeto'");
        $colunaprojeto = mysqli_fetch_assoc($idprojetoquery);
        $idprojeto = current($colunaprojeto);

        $sqlsessao = "INSERT INTO sessoes (idsessao, titulo, datasessao ,idprojeto) VALUES (null, '$titulo', '$data', '$idprojeto')";
        $resultadosessao = mysqli_query($db,$sqlsessao);

        if ($resultadosessao) {
		    $ultimo_id = mysqli_insert_id($db);
		    for($i=0;$i<count($vereador);$i++) {
				if($vereador[$i]!="") {
					$idvereadorquery = mysqli_query($db,"SELECT idvereador FROM vereadores WHERE nome like '$vereador[$i]'");
			        $colunavereador = mysqli_fetch_assoc($idvereadorquery);
			        $idvereador = current($colunavereador);

					$sqlvotos = "INSERT INTO votos (idsessao, idvereador, voto) VALUES ('$ultimo_id','$idvereador', null)";
					$resultadovotos = mysqli_query($db,$sqlvotos);	
				}
			}
		} else {
		    echo mysqli_error($db);
		} 
    } 
    if (isset($_POST['alterar'])) {
    	$db = mysqli_connect(DB_SERVER,DB_USUARIO,DB_SENHA,DB_DATABASE);
    	$codigo = mysqli_real_escape_string($db,$_POST['codigo']);
    	$titulo = mysqli_real_escape_string($db,$_POST['titulo']); 
        $data = mysqli_real_escape_string($db,$_POST['data']);
        $projeto = mysqli_real_escape_string($db,$_POST['numero']);

        $idprojetoquery = mysqli_query($db,"SELECT idprojeto FROM projetos WHERE numero = '$projeto'");
        $coluna = mysqli_fetch_assoc($idprojetoquery);
        $idprojeto = current($coluna);

        $sqlsessao = "UPDATE sessoes SET titulo='$titulo',  datasessao='$data', idprojeto='$idprojeto' WHERE idsessao='$codigo'";
        $resultado = mysqli_query($db,$sqlsessao);

        # Como é array eu estou primeiramente guardando ela suja com caracteres especiais para depois dar escape.
        $votosujo = $_POST['voto'];
        $votos = array_map(array($db, 'real_escape_string'), $votosujo);

        $vereadorsujo = $_POST['vereador'];
        $vereador = array_map(array($db, 'real_escape_string'), $vereadorsujo);

        if ($resultado) {
		    for($i=0;$i<count($votos);$i++) {
				if($votos[$i]!="" && $vereador[$i]!="") {
					$idvereadorquery = mysqli_query($db,"SELECT idvereador FROM vereadores WHERE nome like '$vereador[$i]'");
			        $colunavereador = mysqli_fetch_assoc($idvereadorquery);
			        $idvereador = current($colunavereador);

					$sqlvoto = "UPDATE votos SET voto='$votos[$i]' WHERE idsessao='$codigo' AND idvereador='$idvereador'";
					$resultadovotos = mysqli_query($db,$sqlvoto);	
				}
			}
		} else {
		    echo mysqli_error($db);
		} 

    } 
    if (isset($_POST['excluir'])) {
    	$db = mysqli_connect(DB_SERVER,DB_USUARIO,DB_SENHA,DB_DATABASE);
    	$codigo = mysqli_real_escape_string($db,$_POST['codigo']);

        $sqlsessao = "DELETE FROM sessoes WHERE idsessao='$codigo'";
        $resultadosessao = mysqli_query($db,$sqlsessao);
        $sqlvotos = "DELETE FROM votos WHERE idsessao='$codigo'";
        $resultadovotos = mysqli_query($db,$sqlvotos);
    }
?>
<script>
	//JQuery para criar ou remover campos adicionais de vereadores que estão em uma sessão
	$(function() {
	  $(document).on('click', '.btn-add', function(e) {
	    e.preventDefault();

	    var dynaForm = $('.dinamico span:first'),
	      currentEntry = $(this).parents('.input-group'),
	      newEntry = $(currentEntry.clone()).appendTo(dynaForm);
	    newEntry.find('input').val('');
	    dynaForm.find('.input-group:not(:last) .btn-add')
	      .removeClass('btn-add').addClass('btn-remove')
	      .removeClass('btn-success').addClass('btn-danger')
	      .html('<i class="fa fa-minus"></i>');
	  }).on('click', '.btn-remove', function(e) {
	    $(this).parents('.input-group:first').remove();

	    e.preventDefault();
	    return false;
	  });
	});
</script>
</head>
<body>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
	    	<li class="breadcrumb-item"><a href="index.php" target="_parent">Início</a></li>
	    	<li class="breadcrumb-item active" aria-current="page">Sessões</li>
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
						<th scope='col'>Título</th>
						<th scope='col'>Data</th>
						<th scope='col'>Projeto</th>
						<th scope='col'></th>
					</tr>
				</thead>
				<tbody>";
		$id = $_SESSION['idvereador'];
		$resultado = mysqli_query($db,"SELECT ss.idsessao AS idsessao, ss.titulo AS titulo, ss.datasessao AS datasessao, ss.idprojeto AS idprojeto, pj.numero AS numero FROM sessoes AS ss LEFT JOIN projetos pj ON pj.idprojeto = ss.idprojeto LEFT JOIN votos vt ON vt.idsessao = ss.idsessao WHERE vt.idvereador='$id'");
		echo mysqli_error($db);
		while($coluna = mysqli_fetch_array($resultado)) {
			echo "<tr>";
			echo "<td>" . $coluna['idsessao'] . "</td>";
			echo "<td>" . $coluna['titulo'] . "</td>";
			echo "<td>" . $coluna['datasessao'] . "</td>";
			echo "<td>" . $coluna['numero'] . "</td>";
			# Alimentando o código, nome e partido dentro da tag A para depois alimentar via JavaScript o modal de alterar e deletar
			echo "<td>
			<a class='fa fa-fw fa-search' data-toggle='modal' data-target='#detalhes' data-id='".$coluna['idsessao']."' data-titulo='".$coluna['titulo']."' data-datasessao='".$coluna['datasessao']."'  data-numero='".$coluna['numero']."' data-idprojeto='".$coluna['idprojeto']."' onclick='sessaoDetalhe(this)'></a>
			<a class='fa fa-fw fa-pencil' data-toggle='modal' data-target='#alterar' data-id='".$coluna['idsessao']."' data-titulo='".$coluna['titulo']."' data-datasessao='".$coluna['datasessao']."'  data-numero='".$coluna['numero']."' data-idprojeto='".$coluna['idprojeto']."' onclick='sessaoAlterar(this)'></a>
			<a class='fa fa-fw fa-trash' data-toggle='modal' data-target='#excluir' data-id='".$coluna['idsessao']."' data-titulo='".$coluna['titulo']."' data-datasessao='".$coluna['datasessao']."'  data-numero='".$coluna['numero']."' data-idprojeto='".$coluna['idprojeto']."' onclick='sessaoExcluir(this)'></a>
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
			                <label for="titulo">Titulo</label>
			                <input type="text" name="titulo" id="titulocriar" class="form-control" required/>
			            </div>
			          	<div class="form-group">
			          		<label for="data">Data</label>
			                <input type="date" name="data" id="datacriar" class="form-control" required/>
			            </div>
			            <div class="form-group">
			                <label for="projeto">Projeto</label>
			                <input list="projetos" name="projeto" id="projetocriar" class="form-control" required/>
				            <datalist id="projetos">
				             	<?php
				               		# Iniciando outra conexão devido a conexão que vem do header.php foi fechada ao carregar a tabela
				               		$db = mysqli_connect(DB_SERVER,DB_USUARIO,DB_SENHA,DB_DATABASE);
				               		$resultado = mysqli_query($db,"SELECT numero FROM projetos");
									while($row = mysqli_fetch_array($resultado)) {
										echo "<option value='" . $row['numero'] . "'>";
									}
									mysqli_close($db);
				               	?>
				            </datalist>
			            </div>
			            <div class="form-group dinamico">
				            <label for="vereador">Vereador(es)</label>
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
					        <span class="copiar">
				            <div class="input-group mb-3">
				                <input list="vereadores" name="vereador[]" id="vereadorcriar" class="form-control" required/>
				                <div class="input-group-btn">
		                            <button class="btn btn-success btn-add" type="button">
		                            	<i class="fa fa-plus"></i>
		                            </button>
	                        	</div>
				            </div>
				        	</span>
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
	<div class="modal fade" id="detalhes" tabindex="-1" role="dialog" aria-labelledby="detalhes registro" aria-hidden="true">
	    <div class="modal-dialog" role="document">
		    <div class="modal-content">
		        	<div class="modal-header">
			            <h5 class="modal-title" id="detalhe">Detalhes do Registro</h5>
			            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			            	<span aria-hidden="true">&times;</span>
			            </button>
			        </div>
			        <div class="modal-body">
			        	<div class="form-group">
			                <label for="codigo">Código</label>
			                <input type="text" name="codigo" id="codigodetalhe" class="form-control" readonly/>
			            </div>
			        	<div class="form-group">
			                <label for="numero">Número do Projeto</label>
			                <input type="text" name="numero" id="numerodetalhe" class="form-control" readonly/>
			            </div>
			          	<div class="form-group">
			                <label for="titulo">Título</label>
			                <input type="text" name="titulo" id="titulodetalhe" class="form-control" readonly/>
			            </div>
			            <div class="form-group">
			                <label for="data">Data</label>
			                <input type="date" name="data" id="datadetalhe" class="form-control" readonly/>
			            </div>
			            <table class='table table-hover sortable' id="table-json-detalhe">
							<thead>
								<tr>
									<th scope='col'>#</th>
									<th scope='col'>Sessão</th>
									<th scope='col'>Vereador</th>
									<th scope='col'>Voto</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
			        </div>
			        <div class="modal-footer">
			            <button type="button" name="detalhe" class="btn btn-primary" data-dismiss="modal">Sair</button>
			        </div>
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
			                <label for="numero">Número do Projeto</label>
			                <input list="projetos" name="numero" id="numeroalterar" class="form-control" required/>
				            <datalist id="projetos">
				             	<?php
				               		# Iniciando outra conexão devido a conexão que vem do header.php foi fechada ao carregar a tabela
				               		$db = mysqli_connect(DB_SERVER,DB_USUARIO,DB_SENHA,DB_DATABASE);
				               		$resultado = mysqli_query($db,"SELECT numero FROM projetos");
									while($row = mysqli_fetch_array($resultado)) {
										echo "<option value='" . $row['nome'] . "'>";
									}
									mysqli_close($db);
				               	?>
				            </datalist>
			            </div>
			          	<div class="form-group">
			                <label for="titulo">Título</label>
			                <input type="text" name="titulo" id="tituloalterar" class="form-control"/>
			            </div>
			            <div class="form-group">
			                <label for="data">Data</label>
			                <input type="date" name="data" id="dataalterar" class="form-control"/>
			            </div>
			            <table class='table table-hover sortable' id="table-json-alterar">
							<thead>
								<tr>
									<th scope='col'>#</th>
									<th scope='col'>Sessão</th>
									<th scope='col'>Vereador</th>
									<th scope='col'>Voto</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
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
			                <label for="numero">Número do Projeto</label>
			                <input type="text" name="numero" id="numeroexcluir" class="form-control" readonly/>
			            </div>
			          	<div class="form-group">
			                <label for="titulo">Título</label>
			                <input type="text" name="titulo" id="tituloexcluir" class="form-control" readonly/>
			            </div>
			            <div class="form-group">
			                <label for="data">Data</label>
			                <input type="date" name="data" id="dataexcluir" class="form-control" readonly/>
			            </div>
			            <table class='table table-hover sortable' id="table-json-excluir">
							<thead>
								<tr>
									<th scope='col'>#</th>
									<th scope='col'>Sessão</th>
									<th scope='col'>Vereador</th>
									<th scope='col'>Voto</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
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