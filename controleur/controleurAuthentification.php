<?php
require_once PATH_VUE."/vue.php";
require_once PATH_MODELE."/Authentification.php";
require_once PATH_MODELE."/Plateau.php";

class ControleurAuthentification{

	private $vue;
	private $modeleAuthentification;
	private $modelePlateau;


	function __construct(){
		$this->vue=new Vue();
		$this->modeleAuthentification = new Authentification();
		$this->modelePlateau = new Plateau();
	}

	function connexion(){
		$this->vue->connexion();
	}

	function verif($login,$password){
		if ($this->modeleAuthentification->exists($login)) {
			//LE PSEUDO EST DANS LA BDD
			if($this->modeleAuthentification->verificationMDP($login,$password)){
				//MDP VALIDE --> affichage du plateau de jeu
				$this->vue->plateau_de_jeu();
			}
			else {
				echo "Mauvais mot de passe";
				$this->vue->connexion();
			}
		}
		else {
			echo "Ce nom d'utilisateur n'existe pas";
			$this->vue->connexion();
		}

	}

	function deconnexion(){
		$this->modeleAuthentification->deco();
		$this->vue->connexion();
	}


}
