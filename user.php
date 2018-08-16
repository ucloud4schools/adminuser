<?php
//Einbindung Controller
require 'controller.php';
//Anmeldung DB
require_once ('db.php');
error_reporting(E_ALL & ~E_NOTICE);
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
$user_query=$_GET["parameter"];
$prefix=MYSQL_PREFIX;


//SQL Abfragen
$sql_user='SELECT owncloud_name from '.$prefix.'ldap_user_mapping';
$result_user = mysqli_query($db_link,$sql_user) OR die(mysql_error());
$sql_group_user		='SELECT gid FROM '.$prefix.'group_user WHERE uid LIKE "'.$user_query.'"';
$sql_all_group		='SELECT gid from '.$prefix.'groups WHERE gid != "admin" AND gid != "adminuser"';
$result_group_user 	= mysqli_query($db_link,$sql_group_user) OR die(mysql_error());
$result_all_group	= mysqli_query($db_link,$sql_all_group) OR die(mysql_error());


//EINLESEN GRUPPEN des USER
	$temp_user_group=array();
	while ($row = mysqli_fetch_array($result_group_user, MYSQLI_ASSOC))
	{
		$temp_user_group[]=$row['gid'];
	}
//EINLESEN aller GRUPPEN

	//$user = $output_search;
	$temp_all_group=array();
	while ($row = mysqli_fetch_array($result_all_group, MYSQLI_ASSOC))
	{
		$temp_all_group[]=$row['gid'];
	}
//VERGLEICH ARRAYS (Alle Gruppen und Gruppen des Users)
	$result_groups = array_diff($temp_all_group, $temp_user_group);
?>
<!DOCTYPE html>
<html lang="en">
<?php echo getHead();?>
<body>

    <div id="wrapper">
		
        <?php echo getNavbar();?>

          <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Benutzerverwaltung</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           1) Bitte den zu bearbeitenden Nutzer durch <b>Anklicken</b> des Benutzernamen auswählen:
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
											<th>Name</th>
                                            <th>Benutzername</th>
                                            <th>Gruppenübersicht</th>
                                            <th>Quota</th>
                                            <!--<th>Rest-Speicher</th>
											<th>letzte Anmeldung</th>-->
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
										while ($row = mysqli_fetch_assoc($result_user)) 
										{
										$user=$row['owncloud_name'];	
										//Abfrage Quota
										$sql_quota='SELECT configvalue FROM '.$prefix.'preferences WHERE userid LIKE "'.$user.'" AND `appid` LIKE "files" AND configkey LIKE "quota"';
										$result_quota = mysqli_query($db_link,$sql_quota) OR die(mysql_error());
										$quota = mysqli_fetch_assoc($result_quota);
										//Abfrage Gruppen
										$sql_group='SELECT gid FROM '.$prefix.'group_user WHERE uid LIKE "'.$user.'"';
										$result_group = mysqli_query($db_link,$sql_group) OR die(mysql_error());
										
										//Abfrage Name
										$sql_name='SELECT data FROM '.$prefix.'accounts WHERE uid LIKE "'.$user.'"';
										$result_name = mysqli_query($db_link,$sql_name) OR die(mysql_error());

										
										
										echo $tr_open;
												//Anzeige des Namens
												echo $td_open;
												while($row3=mysqli_fetch_assoc($result_name))
													{													
													$nameerg=$row3["data"];
													
													// String Start
													$start_nameerg=substr($nameerg,25);
													
													//String Suchfeld
													$find_nameerg='"';
													
													//String Ende
													$end_nameerg = strpos($nameerg, $find_nameerg ,25);												
													
													//Substring
													$final_nameerg= substr($nameerg,25,$end_nameerg-25 );
													
													//SOnderzeichen
													$final_nameerg = 
													strtr($final_nameerg, array(
														'\u00A0'    => ' ',
														'\u0026'    => '&',
														'\u003C'    => '<',
														'\u003E'    => '>',
														'\u00E4'    => 'ä',
														'\u00C4'    => 'Ä',
														'\u00F6'    => 'ö',
														'\u00D6'    => 'Ö',
														'\u00FC'    => 'ü',
														'\u00DC'    => 'Ü',
														'\u00DF'    => 'ß',
														'\u20AC'    => '€',
														'\u0024'    => '$',
														'\u00A3'    => '£',
													 
														'\u00a0'    => ' ',
														'\u003c'    => '<',
														'\u003e'    => '>',
														'\u00e4'    => 'ä',
														'\u00c4'    => 'Ä',
														'\u00f6'    => 'ö',
														'\u00d6'    => 'Ö',
														'\u00fc'    => 'ü',
														'\u00dc'    => 'Ü',
														'\u00df'    => 'ß',
														'\u20ac'    => '€',
														'\u00a3'    => '£',
													));
																									
													echo $final_nameerg;
																									
													}
												echo $td_close;
										
										
												echo $td_open;
													$username='
													<a href="user.php?parameter='.$row['owncloud_name'].'#user_bearbeiten">
													 '.$row['owncloud_name'].'
													</a>';
													echo $username;
												echo $td_close;
												echo $td_open;
													 while($row2=mysqli_fetch_assoc($result_group))
													{			
													$group=$row2["gid"];
													 echo $group;
													 echo "<br>";
													}
												echo $td_close;
												echo $td_open;
												$quota_user='
												 '.$quota['configvalue'].'';
												echo $quota_user;
												echo $td_close;	
                                        echo $tr_close;																						
										}															
										?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
						<!-- BEGINN ABSCHNITT 2-->
			
			<?php $output_search = $_GET["parameter"];?>
			<div class="row">
                <div class="col-lg-12" id="user_bearbeiten">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            2) Bearbeitung des Benutzers: <?php echo $output_search?>
                        </div>
                        <!-- /.panel-heading -->
						<div class="panel-body">
									<div class="col-lg-6">	
										<form action="">
										<label>Gruppen des Benutzers:</label>
											<input type="hidden" name="sent_user_groups" value="yes">
											<input type="hidden" name="user" value= "<?php echo $output_search ?>">
											<?php 
											if ($user_query != "")
											{
												foreach($temp_user_group as $array_group_user)
												{
												$group_of_user='<div class="checkbox"><label><input type="checkbox" name="groups_to_delete[]" value='.$array_group_user.'>'.$array_group_user.'</label></div>';
												echo $group_of_user;
												}
											}
											?>
											<br>
											<button type="submit_search" class="btn btn-danger">Benutzer aus Gruppe(n) entfernen</button>
											<!--<input type="submit" value=" Aus Gruppe(n) entfernen">-->
											</form>
									</div>	
									<div class="col-lg-6">	
										<form action="">
										<label>verfügbare Gruppen:</label>
										<input type="hidden" name="sent_all_groups" value="yes">
										<input type="hidden" name="user" value= "<?php echo $output_search ?>">
										<?php
										if ($user_query != "")
										{
											foreach($result_groups as $array_all_groups)
											{
											$group_all='<div class="checkbox"><label><input type="checkbox" name="groups_to_add[]" value='.$array_all_groups.'>'.$array_all_groups.'</label></div>';
											echo $group_all;
											}
										}
										?>
										<br>
										<button type="submit_search" class="btn btn-primary">Benutzer zu Gruppe(n) hinzufügen</button>
										<!--<input type="submit" value="Zu Gruppe(n) hinzufügen">-->
										</form>
									</div>
						</div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
		
			<!--ENDE Abschnitt 2-->	
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

  <?php echo getFooter();?>
</body>

</html>
<?php
//Anweisungen GRUPPE Hinzufügen
		//Einlesen der übergebenen Variablen
         $sent_user_groups = $_GET['sent_all_groups'];			//Weichensteller
         $groups_to_add = $_GET['groups_to_add'];		//Inhalt der Checkboxen
		 $user_temp = $_GET['user']; 
		 $count_array=count ($groups_to_add );
		//Überprüfung, ob der Absendeknopf gedrückt wurde
         if ($sent_user_groups == 'yes') 
		 {
				$i=1;
				$y=0;
				//Gruppen dem User hinzufügen
				while ($i<=$count_array){

				$zuweisung=$groups_to_add[$y];
				echo "<br />\n";
				$sql_insert='INSERT INTO '.$prefix.'group_user (gid,uid) VALUES ("'.$zuweisung.'","'.$user_temp.'")';
				//echo $sql_insert ;
				echo "<br />\n";
				mysqli_query($db_link,$sql_insert) OR die(mysql_error());
				$i++;
				$y++;
				}
			//Weiterleitung an die index.php			
			$alert='<script> alert("Ihre Änderungen wurden erfolgreich gespeichert!")</script>
			<script> document.location="user.php" </script>';
			echo $alert;
		 }
		//Anweisungen GRUPPE LÖSCHEN
		//Einlesen der übergebenen Variablen
		$sent_user_groups = $_GET['sent_user_groups'];			//Weichensteller
        $groups_to_delete = $_GET['groups_to_delete'];		//Inhalt der Checkboxen
		//$user = $output_search;
		$user_temp = $_GET['user'];
		$count_array2=count($groups_to_delete);
		//Überprüfung, ob der Absendeknopf gedrückt wurde

         if ($sent_user_groups == 'yes')
		{
				$j=1;
				$z=0;

				//Gruppen des Users löschen
				while ($j<=$count_array2){

				$zuweisung2=$groups_to_delete[$z];
				$sql_delete='DELETE FROM '.$prefix.'group_user WHERE gid="'.$zuweisung2.'" AND uid="'.$user_temp.'"';
				mysqli_query($db_link,$sql_delete) OR die(mysql_error());
				$j++;
				$z++;
				}
			//Weiterleitung an die index.php
			$alert='<script> alert("Ihre Änderungen wurden erfolgreich gespeichert!")</script>
			<script> document.location="user.php" </script>';
			echo $alert;

		}
	

?>