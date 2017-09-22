<?php
class FARMS
{
  private $db;
   
  function __construct($DB_con)
  {
    $this->db = $DB_con;
  }
 
  public function getfarmslist()
  {
    try
	  {
      $stmt = $this->db->prepare("SELECT * FROM farms WHERE status = 'active' ORDER BY `updated` DESC");
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

  public function insert($fname,$fblock, $flat,$flong, $blockArray)
  {
    try
    {
      $created = date("Y-m-d H:i:s"); 
      $updated = date("Y-m-d H:i:s");
      $fprice = 1000; 
      $stmt = $this->db->prepare("INSERT INTO farms(name, blocks, price, lat, lng, created, updated)VALUES(:fname, :fblock, :fprice, :flat, :flong, :created, :updated)");
      $stmt->bindparam(":fname", $fname);
      $stmt->bindparam(":fblock", $fblock);
      $stmt->bindparam(":fprice", $fprice);
      $stmt->bindparam(":flat", $flat);
      $stmt->bindparam(":flong", $flong);
      $stmt->bindparam(":created", $created);
      $stmt->bindparam(":updated", $updated);
      // print_r($stmt); die;
      $stmt->execute(); 
      $farm_id = $this->db->lastInsertId();
      if($farm_id )
      {
        foreach($blockArray as $block)
        {
          $stmt1 = $this->db->prepare("
            INSERT INTO blocks(farm_id, fruit_id, block_size)VALUES(:fid, :fruitid, :bsize)");
          $stmt1->bindparam(":fid", $farm_id);
          $stmt1->bindparam(":fruitid", $block['fruitName']);
          $stmt1->bindparam(":bsize", $block['blockName']);
          $stmt1->execute();   
        }
      }
      return $stmt; 
    }
    catch(PDOException $e)
    {
      echo $e->getMessage();
    }    
  } 

  public function deletefarms($farmId)
  {
    try
    {
      $status = 'inactive';
      $stmt = $this->db->prepare("UPDATE farms SET status= :status WHERE id=:id");
      $stmt->bindparam(":id", $farmId);
      $stmt->bindparam(":status", $status);
      $stmt->execute();
      return $stmt;
    }
    catch(PDOException $e)
    {
      echo $e->getMessage();
    }
  }

  public function getfarmsdetail($id)
  {
    try
    {
      $stmt = $this->db->prepare("SELECT  `farms`.`id` AS `fid`, `farms`.`name` AS address, `farms`.`lat` AS lat, `farms`.`lng` AS lng FROM farms WHERE `farms`.`status` = 'active' AND `farms`.`id` = '".$id."' ");
      $stmt->execute();
      $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
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

  public function getAllBlocks($id)
  {
    try
    {
      $stmt = $this->db->prepare("SELECT `blocks`.`id` AS `id`, `blocks`.`fruit_id` AS fruit_id, `blocks`.`block_size` AS block_size, `fruits`.`fruit_image_path` as fruit_image_path FROM blocks INNER JOIN `fruits` ON `blocks`.`fruit_id` = `fruits`.`fruit_id` WHERE `blocks`.`farm_id` = '".$id."' ");
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

  public function deletefarmBlock($farmId, $blockId)
  {
    
    
    try
    {
      $stmt = $this->db->prepare("DELETE FROM blocks WHERE id=:blockId AND farm_id=:farmId");
      $stmt->bindparam(":blockId", $blockId);
      $stmt->bindparam(":farmId", $farmId);
      if($stmt->execute())
      {
        $countFarmBlocks = $this->countFarmBlocks($farmId);
        $updateCount = $countFarmBlocks['blocks'] - 1;
        $stmt1 = $this->db->prepare("UPDATE farms SET `blocks` = :updateCount WHERE id= :farmId ");
        $stmt1->bindparam(":farmId", $farmId);
        $stmt1->bindparam(":updateCount", $updateCount);
        $stmt1->execute();
        return true;
      } 
    }
    catch(PDOException $e)
    {
     echo $e->getMessage();
    }
  }

  public function countFarmBlocks($farmId)
  {
    try
    {
      $stmt = $this->db->prepare("SELECT `blocks` FROM farms WHERE id = '".$farmId."'");
      $stmt->execute();
      $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
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

  public function update($fname,$fblock, $flat,$flong, $fid)
  {
    try
    {
      $updated = date("Y-m-d H:i:s");
      $stmt = $this->db->prepare("UPDATE farms SET name= :fname, blocks= :fblock, lat= :flat, lng= :flong, updated= :updated WHERE id=:fid ");
      $stmt->bindparam(":fname", $fname);
      $stmt->bindparam(":fblock", $fblock);
      $stmt->bindparam(":fid", $fid);
      $stmt->bindparam(":flat", $flat);
      $stmt->bindparam(":flong", $flong);
      $stmt->bindparam(":updated", $updated);
      $stmt->execute(); 
      return $stmt; 
    }
    catch(PDOException $e)
    {
      echo $e->getMessage();
    }    
  } 
  
  public function updateBlocks($blockArray, $fid)
  {
    try
    {
      foreach($blockArray as $block)
        {
          $stmt1 = $this->db->prepare("UPDATE blocks SET fruit_id= :fruitid, block_size= :bsize WHERE farm_id= :fid AND id= :bid");
          $stmt1->bindparam(":fid", $fid);
          $stmt1->bindparam(":bid", $block['blockId']);
          $stmt1->bindparam(":fruitid", $block['fruitName']);
          $stmt1->bindparam(":bsize", $block['blockName']);
          $stmt1->execute(); 
        }
      return true;
    }
    catch(PDOException $e)
    {
      echo $e->getMessage();
    }    
  } 

  public function insertBlocks($blockArray, $fid)
  {
    try
    {
      foreach($blockArray as $block)
        {
          $stmt1 = $this->db->prepare("INSERT INTO blocks (farm_id, fruit_id, block_size) VALUES( :fid, :fruitid, :bsize)");
          $stmt1->bindparam(":fid", $fid);
          $stmt1->bindparam(":fruitid", $block['fruitName']);
          $stmt1->bindparam(":bsize", $block['blockName']);
          $stmt1->execute();   
        }
      return true;
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