<?php require_once '../dbconfig.php';
include ('../header.php');
if($user->is_loggedin()=="")
{
 $user->redirect('index.php');
}
?>
<?php $farms_value= $farms->getfarmslist();
?>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 workers_section">
            <table class="tabledata table workers_table">
                <thead>
                    <tr>
                        <th>Job</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Workers</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Rotar...</td>
                        <td>7:00 AM</td>
                        <td>10:00 AM</td>
                        <td>John Edwards. Peter Bones</td>
                    </tr>
                    <tr>
                        <td>Deleafing</td>
                        <td>10:00 AM</td>
                        <td>12:00 PM</td>
                        <td>John Edwards</td>
                    </tr>
                    <tr>
                        <td>Picking</td>
                        <td>12:00 PM</td>
                        <td>5:00 PM</td>
                        <td>Peter Bones</td>
                    </tr>
                    <tr>
                        <td>Harvesting</td>
                        <td>5:00 PM</td>
                        <td>6:00 PM</td>
                        <td>Peter Bones</td>
                    </tr>
                    <tr>
                        <td>Weeding</td>
                        <td>5:00 PM</td>
                        <td>6:00 PM</td>
                        <td>John Edwards</td>
                    </tr>
                    <tr>
                        <td>Deleafing</td>
                        <td>6:00 PM</td>
                        <td>6:30 PM</td>
                        <td>John Edwards, Peter Bones</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php  
include ('../footer.php');  ?>
<?php include ('footer-bottom.php');  ?>