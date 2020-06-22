<?php

/**
 *
 */

class Authentification{
	private $connexion;

// Constructeur de la classe
	public function __construct(){
		try{


			$chaine="mysql:host=".HOST.";dbname=".BD;
			$this->connexion = new PDO($chaine,LOGIN,PASSWORD);
			$this->connexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e){
			$exception=new ConnexionException("problème de connexion à la base");
			throw $exception;
		}
	}




// méthode qui permet de se deconnecter de la base
	public function deconnexion(){
		$this->connexion=null;
	}



//vérifie qu'un pseudo existe dans la table joueurs
// post-condition retourne vrai si le pseudo existe sinon faux
// si un problème est rencontré, une exception de type TableAccesException est levée
	public function exists($pseudo){
		try{
			$statement = $this->connexion->prepare("select * from joueurs where pseudo=?;");
      $statement->bindParam(1,$pseudo);
			$statement->execute();
			$result=$statement->fetch(PDO::FETCH_ASSOC);

			if ($result["pseudo"]!=NULL){
				return true;
			}
			else{
				return false;
			}
		}
		catch(PDOException $e){
			$this->deconnexion();
			throw new TableAccesException("problème avec la table pseudonyme");
		}
	}

/*
* Permet de verifier le mot de passe d'un joueur
@param : un pseudo et un mot de passe
*/

  public function verificationMDP($pseudo,$password){
    try {
      $statement = $this->connexion->prepare("select motDePasse from joueurs where pseudo=?;");
      $statement->bindParam(1,$pseudo);
			$statement->execute();
			$result=$statement->fetchAll();
			foreach ($result as $mdp) {
				//echo "mdp : " . $mdp['motDePasse'];
				if (password_verify($password,$mdp['motDePasse'])) {
					$_SESSION['pseudo'] = $pseudo;
					return TRUE;
				}
				else {
					return FALSE;
				}
			}

    }
    catch(PDOException $e){
			$this->deconnexion();
			throw new TableAccesException("problème avec la table pseudonyme");
		}
  }

	/*
	* Permet de detruire la session en cours et donc de deconnecter le joueur
	*/
	public function deco(){
		session_destroy();
	}

	/*
	* Ajoute une victoire au joueur courant
	*/
	public function ajoutVictoire(){
		$statement = $this->connexion->prepare('update parties SET nbPartiesGagnees = nbPartiesGagnees + 1 WHERE pseudo = ? ');
		$statement->bindParam(1,$_SESSION['pseudo']);
		$statement->execute();
	}

	/*
	* Ajoute une partie jouée au joueur courant
	*/
	public function ajoutPartie(){
		try {
			if (!$this->existsParties($_SESSION['pseudo'])) {
				$statement = $this->connexion->prepare('insert into parties (pseudo,nbPartiesJouees,nbPartiesGagnees) values (?,1,0)');
			}

			else {
					$statement = $this->connexion->prepare('update parties SET nbPartiesJouees = nbPartiesJouees + 1 WHERE pseudo = ? ');
			}
			$statement->bindParam(1,$_SESSION['pseudo']);
			$statement->execute();

		}
		catch(PDOException $e){
			$this->deconnexion();
			throw new TableAccesException("problème avec la table parties");
		}
	}

	/**
 * Méthode qui renvoie le classement des meilleurs joueurs en fonction de leur nombre de victoires
 * @return array
 */
public function getBestPlayers()
{
		//Création d'une requête de sélection des meilleurs joueurs
		$statement = $this->connexion->query("SELECT pseudo,nbPartiesGagnees g FROM parties GROUP BY pseudo ORDER BY g DESC LIMIT 3");
		//Execution de la requête
		$statement->execute();
		//Récupération du résultat de la requête
		$result = $statement->fetchAll();
		//Retour du résultat
		return $result;
}

//vérifie qu'un pseudo existe dans la table parties
// post-condition retourne vrai si le pseudo existe sinon faux
// si un problème est rencontré, une exception de type TableAccesException est levée
	public function existsParties($pseudo){
		try{
			$statement = $this->connexion->prepare("select * from parties where pseudo=?;");
      $statement->bindParam(1,$pseudo);
			$statement->execute();
			$result=$statement->fetch(PDO::FETCH_ASSOC);

			if ($result["pseudo"]!=NULL){
				return true;
			}
			else{
				return false;
			}
		}
		catch(PDOException $e){
			$this->deconnexion();
			throw new TableAccesException("problème avec la table pseudonyme");
		}
	}


/**
*Methode qui permet de récuperer les stats d'un joueur (Nombre de parties jouées et Nombre de parties gagnées)
* @return array
*/
	public function getStats(){
		try{
			$statement = $this->connexion->prepare("select * from parties where pseudo=?;");
      $statement->bindParam(1,$_SESSION['pseudo']);
			$statement->execute();
			$result=$statement->fetchAll();

			return $result;
		}
		catch(PDOException $e){
			$this->deconnexion();
			throw new TableAccesException("problème avec la table pseudonyme");
		}
	}


}

?>
