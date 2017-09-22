<?php 
/** els job site functions define here **/

/*
 * Base_url
 *
 */
if (!function_exists('home_base_url')) {
   
function home_base_url(){   

// first get http protocol if http or https

$base_url = (isset($_SERVER['HTTPS']) &&

$_SERVER['HTTPS']!='off') ? 'https://' : 'http://';

// get default website root directory

$tmpURL = dirname(__FILE__);

// when use dirname(__FILE__) will return value like this "C:\xampp\htdocs\my_website",

//convert value to http url use string replace, 

// replace any backslashes to slash in this case use chr value "92"

$tmpURL = str_replace(chr(92),'/',$tmpURL);

// now replace any same string in $tmpURL value to null or ''

// and will return value like /localhost/my_website/ or just /my_website/

$tmpURL = str_replace($_SERVER['DOCUMENT_ROOT'],'',$tmpURL);

// delete any slash character in first and last of value

$tmpURL = ltrim($tmpURL,'/');

$tmpURL = rtrim($tmpURL, '/');


// check again if we find any slash string in value then we can assume its local machine

    if (strpos($tmpURL,'/')){

// explode that value and take only first value

       $tmpURL = explode('/',$tmpURL);

       $tmpURL = $tmpURL[0];

      }

// now last steps

// assign protocol in first value

   if ($tmpURL !== $_SERVER['HTTP_HOST'])

// if protocol its http then like this

      $base_url .= $_SERVER['HTTP_HOST'].'/'.$tmpURL.'/';

    else

// else if protocol is https

      $base_url .= $tmpURL;

// give return value

return $base_url; 

}

}

/*
 * current_page_title
 *
 */
if (!function_exists('current_page_title')) {
   
function current_page_title(){
	
$currenturls = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$arrayurl = explode('/' , $currenturls);
$lastval = $arrayurl[count($arrayurl) - 1];
$newurlstring = strstr($lastval, '?', true);
if($newurlstring){
	$currenturl=$newurlstring;
}else{
	$currenturl=$lastval;
} 

//echo $currenturl;
//print_r($arrayval);

switch($currenturl){
    case "home.php":
        $pagetitle = "DashBoard Page";
        break;
    case "add_job.php":
        $pagetitle = "Add Job Page";
        break;
	case "edit_job.php":
        $pagetitle = "Edit Job Page";
        break;
	 case "delete_job.php":
        $pagetitle = "Delete Job Page";
        break;		
    case "equipments.php":
        $pagetitle = "Equipment List Page";
        break;
    case "add_equipment.php":
        $pagetitle = "Add Equipment Page";
        break;
    case "edit_equipment.php":
        $pagetitle = "Edit Equipment Page";
        break;
    case "delete_equipment.php":
        $pagetitle = "Delete Equipment Page";
        break;
	case "users.php":
        $pagetitle = "User List Page";
        break;
    case "add_users.php":
        $pagetitle = "Add User Page";
        break;
    case "edit_users.php":
        $pagetitle = "Edit User Page";
        break;
    case "delete_users.php":
        $pagetitle = "Delete User Page";
        break;	
	case "customers.php":
        $pagetitle = "Customer List Page";
        break;
    case "add_customer.php":
        $pagetitle = "Add Customer Page";
        break;
    case "edit_customer.php":
        $pagetitle = "Edit Customer Page";
        break;
    case "delete_customer.php":
        $pagetitle = "Delete Customer Page";
        break;		
    default:
        //$pagetitle = "Page Not Found";
		$pagetitle = "ELS";
        break;
}

return $pagetitle;	 
	
	}

}

