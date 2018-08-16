<?php

OC_Util::checkAdminUser();

$iframe_src = filter_input(INPUT_POST, 'adminuser_iframe_src', FILTER_VALIDATE_URL);
if ($iframe_src !== FALSE && $iframe_src !== NULL) {
    OC_Config::setValue('adminuser_iframe_src', $iframe_src);
} else {
    $iframe_src = OC::$server->getSystemConfig()->getValue('adminuser_iframe_src');
}

$tpl = new OC_Template('adminuser', 'settings');
$tpl->assign('adminuser_iframe_src', $iframe_src);
return $tpl->fetchPage();
