<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
<!--320-->
<!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
<title>AAKQLD PHP</title>

<!-- css include here -->
<link rel="stylesheet" href="<?php echo home_base_url(); ?>css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo home_base_url(); ?>css/bootstrap-datepicker.css" />
<link rel="stylesheet/less" href="<?php echo home_base_url(); ?>css/timepicker.less" />
<link rel="stylesheet" href="<?php echo home_base_url(); ?>css/bootstrap-multiselect.css" />
<link rel="stylesheet" href="<?php echo home_base_url(); ?>css/dataTables.bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo home_base_url(); ?>css/font-awesome.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo home_base_url(); ?>css/small-business.css" >
<link rel="stylesheet" href="<?php echo home_base_url(); ?>css/uploadfile.css" >
<link rel="stylesheet" href="<?php echo home_base_url(); ?>style.css" type="text/css" />
<link rel="stylesheet" href="<?php echo home_base_url(); ?>responsive.css" type="text/css" />
<link rel="stylesheet" href="<?php echo home_base_url(); ?>css/weather.css" type="text/css" />
<link rel="apple-touch-icon" href="<?php echo home_base_url(); ?>images/apple-touch-icon.png">
<link rel="stylesheet" href="<?php echo home_base_url(); ?>css/ns-default.css" type="text/css" />
<link rel="stylesheet" href="<?php echo home_base_url(); ?>css/ns-style-growl.css" type="text/css" />
<script type="text/javascript" src="<?php echo home_base_url(); ?>js/jquery-1.12.3.min.js"></script>
<!-- <script src="<?php echo home_base_url(); ?>js/socket.io-1.2.0.js"></script> 
<script type="text/javascript" src="<?php echo home_base_url(); ?>js/node.js"></script>-->


<!--         AAQULD                   -->

    <link href="<?php echo home_base_url(); ?>css/custom.css" rel="stylesheet">

    <link href="<?php echo home_base_url(); ?>css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <script src="<?php echo home_base_url(); ?>js/ie-emulation-modes-warning.js"></script>
    <script src="<?php echo home_base_url(); ?>js/ie10-viewport-bug-workaround.js"></script>

</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container"> 
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header customheader col-lg-3 col-md-3 col-sm-4 col-xs-12 padding0">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      
      <a class="navbar-brand" href="<?php echo home_base_url(); ?>"> AAKQLD </a> 
    </div>
    
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse col-lg-8 col-md-8 col-sm-8 col-xs-12 pull-right" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        
          <li> <a href="<?php echo home_base_url(); ?>dashboard.php">Dashboard</a> </li>
          <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Farm Settings<span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="<?php echo home_base_url(); ?>settings/farmsetup.php">Settings</a></li>
              <li><a href="<?php echo home_base_url(); ?>settings/general.php">Manage Fruit/Vegetables</a></li>
              <li><a href="<?php echo home_base_url(); ?>settings/users.php">Users</a></li>
            </ul> 
          </li>   
        
        <?php if($userdata['user_name']) {?>
        <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $userdata['user_name']; ?><span class="caret"></span></a>
          <ul class="dropdown-menu">
          	 
            <li><a href=" <?php echo home_base_url(); ?>logout.php">Sign Out</a></li>
          </ul>
        </li>
        <?php } ?>
        
      </ul>
      <div class="searchform pull-right">
        <form class="navbar-form" role="search" action="<?php echo home_base_url(); ?>search.php" method="get">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Search" name="q" id="searchingtext" value="<?php if(isset($_REQUEST['q'])){ echo $_REQUEST['q']; }?>">
            <div class="input-group-btn">
              <button class="btn btn-default" type="submit" id="searchingg"><i class="glyphicon glyphicon-search"></i></button>
            </div>
          </div>
        </form>
      </div>
    <!-- /.navbar-collapse --> 
    </div>
  </div>
  <!-- /.container --> 
</nav>
