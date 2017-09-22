<?php
class PERMISSION
{
    private $db;
 
    function __construct($DB_con)
    {
      $this->db = $DB_con;
    }
 
    public function insert($perm_name,$perm_desc,$perm_url,$perm_order,$perm_category)
    {
       try
       {
		  $perm_status = 'Active';

           $stmt = $this->db->prepare("INSERT INTO permissions(perm_name,perm_desc,perm_url,status,perm_order,perm_category)VALUES(:perm_name, :perm_desc, :perm_url, :perm_status, :perm_order, :perm_category)");
           $stmt->bindparam(":perm_name", $perm_name);
		   $stmt->bindparam(":perm_desc", $perm_desc);
		   $stmt->bindparam(":perm_url", $perm_url);
		   $stmt->bindparam(":perm_status", $perm_status);
		   $stmt->bindparam(":perm_order", $perm_order);
		   $stmt->bindparam(":perm_category", $perm_category);
           $stmt->execute(); 
		   
           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
    }
	
	public function updated($perm_id,$perm_name,$perm_desc,$perm_url,$perm_status,$perm_order,$perm_category)
    {
       try
       {
		  
           $stmt = $this->db->prepare("UPDATE permissions SET perm_name=:perm_name, perm_desc=:perm_desc, perm_url=:perm_url, status=:perm_status, perm_order=:perm_order, perm_category=:perm_category WHERE perm_id=:perm_id");
		   $stmt->bindparam(":perm_id", $perm_id);	  
		   $stmt->bindparam(":perm_name", $perm_name);
		   $stmt->bindparam(":perm_desc", $perm_desc);
		   $stmt->bindparam(":perm_url", $perm_url);
		   $stmt->bindparam(":perm_status", $perm_status);
		   $stmt->bindparam(":perm_order", $perm_order);
		    $stmt->bindparam(":perm_category", $perm_category);
           $stmt->execute(); 
		   
		   
           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
    }
	
	public function deleted($perm_id)
    {
       try
       {
		  $status = 'Inactive';
		   $stmt = $this->db->prepare("UPDATE permissions SET status=:status WHERE perm_id=:perm_id");
		   $stmt->bindparam(":perm_id", $perm_id);	
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
   
   
   public function getperm($perm_id)
   {
       try
       {
          $stmt = $this->db->prepare("SELECT * FROM permissions WHERE perm_id=:perm_id LIMIT 1");
          $stmt->execute(array(':perm_id'=>$perm_id));
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);

		  return $userRow;
          
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
   }

  
    public function getpermlist()
   {
        try
		{
          $stmt = $this->db->prepare("SELECT * FROM permissions WHERE status NOT IN ('Inactive')");
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
   
 public function permissioncategory()
   {
        try
		{
          $stmt = $this->db->prepare("SELECT * FROM permission_category");
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
}
?>