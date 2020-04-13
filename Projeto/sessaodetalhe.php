<?php
include("config.php");
$codigo = mysqli_real_escape_string($db, $_GET['id']);
$query = "SELECT vt.idvotacao as idvotacao, vt.idsessao as idsessao, vr.nome as nome, vt.voto as voto FROM votos vt left join vereadores vr on vr.idvereador = vt.idvereador WHERE vt.idsessao = '$codigo'";
$resultado = mysqli_query($db, $query);
$i = 0;
$array = [];
while($row = mysqli_fetch_array($resultado)) {
		$array[$i] = $row;
		$i+=1;
	}
if ($array) {
	echo json_encode($array); exit();
} else{
	echo json_encode(mysqli_error($db)); exit();
}
?>