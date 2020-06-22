<?php

require_once 'controleurAuthentification.php';
require_once 'controleurPlateau.php';



class Routeur {

	private $ctrlAuthentification;
	private $ctrlPlateau;


	public function __construct() {
		$this->ctrlAuthentification= new ControleurAuthentification();
		$this->ctrlPlateau = new ControleurPlateau();

	}

  // Traite une requête entrante
	public function routerRequete() {

		if (isset($_SESSION['pseudo'])) { //SI ON CONNECTE
			if (isset($_POST['reset'])) {
				$this->ctrlPlateau->reset();
			}
			else if (isset($_POST['choix'])) {
				$this->ctrlPlateau->changerMode();
			}
			else if (isset($_GET['x1']) && isset($_GET['x2'])) {
				$this->ctrlPlateau->clique($_GET['x1'],$_GET['x2']);
			}
			else if (isset($_POST['deco'])) {
				$this->ctrlAuthentification->deconnexion();
			}
			else{
				$this->ctrlPlateau->affichage();
			}
		}
		else{//PAGE DE CONNEXION

			if (isset($_POST['login']) && isset($_POST['password'])) {
				$this->ctrlAuthentification->verif($_POST['login'],$_POST['password']);
			}
			else{
				$this->ctrlAuthentification->connexion();
			}
		}



	}


}




?>
