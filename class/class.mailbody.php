<?php
class Mailbody
{
    private $db;
 
    function __construct($DB_con)
    {
      $this->db = $DB_con;
    }
 
	
	public function updated($mailid,$messagebody,$attachmenturl,$attachmentname,$subject)
    {
       try
       { 
	   
	   $result = count($attachmenturl);
		   
		  if(is_array($attachmenturl) && is_array($attachmentname)){
			  for($i=0; $i < $result; $i++){
				  $attachmentdetail[] = array('imagepath' => $attachmenturl[$i], 'imagename' => $attachmentname[$i] ); 
				  }
			  } 
			  
			$pdflink = json_encode($attachmentdetail);  


           $stmt = $this->db->prepare("UPDATE mailbody SET mail_body=:mail_body, mail_pdflink=:mail_pdflink, subject=:subject WHERE mail_id=:mail_id");

		   $stmt->bindparam(":mail_id", $mailid);
		   $stmt->bindparam(":mail_body", $messagebody);
           $stmt->bindparam(":mail_pdflink", $pdflink);
		   $stmt->bindparam(":subject", $subject);
           $stmt->execute(); 
		   
           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
    }
	
	/*public function deleted($equi_id)
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
    }*/
	
	

   public function redirect($url)
   {
       header("Location: $url");
   }
   
   public function mailbodydata($mail_id)
   {
       try
       {
          $stmt = $this->db->prepare("SELECT * FROM mailbody WHERE mail_id=:mail_id LIMIT 1");
          $stmt->execute(array(':mail_id'=>$mail_id));
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