<?php
class ROLES
{
    private $db;
 
    function __construct($DB_con)
    {
      $this->db = $DB_con;
    }
 
    public function insert($role_name,$permissions)
    {
       try
       {
		  $role_permissions = json_encode($permissions);

           $stmt = $this->db->prepare("INSERT INTO roles(role_name,capability)VALUES(:role_name, :capability)");
           $stmt->bindparam(":role_name", $role_name);
           $stmt->bindparam(":capability", $role_permissions);
           $stmt->execute(); 
		   
           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
    }
	
	public function updated($role_id,$permissions)
    {
       try
       {
		  
	$role_permissions = json_encode($permissions);
   
           $stmt = $this->db->prepare("UPDATE roles SET capability=:capability WHERE role_id=:role_id");
		   $stmt->bindparam(":role_id", $role_id);	  
		   $stmt->bindparam(":capability", $role_permissions);
           $stmt->execute(); 
		   
		   
           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
    }
	
	public function deleted($role_id)
    {
       try
       {
		  $status = 'Inactive';
		   $stmt = $this->db->prepare("UPDATE roles SET status=:status WHERE role_id=:role_id");
		   $stmt->bindparam(":role_id", $role_id);	
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
   
   
   public function getrole($role_id)
   {
       try
       {
          $stmt = $this->db->prepare("SELECT * FROM roles WHERE role_id=:role_id LIMIT 1");
          $stmt->execute(array(':role_id'=>$role_id));
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);

		  return $userRow;
          
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
   }

  
    public function getroleslist()
   {
        try
		{
          $stmt = $this->db->prepare("SELECT * FROM roles WHERE	status NOT IN ('Inactive')");
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
   
   
    public function getpermisionlist()
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
   public function getpermisionurl($url)
   {
        try
		{
          $stmt = $this->db->prepare("SELECT * FROM permissions WHERE status NOT IN ('Inactive') AND perm_url=:perm_url LIMIT 1");
		  $stmt->bindparam(":perm_url", $url);
		  $stmt->execute();
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
		  
			return $userRow;
		  
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
   }
  
   public function getpermisionlistcategory($perm_category)
   {
        try
		{
          $stmt = $this->db->prepare("SELECT * FROM permissions WHERE perm_category=:perm_category AND status NOT IN ('Inactive')");
		  $stmt->bindparam(":perm_category", $perm_category);
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

public function user_role_per($role_name)
   {
        try
		{
          $stmt = $this->db->prepare("SELECT capability FROM roles WHERE role_name=:role_name");
		  $stmt->bindparam(":role_name", $role_name);
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