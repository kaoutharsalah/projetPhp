<?php
class livraison {
	private $data=array();
	// La syntaxe ... = NULL signifie que l'argument est optionel
	// Si un argument optionnel n'est pas fourni,
	// alors il prend la valeur par défaut, NULL dans notre cas
	public function __construct($refLiv=null,$refCom=null, $date=null, $etat=null){
		if (!is_null($refLiv) && !is_null($refCom) && !is_null($date) && !is_null($etat)) {
			// Si aucun des paramètre n'est nul,
			// c'est forcement qu'on les a fournis
			// donc on retombe sur le constructeur à 3 arguments
			$this->data['refLiv'] = $refLiv;
			$this->data['refCom'] = $refCom;
			$this->data['date'] = $date;
			$this->data['etat'] = $etat;
		}
	}
	public function __get($attr){
		if (!isset($this->data[$attr]))
			return "erreur";
		else return ($this->data[$attr]);
	}
	
	public function __set($attr,$value) {
		$this->data[$attr] = $value; 
	}
	
	public function addlivraison($conn){
		try{
			$stm = $conn->prepare("INSERT INTO livraison(refLiv,refCom,date,etat) VALUES (?,?,?,?)");
			$stm->execute([$this->data['refLiv'],$this->data['refCom'],$this->data['date'],$this->data['etat']]);
			return true;
		}catch(PDOException $e ){
			if ($e->getrefLiv() == 2300){
				$message=$e->getMessage();
			}
			return false;
		}
	}
	
	public static function getAllLivraison($conn){
		$result=$conn->query("SELECT * FROM livraison");
		if(!$result) {
			$erreur=$conn->errorInfo();
		echo "Lecture impossible, code", $conn->errorCode(),$erreur[2];
		}
		else{
			//return $result->fetchAll(PDO::FETCH_OBJ);
			return $result->fetchAll(PDO::FETCH_CLASS, 'livraison'); //fatch_class returne tableau des objets de class modelProduit
		}
	}
	
	public static function getProduitByrefLiv($conn, $refLiv){
		$stm = $conn->prepare("SELECT * FROM livraison WHERE refLiv=?");
		$stm->execute([$refLiv]);
		return $stm->fetchAll(PDO::FETCH_CLASS, 'livraison');
	}
	public static function updateEtat($conn, $etat, $refLiv){
		$stm = $conn->prepare("UPDATE livraison SET etat=? WHERE refLiv=?");
		$stm->execute([$etat, $refLiv]);
	}
	public static function deleteLivraison($conn, $refLiv){
		$stm = $conn->prepare("DELETE FROM livraison WHERE refLiv=?");
		try{
			$stm->execute([$refLiv]);
			return true;
		}
		catch(PDOException $e){
			return false;
		}
	}
	
}