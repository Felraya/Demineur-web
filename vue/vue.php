<?php
require_once PATH_METIER."/Message.php";

class Vue{

	function plateau_de_jeu(){
		//header("Content-type: text/html; charset=utf-8");
		?>
		<html>
		<head>
			<link rel="stylesheet" href="./CSS/design.css">
		</head>
		<body>

		<div class=head>
			<h1 class="title">Plateau de jeu Démineur</h1>
			<img src="./image/demineur.png">
		</div>
		<div class=choix>
			<h3>Choix action :</h3>
			<form class="" action="index.php" method="post">
				<?php  if ($_SESSION['etat'] == 'encours') {
					?>
							<input type="submit" name="choix" value="<?php echo $_SESSION['choix']; ?>" id="submit" class="headbut">
					<?php
				}?>
			</form>
		</div>
		


		<div class="plateau">
			<table>
		<?php

		for($i=0;$i<8;$i++){
			?>
			<tr>
			<?php
				for($j=0;$j<8;$j++){
					?>
					<td>
					<?php
					if (($_SESSION['plateauCourant'][$i][$j] == 'N' || $_SESSION['plateauCourant'][$i][$j] == 'D')) {
						if ($_SESSION['etat'] == 'encours') { ?>
							<a href="index.php?x1=<?php echo $i?>&x2=<?php echo $j ?>"><?php //echo $_SESSION['plateau'][$i][$j];?><img src="./image/<?php echo $_SESSION['plateauCourant'][$i][$j]; ?>.png" alt="" width="30" height="30"></a>
						<?php
					}
					else {
						?>
						<img src="https://s1.lmcdn.fr/multimedia/c31502987051/180575dae8d3b/produits/peinture-gris-anthracite-mat-dulux-valentine-creme-de-couleur-2-5-l/73140431-2-0-5433290-v-000001000000.jpg?$p=hi-w795" alt="" width="30" height="30">
						<?php
					}
					}
					else{?>
						<img src="./image/<?php echo $_SESSION['plateauCourant'][$i][$j]; ?>.png" alt="" width="30" height="30"><?php
					}?>
				</td>
					<?php
				}
			?>
			</tr>
			<?php
		}

		?>
		</table>
		<?php
	}


function connexion(){
	?>
	<!DOCTYPE html>
	<html lang="fr" dir="ltr">
		<head>
			<link rel="stylesheet" href="./CSS/design.css">
			<meta charset="utf-8">
			<title>Connexion</title>
		</head>
		<body>
			<div class="pagebody">
			<div class="connexion">
				<h1>Connexion</h1>
				<form class="" action="index.php" method="post">
					<input type="text" name="login" placeholder="Entrez votre pseudo">
					<input type="password" name="password" placeholder="Mot de passe">
					<input type="submit" name="demarrer" value="se connecter" id="submit">
				</form>
			</div>
			</div>

		</body>
	</html>

	<?php
}


function fin_de_partie($stats,$classement){
	?>
	<!DOCTYPE html>
	<html lang="fr" dir="ltr">
		<head>
			<link rel="stylesheet" href="./CSS/design.css">
			<meta charset="utf-8">
			<title></title>
		</head>
		<body>
		<div class="endgame">
		
			<div class="grillecomplete">
				<div class=head>
					<h1 class="title">Plateau de jeu Démineur</h1>
					<img src="./image/demineur.png">
				</div>
				<div class=choix>
					<h3>Choix action :</h3>
					<form class="" action="index.php" method="post">
						<?php  if ($_SESSION['etat'] == 'encours') {
							?>
									<input type="submit" name="choix" value="<?php echo $_SESSION['choix']; ?>" id="submit" class="headbut">
							<?php
						}?>
					</form>
				</div>

				<div class="plateau">
					<table>
					<?php

					for($i=0;$i<8;$i++){
						?>
						<tr>
						<?php
							for($j=0;$j<8;$j++){
								?>
								<td>
									<img src="./image/<?php echo $_SESSION['plateau'][$i][$j]; ?>.png" alt="" width="30" height="30">
								</td>
								<?php
							}
						?>
						</tr>
						<?php
					}

					?>
					</table>
				</div>
			</div>

			<div class="result">
				<?php
				
				if ($_SESSION['etat'] == 'victoire') {
					?>
					<h2>Vous avez gagné</h2>
					<?php
				}
				else {
					?>
					<h1 class="lose">Dommage c'est une défaite !</h1>
					<?php
				}


				?>

				<h3 class="subt">Stats du joueur : </h3>
				<?php
				foreach ($stats as $row) {
					echo "<p>Nombre de parties gagnées : " . $row['nbPartiesGagnees'] . "</p>";
					echo "<p>Nombre de parties jouées : " . $row['nbPartiesJouees'] . "</p>";
				}

				?>
				<h3 class="subt">Classement : </h3>
				<?php
				$place = 1;
				foreach ($classement as $row) {
						echo "<p>TOP $place : " . $row['pseudo'] . " avec ". $row['g'] . " victoires </p>";
						$place ++;
					}
				?>

				<h4>Voulez vous rejouer ? </h4>
				<form action="index.php" method="post">
					<input type="submit" value="RESET" name="reset" id="submit">
					<input type="submit" name="deco" value="Deconnexion" id="submit">
				</form>
			</div>
			</div>

		</body>
	</html>
	<?php
}

}?>
