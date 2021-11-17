<?php
	/**
	* Permite obtener los datos de la base de datos y retornarlos
	* en modo json o array
	*/
	try {
		date_default_timezone_set('America/Bogota');
		// Se capturan las opciones por Post
		$opcion = (isset($_POST["opcion"])) ? $_POST["opcion"] : "";

		// id para los filtros en las consultas
		$idpara = (isset($_POST["idpara"])) ? $_POST["idpara"] : '';

		// Se establece la conexion con la BBDD
		$params = parse_ini_file('dist/config.ini');

		if ($params === false) {
			// exeption leyen archivo config
			throw new \Exception("Error reading database configuration file");
		}

		$conStr = sprintf("Driver={SQL Server};Server=%s;",$params['host_sql']);

		$cltenvo = utf8_decode($params['title']);

		$connec   = odbc_connect( $conStr, $params['user_sql'], $params['password_sql'] );

		switch ($opcion) {
			case 'crearTurno':
				$sql = "SET NOCOUNT ON
						DECLARE @COUNT AS INT
						SELECT @COUNT = COUNT(id) FROM BDES.dbo.turno_atencion WHERE CAST(fecha_crea AS DATE) < CAST(GETDATE() AS DATE)
						IF(@COUNT>0)
						BEGIN
							TRUNCATE TABLE BDES.dbo.turno_atencion
						END
						DECLARE @turno AS INT, @nombre AS VARCHAR(255)
						SELECT TOP 1 @nombre = LTRIM(RTRIM(UPPER(RAZON)))
						FROM BDES_POS.dbo.ESCLIENTESPOS WHERE LTRIM(RTRIM(RIF)) IN ('CC$idpara', 'CE$idpara', 'NIT$idpara', '$idpara')
						INSERT INTO BDES.dbo.turno_atencion(id_cliente, nom_cliente)
						VALUES($idpara, UPPER(COALESCE(@nombre, 'Cliente '+ '$cltenvo')))
						SELECT @turno = IDENT_CURRENT('BDES.dbo.turno_atencion')
						SET NOCOUNT OFF
						SELECT id, id_cliente, nom_cliente FROM BDES.dbo.turno_atencion WHERE id = @turno";

				// Se ejecuta la consulta en la BBDD
				$sql = odbc_exec($connec, $sql );
				if($sql) {
					$sql    = odbc_fetch_array($sql);
					$turno  = $sql['id'];
					$nombre = utf8_encode($sql['nom_cliente']);
				} else {
					$turno  = 0;
					$nombre = '';
				}

				$datos = [
					'turno'  => $turno*1,
					'nombre' => $nombre,
				];

				// Se retornan los datos obtenidos
				echo json_encode($datos);
				break;
			
			case 'listarEspera':
				$sql = "SELECT TOP 9 id, id_cliente, nom_cliente
						FROM BDES.dbo.turno_atencion
						WHERE status = 0
						ORDER BY id ASC";
				
				// Se ejecuta la consulta en la BBDD
				$sql = odbc_exec( $connec, $sql );
				$listaEspera = [];
				if($sql) {
					while ($row = odbc_fetch_array($sql)) {
						$listaEspera[] = [
							'id' => $row['id'],
							'id_cliente' => $row['id_cliente'],
							'nom_cliente' => utf8_encode($row['nom_cliente']),
						];
					}
				}	
				
				$sql = "SELECT TOP 1 id, nom_cliente
							FROM BDES.dbo.turno_atencion
						WHERE status = 1
						ORDER BY id DESC";
			
				// Se ejecuta la consulta en la BBDD
				$sql = odbc_exec( $connec, $sql );
				$enAtencion = [];
				if($sql) {
					while ($row = odbc_fetch_array($sql)) {
						$enAtencion[] = [
							'id' => $row['id'],
							'nom_cliente' => utf8_encode($row['nom_cliente']),
						];
					}
				}	

				echo json_encode(array('listaEspera' => $listaEspera, 'enAtencion' => $enAtencion));
				break;

			case 'atenderCliente':
				$sql = "UPDATE turnos SET turnos.status = 1, fecha_llamado = GETDATE()
						FROM (SELECT TOP 1 status, fecha_llamado
								FROM BDES.dbo.turno_atencion
								WHERE status = 0
								ORDER BY id ASC) turnos";
			
				// Se ejecuta la consulta en la BBDD
				$sql = odbc_exec( $connec, $sql );
				if($sql) echo '1';
				else echo '0';
				break;

			default:
				# code...
				break;
		}

		// Se cierra la conexion
		$connec = null;

	} catch (Exception $e) {
		echo "Error : " . $e->getMessage() . "<br/>";
		die();
	}
?>
