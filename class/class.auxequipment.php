<?php
class Auxequipment
{
    private $db;
 
    function __construct($DB_con)
    {
      $this->db = $DB_con;
    }
 
    public function insert($auxe_name,$auxe_type,$auxe_size,$noti_user_name)
    {
       try
       {	
		   $auxe_sizes = json_encode($auxe_size);
		   $auxe_status = 'Active';
           $stmt = $this->db->prepare("INSERT INTO auxequipment(auxe_name,auxe_type,auxe_size,auxe_status)VALUES(:auxe_name, :auxe_type, :auxe_size, :auxe_status)");
              
           $stmt->bindparam(":auxe_name", $auxe_name);
           $stmt->bindparam(":auxe_type", $auxe_type);
		   $stmt->bindparam(":auxe_size", $auxe_sizes);
           $stmt->bindparam(":auxe_status", $auxe_status);
           $stmt->execute(); 
   		   $auxe_id = $this->db->lastInsertId();
		   if(!empty($auxe_id) )
				{
				for($i=0;$i<count($auxe_size);$i++)
					{
						$stmt = $this->db->prepare("INSERT INTO auxequipment_size(auxequipment_id,auxequipment_size)VALUES(:aux_id, :aux_size)");
						$stmt->bindparam(":aux_id", $auxe_id);
						$stmt->bindparam(":aux_size", $auxe_size[$i]);
						$stmt->execute();
					}
				}
				  ///// auxequipment notifications 
			if(!empty($auxe_id) )
				{
					$stmt = $this->db->prepare("SELECT * FROM users WHERE type in ('SuperAdmin','Admin','General','Dispatch') AND	status NOT IN ('Inactive')");
					$stmt->execute();
					$userfields=$stmt->fetchAll(PDO::FETCH_ASSOC);
					foreach($userfields as $userfield)
						{
							$userid = $userfield['user_id'];
							$noti_type = 'Added a new auxequipment';
							$noti_status = 'Unseen';
							$createdatenoti= date("Y-m-d H:i:s"); 
							$noti_jobtimestamp = strtotime($createdatenoti);	
							$stmt = $this->db->prepare("INSERT INTO equipment_notification(	equi_noti_user_id,equi_noti_jobtimestamp,equi_noti_type,equi_noti_status,created_date,updated_date,noti_user_name,auxequipment_id)VALUES(:noti_user_id, :noti_jobtimestamp, :noti_type, :noti_status, :created_date, :updated_date, :noti_user_name, :auxeq_id)");
							$stmt->bindparam(":noti_user_id", $userid);  
							$stmt->bindparam(":auxeq_id", $auxe_id);
							$stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
							$stmt->bindparam(":noti_type", $noti_type);
							$stmt->bindparam(":noti_status", $noti_status);
							$stmt->bindparam(":noti_user_name", $noti_user_name);
							$stmt->bindparam(":created_date", $createdatenoti);
							$stmt->bindparam(":updated_date", $createdatenoti);
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
	
public function updated($auxe_id,$auxe_name,$auxe_type,$auxe_size,$auxe_status,$noti_user_name)
	{
	try
		{ 
			$auxe_sizes = json_encode($auxe_size);
			$stmt = $this->db->prepare("UPDATE auxequipment SET auxe_name=:auxe_name, auxe_type=:auxe_type, auxe_size=:auxe_size, auxe_status=:auxe_status WHERE auxe_id=:auxe_id");
			$stmt->bindparam(":auxe_id", $auxe_id);
			$stmt->bindparam(":auxe_name", $auxe_name);
			$stmt->bindparam(":auxe_type", $auxe_type);
			$stmt->bindparam(":auxe_size", $auxe_sizes);
			$stmt->bindparam(":auxe_status", $auxe_status);
			$stmt->execute(); 
			$stmt = $this->db->prepare("SELECT * FROM auxequipment_size WHERE auxequipment_id=:auxequipment_id");
			$stmt->execute(array(':auxequipment_id'=>$auxe_id));
			$auxequisize=$stmt->fetchAll(PDO::FETCH_ASSOC);
			$k = 0;
			$auxequisizearray = array();
			foreach($auxequisize as $auxequisizes)
				{
					$auxequi_ids = $auxequisizes['id'];
					for($i=0;$i<count($auxe_size);$i++)
						{
							if($i===$k)
								{
									$stmt = $this->db->prepare("UPDATE auxequipment_size SET auxequipment_size=:auxequipment_size WHERE auxequipment_id=:auxequipment_id AND id=:id");
									$stmt->bindparam(":auxequipment_id", $auxe_id);
									$stmt->bindparam(":auxequipment_size", $auxe_size[$i]);
									$stmt->bindparam(":id", $auxequi_ids); 
									$stmt->execute(); 
								}
						}
				$k++; 
				}
			foreach($auxequisize as $auxequisizess)
				{ 
					$auxequisizearray[] = $auxequisizess['auxequipment_size'];
				}
			for($i=0;$i<count($auxe_size);$i++)
				{
					//if($auxe_size[$i]!=$auxequisizearray[$i])
					if (!in_array($auxe_size[$i], $auxequisizearray))
					{
						$stmt = $this->db->prepare("INSERT INTO auxequipment_size(auxequipment_id,auxequipment_size)VALUES(:aux_id, :aux_size)");
						$stmt->bindparam(":aux_id", $auxe_id);
						$stmt->bindparam(":aux_size", $auxe_size[$i]);
						$stmt->execute();
					}
				}
			// edit equipment notification
			$stmt = $this->db->prepare("SELECT * FROM equipment_notification WHERE auxequipment_id=:auxe_id LIMIT 1");
			$stmt->execute(array(':auxe_id'=>$auxe_id));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			$eq_row = $stmt->rowCount();
			if($eq_row > 0) 
				{
					$noti_type = 'Edited auxequipment';
					$noti_status = 'Unseen';
					$createdatenoti= date("Y-m-d H:i:s"); 
					$noti_jobtimestamp = strtotime($createdatenoti);	
					$stmt = $this->db->prepare("UPDATE equipment_notification SET equi_noti_jobtimestamp=:noti_jobtimestamp, equi_noti_type=:noti_type, equi_noti_status=:noti_status, updated_date=:updated_date, noti_user_name=:noti_user_name WHERE auxequipment_id=:auxe_id");
					$stmt->bindparam(":auxe_id", $auxe_id);
					$stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
					$stmt->bindparam(":noti_type", $noti_type);
					$stmt->bindparam(":noti_status", $noti_status);
					$stmt->bindparam(":noti_user_name", $noti_user_name);
					$stmt->bindparam(":updated_date", $createdatenoti);
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
							$noti_type = 'Added a new auxequipment';
							$noti_status = 'Unseen';
							$createdatenoti= date("Y-m-d H:i:s"); 
							$noti_jobtimestamp = strtotime($createdatenoti);	
							$stmt = $this->db->prepare("INSERT INTO equipment_notification(	equi_noti_user_id,equi_noti_jobtimestamp,equi_noti_type,equi_noti_status,created_date,updated_date,noti_user_name,auxequipment_id)VALUES(:noti_user_id, :noti_jobtimestamp, :noti_type, :noti_status, :created_date, :updated_date, :noti_user_name, :auxe_id)");
							$stmt->bindparam(":noti_user_id", $userid);  
							$stmt->bindparam(":auxe_id", $auxe_id);
							$stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
							$stmt->bindparam(":noti_type", $noti_type);
							$stmt->bindparam(":noti_status", $noti_status);
							$stmt->bindparam(":noti_user_name", $noti_user_name);
							$stmt->bindparam(":created_date", $createdatenoti);
							$stmt->bindparam(":updated_date", $createdatenoti);
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
	
	public function deleted($auxe_id)
    {
       try
       {   $auxe_status = 'Inactive';
           $stmt = $this->db->prepare("UPDATE auxequipment SET auxe_status=:auxe_status WHERE auxe_id=:auxe_id");
		   $stmt->bindparam(":auxe_id", $auxe_id);
		   $stmt->bindparam(":auxe_status", $auxe_status);	  
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
   
   public function auxequipmentdata($auxe_id)
   {
       try
       {
          $stmt = $this->db->prepare("SELECT * FROM auxequipment WHERE auxe_id=:auxe_id LIMIT 1");
		  $stmt->bindparam(":auxe_id", $auxe_id);
          $stmt->execute();
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);

		  return $userRow;
          
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
   }
 
   
    public function getauxequipmentlist()
   {
        try
		{
          $stmt = $this->db->prepare("SELECT * FROM auxequipment WHERE auxe_status NOT IN ('Inactive') ORDER by auxe_order ASC");
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
   
   public function auxequimentsize($auxequi_id)
       {
            try
            {
              $stmt = $this->db->prepare("SELECT * FROM auxequipment_size WHERE auxequipment_id=:auxequipment_id AND status NOT IN ('Inactive')");
			  $stmt->bindparam(":auxequipment_id", $auxequi_id);
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
	   
	   public function auxequipsize($auxequi_id)
       {
            try
            {
              $stmt = $this->db->prepare("SELECT * FROM auxequipment_size WHERE id=:id");
			  $stmt->bindparam(":id", $auxequi_id);
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
	   
	   public function auxequimentsizeedit($auxequi_id,$auxequisize)
       {
            try
            {
              $stmt = $this->db->prepare("SELECT * FROM auxequipment_size WHERE auxequipment_id=:auxequipment_id AND id=:auxequipments_sizeid");
			  $stmt->bindparam(":auxequipment_id", $auxequi_id);
			  $stmt->bindparam(":auxequipments_sizeid", $auxequisize);
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
	   
	   public function auxequimentsizekoedit($auxequisize)
       {
            try
            {
              $stmt = $this->db->prepare("SELECT * FROM auxequipment_size_attachment WHERE auxequipment_size_id=:auxequipment_size_id AND status in ('Active')");
			  $stmt->bindparam(":auxequipment_size_id", $auxequisize);
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
	   
	public function auxequipmentadd($auxequi_id,$auxequisize_id,$auxlicenses_name,$auxequisizeattachmenturl,$auxequisizeattachmentname,$auxequisizeattachmenturl1,$auxequisizeattachmentname1,$auxequicheck,$noti_user_name)
		{
		try
			{
				$result = count($auxequisizeattachmenturl1);
				if(is_array($auxequisizeattachmenturl1) && is_array($auxequisizeattachmentname1))
					{
					for($i=0; $i < $result; $i++)
						{
							$auxcustomerattachmentdetail[] = array('imagepath' => $auxequisizeattachmenturl1[$i], 'imagename' => $auxequisizeattachmentname1[$i] ); 
						}
					} 
				$auxequiimagepath = $auxcustomerattachmentdetail;
				$auxequipment_imagepath = json_encode($auxequiimagepath);
				
				$stmt = $this->db->prepare("UPDATE auxequipment_size SET attachment=:attachment WHERE auxequipment_id=:auxequipment_id AND id=:id"); 
				$stmt->bindparam(":auxequipment_id", $auxequi_id);
				$stmt->bindparam(":id", $auxequisize_id);	
				$stmt->bindparam(":attachment", $auxequipment_imagepath);
				$stmt->execute(); 
				
				$indexcount = count($auxlicenses_name['name']);
				for($i=0;$i<$indexcount;$i++)
					{
						$auxequilists[] = array( 'name' => $auxlicenses_name['name'][$i], 'date' => $auxlicenses_name['date'][$i], 'imagepath' => $auxequisizeattachmenturl[$i], 'imagename' => $auxequisizeattachmentname[$i],'date_diff' => $auxequicheck[$i],);
					}
				
				foreach($auxequilists as $equilist)
					{
						if($equilist['name']!='')
							{
								$equi_name = $equilist['name'];
								$equi_date = $equilist['date'];
								$attachimagepath = $equilist['imagepath'];
								$date_difference = $equilist['date_diff'];
								$stmt = $this->db->prepare("INSERT INTO auxequipment_size_attachment(auxequipment_size_id,auxequipment_license_name,auxequipment_license_date,attachment,date_difference)VALUES(:auxequipment_size_id, :auxequipment_license_name, :auxequipment_license_date, :attachment, :date_difference)");
								$stmt->bindparam(":auxequipment_size_id", $auxequisize_id);
								$stmt->bindparam(":auxequipment_license_name", $equi_name);
								$stmt->bindparam(":auxequipment_license_date", $equi_date);
								$stmt->bindparam(":attachment", $attachimagepath);
								$stmt->bindparam(":date_difference", $date_difference);
								$stmt->execute();
								$auxlisence_id = $this->db->lastInsertId();
								$stmt = $this->db->prepare("INSERT INTO auxequipment_size_lisence_attachment(auxlisence_id,auxequipment_size_id,attachment)VALUES(:auxlisence_id, :auxequipment_size_id, :attachment)");
								$stmt->bindparam(":auxlisence_id", $auxlisence_id);
								$stmt->bindparam(":auxequipment_size_id", $auxequisize_id);
								$stmt->bindparam(":attachment", $attachimagepath);
								$stmt->execute();
								if(!empty($equilist['date']))
									{
									if($equilist['date_diff'] == '1month' || $equilist['date_diff'] == '3month' || $equilist['date_diff'] == 'yearly' || $equilist['date_diff'] == '6month')
										{
											$stmt = $this->db->prepare("INSERT INTO auxequipment_date_history(auxequipments_size_id,auxequipment_date,auxequipment_license_name,auxequipment_license_id)VALUES(:auxequipment_size_id, :auxequipment_date, :license_name, :auxlisence_id)");
											
											$stmt->bindparam(":auxlisence_id", $auxlisence_id);
											$stmt->bindparam(":auxequipment_size_id", $auxequisize_id);
											$stmt->bindparam(":auxequipment_date", $equi_date);
											$stmt->bindparam(":license_name", $equi_name);
											$stmt->execute();
										}
									}
							}
					
					}
				
				
				
				if(!empty($auxequisize_id) )
					{
						$stmt = $this->db->prepare("SELECT * FROM users WHERE type in ('SuperAdmin','Admin','General','Dispatch') AND	status NOT IN ('Inactive')");
						$stmt->execute();
						$userfields=$stmt->fetchAll(PDO::FETCH_ASSOC);
						foreach($userfields as $userfield)
							{
								$userid = $userfield['user_id'];
								$noti_type = 'Edited auxequipment size';
								$noti_status = 'Unseen';
								$createdatenoti= date("Y-m-d H:i:s"); 
								$noti_jobtimestamp = strtotime($createdatenoti);	
								$stmt = $this->db->prepare("INSERT INTO equipment_notification(	equi_noti_user_id,equi_noti_jobtimestamp,equi_noti_type,equi_noti_status,created_date,updated_date,noti_user_name,auxequipments_size_id)VALUES(:noti_user_id, :noti_jobtimestamp, :noti_type, :noti_status, :created_date, :updated_date, :noti_user_name, :auxequipments_size_id)");
								$stmt->bindparam(":noti_user_id", $userid);  
								$stmt->bindparam(":auxequipments_size_id", $auxequisize_id);
								$stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
								$stmt->bindparam(":noti_type", $noti_type);
								$stmt->bindparam(":noti_status", $noti_status);
								$stmt->bindparam(":noti_user_name", $noti_user_name);
								$stmt->bindparam(":created_date", $createdatenoti);
								$stmt->bindparam(":updated_date", $createdatenoti);
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
	   
 public function auxequipmentupdate($auxrows_ids,$auxequi_id,$auxequisize_id,$auxlicenses_name,$auxequisizeattachmenturl,$auxequisizeattachmentname,$auxequisizeattachmenturl1,$auxequisizeattachmentname1,$auxequicheck,$auxpicedit_id,$noti_user_name)
		{
		try
			{
				$result = count($auxequisizeattachmenturl1);
				if(is_array($auxequisizeattachmenturl1) && is_array($auxequisizeattachmentname1))
					{
					for($i=0; $i < $result; $i++)
						{
							$auxcustomerattachmentdetail[] = array('imagepath' => $auxequisizeattachmenturl1[$i], 'imagename' => $auxequisizeattachmentname1[$i] ); 
						}
					} 
				$auxequiimagepath = $auxcustomerattachmentdetail;
				$auxequipment_imagepath = json_encode($auxequiimagepath);
				$stmt = $this->db->prepare("UPDATE auxequipment_size SET attachment=:attachment WHERE auxequipment_id=:auxequipment_id AND id=:id"); 
				$stmt->bindparam(":auxequipment_id", $auxequi_id);
				$stmt->bindparam(":id", $auxequisize_id);	
				$stmt->bindparam(":attachment", $auxequipment_imagepath);
				$stmt->execute(); 
				$idsss = array();
				$attachmentss = array();
				$indexcount = count($auxlicenses_name['name']);
				for($ii=0;$ii<$indexcount;$ii++)
					{
						$imagename = $auxlicenses_name[$auxrows_ids[$ii]][1]; 
						$imagepath = $auxlicenses_name[$auxrows_ids[$ii]][0];
						$auxequilists[] = array( 
						'eid' => $auxrows_ids[$ii], 
						'name' => $auxlicenses_name['name'][$ii],
						'date' => $auxlicenses_name['date'][$ii], 
						'imagepath' =>$imagepath,
						'imagename' => $imagename, 
						'date_diff' => $auxequicheck[$ii]);
					}
				
				
				$data = array();
				for($as=$ii;$as<count($auxlicenses_name['name']);$as++)
					{
						$data[] = $auxlicenses_name[$as][0];
					}
				
				$stmt = $this->db->prepare("SELECT id FROM auxequipment_size_attachment WHERE auxequipment_size_id=:auxequipment_size_id");
				$stmt->bindparam(":auxequipment_size_id", $auxequisize_id);
				$stmt->execute();
				$rowidss = $stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach($rowidss as $rowidss1)
					{ 
						$idsss[] = $rowidss1['id'];
					}
				
				$checki = 0;
				foreach($auxequilists as $auxequilist)
					{
						$equi_name = $auxequilist['name'];
						$equi_date = $auxequilist['date'];
						$attachimagepath = $auxequilist['imagepath'];
						$ids = $auxequilist['eid'];
						$date_difference = $auxequilist['date_diff'];
						if (in_array($ids, $idsss) && $auxequilist['name']!='')
							{
								$stmt = $this->db->prepare("UPDATE auxequipment_size_attachment SET auxequipment_license_name=:auxequipment_license_name, auxequipment_license_date=:auxequipment_license_date, attachment=:attachment, date_difference=:date_difference  WHERE id=:id");
								$stmt->bindparam(":auxequipment_license_name", $equi_name);
								$stmt->bindparam(":auxequipment_license_date", $equi_date);
								$stmt->bindparam(":attachment", $attachimagepath);
								$stmt->bindparam(":id", $ids);
								$stmt->bindparam(":date_difference", $date_difference);
								$stmt->execute();
								$stmt = $this->db->prepare("SELECT auxequipment_date FROM auxequipment_date_history WHERE auxequipment_license_id=:auxequipment_license_id");
								$stmt->bindparam(":auxequipment_license_id", $ids);
								$stmt->execute();
								$rowdates = $stmt->fetchAll(PDO::FETCH_ASSOC);
								foreach($rowdates as $rowdates1)
									{ 
										$dates[] = $rowdates1['auxequipment_date'];
									}
								if(!empty($auxequilist['date']))
									{
									if($auxequilist['date_diff'] == '1month' || $auxequilist['date_diff'] == '3month' || $auxequilist['date_diff'] == 'yearly' || $auxequilist['date_diff'] == '6month')
										{
										if (!in_array($equi_date, $dates))
											{
												$stmt = $this->db->prepare("INSERT INTO auxequipment_date_history(auxequipments_size_id,auxequipment_date,auxequipment_license_name,auxequipment_license_id)VALUES(:auxequipment_size_id, :auxequipment_date, :license_name, :auxlisence_id)");
												$stmt->bindparam(":auxlisence_id", $ids);
												$stmt->bindparam(":auxequipment_size_id", $auxequisize_id);
												$stmt->bindparam(":auxequipment_date", $equi_date);
												$stmt->bindparam(":license_name", $equi_name);
												$stmt->execute();
											}
										}
									}
								$stmt = $this->db->prepare("SELECT * FROM auxequipment_size_lisence_attachment WHERE auxlisence_id=:auxlisence_id");
								$stmt->bindparam(":auxlisence_id", $ids);
								$stmt->execute();
								$rowattach = $stmt->fetchAll(PDO::FETCH_ASSOC);
								foreach($rowattach as $rowattachss)
										{ 
											$attachmentss[] = $rowattachss['attachment'];
										}
								if (!in_array($attachimagepath, $attachmentss) && $auxequilist['name']!='' && $attachimagepath!='')
									{
										$stmt = $this->db->prepare("INSERT INTO auxequipment_size_lisence_attachment(auxlisence_id,auxequipment_size_id,attachment)VALUES(:auxlisence_id, :auxequipment_size_id, :attachment)");
										$stmt->bindparam(":auxlisence_id", $ids);
										$stmt->bindparam(":auxequipment_size_id", $auxequisize_id);
										$stmt->bindparam(":attachment", $attachimagepath);
										$stmt->execute();
									}
							}
						else
							{
							
								if($auxequilist['name']!='')
									{
										$stmt = $this->db->prepare("INSERT INTO auxequipment_size_attachment(auxequipment_size_id,auxequipment_license_name,auxequipment_license_date,attachment)VALUES(:auxequipment_size_id, :auxequipment_license_name, :auxequipment_license_date, :attachment)");
										$stmt->bindparam(":auxequipment_size_id", $auxequisize_id);
										$stmt->bindparam(":auxequipment_license_name", $equi_name);
										$stmt->bindparam(":auxequipment_license_date", $equi_date);
										$stmt->bindparam(":attachment", $attachimagepath);
										$stmt->execute();
										$lisence_id = $this->db->lastInsertId();
										$stmt = $this->db->prepare("INSERT INTO auxequipment_size_lisence_attachment(auxlisence_id,auxequipment_size_id,attachment)VALUES(:auxlisence_id, :auxequipment_size_id, :attachment)");
										$stmt->bindparam(":auxlisence_id", $lisence_id);
										$stmt->bindparam(":auxequipment_size_id", $auxequisize_id);
										$stmt->bindparam(":attachment", $attachimagepath);
										$stmt->execute();
									}
							}
					$checki++;
					}
				
				
				// edit equipment notification
				$stmt = $this->db->prepare("SELECT * FROM equipment_notification WHERE auxequipments_size_id=:auxequipments_size_id LIMIT 1");
				$stmt->execute(array(':auxequipments_size_id'=>$auxequisize_id));
				$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
				$eq_row = $stmt->rowCount();
				if($eq_row > 0) 
					{
						$noti_type = 'Edited auxequipment size';
						$noti_status = 'Unseen';
						$createdatenoti= date("Y-m-d H:i:s"); 
						$noti_jobtimestamp = strtotime($createdatenoti);	
						$stmt = $this->db->prepare("UPDATE equipment_notification SET equi_noti_jobtimestamp=:noti_jobtimestamp, equi_noti_type=:noti_type, equi_noti_status=:noti_status, updated_date=:updated_date, noti_user_name=:noti_user_name WHERE auxequipments_size_id=:auxequisize_id");
						$stmt->bindparam(":auxequisize_id", $auxequisize_id);
						$stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
						$stmt->bindparam(":noti_type", $noti_type);
						$stmt->bindparam(":noti_status", $noti_status);
						$stmt->bindparam(":noti_user_name", $noti_user_name);
						$stmt->bindparam(":updated_date", $createdatenoti);
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
								$noti_type = 'Edited auxequipment size';
								$noti_status = 'Unseen';
								$createdatenoti= date("Y-m-d H:i:s"); 
								$noti_jobtimestamp = strtotime($createdatenoti);	
								$stmt = $this->db->prepare("INSERT INTO equipment_notification(	equi_noti_user_id,equi_noti_jobtimestamp,equi_noti_type,equi_noti_status,created_date,updated_date,noti_user_name,auxequipments_size_id)VALUES(:noti_user_id, :noti_jobtimestamp, :noti_type, :noti_status, :created_date, :updated_date, :noti_user_name, :auxequipments_size_id)");
								$stmt->bindparam(":noti_user_id", $userid);  
								$stmt->bindparam(":auxequipments_size_id", $auxequisize_id);
								$stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
								$stmt->bindparam(":noti_type", $noti_type);
								$stmt->bindparam(":noti_status", $noti_status);
								$stmt->bindparam(":noti_user_name", $noti_user_name);
								$stmt->bindparam(":created_date", $createdatenoti);
								$stmt->bindparam(":updated_date", $createdatenoti);
								$stmt->execute();  	
							}
					}
			
			}
		catch(PDOException $e)
		{
		echo $e->getMessage();
		}
		
		}
	   
	   public function auxequiattachment($auxequiattachid)
       {
            try
            {
              $stmt = $this->db->prepare("SELECT * FROM auxequipment_size WHERE id=:id");
			  $stmt->bindparam(":id", $auxequiattachid);
              $stmt->execute();
              $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
    
              return $userRow;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
       }
	   
	    public function auxdeleteequipmentsizeattach($auxequisize)
	   {
		  
		   try
      		{
		  $auxequi_status = 'Inactive';
		  $stmt = $this->db->prepare("UPDATE auxequipment_size_attachment SET status=:status WHERE id=:id");
		  $stmt->bindparam(":id", $auxequisize);
		  $stmt->bindparam(":status", $auxequi_status);
		  $stmt->execute();
		  return $stmt;
	 	 }
	   catch(PDOException $e)
            {
                echo $e->getMessage();
            }
    	}
		 public function deleteauxequipmentsize($auxequisize)
		{
		try
			{
				$auxequi_status = 'Inactive';
				$stmt = $this->db->prepare("UPDATE auxequipment_size SET status=:status WHERE id=:id");
				$stmt->bindparam(":id", $auxequisize);
				$stmt->bindparam(":status", $auxequi_status);
				$stmt->execute();
				return $stmt;
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
			 $stmt = $this->db->prepare("UPDATE auxequipment_size_lisence_attachment SET status=:status WHERE auxlisence_id=:auxlisence_id AND attachment=:attachment");
			 $stmt->bindparam(":auxlisence_id", $attachid);
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
		
		
		public function auxattachequisize($attachequisize_id)
		{
			
			
		try 
				{
			 $stmt = $this->db->prepare("SELECT attachment FROM auxequipment_size_lisence_attachment WHERE auxlisence_id=:auxlisence_id AND status IN ('Inactive')");
			 $stmt->bindparam(":auxlisence_id", $attachequisize_id);
			 $stmt->execute();
			 $userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
        
       		return $userRow;
			}
		 	catch(PDOException $e)
				{
			echo $e->getMessage();
				} 
		}
		
		public function auxequisizelogbook($logbook_id,$logbook)
		{
			
			try 
				{
				//echo $logbook_id;
			 $stmt = $this->db->prepare("UPDATE auxequipment_size_attachment SET logbook=:logbook WHERE id=:id");
			 $stmt->bindparam(":logbook", $logbook);
			 $stmt->bindparam(":id", $logbook_id);
			 //$stmt->bindparam(":status", $status);
             $stmt->execute();
			 
			}
		 	catch(PDOException $e)
				{
			echo $e->getMessage();
				} 
		}
		
		public function auxequipmentvaluedata()
       {
            try
            {
              $stmt = $this->db->prepare("SELECT * FROM auxequipment_size_attachment WHERE status NOT IN ('Inactive') ");
			  //$stmt->bindparam(":id", $equiattachid);
              $stmt->execute();
              $userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
    
              return $userRow;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
       }
	   
	   public function auxequipmentexpirydate($equipment_expiry_date_id)
       {
            try
            {
              $stmt = $this->db->prepare("SELECT * FROM auxequipment_size_attachment WHERE status NOT IN ('Inactive') ");
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
	   
	   public function singleauxequipmentexpirydate($auxequipment_expiry_date_id)
       {
            try
            {
              $stmt = $this->db->prepare("SELECT * FROM auxequipment_size_attachment WHERE id=:auxequipment_size_id");
			  $stmt->bindparam(":auxequipment_size_id", $auxequipment_expiry_date_id);
              $stmt->execute();
              $userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
    
              return $userRow;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
       }
	   
	   public function auxequipmentexpirehistory($auxequipment_license_id)
       {
            try
            {
             
		   $stmt = $this->db->prepare("SELECT * FROM auxequipment_date_history WHERE auxequipment_license_id=:auxequipment_license_id ");
			  $stmt->bindparam(":auxequipment_license_id", $auxequipment_license_id);
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