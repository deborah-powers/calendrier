<?php
class database{
	$DBhost = '127.0.0.1:3306';
	$DBuser = 'root';
	$DBpassword = 'noisette416';
	$DBname = 'deborahprrdebbie';
	// $DBtables =[];
	$mysqli = new mysqli ($this->DBhost, $this->DBuser, $this->DBpassword, $this->DBname) or die ($mysqli->error);

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
		return [$tableFields, $tableData];
	}
	// recuperer un objet dans la table
	public function findObjectInDb ($tableName, $strCmd){
		// strCmd est une string permettant d'identifier l'objet. l'echapper afin de bien le recuperer
		$strCmd = addslashes ($strCmd);
		$request = "SELECT * FROM $tableName WHERE $strCmd;";
		$response = $this->mysqli->query ($request) or die ($mysqli->error);
		$tableData = mysqli_fetch_all ($response);
		return $tableData;
	}
	// verifier si un objet est dans la table
	public function isObjInDb ($tableName, $strCmd){
		// strCmd est une string permettant d'identifier l'objet
		$tableData = $this->findObjectInDb ($tableName, $strCmd);
		$presence =0;
		if (count ($tableData) >0) $presence =1;
		return $presence;
	}
	// verifier si un array est dans la table
	public function isArrayInDb ($tableName, $array){
		// array est un tableau associatif (champ: valeur)
		// creer strCmd
		$listBuildStrcmd =[]
		foreach ($array as $field => $value){
			$tmpTxt = "$field=$value";
			$typeValue = gettype ($value);
			if ($typeValue == 'string') $tmpTxt = "$field='$value'";
			array_push ($listBuildStrcmd, $tmpTxt);
		}
		$strCmd = implode (' AND ', $listBuildStrcmd);
		// verification proprement dite
		$presence = $this->isObjInDb ($tableName, $strCmd);
		return $presence;
	}
	// enregistrer un objet dans la table
	public function postObjToDb ($tableName, $listFields, $listValues){
		$result =0;
		$request = "INSERT INTO $tableName ($listFields) VALUES ($listValues)";
		$response = $this->mysqli->query ($request) or die ($mysqli->error);
		if ($response) $result =1;
		return $result;
	}
	// enregistrer un array dans la table
	public function postArrayToDb ($tableName, $array){
		// array est un tableau associatif (champ: valeur)
		// verifier si l'array est deja dans la table
		$result =0;
		$presence = $this->isArrayInDb ($tableName, $array);
		if ($presence ==0){
			$result =2;
			// creer les listes de champs et de valeurs utilisees dans l'echange avec la bdd
			$listFields = array_values ($array);
			$strFields = implode (', ' $listFields);
			$listValues = array_keys ($array);
			$strValues = implode ("', '", $listValues);
			$strValues = "'". $strValues ."'";
			// enregistrement proprement dit
			$result = $this->postObjToDb ($tableName, $strFields, $strValues);
		}
		return $result;
	}
}








public function updateObjToDb ($tableName, $obj){
	$message = "l'objet n'a pas put etre update";
	// verifier si l'objet existe deja dans la table
	$presence = isObjInDB ($tableName, $obj);
	if (! $presence) $message = "l'objet n'est pas dans la table";
	else{
		// recuperer les champ d'obj dans un array associatif
		$objFields = get_object_vars ($obj);
		$listToSet =[];
		foreach ($objFields as $key => $value){
			if (gettype ($value) == string){
				$escapedValue = addslashes ($value);
				$listToSet[] = "$key='$escapedValue'";
			}
			else $listToSet[] = "$key=$value";
		}
		$toSet = implode (', ', $listToSet);
		// echapper le titre afin de bien le recuperer dans la table
		$title = addslashes ($obj->title);
		// connexion a la bdd
		$request = "UPDATE $tableName SET $toSet WHERE title='$title' AND year=$obj->year AND month=$obj->month AND day=$obj->day";
		$response = $this->mysqli->query ($request) or die ($mysqli->error);
		if ($response) $message = "l'objet a ete modifie";
		return $message;
	}
}
public function tableToJsonArray ($tableFields, $tableData){
	// transforme une liste d'array simple (et le nom des colonnes) en liste d'arrays associatifs
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
