function vereadorAlterar(d) {
	var codigo = d.getAttribute("data-id");
	document.getElementById("codigoalterar").value = codigo;
	var nome = d.getAttribute("data-nome");
	document.getElementById("nomealterar").value = nome;
	var partido = d.getAttribute("data-partido");
	document.getElementById("partidoalterar").value = partido;
}
function vereadorExcluir(d) {
	var codigo = d.getAttribute("data-id");
	document.getElementById("codigoexcluir").value = codigo;
	var nome = d.getAttribute("data-nome");
	document.getElementById("nomeexcluir").value = nome;
	var partido = d.getAttribute("data-partido");
	document.getElementById("partidoexcluir").value = partido;
}
function projetoAlterar(d) {
	var codigo = d.getAttribute("data-id");
	document.getElementById("codigoalterar").value = codigo;
	var numero = d.getAttribute("data-numero");
	document.getElementById("numeroalterar").value = numero;
	var tipo = d.getAttribute("data-tipo");
	document.getElementById("tipoalterar").value = tipo;
	var ano = d.getAttribute("data-ano");
	document.getElementById("anoalterar").value = ano;
}
function projetoExcluir(d) {
	var codigo = d.getAttribute("data-id");
	document.getElementById("codigoexcluir").value = codigo;
	var numero = d.getAttribute("data-numero");
	document.getElementById("numeroexcluir").value = numero;
	var tipo = d.getAttribute("data-tipo");
	document.getElementById("tipoexcluir").value = tipo;
	var ano = d.getAttribute("data-ano");
	document.getElementById("anoexcluir").value = ano;
}
function sessaoDetalhe(d) {
	var codigo = d.getAttribute("data-id");
	document.getElementById("codigodetalhe").value = codigo;
	var numero = d.getAttribute("data-numero");
	document.getElementById("numerodetalhe").value = numero;
	var titulo = d.getAttribute("data-titulo");
	document.getElementById("titulodetalhe").value = titulo;
	var data = d.getAttribute("data-datasessao");
	document.getElementById("datadetalhe").value = data;
	var dataString = 'ajax=true';
		$.ajax({
	        type: "GET",
	        url: 'sessaodetalhe.php?id='+codigo,
	        data: dataString,
	        dataType:'json',
	        success: function (response) {
        		var len = response.length;
        		var table = $("#table-json-detalhe > tbody");
        		table.empty();
	            for(let i=0; i<len; i++){
	                var voto = response[i].voto;
	                var tr_str = "<tr>" +
	                    "<td>" + response[i].idvotacao  + "</td>" +
	                    "<td>" + response[i].idsessao + "</td>" +
	                    "<td>" + response[i].nome + "</td>" +
	                    "<td>" + (voto===null?'-':voto) + "</td>" +
	                    "</tr>";
	                table.append(tr_str);
	            }
            },
			error: function (err) {
				console.log(err);
			}
        });
}
function sessaoAlterar(d) {
	var codigo = d.getAttribute("data-id");
	document.getElementById("codigoalterar").value = codigo;
	var numero = d.getAttribute("data-numero");
	document.getElementById("numeroalterar").value = numero;
	var titulo = d.getAttribute("data-titulo");
	document.getElementById("tituloalterar").value = titulo;
	var data = d.getAttribute("data-datasessao");
	var dataString = 'ajax=true';
	$.ajax({
	        type: "GET",
	        url: 'sessaodetalhe.php?id='+codigo,
	        data: dataString,
	        dataType:'json',
	        success: function (response) {
	        	console.log(response);
        		var len = response.length;
        		var table = $("#table-json-alterar > tbody");
        		table.empty();
	            for(let i=0; i<len; i++){
	                var voto = response[i].voto;
	                var tr_str = "<tr>" +
	                    "<td>" + response[i].idvotacao  + "</td>" +
	                    "<td>" + response[i].idsessao + "</td>" +
	                    "<td>" + "<input type='text' name='vereador[]' id='vereadoralterar' class='form-control' value='"+response[i].nome + "'' readonly></td>" +
	                    "<td>" + "<input list='votos' class='form-control' name='voto[]' value='"+(voto===null?'-':voto)+"'><datalist id='votos'><option>-</option><option>Sim</option><option>Não</option><option>Abster</option></datalist></td>" +
	                    "</tr>";
	                table.append(tr_str);
	            }
            },
			error: function (err) {
				console.log(err);
			}
        });
}
function sessaoExcluir(d) {
	var codigo = d.getAttribute("data-id");
	document.getElementById("codigoexcluir").value = codigo;
	var numero = d.getAttribute("data-numero");
	document.getElementById("numeroexcluir").value = numero;
	var titulo = d.getAttribute("data-titulo");
	document.getElementById("tituloexcluir").value = titulo;
	var data = d.getAttribute("data-datasessao");
	document.getElementById("dataexcluir").value = data;
	var dataString = 'ajax=true';
	$.ajax({
	        type: "GET",
	        url: 'sessaodetalhe.php?id='+codigo,
	        data: dataString,
	        dataType:'json',
	        success: function (response) {
        		var len = response.length;
        		var table = $("#table-json-excluir > tbody");
        		table.empty();
	            for(let i=0; i<len; i++){
	                var voto = response[i].voto;
	                var tr_str = "<tr>" +
	                    "<td>" + response[i].idvotacao  + "</td>" +
	                    "<td>" + response[i].idsessao + "</td>" +
	                    "<td>" + response[i].nome + "</td>" +
	                    "<td>" + (voto===null?'-':voto) + "</td>" +
	                    "</tr>";
	                table.append(tr_str);
	            }
            },
			error: function (err) {
				console.log(err);
			}
        });
}