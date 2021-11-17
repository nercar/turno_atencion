<?php
	$params = parse_ini_file('dist/config.ini');
	if ($params === false) {
		$titulo = '';
	}
	$titulo = $params['title'];
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta http-equiv='cache-control' content='no-cache'> 
		<meta http-equiv='expires' content='0'> 
		<meta http-equiv='pragma' content='no-cache'>
		<title><?php echo $titulo; ?></title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta name="viewport" content="width=device-width, initial-scale=0.66">
		<meta name="mobile-web-app-capable" content="yes">
		
		<!-- Icon Favicon -->
		<link rel="shortcut icon" href="dist/img/favicon.png">
		<link rel="icon" sizes="192x192" href="dist/img/favicon.png">
		
		<!-- Theme style -->
		<link rel="stylesheet" href="dist/css/adminlte.css">
		<link rel="stylesheet" href="dist/css/style.css">
	</head>
	<body style="overflow: hidden;">
		<div class="navbar navbar-expand navbar-dark bg-dark m-0 p-0 pl-2 pr-2 pb-1">
			<img src="dist/img/solologo.png" class="m-0 p-0 bg-transparent imgmain" height="45px">
			<span id="titulo" class="align-items-center m-0 p-0 ml-2 h4">Información <?php echo substr($titulo, strpos($titulo, '#')); ?></span>
			<div id="clock" class="dark ml-auto p-0 m-0">
				<div class="display">
					<div class="ampm"></div>
					<div class="digits"></div>
					<div class="weekdays"></div>
				</div>
			</div>
		</div>
		<div class="content">
			<div class="row" id="ppal">
				<div class="col-6 text-center">
					<h2>EN ESPERA POR ATENCIÓN</h2>
					<table class="table table-striped w-100" id="listaEspera" style="font-size: 120%">
						<thead>
							<tr class="bg-primary">
								<th>Turno</th>
								<th>ID Cliente</th>
								<th>Nombre Cliente</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				<div class="col-6 text-center">
					<div class="row align-items-center justify-content-center h1 mt-4">En Atención</div>
					<span class="row align-items-center h-25 justify-content-center turno thickOutlined">&nbsp;</span>
					<br>
					<div class="row align-items-center justify-content-center h1">Bienvenid@</div>
					<span class="row align-items-center h-25 justify-content-center nombre">&nbsp;</span>
				</div>
			</div>
		</div>
		<div class="fixed-bottom p-1">
			<input type="hidden" id="siguiente" value="0">
			<button class="btn btn-sm btn-link ml-auto float-right text-white"
				id="btn_siguiente" onblur="$('#btn_siguiente').focus()"
		onclick="if(teclapres==0) { marcarSiguiente() }">
				Atender
			</button>
		</div>
	</body>

	<!-- jQuery -->
	<script src="dist/js/jquery.min.js"></script>
	<!-- jQuery UI 1.12.1 -->
	<script src="dist/js/jquery-ui.min.js"></script>
	<!-- AdminLTE App -->
	<script src="dist/js/adminlte.min.js"></script>
	<!-- Moment.js -->
	<script src="dist/js/moment.min.js"></script>
	<!-- app.js -->
	<script src="dist/js/app.js"></script>

	<script>
		$(function() {
			$('#btn_siguiente').focus()
		})
		var teclapres = 0;
		var veces = 0;		
		hora();
		actualizarTabla();

		$('#ppal').height(window.innerHeight - $('#ppal').offset().top)

		function actualizarTabla() {
			$.ajax({
				data: { opcion: "listarEspera" },
				type: "POST",
				dataType: "json",
				url: "DBProcs.php",
				success: function (data) {
					$('#listaEspera tbody').empty();
					let enAtencion = data.enAtencion;
					let listaEspera = data.listaEspera;
					if(listaEspera.length > 0) {
						$('#siguiente').val(listaEspera[0].id);
						$.each(listaEspera, function (index, valor) { 
							var htmlTags = '<tr>'+
									'<td>' + listaEspera[index].id + '</td>'+
									'<td align="left">' + listaEspera[index].id_cliente + '</td>'+
									'<td align="left">' + listaEspera[index].nom_cliente + '</td>'+
								'</tr>';
								
							$('#listaEspera tbody').append(htmlTags);
						});
					} else {
						$('#siguiente').val(0);
						var htmlTags = '<tr>'+
								'<td colspan="3" align="center">No hay clientes en espera de Atención</td>'+
							'</tr>';
							
						$('#listaEspera tbody').append(htmlTags);
					}
					if(enAtencion.length > 0) {
						$('.turno').html(enAtencion[0].id)
						$('.nombre').html('Sr(a). '+enAtencion[0].nom_cliente)
					} else {
						$('.turno').html('&nbsp;')
						$('.nombre').html('Sr(a). &nbsp;')
					}
					setTimeout(()=> {
						if($('#siguiente').val()>0) {
							teclapres = 0;
							veces = 0;
						}
						actualizarTabla();
					}, 5000);
				}
			});
		}

		function marcarSiguiente() {
			if(teclapres==0 && $('#siguiente').val() > 0) {
				teclapres = 1;
				$.ajax({
					url: "DBProcs.php",
					data: {
						opcion: "atenderCliente",
						idpara: function() { return $('#siguiente').val() },
					},
					type: "POST",
					dataType: "text",
					success : function(data) {
						if(data==0) {
							veces++;
							if(veces<=30) {
								teclapres = 0
								marcarSiguiente();
							}
						} else {
							actualizarTabla();
						}
					},
				})
			}
		}
	</script>	
</html>