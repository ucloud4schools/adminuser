<?php
$urlGenerator = \OC::$server->getURLGenerator();
//if(OCP\Group::inGroup(OCP\User::getUser(), 'adminuser')){
\OCP\App::addNavigationEntry(array(
    'id' => 'adminuser',
     'href' => $urlGenerator->linkToRoute('adminuser_index'),
    'name' => 'Adminuser',
    'icon' => $urlGenerator->imagePath('adminuser', 'adminuser.png'),
    'order' => 79
));


OC_APP::registerAdmin('adminuser', 'settings');
