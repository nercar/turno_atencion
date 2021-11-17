<?php
	if(isset($_GET['cliente'])) {
		include 'suturno.php';
	} else {
		include 'enespera.php';
	}
?>