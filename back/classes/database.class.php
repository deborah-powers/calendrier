<?php
class database{
	public $DBhost = 'host:3306';
	public $DBuser = 'user';
	public $DBpassword = 'password';
	public $DBname = 'dbName';
	public $mysqli = Null;
	public function connect(){
		$this->mysqli = new mysqli ($this->DBhost, $this->DBuser, $this->DBpassword, $this->DBname) or die ($this->mysqli->error);
		// indiquer que les elements envoyes sont en utf8, adapte a la langue francaise
		$this->mysqli->set_charset ('utf8');
	}
	public function __construct(){
		$this->connect();
	}
	// transforme une liste d'array simple (et le nom des colonnes) en liste d'arrays associatifs
	public function toArray ($tableFields, $tableData){
		$nbFields = count ($tableFields);
		$jsonData =[];
		foreach ($tableData as $key => $value){
			$rowsData =[];
			for ($i=0; $i< $nbFields; $i++){
				$rowsData [$tableFields[$i]] = $value[$i];
			}
			$jsonData[] = $rowsData;
		}
		return $jsonData;
	}
	// recuperer le contenu de la table
	public function getTableContent ($tableName){
		$request = "SELECT * FROM $tableName";
		$response = $this->mysqli->query ($request) or die ($mysqli->error);
		$tableData = mysqli_fetch_all ($response);
		// recuperer le noms des colonnes de la table
		// fetch_fields renvoit pleins d'infos sur les champs de la table, il faut extraire le nom
		$tableFieldsWithAllInfos = mysqli_fetch_fields ($response);
		$tableFields = [];
		foreach ($tableFieldsWithAllInfos as $key => $field){
			$tableFields[] = $field->name;
		}
		$jsonData = $this->toArray ($tableFields, $tableData);
		return $jsonData;
	}
	// recuperer un objet dans la table
	public function findObject ($tableName, $strCmd){
		// strCmd est une string permettant d'identifier l'objet. l'echapper afin de bien le recuperer
		$request = "SELECT * FROM $tableName WHERE $strCmd";
		$response = $this->mysqli->query ($request);
		$tableData = mysqli_fetch_all ($response);
		// recuperer le noms des colonnes de la table
		$tableFieldsWithAllInfos = mysqli_fetch_fields ($response);
		$tableFields = [];
		foreach ($tableFieldsWithAllInfos as $key => $field){
			$tableFields[] = $field->name;
		}
		$jsonData = $this->toArray ($tableFields, $tableData);
		return $jsonData;
	}
	// verifier si un objet est dans la table
	public function isObjInDb ($tableName, $strCmd){
		// strCmd est une string permettant d'identifier l'objet
		$tableData = $this->findObject ($tableName, $strCmd);
		$presence =0;
		if (count ($tableData) >0) $presence =1;
		return $presence;
	}
	// creer la string strCmd
	public function createStrcmdFromArray ($array, $glue=' AND '){
		// array est un tableau associatif (champ: valeur)
		// glue peut valoir " AND " ou ", "
		$listBuildStrcmd =[];
		foreach ($array as $field => $value){
			$tmpTxt = "$field=$value";
			$typeValue = gettype ($value);
			if ($typeValue == 'string'){
				// les elements de texte sont echappes
				$tmpValue = $this->mysqli->escape_string ($value);
				$tmpTxt = "$field='$tmpValue'";
			}
			array_push ($listBuildStrcmd, $tmpTxt);
		}
		$strCmd = implode ($glue, $listBuildStrcmd);
		return $strCmd;
	}
	// recuperer un array dans la table
	public function findArray ($tableName, $array){
		$strCmd = $this->createStrcmdFromArray ($array);
		$jsonObj = $this->findObject ($tableName, $strCmd);
		return $jsonObj;
	}
	// verifier si un array est dans la table
	public function isArrayInDb ($tableName, $array){
		// array est un tableau associatif (champ: valeur)
		// creer strCmd
		$strCmd = $this->createStrcmdFromArray ($array);
		// verification proprement dite
		$presence = $this->isObjInDb ($tableName, $strCmd);
		return $presence;
	}
	// enregistrer un objet dans la table
	public function postObj ($tableName, $listFields, $listValues){
		$result =0;
		$request = "INSERT INTO $tableName ($listFields) VALUES ($listValues)";
		$response = $this->mysqli->query ($request);
		if ($response) $result =1;
		return $result;
	}
	// enregistrer un array dans la table
	public function postArray ($tableName, $array){
		// array est un tableau associatif (champ: valeur)
		// verifier si l'array est deja dans la table
		$result =0;
		$presence = $this->isArrayInDb ($tableName, $array);
		if ($presence ===0){
			// creer les listes de champs et de valeurs utilisees dans l'echange avec la bdd
			$listFields = array_keys ($array);
			$strFields = implode (', ', $listFields);
			$listValues =[];
			// proteger les caracteres speciaux dans les valeurs
			foreach ($array as $key => $value){
				$newValue = $this->mysqli->escape_string ($value);
				array_push ($listValues, $newValue);
			}
			$strValues = implode ("', '", $listValues);
			$strValues = "'". $strValues ."'";
			// enregistrement proprement dit
			$result = $this->postObj ($tableName, $strFields, $strValues);
		}
		return $result;
	}
	// updater un objet dans la table
	public function updateObj ($tableName, $strCmd, $strToset){
		$result =0;
		$presence = $this->isObjInDb ($tableName, $strCmd);
		if ($presence ===1){
			$result =2;
			$request = "UPDATE $tableName SET $strToset WHERE $strCmd";
			$response = $this->mysqli->query ($request) or die ($mysqli->error);
			if ($response) $result =1;
		}
		return $result;
	}
	// updater un array dans la table
	public function updateArray ($tableName, $array, $valuesForFindingLine){
		// array est une liste associative
		// valuesForFindingLine est une liste associative contenant les couples (champ: valeur) permettant d'identifier l'objet a modifier
		// creer strCmd
		$strCmd = $this->createStrcmdFromArray ($valuesForFindingLine);
		$result =0;
		$presence = $this->isObjInDb ($tableName, $strCmd);
		if ($presence ===1){
			// creer la string des valeurs a modifier
			$strToset = $this->createStrcmdFromArray ($array, ', ');
			$result = $this->updateObj ($tableName, $strCmd, $strToset);
		}
		return $result;
	}
	// supprimer un objet de la table
	public function deleteObj ($tableName, $strCmd){
		$result =0;
		$presence = $this->isObjInDb ($tableName, $strCmd);
		if ($presence ===1){
			$result =2;
			$request = "DELETE FROM $tableName WHERE $strCmd";
			$response = $this->mysqli->query ($request) or die ($mysqli->error);
			if ($response) $result =1;
		}
		return $result;
	}
	// supprimer un array de la table
	public function deleteArray ($tableName, $array){
		// array est une liste associative
		// creer strCmd
		$strCmd = $this->createStrcmdFromArray ($array);
		$result =0;
		$presence = $this->isObjInDb ($tableName, $strCmd);
		if ($presence ===1){
			// creer la string des valeurs a modifier
			$result = $this->deleteObj ($tableName, $strCmd);
		}
		return $result;
	}
}
?>