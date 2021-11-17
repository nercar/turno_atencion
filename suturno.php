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
		<!-- Tell the browser to be responsive to screen width -->
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="mobile-web-app-capable" content="yes">
		<title><?php echo $titulo; ?></title>
		
		<!-- Icon Favicon -->
		<link rel="shortcut icon" href="dist/img/favicon.png">
		<link rel="icon" sizes="192x192" href="dist/img/favicon.png">
		
		<!-- Theme style -->
		<link rel="stylesheet" href="dist/css/adminlte.css">
		<link rel="stylesheet" href="dist/css/style.css">
		<style>
			@media (max-width: 991px) {
				body { font-size: .7em;}
				.imgmain {height: 30px;}
				.num {
					font-size: 3em;
					line-height: .9em;
				}
				.botonB {
					font-size: 3em;
					margin: .1em;
				}
				.botonT { font-size: 2em;}
				.numero {
					font-size: 2.7em;
					font-weight: bolder;
					white-space: nowrap;
				}
				#idcliente { font-size: 3em;}
				.nombre {
					letter-spacing: -.1em;
				}
			}
			@media (min-width: 992px) {
				.imgmain {height: 45px;}
				.num {
					font-size: 3em;
				}
				.botonB {
					font-size: 4.8em;
					margin: .1em;
				}
				.botonT { font-size: 3em;}
				.numero {
					font-size: 2.8em;
					font-weight: bolder;
					white-space: nowrap;
				}
				#idcliente { font-size: 5em;}
			}

			#idcliente {
				letter-spacing: -1px;
				font-weight: bolder;
				line-height: 0px;
				margin: .1em;
				width: 80%;
			}

			.table_teclado td {
				padding: .2em;
				cursor: pointer;
				font-family: sans-serif;
				max-width: 10px;
				position: relative;
			}

			.table_teclado {
				border: 0px;
				width: 100%;
				margin: 0 .2em 0 .2em;
			}

			.boton:hover {
				background: #006291;
				color: #FFFFFF;
			}

			.boton {
				border: 2px solid #000000;
				background-color: #0080ff;
				font-weight: bold;
				border-radius: 10px;
				color: #FFFFFF;
				width:  100%;
			}

			.botonT:hover {
				background: #008000;
			}

			.botonT {
				background-color: #00ff40;
				color: #000000;
			}

			.botonB:hover {
				background: #ee4311;
			}

			.botonB {
				background-color: #ff1e04;
				width:  20%;
				padding: 0px;
			}
		</style>
	</head>
	<body style="overflow: hidden;">
		<div class="navbar navbar-expand elevation-2 navbar-dark bg-dark m-0 p-0 pl-2 pr-2 pb-1">
			<img src="dist/img/solologo.png" class="m-0 p-0 bg-transparent imgmain">
			<span class="h2 align-items-center m-0 p-0 ml-2 text-nowrap"><?php echo substr($titulo, strpos($titulo, '#')); ?></span>
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
					<span class="numero h2">Ingrese su Número de Identidad</span>
					<div class="d-flex">
						<input type="text" id="idcliente" class="text-center rounded" readonly>
						<button class="botonB boton">&#x232b;</button>
					</div>
					<table class="table_teclado">
						<tr>
							<td><button class="boton btn btn-lg num">1</button></td>
							<td><button class="boton btn btn-lg num">2</button></td>
							<td><button class="boton btn btn-lg num">3</button></td>
						</tr>
						<tr>
							<td><button class="boton btn btn-lg num">4</button></td>
							<td><button class="boton btn btn-lg num">5</button></td>
							<td><button class="boton btn btn-lg num">6</button></td>
						</tr>
						<tr>
							<td><button class="boton btn btn-lg num">7</button></td>
							<td><button class="boton btn btn-lg num">8</button></td>
							<td><button class="boton btn btn-lg num">9</button></td>
						</tr>
						<tr>
							<td><button class="boton btn btn-lg num">0</button></td>
							<td colspan="2"><button class="botonT boton btn btn-lg">TURNO</button></td>
						</tr>
					</table>
				</div>
				<div class="col-6 text-center">
					<div class="row align-items-center justify-content-center h2 numero">Bienvenid@</div>
					<span class="row align-items-center h-25 justify-content-center nombre">&nbsp;</span>
					<div class="row align-items-center justify-content-center h2 numero mt-4">Su Turno de Atención Es:</div>
					<span class="row align-items-center h-25 justify-content-center turno thickOutlined">&nbsp;</span>
				</div>
			</div>
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
		hora();

		$('.table_teclado').height(window.innerHeight - $('.table_teclado').offset().top - 10)
		
		$('.num').click(function(){
			var number = $(this).text();
			$('#idcliente').val($('#idcliente').val() + number).focus();
		});

		$('.botonB').click(function(){
			$('#idcliente').val($('#idcliente').val().substr(0, $('#idcliente').val().length - 1)).focus();
		})

		$('.botonT').click(function(){
			if($('#idcliente').val().length >= 5) {
				$('.turno').html('&nbsp;');
				$('.nombre').html('&nbsp;');
				if(!$('.botonT').hasClass('d-none')) $('.botonT').addClass('d-none')
				$.ajax({
					url: "DBProcs.php",
					data: {
						opcion: "crearTurno",
						idpara: function() { return $('#idcliente').val() },
					},
					type: "POST",
					dataType: "json",
					success : function(data) {
						$('#idcliente').val([])
						if(data.turno>0) {
							$('.botonT').removeClass('d-none')
							$('.turno').html(data.turno)
							$('.nombre').html('Sr(a). '+data.nombre)
							setTimeout(()=> {
								$('.turno').html('&nbsp;');
								$('.nombre').html('&nbsp;');
							}, 10000);
						}
					},
				})
			}
		})
	</script>	
</html>