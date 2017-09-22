<?php
class FRUIT
{
   private $db;
 
   function __construct($DB_con)
    {
      $this->db = $DB_con;
    }
 
public function insert($fvname,$fruitimgpath,$fruitimgname)
{
	try
	{
		$createdate= date("Y-m-d H:i:s"); 
		
		
		$stmt = $this->db->prepare("INSERT INTO fruits(fruit_name,fruit_image_path,fruit_image_name,created_date)VALUES(:fvname, :fvimagepath, :fvimagename, :createdate)");
		$stmt->bindparam(":fvname", $fvname);
		$stmt->bindparam(":fvimagepath", $fruitimgpath);
		$stmt->bindparam(":fvimagename", $fruitimgname);
		$stmt->bindparam(":createdate", $createdate);
		$stmt->execute(); 
		$user_id = $this->db->lastInsertId();
		if($user_id )
			{
			
			}
		return $stmt; 
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}    
}
	
public function updated($fruit_id,$fvname,$fruitimgpath,$fruitimgname,$status)
{
    try
	{	
		$updatedate = date("Y-m-d H:i:s"); 
		
		$stmt = $this->db->prepare("UPDATE fruits SET fruit_name=:fvname, fruit_image_path=:fruitimgpath, 	fruit_image_name=:fruitimgname, status=:status WHERE fruit_id=:fruit_id");
		$stmt->bindparam(":fruit_id", $fruit_id);	  
        $stmt->bindparam(":fvname", $fvname);
		$stmt->bindparam(":fruitimgpath", $fruitimgpath);
		$stmt->bindparam(":fruitimgname", $fruitimgname);
		$stmt->bindparam(":status", $status);
        $stmt->execute(); 
        
		return $stmt; 
	}
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }    
}
	
public function deleted($fruitid)
{
    try
    {
		$status = 'Inactive';
		$stmt = $this->db->prepare("UPDATE fruits SET status=:status WHERE fruit_id=:fruitid");
		$stmt->bindparam(":fruitid", $fruitid);	
		$stmt->bindparam(":status", $status);  
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
   
public function getfruitslist()
{
    try
	{
        $stmt = $this->db->prepare("SELECT * FROM fruits WHERE	status NOT IN ('Inactive')");
		$stmt->execute();
        $userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
		if($stmt->rowCount() > 0)
        {
			return $userRow;
		} 
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}
}

public function getfruitbyid($fruitid)
{
    try
	{
        $stmt = $this->db->prepare("SELECT * FROM fruits WHERE fruit_id=".$fruitid." AND status NOT IN ('Inactive')");
		$stmt->execute();
        $fruitRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $fruitRow;
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}
}

}
?>