<?php
require_once PATH_VUE."/vue.php";
require_once PATH_MODELE."/Plateau.php";
require_once PATH_MODELE."/Authentification.php";



class ControleurPlateau{

    private $vue;
    private $modelePlateau;
    private $modeleAuthentification;


	function __construct(){
		$this->vue=new Vue();
    $this->modelePlateau = new Plateau();
    $this->modeleAuthentification = new Authentification();
	}


  function affichage(){
    $this->vue->plateau_de_jeu();
	}

  function clique($x1,$x2){
    $this->modelePlateau->clique_case($x1,$x2);
    if ($_SESSION['choix'] != 'DRAPEAU') {
      if ($this->modelePlateau->perdu($x1,$x2) || $this->modelePlateau->victoire()) {
        $this->modeleAuthentification->ajoutPartie();
        if ($this->modelePlateau->victoire()) {
          $this->modeleAuthentification->ajoutVictoire();
        }
        $this->vue->fin_de_partie($this->modeleAuthentification->getStats(),$this->modeleAuthentification->getBestPlayers());
      }
    }
    if($_SESSION['etat'] == 'encours'){
      $this->vue->plateau_de_jeu();
    }


  }

  function reset(){
    $this->modelePlateau->startGame();
    $this->vue->plateau_de_jeu();
  }

  function changerMode(){
    $this->modelePlateau->setMode();
    $this->vue->plateau_de_jeu();
  }

}


?>
