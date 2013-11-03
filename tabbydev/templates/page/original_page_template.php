<?php

// Get Header that goes into every skin
require($objConfig->getsiteRootPath().'skins/_common/templates/skinpageheader.php');


if (!isset($pageTitle)) {
    $pageTitle = $objConfig->getSiteName();
}
?>
<head>
<title><?php echo $pageTitle; ?></title>
<?php

if (!isset($pageSuppressSkin)){
    echo $objSkin->putSkinCssLinks();

    if (!isset($pageSuppressToolbar)) {
        echo '
        <!--[if lte IE 6]>
        <style type="text/css">
            body { behavior:url("skins/_common/js/ADxMenu_prof.htc"); }
        </style>
        <![endif]-->
        ';
    }
}

    echo $objSkin->putJavaScript($mime, $headerParams, $bodyOnLoad);


?>
</head>
<?php

if ($objUser->isLoggedIn()) {
    $module = 'tabeisa_postlogin';
} else {
    $module = 'prelogin';
}


if (isSet($bodyParams)) {
    echo "<body " . $bodyParams . ">";
}else{
    echo '<body>';
}


 	if (!isset($pageSuppressContainer)) {
 	    echo '<div id="container">';
 	}
    
    if (isset($enableTopLinks)) {
        ?><table width="100%"  border="0" cellspacing="0" cellpadding="0" id="headertable">
  <tr align="center" valign="top">
    <td bgcolor="#000000"><img src="skins/tabeisa/images/topspacer.gif" width="1" height="20" /><a href="index.php?module=<?php echo $module; ?>"><img src="skins/tabeisa/images/transparent.gif" name="homeimage" width="60" height="20" border="0" id="homeimage" /></a><img src="skins/tabeisa/images/topspacer.gif" width="1" height="20" /><a href="index.php?module=splashscreen"><img src="skins/tabeisa/images/transparent.gif" name="flashhomeimage" width="90" height="20" border="0" id="flashhomeimage" /></a><img src="skins/tabeisa/images/topspacer.gif" width="1" height="20" /><a href="index.php?module=tabeisa_about"><img src="skins/tabeisa/images/transparent.gif" name="aboutimage" width="67" height="20" border="0" id="aboutimage" /></a><img src="skins/tabeisa/images/topspacer.gif" width="1" height="20" /><a href="index.php?module=tabeisa_services"><img src="skins/tabeisa/images/transparent.gif" name="servicesimage" width="68" height="20" border="0" id="servicesimage" /></a><img src="skins/tabeisa/images/topspacer.gif" width="1" height="20" /><a href="index.php?module=tabeisa_contact"><img src="skins/tabeisa/images/transparent.gif" name="contactimage" width="58" height="20" border="0" id="contactimage" /></a><img src="skins/tabeisa/images/topspacer.gif" width="1" height="20" /><?php 
    
    if ($objUser->isAdmin()) {
        echo '<a href="index.php?module=toolbar"><img src="skins/tabeisa/images/transparent.gif" name="adminimage" width="67" height="20" border="0" id="adminimage" /></a><img src="skins/tabeisa/images/topspacer.gif" width="1" height="20" />';
    }
    
    if ($objUser->isLoggedIn()) {
        echo '<a href="javascript: if(confirm(\'Are you sure you want to logout?\')) {document.location= \'index.php?module=security&amp;action=logoff\'};"><img src="skins/tabeisa/images/transparent.gif" name="logoutimage" width="67" height="20" border="0" id="logoutimage" /></a><img src="skins/tabeisa/images/topspacer.gif" width="1" height="20" />';
    }
    
    ?></td>
  </tr></table><?
    }

 	if (!isset($pageSuppressBanner)) {

?>


			<div id="header" align="center">
            <table width="735"  border="0" cellspacing="0" cellpadding="0" id="headertable">
  <tr align="center" valign="top">
    <td bgcolor="#000000"><img src="skins/tabeisa/images/topspacer.gif" width="1" height="20" /><a href="index.php?module=<?php echo $module; ?>"><img src="skins/tabeisa/images/transparent.gif" name="homeimage" width="60" height="20" border="0" id="homeimage" /></a><img src="skins/tabeisa/images/topspacer.gif" width="1" height="20" /><a href="index.php?module=splashscreen"><img src="skins/tabeisa/images/transparent.gif" name="flashhomeimage" width="90" height="20" border="0" id="flashhomeimage" /></a><img src="skins/tabeisa/images/topspacer.gif" width="1" height="20" /><a href="index.php?module=tabeisa_about"><img src="skins/tabeisa/images/transparent.gif" name="aboutimage" width="67" height="20" border="0" id="aboutimage" /></a><img src="skins/tabeisa/images/topspacer.gif" width="1" height="20" /><a href="index.php?module=tabeisa_services"><img src="skins/tabeisa/images/transparent.gif" name="servicesimage" width="68" height="20" border="0" id="servicesimage" /></a><img src="skins/tabeisa/images/topspacer.gif" width="1" height="20" /><a href="index.php?module=tabeisa_contact"><img src="skins/tabeisa/images/transparent.gif" name="contactimage" width="58" height="20" border="0" id="contactimage" /></a><img src="skins/tabeisa/images/topspacer.gif" width="1" height="20" /><?php 
    
    if ($objUser->isAdmin()) {
        echo '<a href="index.php?module=toolbar"><img src="skins/tabeisa/images/transparent.gif" name="adminimage" width="67" height="20" border="0" id="adminimage" /></a><img src="skins/tabeisa/images/topspacer.gif" width="1" height="20" />';
    }
    
    if ($objUser->isLoggedIn()) {
        echo '<a href="javascript: if(confirm(\'Are you sure you want to logout?\')) {document.location= \'index.php?module=security&amp;action=logoff\'};"><img src="skins/tabeisa/images/transparent.gif" name="logoutimage" width="67" height="20" border="0" id="logoutimage" /></a><img src="skins/tabeisa/images/topspacer.gif" width="1" height="20" />';
    }
    
    ?></td>
  </tr>
  <tr>
  <td class="bannertd">
  <?php $tabeisaBanner = $this->getObject('tabeisabanner', 'tabeisa_about');
    echo $tabeisaBanner->getBannerImg($this->getParam('module', '_default'));?></td>
  </tr>
</table>
			</div>
<?php  } else {

?>
<style type="text/css">
body, html { background-color: #fff;}
</style>
<?

}

    // get content
    echo $this->getLayoutContent();
?>

<?php
if (!isset($suppressFooter)) {
     // Create the bottom template area
    $this->footerNav = & $this->newObject('layer', 'htmlelements');
    $this->footerNav->id = 'footer';
    $this->footerNav->cssClass='';
    $this->footerNav->position='';
    if (isset($footerStr)) {
        $this->footerNav->str = $footerStr;
    } else if ($objUser->isLoggedIn()) {

        $this->loadClass('link', 'htmlelements');
        $link = new link ($this->URI(array('action'=>'logoff'),'security'));
        $link->link=$objLanguage->languageText("word_logout");
        $str=$objLanguage->languageText("mod_context_loggedinas", 'context').' <strong>'.$objUser->fullname().'</strong>  ('.$link->show().')';
        $this->footerNav->str = $str;
    }


    echo $this->footerNav->show();
}
?>

<?php if (!isset($pageSuppressContainer)) { ?>
	 </div>
<?php } ?>
<?php
 $this->putMessages();
?>
</body>
</html>
