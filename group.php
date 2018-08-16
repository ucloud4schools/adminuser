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
//SQL Abfragen-USER
$sql_user='SELECT owncloud_name from '.$prefix.'ldap_user_mapping';
$result_user = mysqli_query($db_link,$sql_user) OR die(mysql_error());

//WELCHE GRUPPE  WURDE AUSGEWÄHLT
$grouptoedit = $_GET['grouptoedit'];

//SQL-Abfragen-GROUP
$group_user='SELECT uid FROM '.$prefix.'group_user WHERE gid = "'.$grouptoedit.'" ';
$user_all='SELECT owncloud_name from '.$prefix.'ldap_user_mapping';
$result_group_user 	= mysqli_query	($db_link,$group_user) 		OR die(mysql_error());
$result_user_all	= mysqli_query	($db_link,$user_all) 		OR die(mysql_error());


//EINLESEN welche User in auszuwählender Gruppe sind.
	$temp_user_group=array();
	while ($row = mysqli_fetch_array($result_group_user, MYSQLI_ASSOC))
	{
		$temp_user_group[]=$row['uid'];
	}
//EINLESEN aller Benutzer

	//$user = $output_search;
	$temp_user_all=array();
	while ($row = mysqli_fetch_array($result_user_all, MYSQLI_ASSOC))
	{
		$temp_user_all[]=$row['owncloud_name'];
	}
//VERGLEICH ARRAYS (Alle Gruppen und Gruppen des Users)
$result_user_group = array_diff($temp_user_all,$temp_user_group);

				




//Gruppe hinzufügen
$sent = $_GET['sent_newgroup'];
if ($sent == 'yes')
{
$groups_to_insert="";
$groups_to_insert = $_GET['group_name'];
//Fehleingaben abfangen
$groups_to_insert=preg_replace("/\s/s","_",$groups_to_insert);
$groups_to_insert=preg_replace("/ö/","oe",$groups_to_insert);
$groups_to_insert=preg_replace("/ä/","ae",$groups_to_insert);
$groups_to_insert=preg_replace("/ü/","ue",$groups_to_insert);
$groups_to_insert=preg_replace("/ß/","ss",$groups_to_insert);
$groups_to_insert=preg_replace("/Ä/","Ae",$groups_to_insert);
$groups_to_insert=preg_replace("/Ö/","Oe",$groups_to_insert);
$groups_to_insert=preg_replace("/Ü/","Ue",$groups_to_insert);

	if ($groups_to_insert !="")
		{
		$sql_insert_group='INSERT INTO '.$prefix.'groups (gid) VALUES ("'.$groups_to_insert.'")';
		mysqli_query($db_link,$sql_insert_group) OR die(mysql_error());
		$alert='<script> alert("Ihre Änderungen wurden erfolgreich gespeichert!")</script>
		<script> document.location="group.php" </script>';
		echo $alert;
		}
}
//Gruppen löschen
$sent2="";
$sent2 = $_GET['sent_all_groups'];			
$groups_all = $_GET['groupsall'];
$count_groups=count($groups_all);
if ($sent2 == 'yes')
{
	$a=1;
	$b=0;
		while ($a<=$count_groups){
		$zuweisung=$groups_all[$b];
		$sql_delete_groups='DELETE FROM '.$prefix.'groups WHERE gid="'.$zuweisung.'"';
		mysqli_query($db_link,$sql_delete_groups) OR die(mysql_error());
		$a++;
		$b++;
		}
		$alert='<script> alert("Ihre Änderungen wurden erfolgreich gespeichert!")</script>
		<script> document.location="group.php" </script>';
		echo $alert;
}
//Gruppen-Übersicht
$sql_group_overview='SELECT gid from '.$prefix.'groups WHERE gid != "admin" AND gid != "adminuser"';
$groupall_overview=mysqli_query($db_link,$sql_group_overview) OR die(mysql_error());
$group_overview_output=array();
while ($row = mysqli_fetch_array($groupall_overview, MYSQLI_ASSOC))
{
	$group_overview_output[]=$row['gid'];
}
$count_all_groups=count($group_overview_output);
//USER DER GRUPPE HINZUFÜGEN
$sent3="";
$sent3 = $_GET['sent_choosen_user'];			
$groups_user_all = $_GET['user_to_add'];
$count_users=count($groups_user_all);

//WELCHE GRUPPE  WURDE AUSGEWÄHLT
$group_user_add = $_GET['sent_group_for_user'];
$grouptoedit = $_GET['grouptoedit'];

if ($sent3 == 'yes')
{
	$c=1;
	$d=0;
		while ($c<=$count_users){
		$zuweisung2=$groups_user_all[$d];
		$sql_add_user='INSERT INTO '.$prefix.'group_user (gid,uid) VALUES ("'.$group_user_add.'","'.$zuweisung2.'")';
		mysqli_query($db_link,$sql_add_user) OR die(mysql_error());
		$c++;
		$d++;
		}
		$alert='<script> alert("Ihre Änderungen wurden erfolgreich gespeichert!")</script>
		<script> document.location="group.php" </script>';
		echo $alert;
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
		 <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Gruppenverwaltung</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
		<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             Erstellen und löschen von Gruppen: 
                        </div>
                        <div class="panel-body">
									<div class="col-lg-6">
										<div class="form-group">
											 <form action="">
											<input type="hidden" name="sent_all_groups" value="yes">
                                            <label>Verfügbare Gruppen</label>
                                            
											<?php 
											foreach($group_overview_output as $array_groups)
												{
												$group_delete='<div class="checkbox"><label><input type="checkbox" name="groupsall[]" value='.$array_groups.'>'.$array_groups.'</label></div>';
												echo $group_delete;
												}
											?>
                                            
											<br>
											<button type="submit" class="btn btn-danger">Markierte Gruppen löschen</button>
											</form>
                                        </div>
									</div>
									<div class="col-lg-6">	
										
										<div class="form-group">
										<form action="" id="groupForm">
											<input type="hidden" name="sent_newgroup" value="yes"  align="center">
                                            <label>Neue Gruppe erstellen:</label>
														<span style="color:firebrick" id="errorMsg"></span><br>
                                            <input  type="text" class="form-control" placeholder="Gruppenname" id="group_name" name="group_name">
											<br>
											<button type="submit" id="buttongroup" class="btn btn-primary">Gruppe anlegen</button>
											</form>
                                        </div>
										<div class="alert alert-danger">
												<strong>Achtung!</strong> Bitte keine Leerzeichen oder Sonderzeichen (Umlaute, koma, Schrägstrich, usw...) im Gruppennamen verwenden!
										</div>
										
										<script>
										document.getElementById('groupForm').onsubmit = function () 
										{
												var group_name = document.getElementById('group_name');
												var errorMsg = document.getElementById('errorMsg');
												
												console.log('Formular wird ausgefüllt');
												if ( group_name.value == '') {
													errorMsg.innerHTML = 'Gruppenname darf nicht leer sein!';
													group_name.focus();
													return false;
												}
											}	
										
										</script>
										
										
										
									</div>										
						</div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
			
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Mehrere Benutzer einer Gruppe hinzufügen: <?php echo $output_search?>
                        </div>
                        <!-- /.panel-heading -->
						<div class="panel-body">
						
						<?php 
						//Gruppen nach Auswahl ausblenden
						$hidegroup = $_GET['hide_groups'];?>
									<div class="col-lg-6">	
										<div class="form-group">
											<form action="">
											<input type="hidden" name="sent_choosen_group" value="yes">
											<input type="hidden" name="hide_groups" value="hide">
											
                                            <label>1) Gruppe auswählen: <?php echo $grouptoedit?></label>
											<select class="form-control" name="grouptoedit">
											<?php
											If($hidegroup != "hide")
											{
												foreach($group_overview_output as $array_groups)
													{
													$group_show=' <option value='.$array_groups.'>'.$array_groups.'  </option>';
													echo $group_show;
													}
											}
												?>
                                               
                                            </select>
											<br>
													<div float=left>
											<button type="submit" class="btn btn-primary">Gruppe auswählen</button>
											</form>
													<?php
													$check_group = $_GET['sent_choosen_group'];
													If($check_group == 'yes')
													{
													$back_button='<a href="group.php" class="btn btn-primary">Zurück</a>';
													echo $back_button;
													}
													?>
													
													</div>
                                        </div>
									</div>	
									<div class="col-lg-6">
										<form action="">
										<input type="hidden" name="sent_choosen_user" value="yes">
										<input type="hidden" name="sent_group_for_user" value="<?php echo $grouptoedit?>">
										<label>2) Bitte wählen Sie die hinzuzufügenden Benutzer für die Gruppe: </label>
										<?php
										// Ausgewählte Gruppe
										$grouptoedit = $_GET['grouptoedit'];
										
										//Auswahl der Benutzer
										$check_group = $_GET['sent_choosen_group'];
										If($check_group == 'yes')
										{
										$check_all_users_script=
										'
										<script language="JavaScript">
										function toggle(source) {
										  checkboxes = document.getElementsByName("user_to_add[]");
										  for(var i=0, n=checkboxes.length;i<n;i++) {
											checkboxes[i].checked = source.checked;
										  }
										}
										</script>
																												
										';
										echo $check_all_users_script;
											$brtag='<br>';
											echo $brtag;		
											$check_all_users='<input type="checkbox" onClick="toggle(this)" /> Alle auswählen<br/>';
											echo $check_all_users;
											foreach($result_user_group as $array_user_group)
											{
													
											$user_to_display='<div class="checkbox"><label><input type="checkbox" name="user_to_add[]" value='.$array_user_group.'>'.$array_user_group.'</label></div>';
											echo $user_to_display;
											}
											
										}
										?>
									<br>
											<button type="submit" class="btn btn-primary">Benutzer der Gruppe hinzufügen</button>
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
			    <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
								 Gruppen-Benutzer-Übersicht:
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Gruppe</th>
                                            <th>Benutzer</th>                                  
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php								
									
									$sql_group_all='SELECT gid from '.$prefix.'group_user WHERE gid NOT LIKE "admin" GROUP BY gid';
									$result_group_all = mysqli_query($db_link,$sql_group_all) OR die(mysql_error());
								
									
										while ($row4 = mysqli_fetch_assoc($result_group_all)) 
										{
										$groups=$row4['gid'];									
										//Abfrage Benutzer
										$sql_group_user='SELECT uid FROM '.$prefix.'group_user WHERE gid LIKE "'.$groups.'" AND gid NOT LIKE "admin" AND uid NOT LIKE "administrator" AND uid NOT LIKE "bueschsa" AND uid NOT LIKE "ndiayeso" AND uid NOT LIKE "henricma"  ';								
										$result_group_user = mysqli_query($db_link,$sql_group_user) OR die(mysql_error());
													
										echo $tr_open;
												echo $td_open;
													$temp_group=$row4['gid'];
													echo $temp_group;
												echo $td_close;
												echo $td_open;
													 while($row5=mysqli_fetch_assoc($result_group_user))
													{			
													$group_user_temp=$row5['uid'];
													 echo $group_user_temp;
													 echo "<br>";
													}
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
			
		</div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<?php echo getFooter();?>

</body>

</html>