<?php
class Equipment
 {
   private $db;
     
   function __construct($DB_con)
        {
          $this->db = $DB_con;
        }
     
   public function register($equi_name,$equi_type,$equi_size,$noti_user_name)
	{
	try
		{	
			$stmt = $this->db->prepare("SELECT * FROM equipment WHERE	equi_status NOT IN ('Inactive') ORDER by equi_order ASC");
			$stmt->execute();
			$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
			$order = $stmt->rowCount();
			$order += 1;
			$equi_order = $order; 
			$equi_sizes = json_encode($equi_size);
			$equi_status = 'Active';
			$createdate= date("Y-m-d H:i:s"); 
			$stmt = $this->db->prepare("INSERT INTO equipment(equi_name,equi_type,equi_size,equi_order,equi_status,created_date,updated_date)VALUES(:equi_name, :equi_type, :equi_size, :equi_order, :equi_status, :created_date, :updated_date)");
			
			$stmt->bindparam(":equi_name", $equi_name);
			$stmt->bindparam(":equi_type", $equi_type);
			$stmt->bindparam(":equi_size", $equi_sizes);
			$stmt->bindparam(":equi_order", $equi_order);
			$stmt->bindparam(":equi_status", $equi_status);
			$stmt->bindparam(":created_date", $createdate);
			$stmt->bindparam(":updated_date", $createdate);
			$stmt->execute();
			
			$eq_id = $this->db->lastInsertId();
			
			if(!empty($eq_id) )
				{
					for($i=0;$i<count($equi_size);$i++)
						{
							$stmt = $this->db->prepare("INSERT INTO equiment_size(equipment_id,equipments_size)VALUES(:equipment_id, :equipment_size)");
							$stmt->bindparam(":equipment_id", $eq_id);
							$stmt->bindparam(":equipment_size", $equi_size[$i]);
							//$stmt->bindparam(":equi_size", $equi_sizes);
							$stmt->execute();
						}
				}
			///// equipment notifications 
			if(!empty($eq_id) )
				{
					$stmt = $this->db->prepare("SELECT * FROM users WHERE type in ('SuperAdmin','Admin','General','Dispatch') AND	status NOT IN ('Inactive')");
					$stmt->execute();
					$userfields=$stmt->fetchAll(PDO::FETCH_ASSOC);
					foreach($userfields as $userfield){
					$userid = $userfield['user_id'];
					$noti_type = 'Added a new equipment';
					$noti_status = 'Unseen';
					$createdatenoti= date("Y-m-d H:i:s"); 
					$noti_jobtimestamp = strtotime($createdatenoti);	
					$stmt = $this->db->prepare("INSERT INTO equipment_notification(	equi_noti_user_id,equi_noti_jobtimestamp,equi_noti_type,equi_noti_status,created_date,updated_date,equipment_id,noti_user_name)VALUES(:noti_user_id, :noti_jobtimestamp, :noti_type, :noti_status, :created_date, :updated_date, :eq_id, :noti_user_name)");
					$stmt->bindparam(":noti_user_id", $userid);  
					$stmt->bindparam(":eq_id", $eq_id);
					$stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
					$stmt->bindparam(":noti_type", $noti_type);
					$stmt->bindparam(":noti_status", $noti_status);
					$stmt->bindparam(":created_date", $createdatenoti);
					$stmt->bindparam(":updated_date", $createdatenoti);
					$stmt->bindparam(":noti_user_name", $noti_user_name);
					$stmt->execute(); 	
					
					}
				}  
		
		return $stmt; 
		}
	catch(PDOException $e)
		{
			echo $e->getMessage();
		}    
	}
        
   public function updated($equi_id,$equi_name,$equi_type,$equi_size,$equi_status,$noti_user_name)
        {
           try
           { 
				$updated_date = date("Y-m-d H:i:s"); 
				$equi_sizes = json_encode($equi_size);
				$stmt = $this->db->prepare("UPDATE equipment SET equi_name=:equi_name, equi_type=:equi_type, equi_size=:equi_size, equi_status=:equi_status, updated_date=:updated_date WHERE equi_id=:equi_id");
				$stmt->bindparam(":equi_id", $equi_id);
				$stmt->bindparam(":equi_name", $equi_name);
				$stmt->bindparam(":equi_type", $equi_type);
				$stmt->bindparam(":equi_size", $equi_sizes);
				$stmt->bindparam(":equi_status", $equi_status);
				$stmt->bindparam(":updated_date", $updated_date);
				$stmt->execute();
				$stmt = $this->db->prepare("SELECT * FROM equiment_size WHERE equipment_id=:equi_id");
				$stmt->execute(array(':equi_id'=>$equi_id));
				$equisize=$stmt->fetchAll(PDO::FETCH_ASSOC);
				$k = 0;
				$equisizearray = array();
				$last_size = end($equi_size);
			 	foreach($equisize as $equisizes)
				{
				   $equi_ids = $equisizes['id'];
				   $equisi = $equisizes['equipments_size'];
				  for($i=0;$i<count($equisize)-2;$i++){
					  if($i===$k)
					  {
							$stmt = $this->db->prepare("UPDATE equiment_size SET equipments_size=:equipments_size WHERE equipment_id=:equi_id AND id=:id");
							$stmt->bindparam(":equi_id", $equi_id);
							$stmt->bindparam(":equipments_size", $equisizes['equipments_size']);
							$stmt->bindparam(":id", $equi_ids); 
							$stmt->execute(); 
			   
					 }
					  
			 	 }
				$k++; 
				 }
				
					foreach($equisize as $equipsize)
						{ 
							$equisizearray[] = $equipsize['equipments_size'];
						}
						for($i=0;$i<count($equi_size);$i++)
						{
							if (!in_array($equi_size[$i], $equisizearray))
							{
								$stmt = $this->db->prepare("INSERT INTO equiment_size(equipment_id,equipments_size)VALUES(:equipment_id, :equipment_size)");
								$stmt->bindparam(":equipment_id", $equi_id);
								$stmt->bindparam(":equipment_size", $equi_size[$i]);
								$stmt->execute();
							}
						}
                // edit equipment notification
					$stmt = $this->db->prepare("SELECT * FROM equipment_notification WHERE equipment_id=:equi_id LIMIT 1");
					$stmt->execute(array(':equi_id'=>$equi_id));
					$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
					$eq_row = $stmt->rowCount();
                 	if($eq_row > 0) 
					 {
                       $noti_type = 'Edited equipment';
                       $noti_status = 'Unseen';
                       $createdatenoti= date("Y-m-d H:i:s"); 
                       $noti_jobtimestamp = strtotime($createdatenoti);	
                        $stmt = $this->db->prepare("UPDATE equipment_notification SET equi_noti_jobtimestamp=:noti_jobtimestamp, equi_noti_type=:noti_type, equi_noti_status=:noti_status, updated_date=:updated_date, noti_user_name=:noti_user_name WHERE equipment_id=:eq_id");
                       $stmt->bindparam(":eq_id", $equi_id);
                       $stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
                       $stmt->bindparam(":noti_type", $noti_type);
                       $stmt->bindparam(":noti_status", $noti_status);
                       $stmt->bindparam(":updated_date", $createdatenoti);
					   $stmt->bindparam(":noti_user_name", $noti_user_name);
                       $stmt->execute();
                	 }
				 
				 else 
				 	{
                    
					  $stmt = $this->db->prepare("SELECT * FROM users WHERE type in ('SuperAdmin','Admin','General','Dispatch') AND	status NOT IN ('Inactive')");
					  $stmt->execute();
					  $userfields=$stmt->fetchAll(PDO::FETCH_ASSOC);
                   	  foreach($userfields as $userfield)
					  	{
							$userid = $userfield['user_id'];
							$noti_type = 'Added a new equipment';
							$noti_status = 'Unseen';
							$createdatenoti= date("Y-m-d H:i:s"); 
							$noti_jobtimestamp = strtotime($createdatenoti);	
							$stmt = $this->db->prepare("INSERT INTO equipment_notification(	equi_noti_user_id,equi_noti_jobtimestamp,equi_noti_type,equi_noti_status,created_date,updated_date,equipment_id,noti_user_name)VALUES(:noti_user_id, :noti_jobtimestamp, :noti_type, :noti_status, :created_date, :updated_date, :eq_id, :noti_user_name)");
							$stmt->bindparam(":noti_user_id", $userid);  
							$stmt->bindparam(":eq_id", $equi_id);
							$stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
							$stmt->bindparam(":noti_type", $noti_type);
							$stmt->bindparam(":noti_status", $noti_status);
							$stmt->bindparam(":created_date", $createdatenoti);
							$stmt->bindparam(":updated_date", $createdatenoti);
							$stmt->bindparam(":noti_user_name", $noti_user_name);
							$stmt->execute();  	
                        
                        }
                   }
               // end of edit job notifications 
               return $stmt; 
           }
        catch(PDOException $e)
           {
               echo $e->getMessage();
           }    
        }
        
  public function deleted($equi_id)
        {
           try
           {   $equi_status = 'Inactive';
               $stmt = $this->db->prepare("UPDATE equipment SET equi_status=:equi_status WHERE equi_id=:equi_id");
               $stmt->bindparam(":equi_id", $equi_id);
               $stmt->bindparam(":equi_status", $equi_status);	  
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
       
   public function equipmentdata($equi_id)
       {
           try
           {
              $stmt = $this->db->prepare("SELECT * FROM equipment WHERE equi_id=:equi_id LIMIT 1");
              $stmt->execute(array(':equi_id'=>$equi_id));
              $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
    
              return $userRow;
              
           }
           catch(PDOException $e)
           {
               echo $e->getMessage();
           }
       }
     
       
   public function getequipmentlist()
       {
            try
            {
              $stmt = $this->db->prepare("SELECT * FROM equipment WHERE	equi_status NOT IN ('Inactive') ORDER by equi_order ASC");
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
	   
	   
   public function equipmentlist($csearch)
       {
            try
            {
				 $csearch = '%'.$csearch.'%';
              $stmt = $this->db->prepare("SELECT * FROM equipment WHERE equi_name LIKE :equi_name OR equi_size LIKE :equi_name AND equi_status NOT IN ('Inactive') ORDER by equi_order ASC");
			  $stmt->bindparam(":equi_name", $csearch);
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
	   
       
   public function equimentsize($equi_id)
       {
            try
            {
              $stmt = $this->db->prepare("SELECT * FROM equiment_size WHERE equipment_id=:equipment_id AND 	status NOT IN ('Inactive') ORDER BY id ASC");
			  $stmt->bindparam(":equipment_id", $equi_id);
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
	   
  public function jobsequisize($equi_id)
       {
            try
            {
              $stmt = $this->db->prepare("SELECT * FROM equiment_size WHERE id=:id LIMIT 1");
			  $stmt->bindparam(":id", $equi_id);
              $stmt->execute();
              $userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
              
                return $userRow;
              
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
       }
  public function equimentsizedata($equi_id)
       {
            try
            {
              $stmt = $this->db->prepare("SELECT * FROM equiment_size WHERE id=:id");
			  $stmt->bindparam(":id", $equi_id);
              $stmt->execute();
              $userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
             
                return $userRow;
               
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
       }
	   
	   
  public function jobsequisize_report($equi_id)
       {
            try
            {
              $stmt = $this->db->prepare("SELECT * FROM equiment_size WHERE id=:id");
			  $stmt->bindparam(":id", $equi_id);
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
	   
  public function equimentsizes()
       {
            try
            {
              $stmt = $this->db->prepare("SELECT * FROM equiment_size");
			  //$stmt->bindparam(":equipment_id", $equi_id);
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
	   
  public function equimentsizeedit($equi_id,$equisize)
       {
            try
            {
              $stmt = $this->db->prepare("SELECT * FROM equiment_size WHERE equipment_id=:equipment_id AND id=:equipments_size AND 	status NOT IN ('Inactive') ORDER BY id ASC");
			  $stmt->bindparam(":equipment_id", $equi_id);
			  $stmt->bindparam(":equipments_size", $equisize);
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
       
       
  public function getequipmentData($query1)
	{
	try
		{
		
			$stmt = $this->db->prepare("SELECT eq.equi_id,eq.equi_name,eq.equi_size,eqjob.id,eqjob.job_equi_size,eqjob.change_eq_size,eqjob.change_eq_id,eqjob.job_time_leaving_yard,eqjob.job_time_arrived_back_at_yard,TIMEDIFF(STR_TO_DATE(eqjob.job_time_leaving_yard, '%h:%i %p'),STR_TO_DATE(job_time_arrived_back_at_yard, '%h:%i %p')) as totalWorkingHours,TIMEDIFF(STR_TO_DATE(eqjob.job_time, '%h:%i %p'),STR_TO_DATE(job_finishtime, '%h:%i %p')) as totalHours,eqjob.job_time as job_starttime,eqjob.job_finishtime  FROM equipment as eq LEFT JOIN job as eqjob ON eq.equi_id=eqjob.job_equi_id   WHERE eq.equi_status NOT IN ('Inactive') AND job_status_billed='1' $query1 ORDER BY eqjob.change_eq_size ASC;");
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
       
  public function getalljob()
	{
	try
		{
			$stmt = $this->db->prepare("SELECT * FROM job");
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
       
  public function getstaffData($query)
	{
	try
		{
		
			$stmt = $this->db->prepare("SELECT *  FROM `users` WHERE $query AND status NOT IN ('Inactive') GROUP BY user_id");
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
       
  public function get_start_end($user_id,$query2)
	{
	try
		{ 
			$stmt = $this->db->prepare("SELECT id as job_id, TIMEDIFF(STR_TO_DATE(job_time, '%h:%i %p'),STR_TO_DATE(job_finishtime, '%h:%i %p')) as totalHours FROM `job` WHERE $user_id $query2 ORDER by change_eq_size ASC ");
			
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
       
  public function getFilterData($query)
	{
	try
		{
			$stmt = $this->db->prepare("SELECT eq.equi_id,eq.equi_name,eq.equi_size,eqjob.id,eqjob.job_clie_id,eqjob.change_eq_size,eqjob.change_eq_id,cust.cust_name,eqjob.job_address,eqjob.job_time_leaving_yard,eqjob.job_time_arrived_back_at_yard,TIMEDIFF(STR_TO_DATE(eqjob.job_time_leaving_yard, '%h:%i %p'),STR_TO_DATE(job_time_arrived_back_at_yard, '%h:%i %p')) as totalWorkingHours,TIMEDIFF(STR_TO_DATE(eqjob.job_time, '%h:%i %p'),STR_TO_DATE(job_finishtime, '%h:%i %p')) as totalHours,eqjob.job_time as job_starttime,eqjob.job_finishtime  FROM equipment as eq LEFT JOIN job as eqjob ON eq.equi_id=eqjob.change_eq_id LEFT JOIN customer as cust ON cust.cust_id = eqjob.job_clie_id WHERE eq.equi_status NOT IN ('Inactive') AND job_status_billed='1' $query ORDER by eq.equi_order ASC");
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
	
  public function crosshiredata($croshiredata)
	{
	try
		{
			$stmt = $this->db->prepare("SELECT eq.equi_id,eq.equi_name,eq.equi_size,eqjob.id,eqjob.job_equi_size,eqjob.job_date,eqjob.job_cross_hire,eqjob.change_eq_size,eqjob.change_eq_id,eqjob.job_time_leaving_yard,eqjob.job_time_arrived_back_at_yard,TIMEDIFF(STR_TO_DATE(eqjob.job_time_leaving_yard, '%h:%i %p'),STR_TO_DATE(job_time_arrived_back_at_yard, '%h:%i %p')) as totalWorkingHours,TIMEDIFF(STR_TO_DATE(eqjob.job_time, '%h:%i %p'),STR_TO_DATE(job_finishtime, '%h:%i %p')) as totalHours,eqjob.job_time as job_starttime,eqjob.job_finishtime  FROM equipment as eq LEFT JOIN job as eqjob ON eq.equi_id=eqjob.job_equi_id   WHERE eq.equi_status NOT IN ('Inactive') AND job_status_billed='1' AND job_cross_hire='1' $croshiredata ORDER BY eqjob.change_eq_size ASC;");
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
	   
	   
	   
  public function getcrosshireData($query)
	{
	try
		{
			$stmt = $this->db->prepare("SELECT *  FROM `equipment` WHERE $query AND equi_status NOT IN ('Inactive') GROUP BY equi_id");
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
      
	  
  public function equipmentadd($equi_id,$equisize_id,$licenses_name,$equisizeattachmenturl,$equisizeattachmentname,$equisizeattachmenturl1,$equisizeattachmentname1,$equicheck,$noti_user_name,$last_service,$equisizesecureattach,$equisizesecureattachname,$year,$make,$modal,$vinnumber,$fueltype,$rego){
	   try
		{
			$result = count($equisizeattachmenturl1);
			if(is_array($equisizeattachmenturl1) && is_array($equisizeattachmentname1))
				{
				for($i=0; $i < $result; $i++)
					{
						$customerattachmentdetail[] = array('imagepath' => $equisizeattachmenturl1[$i], 'imagename' => $equisizeattachmentname1[$i] ); 
					}
				} 
			
			if(is_array($equisizesecureattach) && is_array($equisizesecureattachname))
				{
					for($i=0; $i < count($equisizesecureattach); $i++)
						{
							$equisizesecureattachattach[] = array('imagepath' => $equisizesecureattach[$i], 'imagename' => $equisizesecureattachname[$i] ); 
						}
				} 
			$secureattachattach = $equisizesecureattachattach;
			$secureattachattach_imagepath = json_encode($secureattachattach);
			$equiimagepath = $customerattachmentdetail;
			$equipment_imagepath = json_encode($equiimagepath);
			
			$stmt = $this->db->prepare("UPDATE equiment_size SET attachment=:attachment,secure_attachment=:secure_attachment WHERE equipment_id=:equipment_id AND id=:id"); 
			$stmt->bindparam(":equipment_id", $equi_id);
			$stmt->bindparam(":id", $equisize_id);	
			$stmt->bindparam(":attachment", $equipment_imagepath);
			$stmt->bindparam(":secure_attachment", $secureattachattach_imagepath);
			$stmt->execute(); 
			
			$indexcount = count($licenses_name['name']);
			for($i=0;$i<$indexcount;$i++)
				{
					$equilists[] = array( 'name' => $licenses_name['name'][$i], 'date' => $licenses_name['date'][$i], 'imagepath' => $equisizeattachmenturl[$i], 'imagename' => $equisizeattachmentname[$i],'date_diff' => $equicheck[$i],);
				}
			foreach($equilists as $equilist)
				{
					if($equilist['name']!='')
						{
							$equi_name = $equilist['name'];
							$equi_date = $equilist['date'];
							$attachimagepath = $equilist['imagepath'];
							$date_difference = $equilist['date_diff'];
							$equi_timestamp= strtotime(date("Y-m-d H:i:s"));
							$equi_status = 'Active';
							$stmt = $this->db->prepare("INSERT INTO equipment_size_attachment(equipment_size_id,equipment_license_name,equipment_license_date,attachment,date_difference,equi_status,equi_timestamp,equi_updatedby,last_service,year,make,modal,vinnumber,fueltype,rego)VALUES(:equipment_size_id, :equipment_license_name, :equipment_license_date, :attachment, :date_difference, :equi_status, :equi_timestamp, :equi_updatedby, :last_service, :year, :make, :modal, :vinnumber, :fueltype, :rego)");
							
							$stmt->bindparam(":equipment_size_id", $equisize_id);
							$stmt->bindparam(":equipment_license_name", $equi_name);
							$stmt->bindparam(":equipment_license_date", $equi_date);
							$stmt->bindparam(":attachment", $attachimagepath);
							$stmt->bindparam(":date_difference", $date_difference);
							$stmt->bindparam(":equi_status", $equi_status);
							$stmt->bindparam(":equi_timestamp", $equi_timestamp);
							$stmt->bindparam(":equi_updatedby", $noti_user_name);
							$stmt->bindparam(":last_service", $last_service);
							$stmt->bindparam(":year", $year);
						    $stmt->bindparam(":make", $make);
						    $stmt->bindparam(":modal", $modal);
						    $stmt->bindparam(":vinnumber", $vinnumber);
						    $stmt->bindparam(":fueltype", $fueltype);
						    $stmt->bindparam(":rego", $rego);
							$stmt->execute();
							
							
							$lisence_id = $this->db->lastInsertId();
							
							if(!empty($equilist['date']))
								{
								if($equilist['date_diff'] == '1month' || $equilist['date_diff'] == '3month' || $equilist['date_diff'] == 'yearly' || $equilist['date_diff'] == '6month')
									{
										$stmt = $this->db->prepare("INSERT INTO equipment_date_history(license_id,equipment_size_id,equipment_date,license_name)VALUES(:license_id, :equipment_size_id, :equipment_date, :license_name)");
										
										$stmt->bindparam(":license_id", $lisence_id);
										$stmt->bindparam(":equipment_size_id", $equisize_id);
										$stmt->bindparam(":equipment_date", $equi_date);
										$stmt->bindparam(":license_name", $equi_name);
										$stmt->execute();
									}
								}
							
							$stmt = $this->db->prepare("INSERT INTO equipment_size_lisence_attachment(lisence_id,equipment_size_id,attachment)VALUES(:lisence_id, :equipment_size_id, :attachment)");
							
							$stmt->bindparam(":lisence_id", $lisence_id);
							$stmt->bindparam(":equipment_size_id", $equisize_id);
							$stmt->bindparam(":attachment", $attachimagepath);
							$stmt->execute();
						}
				}
			
			if(!empty($equisize_id) )
				{
					$stmt = $this->db->prepare("SELECT * FROM equipment_notification WHERE equipments_size_id=:equipments_size_id LIMIT 1");
					$stmt->execute(array(':equipments_size_id'=>$equisize_id));
					$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
					$eq_row = $stmt->rowCount();
					if($eq_row > 0) 
						{
							$noti_type = 'Edited equipment size';
							$noti_status = 'Unseen';
							$createdatenoti= date("Y-m-d H:i:s"); 
							$noti_jobtimestamp = strtotime($createdatenoti);	
							$stmt = $this->db->prepare("UPDATE equipment_notification SET equi_noti_jobtimestamp=:noti_jobtimestamp, equi_noti_type=:noti_type, equi_noti_status=:noti_status, updated_date=:updated_date, noti_user_name=:noti_user_name WHERE equipments_size_id=:equisize_id");
							$stmt->bindparam(":equisize_id", $equisize_id);
							$stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
							$stmt->bindparam(":noti_type", $noti_type);
							$stmt->bindparam(":noti_status", $noti_status);
							$stmt->bindparam(":updated_date", $createdatenoti);
							$stmt->bindparam(":noti_user_name", $noti_user_name);
							$stmt->execute();
						} 
				   else 
					   {
						$stmt = $this->db->prepare("SELECT * FROM users WHERE type in ('SuperAdmin','Admin','General','Dispatch') AND	status NOT IN ('Inactive')");
						$stmt->execute();
						$userfields=$stmt->fetchAll(PDO::FETCH_ASSOC);
						foreach($userfields as $userfield)
							{
								$userid = $userfield['user_id'];
								$noti_type = 'Edited equipment size';
								$noti_status = 'Unseen';
								$createdatenoti= date("Y-m-d H:i:s"); 
								$noti_jobtimestamp = strtotime($createdatenoti);	
								$stmt = $this->db->prepare("INSERT INTO equipment_notification(	equi_noti_user_id,equi_noti_jobtimestamp,equi_noti_type,equi_noti_status,created_date,updated_date,equipments_size_id,noti_user_name)VALUES(:noti_user_id, :noti_jobtimestamp, :noti_type, :noti_status, :created_date, :updated_date, :equipments_size_id, :noti_user_name)");
								$stmt->bindparam(":noti_user_id", $userid);  
								$stmt->bindparam(":equipments_size_id", $equisize_id);
								$stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
								$stmt->bindparam(":noti_type", $noti_type);
								$stmt->bindparam(":noti_status", $noti_status);
								$stmt->bindparam(":created_date", $createdatenoti);
								$stmt->bindparam(":updated_date", $createdatenoti);
								$stmt->bindparam(":noti_user_name", $noti_user_name);
								$stmt->execute();  	
							}
						
						}
				}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	   
	   }
	   
	 public function equipmentupdate($rows_ids,$equi_id,$equisize_id,$licenses_name,$equisizeattachmenturl,$equisizeattachmentname,$equisizeattachmenturl1,$equisizeattachmentname1,$equicheck,$noti_user_name,$picedit_id,$last_service,$equipmentimagename,$equisizesecureattach,$equisizesecureattachname,$year,$make,$modal,$vinnumber,$fueltype,$rego){
	   try
		{
			$result = count($equisizeattachmenturl1);
			
			if(is_array($equisizeattachmenturl1) && is_array($equisizeattachmentname1))
				{
					for($i=0; $i < $result; $i++)
						{
							$customerattachmentdetail[] = array('imagepath' => $equisizeattachmenturl1[$i], 'imagename' => $equisizeattachmentname1[$i] ); 
						}
				} 
			if(is_array($equisizesecureattach) && is_array($equisizesecureattachname))
				{
					for($i=0; $i < count($equisizesecureattach); $i++)
						{
							$equisizesecureattachattach[] = array('imagepath' => $equisizesecureattach[$i], 'imagename' => $equisizesecureattachname[$i] ); 
						}
				} 
			$secureattachattach = $equisizesecureattachattach;
			$secureattachattach_imagepath = json_encode($secureattachattach);
			$equiimagepath = $customerattachmentdetail;
			$equipment_imagepath = json_encode($equiimagepath);
			$stmt = $this->db->prepare("UPDATE equiment_size SET attachment=:attachment,secure_attachment=:secure_attachment WHERE equipment_id=:equipment_id AND id=:id"); 
			$stmt->bindparam(":equipment_id", $equi_id);
			$stmt->bindparam(":id", $equisize_id);	
			$stmt->bindparam(":attachment", $equipment_imagepath);
			$stmt->bindparam(":secure_attachment", $secureattachattach_imagepath);
			$stmt->execute(); 
			$idsss = array();
			$attachmentss = array();
			$indexcount = count($licenses_name['name']);
			for($ii=0;$ii<$indexcount;$ii++)
				{
					$imagename = $licenses_name[$rows_ids[$ii]][1]; 
					$imagepath = $licenses_name[$rows_ids[$ii]][0];
						if(count($licenses_name[$rows_ids[$ii]])==3)
							{
								$imagepath = $licenses_name[$rows_ids[$ii]][2];
							}
					$equilists[] = array( 
					'eid' => $rows_ids[$ii], 
					'name' => $licenses_name['name'][$ii],
					'date' => $licenses_name['date'][$ii], 
					
					'imagepath' =>$imagepath,
					'imagename' => $imagename, 
					
					'date_diff' => $equicheck[$ii]);
				}
			
			
			$stmt = $this->db->prepare("SELECT id FROM equipment_size_attachment WHERE equipment_size_id=:equipment_size_id");
			$stmt->bindparam(":equipment_size_id", $equisize_id);
			$stmt->execute();
			$rowidss = $stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach($rowidss as $rowidss1)
					{ 
						$idsss[] = $rowidss1['id'];
					}
			$checki = 0;
			$a = 0;
			foreach($equilists as $equilist)
				{
					$equi_name = $equilist['name'];
					$equi_date = $equilist['date'];
					$attachimagepath = $equilist['imagepath'];
					$ids = $equilist['eid'];
					$date_difference = $equilist['date_diff'];
					$equi_timestamp= strtotime(date("Y-m-d H:i:s"));
					$equi_status = 'Active';
						if (in_array($ids, $idsss) && $equilist['name']!='')
							{
							
								$stmt = $this->db->prepare("UPDATE equipment_size_attachment SET equipment_license_name=:equipment_license_name, equipment_license_date=:equipment_license_date, attachment=:attachment, date_difference=:date_difference, equi_status=:equi_status, equi_timestamp=:equi_timestamp, equi_updatedby=:equi_updatedby, last_service=:last_service, year=:year, make=:make, modal=:modal, vinnumber=:vinnumber, fueltype=:fueltype, rego=:rego WHERE id=:id");
								
								//$stmt->bindparam(":equipment_size_id", $equisize_id);
								$stmt->bindparam(":equipment_license_name", $equi_name);
								$stmt->bindparam(":equipment_license_date", $equi_date);
								$stmt->bindparam(":attachment", $attachimagepath);
								$stmt->bindparam(":id", $ids);
								$stmt->bindparam(":date_difference", $date_difference);
								$stmt->bindparam(":equi_status", $equi_status);
								$stmt->bindparam(":equi_timestamp", $equi_timestamp);
								$stmt->bindparam(":equi_updatedby", $noti_user_name);
								$stmt->bindparam(":last_service", $last_service);
								$stmt->bindparam(":year", $year);
							    $stmt->bindparam(":make", $make);
							    $stmt->bindparam(":modal", $modal);
							    $stmt->bindparam(":vinnumber", $vinnumber);
							    $stmt->bindparam(":fueltype", $fueltype);
							    $stmt->bindparam(":rego", $rego);
								$stmt->execute();
								
								$stmt = $this->db->prepare("SELECT equipment_date FROM equipment_date_history WHERE license_id=:license_id");
								$stmt->bindparam(":license_id", $ids);
								$stmt->execute();
								$rowdates = $stmt->fetchAll(PDO::FETCH_ASSOC);
									foreach($rowdates as $rowdates1)
										{ 
											$dates[] = $rowdates1['equipment_date'];
										}
								
								if(!empty($equilist['date']))
									{
									if($equilist['date_diff'] == '1month' || $equilist['date_diff'] == '3month' || $equilist['date_diff'] == 'yearly' || $equilist['date_diff'] == '6month' || $equilist['date'] != '')
										{
										if (!in_array($equi_date, $dates))
											{
											
											$stmt = $this->db->prepare("INSERT INTO equipment_date_history(license_id,equipment_size_id,equipment_date,license_name)VALUES(:license_id, :equipment_size_id, :equipment_date, :license_name)");
											
											$stmt->bindparam(":license_id", $ids);
											$stmt->bindparam(":equipment_size_id", $equisize_id);
											$stmt->bindparam(":equipment_date", $equi_date);
											$stmt->bindparam(":license_name", $equi_name);
											$stmt->execute();
											}
										}
									}
								$stmt = $this->db->prepare("SELECT * FROM equipment_size_lisence_attachment WHERE lisence_id=:lisence_id");
								$stmt->bindparam(":lisence_id", $ids);
								$stmt->execute();
								$rowattach = $stmt->fetchAll(PDO::FETCH_ASSOC);
								
								foreach($rowattach as $rowattachss)
									{ 
										$attachmentss[] = $rowattachss['attachment'];
									}
								
								if (!in_array($attachimagepath, $attachmentss) && $equilist['name']!='' && $attachimagepath!='')
									{
										$stmt = $this->db->prepare("INSERT INTO equipment_size_lisence_attachment(lisence_id,equipment_size_id,attachment)VALUES(:lisence_id, :equipment_size_id, :attachment)");
										$stmt->bindparam(":lisence_id", $ids);
										$stmt->bindparam(":equipment_size_id", $equisize_id);
										$stmt->bindparam(":attachment", $attachimagepath);
										$stmt->execute();
									}
							}
					 else
							{
							
							if($equilist['name']!='')
								{
									$attachimagepath =  $equipmentimagename['image'][$a];   
									$stmt = $this->db->prepare("INSERT INTO equipment_size_attachment(equipment_size_id,equipment_license_name,equipment_license_date,attachment,date_difference,year,make,modal,vinnumber,fueltype,rego)VALUES(:equipment_size_id, :equipment_license_name, :equipment_license_date, :attachment, :date_difference, :year, :make, :modal, :vinnumber, :fueltype, :rego)");
									
									$stmt->bindparam(":equipment_size_id", $equisize_id);
									$stmt->bindparam(":equipment_license_name", $equi_name);
									$stmt->bindparam(":equipment_license_date", $equi_date);
									$stmt->bindparam(":attachment", $attachimagepath);
									$stmt->bindparam(":date_difference", $date_difference);
									$stmt->bindparam(":year", $year);
								    $stmt->bindparam(":make", $make);
								    $stmt->bindparam(":modal", $modal);
								    $stmt->bindparam(":vinnumber", $vinnumber);
								    $stmt->bindparam(":fueltype", $fueltype);
								    $stmt->bindparam(":rego", $rego);
									$stmt->execute();
									$lisence_id = $this->db->lastInsertId();
									
									if(!empty($equilist['date']))
										{
										if($equilist['date_diff'] == '1month' || $equilist['date_diff'] == '3month' || $equilist['date_diff'] == 'yearly' || $equilist['date_diff'] == '6month' || $equilist['date'] != '')
											{
											if (!in_array($equi_date, $dates))
												{
												
													$stmt = $this->db->prepare("INSERT INTO equipment_date_history(license_id,equipment_size_id,equipment_date,license_name)VALUES(:license_id, :equipment_size_id, :equipment_date, :license_name)");
													
													$stmt->bindparam(":license_id", $lisence_id);
													$stmt->bindparam(":equipment_size_id", $equisize_id);
													$stmt->bindparam(":equipment_date", $equi_date);
													$stmt->bindparam(":license_name", $equi_name);
													$stmt->execute();
												}
											}
										}
									$stmt = $this->db->prepare("INSERT INTO equipment_size_lisence_attachment(lisence_id,equipment_size_id,attachment)VALUES(:lisence_id, :equipment_size_id, :attachment)");
									
									$stmt->bindparam(":lisence_id", $lisence_id);
									$stmt->bindparam(":equipment_size_id", $equisize_id);
									$stmt->bindparam(":attachment", $attachimagepath);
									$stmt->execute();
								}
								$a++;
							}
					$checki++;
				}
			
			
			
			// edit equipment notification
			$stmt = $this->db->prepare("SELECT * FROM equipment_notification WHERE equipments_size_id=:equipments_size_id LIMIT 1");
			$stmt->execute(array(':equipments_size_id'=>$equisize_id));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			$eq_row = $stmt->rowCount();
			if($eq_row > 0) 
				{
				
					$noti_type = 'Edited equipment size';
					$noti_status = 'Unseen';
					$createdatenoti= date("Y-m-d H:i:s"); 
					$noti_jobtimestamp = strtotime($createdatenoti);	
					$stmt = $this->db->prepare("UPDATE equipment_notification SET equi_noti_jobtimestamp=:noti_jobtimestamp, equi_noti_type=:noti_type, equi_noti_status=:noti_status, updated_date=:updated_date, noti_user_name=:noti_user_name WHERE equipments_size_id=:equisize_id");
					$stmt->bindparam(":equisize_id", $equisize_id);
					$stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
					$stmt->bindparam(":noti_type", $noti_type);
					$stmt->bindparam(":noti_status", $noti_status);
					$stmt->bindparam(":updated_date", $createdatenoti);
					$stmt->bindparam(":noti_user_name", $noti_user_name);
					$stmt->execute();
				} 
			else 
				{
					$stmt = $this->db->prepare("SELECT * FROM users WHERE type in ('SuperAdmin','Admin','General','Dispatch') AND	status NOT IN ('Inactive')");
					$stmt->execute();
					$userfields=$stmt->fetchAll(PDO::FETCH_ASSOC);
					foreach($userfields as $userfield)
						{
							$userid = $userfield['user_id'];
							$noti_type = 'Edited equipment size';
							$noti_status = 'Unseen';
							$createdatenoti= date("Y-m-d H:i:s"); 
							$noti_jobtimestamp = strtotime($createdatenoti);	
							$stmt = $this->db->prepare("INSERT INTO equipment_notification(	equi_noti_user_id,equi_noti_jobtimestamp,equi_noti_type,equi_noti_status,created_date,updated_date,equipments_size_id,noti_user_name)VALUES(:noti_user_id, :noti_jobtimestamp, :noti_type, :noti_status, :created_date, :updated_date, :equipments_size_id, :noti_user_name)");
							$stmt->bindparam(":noti_user_id", $userid);  
							$stmt->bindparam(":equipments_size_id", $equisize_id);
							$stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
							$stmt->bindparam(":noti_type", $noti_type);
							$stmt->bindparam(":noti_status", $noti_status);
							$stmt->bindparam(":created_date", $createdatenoti);
							$stmt->bindparam(":updated_date", $createdatenoti);
							$stmt->bindparam(":noti_user_name", $noti_user_name);
							$stmt->execute();  	
						}
				}
			
		}
		catch(PDOException $e)
			{
				echo $e->getMessage();
			}
	   
   	}
 public function updatejobs($equiids,$equisize,$equisizeid)
	{
	   try
	   {   
		   $stmt = $this->db->prepare("UPDATE job SET job_equi_size=:equisizeid, change_eq_size=:equisizeid WHERE job_equi_size=:equisize AND job_equi_id=:equi_id");
		   $stmt->bindparam(":equi_id", $equiids);
		   $stmt->bindparam(":equisize", $equisize);
		   $stmt->bindparam(":equisizeid", $equisizeid);	  
		   $stmt->execute(); 
		   $stmt = $this->db->prepare("UPDATE job SET job_cranetype_size=:equisizeid WHERE job_cranetype_size=:equisize AND job_cranetype=:equi_id");
		   $stmt->bindparam(":equi_id", $equiids);
		   $stmt->bindparam(":equisize", $equisize);
		   $stmt->bindparam(":equisizeid", $equisizeid);	  
		   $stmt->execute();
		   
		   return $stmt; 
	   }
	   catch(PDOException $e)
	   {
		   echo $e->getMessage();
	   }    
	}
	   
	   
	   
	   
	 public function getequipmentnotificationjob($currentuserid)
		{
		try
			{
			$stmt = $this->db->prepare("SELECT * FROM equipment_notification WHERE equi_noti_user_id=:noti_user_id AND equi_noti_status in ('Unseen') ORDER BY 			equi_noti_jobtimestamp DESC");
			$stmt->bindparam(":noti_user_id", $currentuserid); 
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
	
	public function getallequipmentnotificationjob($currentuserid)
		{
		try
		{
			$stmt = $this->db->prepare("SELECT * FROM equipment_notification WHERE equi_noti_user_id=:noti_user_id ORDER BY equi_noti_jobtimestamp DESC LIMIT 30");
			$stmt->bindparam(":noti_user_id", $currentuserid); 
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
	
	
	public function equipmentupdatednotificationstatus($equi_userid,$equi_notification_status)
	{
		try
			{
		//echo $equi_notification_status;
			$stmt = $this->db->prepare("UPDATE equipment_notification SET equi_noti_status=:equi_noti_status WHERE equi_noti_user_id=:equi_noti_user_id");
			$stmt->bindparam(":equi_noti_status", $equi_notification_status);
			$stmt->bindparam(":equi_noti_user_id", $equi_userid);	  
			$stmt->execute(); 
	
			return $stmt; 
			}
		catch(PDOException $e)
		{
				echo $e->getMessage();
		}
	}
	
	
	
  public function jobsequipmentdata($equi_id)
       {
           try
           {
              $stmt = $this->db->prepare("SELECT job_date FROM job WHERE job_equi_size=:job_equi_size ORDER BY job_timestamp DESC LIMIT 1");
              $stmt->execute(array(':job_equi_size'=>$equi_id));
              $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
    
              return $userRow;
              
           }
           catch(PDOException $e)
           {
               echo $e->getMessage();
           }
       }
	   
  public function equimentsizekoedit($equisize)
       {
            try
            {
              $stmt = $this->db->prepare("SELECT * FROM equipment_size_attachment WHERE equipment_size_id=:equipment_size_id AND equi_status in ('Active')");
			  $stmt->bindparam(":equipment_size_id", $equisize);
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
	   
	   
  public function deleteequipmentsizeattach($equisize)
		{
		try
			{
				$equi_status = 'Inactive';
				$stmt = $this->db->prepare("UPDATE equipment_size_attachment SET equi_status=:equi_status WHERE id=:id");
				$stmt->bindparam(":id", $equisize);
				$stmt->bindparam(":equi_status", $equi_status);
				$stmt->execute();
				return $stmt;
			}
		catch(PDOException $e)
		{
		echo $e->getMessage();
		}
		}
	
	
  public function equiattachment($equiattachid)
		{
		try
			{
				$stmt = $this->db->prepare("SELECT * FROM equiment_size WHERE id=:id");
				$stmt->bindparam(":id", $equiattachid);
				$stmt->execute();
				$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
				return $userRow;
			}
		catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		}
	
  public function deleteequipmentsize($equisize)
		{
		try
			{
				$equi_status = 'Inactive';
				$stmt = $this->db->prepare("UPDATE equiment_size SET status=:status WHERE id=:id");
				$stmt->bindparam(":id", $equisize);
				$stmt->bindparam(":status", $equi_status);
				$stmt->execute();
				return $stmt;
			}
		
		catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		}
	  
	  
  public function equipmentexpirydate($equipment_expiry_date_id)
       {
            try
            {
              $stmt = $this->db->prepare("SELECT * FROM equipment_size_attachment WHERE equi_status NOT IN ('Inactive') ");
			  //$stmt->bindparam(":equipment_size_id", $equipment_expiry_date_id);
              $stmt->execute();
              $userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
    
              return $userRow;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
       }
	   
	   
	  
 public function equipmentexpirydatenotification($userid,$equipment_expiry_date_id)
	{
	try 
		{
			$stmt = $this->db->prepare("SELECT * FROM equipment_notification");
			$stmt->execute();
			$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
			$noti_status = array();
			foreach($userRow as $userRows)
				{
					$noti_status[]= array('equi_noti_type'=>$userRows['equi_noti_type'],'equipment_expiry_date_id'=>$userRows['equipment_expiry_date_id']);
				}
			
			foreach($noti_status as $noti_statuss)
				{
					if($noti_statuss['equipment_expiry_date_id'] == $equipment_expiry_date_id)
						{
							$notityupes = array();
							$notityupes[] = $noti_statuss['equi_noti_type'];
						}
				}
			
			if(is_array($notityupes))
				{
					$noti_status = 'Unseen';
					$stmt = $this->db->prepare("UPDATE equipment_notification SET equi_noti_status=:noti_status WHERE equipment_expiry_date_id=:equipment_expiry_date_id");
					$stmt->bindparam(":noti_status", $noti_status);
					$stmt->bindparam(":equipment_expiry_date_id", $equipment_expiry_date_id);	
					$stmt->execute(); 
					return $stmt;
				}
			else
				{
					$stmt = $this->db->prepare("SELECT * FROM users WHERE type in ('SuperAdmin','Admin','General','Dispatch') AND	status NOT IN ('Inactive')");
					$stmt->execute();
					$userfields=$stmt->fetchAll(PDO::FETCH_ASSOC);
					foreach($userfields as $userfield)
						{
							$noti_status = 'Unseen';
							$userid = $userfield['user_id'];
							$createdatenoti= date("Y-m-d H:i:s"); 
							$noti_jobtimestamp = strtotime($createdatenoti);	
							$expiry_date = 'Equipment Expiry Date';
							$stmt = $this->db->prepare("INSERT INTO equipment_notification(	equi_noti_user_id,equi_noti_jobtimestamp,equi_noti_type,equi_noti_status,created_date,updated_date,equipment_expiry_date_id)VALUES(:noti_user_id, :noti_jobtimestamp, :noti_type, :noti_status, :created_date, :updated_date, :equipment_expiry_date_id)");
							$stmt->bindparam(":noti_user_id", $userid);  
							$stmt->bindparam(":equipment_expiry_date_id", $equipment_expiry_date_id);
							$stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
							$stmt->bindparam(":noti_type", $expiry_date);
							$stmt->bindparam(":noti_status", $noti_status);
							$stmt->bindparam(":created_date", $createdatenoti);
							$stmt->bindparam(":updated_date", $createdatenoti);
							$stmt->execute();
						}
					return $stmt; 
				}
		}
	
	catch(PDOException $e)
		{
			echo $e->getMessage();
		}  
	}
		
		
  public function attachequisize($attachequisize_id)
	{
	try 
		{
			$stmt = $this->db->prepare("SELECT attachment FROM equipment_size_lisence_attachment WHERE lisence_id=:lisence_id AND status IN ('Inactive')");
			$stmt->bindparam(":lisence_id", $attachequisize_id);
			$stmt->execute();
			$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $userRow;
		}
	catch(PDOException $e)
		{
			echo $e->getMessage();
		} 
	}
		
		
  public function previousattach($attachid,$attch)
	{
	$status = 'Inactive';
	try 
		{
			$stmt = $this->db->prepare("UPDATE equipment_size_lisence_attachment SET status=:status WHERE lisence_id=:lisence_id AND attachment=:attachment");
			$stmt->bindparam(":lisence_id", $attachid);
			$stmt->bindparam(":attachment", $attch);
			$stmt->bindparam(":status", $status);
			$stmt->execute();
			return $userRow;
		}
	catch(PDOException $e)
		{
			echo $e->getMessage();
		} 
	}
 public function equisizelogbook($logbook_id,$logbook)
	{
	try 
		{
			echo $logbook_id;
			$stmt = $this->db->prepare("UPDATE equipment_size_attachment SET logbook=:logbook WHERE id=:id");
			$stmt->bindparam(":logbook", $logbook);
			$stmt->bindparam(":id", $logbook_id);
			$stmt->execute();
		}
	catch(PDOException $e)
		{
			echo $e->getMessage();
		} 
	}
		
 public function equipmentvaluedata()
	{
	try
		{
			$stmt = $this->db->prepare("SELECT * FROM equipment_size_attachment WHERE equi_status NOT IN ('Inactive') ");
			$stmt->execute();
			$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $userRow;
		}
	catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
 public function singleequipmentexpirydate($equipment_expiry_date_id)
	{
	try
		{
			$stmt = $this->db->prepare("SELECT * FROM equipment_size_attachment WHERE id=:equipment_size_id");
			$stmt->bindparam(":equipment_size_id", $equipment_expiry_date_id);
			$stmt->execute();
			$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
			return $userRow;
		}
	catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	   
 public function equipmentexpirehistory($license_id)
	{
	try
		{
			$stmt = $this->db->prepare("SELECT * FROM equipment_date_history WHERE license_id=:license_id ");
			$stmt->bindparam(":license_id", $license_id);
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
	   
 public function equipment_history($id,$equipment_size_id,$equipment_license_name,$equipment_license_date)
	{
	try
		{
			$stmt = $this->db->prepare("INSERT INTO equipment_date_history(equipment_size_id,equipment_date,license_id,license_name)VALUES(:equipment_size_id, :equipment_date, :license_id, :license_name)");
			$stmt->bindparam(":license_id", $id);
			$stmt->bindparam(":equipment_size_id", $equipment_size_id);
			$stmt->bindparam(":equipment_date", $equipment_license_date);
			$stmt->bindparam(":license_name", $equipment_license_name);
			$stmt->execute();
		}
	catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
   public function equisecureattachment($equiattachid)
       {
            try
            {
              $stmt = $this->db->prepare("SELECT secure_attachment FROM equiment_size WHERE id=:id");
			  $stmt->bindparam(":id", $equiattachid);
              $stmt->execute();
              $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
    
              return $userRow;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
       }	
}
    ?>