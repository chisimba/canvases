<!DOCTYPE html>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<?php
/*
 * OER
 *
 * This is the page template in the OER skin. 
 *
 */


// Add navigation back to top of page.
define("PAGETOP", '<a name="pagetop"></a>');
define("GOTOTOP", '<a href="#pagetop">Top</a>'); // @todo change this to an icon
// Define the valid canvases for this skin as an array.
$validCanvases = array_map('basename', glob('skins/oeru/canvases/*', GLOB_ONLYDIR));

// Define the name of this skin.
$skinName = "oeru";

// Settings that are needed so that canvase-aware code can function.
$this->setSession('skinName', $skinName);
$_SESSION['skinName'] = $skinName;
$_SESSION['isCanvas'] = TRUE;
$_SESSION['sourceSkin'] = $skinName;
$_SESSION['layout'] = '_DEFAULT';

// Instantiate the canvas object.
$objCanvas = $this->getObject('canvaschooser', 'canvas');
$objLanguage = $this->getObject('language', 'language');
$objUser = $this->getObject("user", "security");

// Set the skin base for the default.
$skinBase = 'skins/' . $skinName . '/canvases/';
if (isset($canvas)) {
    $_SESSION['canvasType'] = 'programmatic';
    $_SESSION['canvas'] = $canvas;
    $canvas = $skinBase . $canvas;
} elseif (isset($prefCanvas)) {
    $canvas = $skinBase . $prefCanvas;
} else {
    // Get what canvas we should be showing
    $canvas = $objCanvas->getCanvas($validCanvases, $skinBase);
}

// Get Header that goes into every skin.
require($objConfig->getsiteRootPath() . 'skins/_common/templates/skinpageheader3-0.php');

// Render the head section of the page. Note that there can be no space or
// blank lines between the PHP closing tag and the HTML head tag. It must be
// exactly as below.
?><head>
    <title>
        <?php echo $pageTitle; ?>
    </title>
    <?php
    // Get the skin version 2 base CSS for all skins.
    if (!isset($pageSuppressSkin)) {
        echo '

        <link rel="stylesheet" type="text/css" href="skins/_common2/base.css">
        ';
    }

    $isLoggedIn = $this->objUser->isLoggedIn() ? "true" : "false";
    $loggedInVar = '<script language="JavaScript" type="text/javascript">
          
         var loggedIn = ' . $isLoggedIn . ';
        </script>';
    echo $loggedInVar;
    // Render the javascript unless it is suppressed.
    if (!isset($pageSuppressJavascript)) {
        //Load cruvy corners
        ?>
        <script type="text/javascript">
            var curvyCornersVerbose = false;
        </script>
        <?php
    }

    // Render the CSS for the current skin unless it is suppressed.
    if (!isset($pageSuppressSkin)) {
        echo '
       <link rel="stylesheet" type="text/css" href="skins/' . $skinName . '/stylesheet.css">
       <link rel="stylesheet" type="text/css" href="skins/' . $skinName . '/oer.css">
       <link rel="stylesheet" type="text/css" href="skins/' . $skinName . '/backwards.css">
       <link rel="stylesheet" type="text/css" href="skins/' . $skinName . '/crystal-stars.css">
       <link rel="stylesheet" type="text/css" href="skins/' . $skinName . '/jquery.ui.stars.css">
       <link rel="stylesheet" type="text/css" href="skins/' . $skinName . '/jquery_notification.css">
       <link rel="stylesheet" type="text/css" href="' . $canvas . '/stylesheet.css">
       
        ';
    }
    echo $objSkin->putJavaScript($mime, $headerParams);
    ?>
</head>

<?php
// Render body parameters if they are set, otherwise render a plain body tag
if (isset($bodyParams)) {
    echo '<body ' . $bodyParams . '>';
} else {
    echo '<body>';
}

// --------------- BELONGS IN LAYOUT TEMPLATE
// Render the container & canvas elements unless it is suppressed.
if (!isset($pageSuppressContainer)) {
    echo "<div class='ChisimbaCanvas' id='_default'>\n"
    . "<div id='Canvas_Content'>\n"
    . "<div id='Canvas_BeforeContainer'></div>"
    . "<div id='container'>";
}

// Render the banner area unless it is suppressed
if (!isset($pageSuppressBanner)) {
    // Because the link to page top is in the footer, put the top here
    // only if the footer is not suppressed.
    if (!isset($suppressFooter)) {
        echo PAGETOP;
    }
    ?>
    <div class="Canvas_Content_Head_Before"></div>
    <div class="Canvas_Content_Head">
        <div class="Canvas_Content_Head" id="header">
            <div id='banner_overlay'></div>
            <?php
            if ($objUser->isLoggedIn()) {
                echo '<h5>' . $objLanguage->languageText('mod_oer_welcome', 'oer') . '&nbsp;' . $objUser->fullname() . '</h5>';
            }
            echo '<a href="' . $objConfig->getSiteRoot() . '"><img align="left" src="skins/' . $skinName . '/images/logo-unesco.gif"></a>';
            echo '<h3>' . $objLanguage->languageText('mod_oer_maintitle1', 'oer') . '</h3>';
            echo '<h3>' . $objLanguage->languageText('mod_oer_maintitle2', 'oer') . '</h3>';
            ;
            ?>


            <?php
            if (!isset($pageSuppressSearch)) {
                echo $objSkin->siteSearchBox();
            }
            ?>
        </div>
        <?php
    }

    if (!isset($pageSuppressToolbar)) {
        $objToolbar = $this->getObject('oertoolbar', 'oer');
        echo '<br/><div id="navigationband">' . $objToolbar->show() . '</div>';
    }


    if (!isset($pageSuppressBanner)) {
        ?>
    </div>
    <div class="Canvas_Content_Head_After">
        <br/>
        <?php
        echo '<div id="controlpanel">';
        if ($objUser->isLoggedIn()) {
            echo '<a href="?module=security&action=logoff">'
            . $objLanguage->languageText('mod_oer_logout', 'oer') . '</a>' .
            '</a>&nbsp;|&nbsp;<a href="?module=oeruserdata">' . $objLanguage->languageText('mod_userdetails_updateyourprofile', 'userdetails') . '</a>';
        } else {
            echo '<a href="?module=oer&action=login">'
            . $objLanguage->languageText('mod_oer_login', 'oer') .
            '</a>&nbsp;|&nbsp;<a href="?module=oeruserdata&action=selfregister">' . $objLanguage->languageText('mod_oeruserdata_selfreg', 'oeruserdata') . '</a>';
        }
        echo '</div>';
        ?>
    </div>
        <?php
    }



// Render the layout content as supplied from the layout template
    echo "<div class='Canvas_Content_Body_Before'>";
    $breadcrumbs = $this->getObject('breadcrumbs', 'toolbar');
    if ($objUser->isLoggedIn()) {
        echo '<div id="breadcrumbband">' . $breadcrumbs->show() . '</div>';
    }

    echo "</div>\n";

    echo "<div id='Canvas_Content_Body'>\n";
    echo $this->getLayoutContent()
    . "</div>\n<div class='Canvas_Content_Body_After'></div>\n"
    . '<br id="footerbr" />';


// If the footer is not suppressed, render it out.
    if (!isset($suppressFooter)) {
        // Add the footer string if it is set
        if (isset($footerStr)) {
            $footerStr = $footerStr;
        } else if ($objUser->isLoggedIn()) {
            $this->loadClass('link', 'htmlelements');
            $link = new link($this->URI(array('action' => 'logoff'), 'security'));
            $link->link = $objLanguage->languageText("word_logout");
            $str = $objLanguage->languageText("mod_context_loggedinas", 'context')
                    . ' <strong>' . $objUser->fullname() . '</strong>  (' . $link->show() . ')';
            $footerStr = $str;
        } else {
            $footerStr = $objLanguage->languageText("mod_security_poweredby", 'security', 'Powered by Chisimba');
        }
        // Do the rendering here.
        echo "<div class='Canvas_Content_Footer_Before'></div>\n"
        . "<div class='Canvas_Content_Footer'><div id='footer'>"
        . $footerStr;
        // Put in the link to the top of the page
        if (!isset($pageSuppressBanner)) {
            echo ' (' . GOTOTOP . ')';
        }
        echo "</div>\n<div class='Canvas_Content_Footer_After'></div>";
    }

// Render the container's closing div if the container is not suppressed
    if (!isset($pageSuppressContainer)) {
        echo "</div></div><div class='Canvas_AfterContainer'></div>\n</div>\n</div>";
    }


// Render any messages available.
    $this->putMessages();

// Close up the body and HTML and finish up.
    ?>
</body>
</html>
