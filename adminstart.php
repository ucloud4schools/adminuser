<?php

	
	

//Einbindung Controller
require 'controller.php';
//Anmeldung DB
require_once ('db.php');
error_reporting(E_ALL /*& ~E_NOTICE*/);
$db_link = mysqli_connect (MYSQL_HOST,
                           MYSQL_BENUTZER,
                           MYSQL_KENNWORT,
                           MYSQL_DATENBANK);
 if ( $db_link )
{
}
else
{
	die('Datenbankverbindung fehlgeschlagen: ' . mysqli_error());
}
$prefix=MYSQL_PREFIX;

//Zählen aller Benutzer
$sql_amount_user='SELECT * FROM '.$prefix.'ldap_user_mapping';
$user_overview=mysqli_query($db_link,$sql_amount_user) OR die(mysql_error());
while ($row = mysqli_fetch_array($user_overview, MYSQLI_ASSOC))
{
	$amount_of_users++;
}
//Zählen aller Gruppen
$sql_amount_group='SELECT * FROM '.$prefix.'groups';
$group_overview=mysqli_query($db_link,$sql_amount_group) OR die(mysql_error());
while ($row = mysqli_fetch_array($group_overview, MYSQLI_ASSOC))
{
	$amount_of_groups++;
}
//Abzug der Gruppen admin und adminuser
//$amount_of_groups=$amount_of_groups-2;
//Zählen des Speicherplatzes 
$sql_quota='SELECT configvalue FROM '.$prefix.'preferences WHERE `appid` LIKE "files" ';
$result_quota = mysqli_query($db_link,$sql_quota) OR die(mysql_error());
//$quota = mysqli_fetch_assoc($result_quota);
while ($row = mysqli_fetch_array($result_quota, MYSQLI_ASSOC))
{
	$space_input=$row['configvalue'];
	$space_explode=explode(" ",$space_input);
	If($space_explode[1] == "GB")
	{
		$amount_of_space+=$space_explode[0];	
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<?php echo getHead();?>

<body>

    <div id="wrapper">

        <?php echo getNavbar();?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Übersicht</h1>
                    </div>
					 <div class="row">
					 <a href="user.php">
                <div class="col-lg-6 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-user fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $amount_of_users;?></div>
                                    <div>Benutzer</div>
                                </div>
                            </div>
                        </div>
                        
                            <div class="panel-footer">
                                <span class="pull-left">Zur Benutzerübersicht</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        
                    </div>
                </div>
				</a>
				<a href="group.php">
                <div class="col-lg-6 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-users fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $amount_of_groups;?></div>
                                    <div>Gruppen</div>
                                </div>
                            </div>
                        </div>
                        
                            <div class="panel-footer">
                                <span class="pull-left">Zur Gruppenverwaltung</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                       
                    </div>
                </div>
				
				 </a>
				<!--
                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-file fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $amount_of_space;;?></div>
                                    <div>GB zur Verfügung</div>
                                </div>
                            </div>
                        </div>
                       
                            <div class="panel-footer">
                                <span class="pull-left">Speicherübersicht</span>
                                <!--<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>-->
								<!--
                                <div class="clearfix"></div>
                            </div>
                        
                    </div>
                </div>
				-->
            </div>
					
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
<?php echo getFooter();?>

</body>

</html>
