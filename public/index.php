<?php
	
	session_name('Bingo_Virtual');
	session_start();
	
	

	if (isset($_POST['nuevo_juego'])) {
		session_destroy();
		session_start();
		$lb = '<span id="texto_inicio">Pulse el botón "Obtener numero" para comenzar...</span>';
	}

	if (!isset($_SESSION['generados'])) {
		$_SESSION['generados'] = array();
	}

	if (!isset($_SESSION['n'])) {
		$_SESSION['n'] = 0;
	}

	if (!isset($_SESSION['etiqueta'])) {
		$_SESSION['etiqueta'] = '<span id="texto_inicio">Pulse el botón "Obtener numero" para comenzar...</span>';
	}

	if (!isset($_SESSION['indices'])) {
		$_SESSION['indices'] = array(0,1,2,3,4,5,6,7,8);
	}

	if (isset($_POST['obtener_numero'])) {
		do {
           	$_SESSION['n'] = rand(1, 90);
        } while (in_array($_SESSION['n'], $_SESSION['generados'])); 
		array_push($_SESSION['generados'], $_SESSION['n']);
		$_SESSION['etiqueta'] = '<span id="numero_actual">'.$_SESSION['n'].'</span>';
	}

	function creaCarton() {

		$_SESSION['casillas'] = array(1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1);
		$_SESSION['casillas'][$_SESSION['indices'][0]] = 0;
		$_SESSION['casillas'][$_SESSION['indices'][1]] = 0;
		$_SESSION['casillas'][$_SESSION['indices'][2]] = 0;
		$_SESSION['casillas'][$_SESSION['indices'][2]+9] = 0;
		$_SESSION['casillas'][$_SESSION['indices'][3]+9] = 0;
		$_SESSION['casillas'][$_SESSION['indices'][4]+9] = 0;
		$_SESSION['casillas'][$_SESSION['indices'][5]+9] = 0;
		$_SESSION['casillas'][$_SESSION['indices'][5]+18] = 0;
		$_SESSION['casillas'][$_SESSION['indices'][6]+18] = 0;
		$_SESSION['casillas'][$_SESSION['indices'][7]+18] = 0;
		$_SESSION['casillas'][$_SESSION['indices'][8]+18] = 0;
		$_SESSION['casillas'][$_SESSION['indices'][8]] = 0;
		
		echo '<table align="center" id="carton" border="1">';
		for ($i=0; $i<3; $i++) {
			echo '<tr>';
			for ($j=0; $j<9; $j++) {
				$c = $i*9+$j;
				echo '<td>';
				if ($_SESSION['casillas'][$c] == 1)
					echo '<input type="text" name="'.$c.'"maxlength="2" size="2" class="inputstyle">';
				echo '</td>';
			}
			echo '</tr>';
		}
		echo '</table>';
	}

	function compruebaCarton() {
		$fila0 = array();
		$fila1 = array();
		$fila2 = array();
		$lineas = 0;
		$sw = true;
		for ($i=0; $i<9; $i++) {
			for ($j=0; $j<=2; $j++) {
				$c = $j*9+$i;
				if ($_SESSION['casillas'][$c] == 1 && $_POST[$c] <> "") {
					$sw = false;
					if ($i == 0) {
						if (!($_POST[$c] > $i*10 && $_POST[$c] < ($i+1)*10)) {
							$sw = true;
							break 2;
						}
					}
					if ($i == 8) {
						if (!($_POST[$c] >= $i*10 && $_POST[$c] <= ($i+1)*10)) {
							$sw = true;
							break 2;
						}
					}
					if ($i > 0 && $i < 8) {
						if (!($_POST[$c] >= $i*10 && $_POST[$c] < ($i+1)*10)) {
							$sw = true;
							break 2;
						}
					}
					array_push($fila1, $_POST[$c]);
				}
			}
		}
		$total_numeros = count($fila0) + count($fila1) + count($fila2);
		if ($sw or $total_numeros <> 15)
			$_SESSION['etiqueta'] = '<span id="texto_inicio">Formato de cartón incorrecto</span>';
		else {
			if (count(array_diff($fila0, $_SESSION['generados'])) == 0)
				$lineas ++;
			if (count(array_diff($fila1, $_SESSION['generados'])) == 0)
				$lineas ++;
			if (count(array_diff($fila2, $_SESSION['generados'])) == 0)
				$lineas ++;
			if ($lineas == 0)
				$_SESSION['etiqueta'] = '<span id="texto_inicio">Cartón sin premio</span>';
			if ($lineas > 0 && $lineas < 3)
				$_SESSION['etiqueta'] = '<span id="texto_inicio">¡¡¡ LINEA !!!</span>';
			if ($lineas == 3)
				$_SESSION['etiqueta'] = '<span id="texto_inicio">¡¡¡ BINGO !!!</span>';
		}
			
		
	}

?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/bingo.css"/>
		<title>Bingo virtual</title>
	</head>
	<body>
		<form action="index.php" method="post">
			<div id="panel_numeros">
<?php
				if (isset($_POST['comprueba_carton'])) {
					compruebaCarton();
				} 

				echo '<label>'.$_SESSION['etiqueta'].'</label>';
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
							if (!in_array($k, $_SESSION['generados']))
								echo '<td>'.$k.'</td>';
							else {
								if ($k == $_SESSION['n'])
									echo '<td class="rojo">'.$k.'</td>';
								else
									echo '<td class="amarillo">'.$k.'</td>';
							}
						}
					}
					echo '</tr>';
				}
				echo '</table>';
?>
				<button type="submit" name="obtener_numero" <?php if(count($_SESSION['generados']) == 90) echo 'disabled'; else echo 'enabled'; ?> >Obtener numero</button>
				<button type="submit" name="nuevo_juego">Nuevo juego</button>
			</div>
			<div id="carton">
				<button type="submit" name="genera_carton">Generar cartón</button>
<?php
				if (isset($_POST['genera_carton'])) {
					shuffle($_SESSION['indices']);
				}
				creaCarton();
?>
				<button type="submit" name="comprueba_carton">Comprueba cartón</button>

			</div>
		</form>
	</body>
</html>