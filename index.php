<?php
class OC_adminuser {

	/**
	* Check if the user is a superadmin, redirects to home if not
	*/
	public static function checkAdminUserGroup(){
		// Check if we are a admin users : group adminuser
		OC_Util::checkLoggedIn();
		if( !OC\Group::inGroup( OCP\User::getUser(), 'adminuser' )){
			header( 'Location: '.OCP\Helper::linkToAbsolute( '', 'index.php' ));
			exit();
		}
	}	
}
$config = \OC::$server->getConfig(); 
OCP\JSON::checkLoggedIn();
//OCP\App::checkAppEnabled('adminuser');
//OC_adminuser::checkAdminUserGroup();
$query_data = array(
    'username' => OC_User::getUser()
);
$src = OC::$server->getSystemConfig()->getValue('adminuser_iframe_src') . '?' . http_build_query($query_data);

$tpl = new OC_Template('adminuser', 'main', 'user');
$tpl->assign('src', $src);
$tpl->printPage();
