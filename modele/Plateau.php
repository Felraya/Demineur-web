<?php

class Plateau{

    private $plateauCourant;// = array();
    private $plateau;// = array();

//Constructeur de la classe
    public function __construct(){
      if (!(isset($_SESSION['plateau']) && isset($_SESSION['plateauCourant']))) {

        $this->plateauCourant = array();
        $this->plateau = array();
        //Initialisation du plateau à cliquer
        for($i=0;$i<8;$i++){
            $this->plateauCourant[$i] = array();
            }


        $_SESSION['plateauCourant'] = $this->plateauCourant;

        //Initialisation du plateau caché
        for($i=0;$i<8;$i++){
            $this->plateau[$i] = array();
        }
        $_SESSION['plateau'] = $this->plateau;

        $this->startGame();
      }

    }

   /**
   *Cette fonction doit être déclanchée au début de la partie
   *Elle permet d'initialiser la game
   */
   public function startGame(){

     $_SESSION['choix'] = "DECOUVRIR";
     $_SESSION['etat'] = 'encours';

     //on reinitialise le plateau
     for ($i=0; $i <8 ; $i++) {
       for ($j=0; $j < 8; $j++) {
           $_SESSION['plateau'][$i][$j] = " ";
       }
     }


    //Generation des bombes
    $n = 0;
    while($n <10){
            $x1 = random_int(0,7);
            $x2 = random_int(0,7);
                if(!($_SESSION['plateau'][$x1][$x2] == 'B')){
                    $_SESSION['plateau'][$x1][$x2] = 'B';
                    $n ++;
                }
        }
      //Remplissage des autres cases
      for ($i=0; $i <8 ; $i++) {
        for ($j=0; $j < 8; $j++) {
          if (!($_SESSION['plateau'][$i][$j] == 'B')) {
            $_SESSION['plateau'][$i][$j] = strval($this->nombres_bombes($i,$j));
          }
        }
      }

      //On cache tout le plateauCourant
      for($i=0;$i<8;$i++){
          for($j=0;$j<8;$j++){
              $_SESSION['plateauCourant'][$i][$j] = 'N';
          }
        }
   }

   /*
   * Methode qui permet de calculer le nombre de bombes adjacentes
   *Parametres : les coordonnees de la case
   */
   public function nombres_bombes($i,$j){
     $nb_bombes = 0;
     if (isset($_SESSION['plateau'][$i-1][$j-1])) {
       if ($_SESSION['plateau'][$i-1][$j-1] == 'B') {
         $nb_bombes ++;
       }
     }
     if (isset(($_SESSION['plateau'][$i-1][$j]))) {
       if ($_SESSION['plateau'][$i-1][$j] == 'B') {
         $nb_bombes ++;
       }
     }
     if (isset($_SESSION['plateau'][$i-1][$j+1])) {
       if ($_SESSION['plateau'][$i-1][$j+1] == 'B') {
         $nb_bombes ++;
       }
     }
     if (isset($_SESSION['plateau'][$i][$j+1])) {
       if ($_SESSION['plateau'][$i][$j+1] == 'B') {
         $nb_bombes ++;
       }
     }
     if (isset($_SESSION['plateau'][$i+1][$j+1])) {
       if ($_SESSION['plateau'][$i+1][$j+1] == 'B') {
         $nb_bombes ++;
       }
     }
     if (isset($_SESSION['plateau'][$i+1][$j])) {
       if ($_SESSION['plateau'][$i+1][$j] == 'B') {
         $nb_bombes ++;
       }
     }
     if (isset($_SESSION['plateau'][$i+1][$j-1])) {
       if ($_SESSION['plateau'][$i+1][$j-1] == 'B') {
         $nb_bombes ++;
       }
     }
     if (isset($_SESSION['plateau'][$i][$j-1])) {
       if ($_SESSION['plateau'][$i][$j-1] == 'B') {
         $nb_bombes ++;
       }
     }

     return $nb_bombes;
   }


   /**
   *Fonction qui permet de decouvrir une case et prenant en paramètre les coordonnees de la case
   *Parametres : les coordonnes de la case que l'on souhaite découvrir
   */
   public function clique_case($x1,$x2){
     if ($_SESSION['choix'] == "DECOUVRIR") {
       $_SESSION['plateauCourant'][$x1][$x2] = $_SESSION['plateau'][$x1][$x2];
       if ($_SESSION['plateauCourant'][$x1][$x2] == 0 && $_SESSION['plateauCourant'][$x1][$x2] != 'B') {
         $this->decouvre_auto($x1,$x2);
       }
       $this->perdu($x1,$x2);
       $this->victoire();
     }

     else{
       if ($_SESSION['plateauCourant'][$x1][$x2] == 'D') {
         $_SESSION['plateauCourant'][$x1][$x2] = 'N';
       }else{
       $_SESSION['plateauCourant'][$x1][$x2] = 'D';
     }
     }

   }

   /**
   *Decouvre les cases autour automatiquement
   *Parametres : les coordonnees de la case que l'on vient de découvrir
   */
   public function decouvre_auto($x1,$x2){

       for ($i=$x1-1; $i <= $x1+1; $i++) {
         for ($j=$x2-1; $j <= $x2+1; $j++) {
           if (isset($_SESSION['plateauCourant'][$i][$j]) && $_SESSION['plateauCourant'][$i][$j] == 'N') {
             $this->clique_case ($i,$j);
           }

         }
       }
     }

   /**
   * Fonction qui permet de mettre fin au jeu si on decouvre une bombe
   * Parametres : les coordonnees de la case que l'on vient de découvrir
   */
   public function perdu($x1,$x2){
     if ($_SESSION['plateau'][$x1][$x2] == 'B') {
       $_SESSION['etat'] = 'defaite';
       return true;
     }
     return false;
   }

   /**
   * Methode qui pemt de tester si on a gagné
   */
   public function victoire(){
     $decouvert = 0;
     for ($i=0; $i <8 ; $i++) {
       for ($j=0; $j < 8; $j++) {
         if ($_SESSION['plateauCourant'][$i][$j] != 'N' && $_SESSION['plateauCourant'][$i][$j] != 'D') {
           $decouvert ++;
         }
       }
     }

     if ($decouvert == 54) {
       $_SESSION['etat'] = 'victoire';
       return true;
     }
     else{
       return false;
     }

   }

   /**
   * Methode qui permet de changer le mode --> soit Découverte soit Drapeau
   */
   public function setMode(){
     if ($_SESSION['choix'] == "DRAPEAU") {
       $_SESSION['choix'] = "DECOUVRIR";
     }
    else {
      $_SESSION['choix'] = "DRAPEAU";
    }
   }

    public function affichageCourant(){
        return $this->plateauCourant;
    }




}


?>
