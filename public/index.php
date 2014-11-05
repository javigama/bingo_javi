<?php
	
	session_name('Bingo_Virtual');
	session_start();

	if (isset($_POST['nuevo_juego'])) {
		session_destroy();
		session_start();
	}

	if (!isset($_SESSION['generados'])) {
		$_SESSION['generados'] = array();
		$lb = '<span id="texto_inicio">Pulse el botón "Obtener numero" para comenzar...</span>';
	}

	if (isset($_POST['obtener_numero'])) {
		do {
           	$n = rand(1, 90);
        } while (in_array($n, $_SESSION['generados'])); 
		array_push($_SESSION['generados'], $n);
		$lb = '<span id="numero_actual">'.$n.'</span>';
	}

?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<title>Bingo virtual</title>
	</head>
	<body>
		<form action="index.php" method="post">
<?php
			echo '<label>'.$lb.'</label>';
			echo '<table align="center" border="1">';
			for ($i=0; $i<11; $i++) {
				echo '<tr>';
				for ($j=0; $j<9; $j++) {
					if (($j == 0 && $i == 9) || ($j <> 8 && $i == 10))
						echo '<td></td>';
					else {
						if ($j == 0)
							$k = $j*10 + $i + 1;
						else
							$k = $j*10 + $i;
						echo '<td>'.$k.'</td>';
					}
				}
				echo '</tr>';
			}
			echo '</table>';
?>
			<button type="submit" name="obtener_numero">Obtener numero</button>
			<button type="submit" name="nuevo_juego">Nuevo juego</button>
		</form>
	</body>
</html>