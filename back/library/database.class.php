<?php

class database{
	$DBhost = '127.0.0.1:3306';
	$DBuser = 'root';
	$DBpassword = 'noisette416';
	$DBname = 'deborahprrdebbie';
	$DBtables =[];
	$mysqli = new mysqli ($this->DBhost, $this->DBuser, $this->DBpassword, $this->DBname) or die ($mysqli->error);
}

public function findObjectInDb ($tableName, $obj){
	// recuperer un objet dans la bdd
	$request = "SELECT * FROM $tableName WHERE title='$obj->title' AND year=$obj->year AND month=$obj->month AND day=$obj->day;";
	$response = $this->mysqli->query ($request) or die ($mysqli->error);
	$tableData = mysqli_fetch_all ($response);
	return $tableData;
}
public function isObjInDB ($tableName, $obj){
	// echapper le titre afin de bien le recuperer dans la bdd
	$title = addslashes ($obj->title);
	// verifier si l'evt a deja ete enregistre dans la bdd. il m'arrive de cliquer deux fois sur le bouton enregistrer par erreur
	$request = "SELECT * FROM $tableName WHERE title='$title' AND year=$obj->year AND month=$obj->month AND day=$obj->day;";
	$response = $this->mysqli->query ($request) or die ($mysqli->error);
	$tableData = mysqli_fetch_all ($response);
	$presence =0;
	if (count ($tableData) >0) $presence =1;
	return $presence;
}
public function postObjToDb ($tableName, $obj){
	$message = "l'objet n'a pas put etre insere dans la bdd";
	// verifier si l'objet existe deja dans la bdd
	$presence = isObjInDB ($tableName, $obj);
	if ($presence) $message = "l'objet est deja dans la bdd";
	else{
		// recuperer les champ d'obj dans un array associatif
		$objFields = get_object_vars ($obj);
		// echapper les champs de la bdd
		foreach ($objFields as $key => $value){
			$objFields[$key] = addslashes ($value);
		}
		// creer les listes de champs et de valeurs utilisees dans l'echange avec la bdd
		$listValues = join ("', '", $objFields);
		$listValues = "'". $listValues ."'";
		$listKeys = array_keys ($objFields);
		$listFields = join (', ', $listKeys);
		// connexion a la bdd
		$request = "INSERT INTO $tableName ($listFields) VALUES ($listValues)";
		$response = $this->mysqli->query ($request) or die ($mysqli->error);
		if ($response) $message = "l'objet a ete insere dans la bdd";
	}
	return $message;
}
public function updateObjToDb ($tableName, $obj){
	$message = "l'objet n'a pas put etre update";
	// verifier si l'objet existe deja dans la bdd
	$presence = isObjInDB ($tableName, $obj);
	if (! $presence) $message = "l'objet n'est pas dans la bdd";
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
		// echapper le titre afin de bien le recuperer dans la bdd
		$title = addslashes ($obj->title);
		// connexion a la bdd
		$request = "UPDATE $tableName SET $toSet WHERE title='$title' AND year=$obj->year AND month=$obj->month AND day=$obj->day";
		$response = $this->mysqli->query ($request) or die ($mysqli->error);
		if ($response) $message = "l'objet a ete modifie";
		return $message;
	}
}
public function getTableContent ($tableName){
	// obj est un objet vide
	// connexion a la bdd
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
