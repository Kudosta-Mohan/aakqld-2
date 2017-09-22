<?php
class SETTING
{
   private $db;
 
   function __construct($DB_con)
    {
      $this->db = $DB_con;
    }
 
public function insertWorkType($wtname)
{
	try
	{
		$createdate= date("Y-m-d H:i:s"); 
		$stmt = $this->db->prepare("INSERT INTO worktype(type_name,created)VALUES(:wtname, :createdate)");
		$stmt->bindparam(":wtname", $wtname);
		$stmt->bindparam(":createdate", $createdate);
		if($stmt->execute())
		{
			return $stmt; 	
		}
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}    
}
	
public function getWorkTypeList()
{
	try
	{
		$stmt = $this->db->prepare("SELECT * FROM worktype");
		$stmt->execute();
		$workRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
	    if($stmt->rowCount() > 0)
	    {
	    	return $workRow;
	    } 
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}  	
}

public function getWorkTypeDetial($id)
{
	try
	{
		$stmt = $this->db->prepare("SELECT * FROM worktype WHERE `id` = :wtid");
		$stmt->bindparam(":wtid", $id);
		$stmt->execute();
		$workRow=$stmt->fetch(PDO::FETCH_ASSOC);
	    if($stmt->rowCount() > 0)
	    {
	    	return $workRow;
	    } 
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	} 	
}

public function updateWorkType($wtname, $id)
{
	try
	{
		$stmt = $this->db->prepare("UPDATE worktype SET `type_name` = :typeName WHERE `id` = :wtid");
		$stmt->bindparam(":typeName", $wtname);
		$stmt->bindparam(":wtid", $id);
		if($stmt->execute())
	    {
	    	return $stmt;
	    } 
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	} 	
}
	
public function deleteWorkType($id)
{
    try
    {
		$stmt = $this->db->prepare("DELETE FROM worktype WHERE id= :id");
		$stmt->bindparam(":id", $id);	
		$stmt->execute(); 
		return $stmt; 
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }    
}

public function redirect($url)
{
    header("Location: $url");
}


}
?>