<?php
/*
 * TU2 canvas page template
 *
 * This is the page template in the TU skin
 * 
 * Notes: 
 *   1. There is no headerwrapper in this skin
 *
 */
// Add navigation back to top of page.
define("PAGETOP", '<a name="pagetop"></a>');
define("GOTOTOP", '<a href="#pagetop">Top</a>'); // @todo change this to an icon

// Get the four banner blocks
$objModuleCatalogue = $this->getObject('modules', 'modulecatalogue');
$isInstalled = $objModuleCatalogue->checkIfRegistered("bannerhelper");
if ($isInstalled) {
    $objBl = $this->getObject('fsbannerhelper', 'bannerhelper');
    $banner0 = $objBl->readContents("banner0");
    $banner1 = $objBl->readContents("banner1");
    $banner2 = $objBl->readContents("banner2");
    $banner3 = $objBl->readContents("banner3");
    $plMenu = $objBl->readContents("plmenu");
} else {
    $banner0 = NULL;
    $banner1 = NULL;
    $banner2 = NULL;
    $banner3 = NULL;
    $plMenu = NULL;
}

// Initialise the variable holding preferred canvas
$prefCanvas=FALSE;

// Initialise the layout settings
$setCanvas = FALSE;

// Define the name of this skin.
$skinName = "tu";

// Define the valid canvases for this skin as an array.
$validCanvases = array_map('basename', glob('skins/' . $skinName . '/canvases/*', GLOB_ONLYDIR));

// Get mobile device detection going.
$objDetector = $this->newObject('detectmobile', 'utilities');
if  ($objDetector->device->isMobile()) {
    //die("Sorry, I am testing iPad detection. Will be back up in 1 minute");
}

// Settings that are needed so that canvase-aware code can function.
$this->setSession('skinName', $skinName);
$_SESSION['skinName'] = $skinName;
$_SESSION['isCanvas'] = TRUE;
$_SESSION['sourceSkin'] = $skinName;
$_SESSION['layout'] = '_DEFAULT';

// Instantiate the canvas object.
$objCanvas = $this->getObject('canvaschooser', 'canvas');

// Set the skin base for the default.
$skinBase='skins/' . $skinName . '/canvases/';
if (isset ($canvas)) {
    $_SESSION['canvasType'] = 'programmatic';
    $_SESSION['canvas'] = $canvas;
    $canvas = $skinBase . $canvas;
} elseif ($prefCanvas) {
    $canvas = $skinBase . $prefCanvas;
} else {
    // Get what canvas we should be showing
    $canvas = $objCanvas->getCanvas($validCanvases, $skinBase);
}

// Check if there is a settings file and load it
if (!isset($pageSuppressSkin)) {
    $canvasName = $objCanvas->getCanvasName($canvas);
    $settingsFile = $objSkin->getSkinLocation().'canvases/' . $canvasName . '/settings.php';
    if(file_exists($settingsFile)) {
        require_once $settingsFile;
    }
}

// Get Header that goes into every skin.
$siteRootPath = $objConfig->getsiteRootPath();
require($siteRootPath . 'skins/_common/templates/skinpageheader3-0.php');


// Set up the open graph stuff
if (!isset($fb_app_id)) {
    $fb_app_id = NULL;
}
if (!isset($og_title)) {
    $og_title = $pageTitle;
}
if (!isset($og_image)) {
    $og_image = 'http://developer.thumbzup.com/skins/' . $skinName . 'images/default.png';
}
if (!isset($og_content)) {
    $og_content = 'Thumbzup API developers.';
} else {
    $og_content = strip_tags($og_content);
}

// Render the head section of the page. Note that there can be no space or
// blank lines between the PHP closing tag and the HTML head tag. It must be
// exactly as below.
?><head>
    <meta property="fb:app_id" content="<?php echo $fb_app_id; ?>" />
    <meta property="og:title" content="<?php echo $og_title; ?>" />
    <meta property="og:image" content="<?php echo $og_image; ?>" />
    <meta property="og:description" content="<?php echo $og_content; ?>" />
    <title>
        <?php echo $pageTitle; ?>
    </title>
    <?php
    // Get the skin version 2 base CSS for all skins.
    if (!isset($pageSuppressSkin)) {
        echo '

        <link rel="stylesheet" type="text/css" href="skins/_common2/css/basecss.php">
        <link rel="icon" type="image/png" href="skins/' . $skinName . '/favicon.png" />
            
        ';
     }


    // Render the javascript unless it is suppressed.
    if (!isset($pageSuppressJavascript)) {
        echo $objSkin->putJavaScript($mime, $headerParams);
        // Load the helper JS from the current skin
        $helperJs = 'skins/' . $skinName . '/javascript/skinhelper.js';
        echo "\n<script type='text/javascript' src='" . $helperJs . "'></script>\n\n";
        //$modernizrJs = 'skins/' . $skinName . '/resources/modernizr/modernizr-2.6.1.min.js';
        //echo "\n<script type='text/javascript' src='" . $modernizrJs . "'></script>\n\n";
    }

    // Render the CSS for the current skin unless it is suppressed.
    if (!isset($pageSuppressSkin)) {
       echo '

       <link rel="stylesheet" type="text/css" href="skins/' . $skinName . '/stylesheet.css">
       <link rel="stylesheet" type="text/css" href="' . $canvas . '/stylesheet.css">

        ';
    }
    ?>
</head>

<?php
// Render body parameters if they are set, otherwise render a plain body tag
if (isset($bodyParams)) {
    echo '<body '.$bodyParams.'>';
} else {
    echo '<body>';
}

// Render the container & canvas elements unless it is suppressed.
if (!isset($pageSuppressContainer)) { ?>
    <div id="fb-root"></div>
    <div id='OutermostWrapper'>
        <div class='ChisimbaCanvas' id='_default'>
            <div id='Canvas_Content'>
                <div id='Canvas_BeforeContainer'></div>
                <div id='container'>
<?php
}

// Render the banner area unless it is suppressed
if (!isset($pageSuppressBanner)) {
    // Because the link to page top is in the footer, put the top here
    // only if the footer is not suppressed.
    if (!isset($suppressFooter)) {
        echo PAGETOP;
    }
    echo "\n\n<div class='Canvas_Content_Head_Before'>" 
      . getIcons($skinName, $canvas);
    if (!isset($pageSuppressSearch)) {
        echo $objSkin->siteSearchBox();
    }
    echo "</div>\n\n"
    ?>
    <div class="Canvas_Content_Head">
        <div class="Canvas_Content_Head_Header" id="header">
            <?php echo '<a class="sitename_link" href="'.$objConfig->getSiteRoot().'">'; ?>
            <h1 id="sitename">
                <?php echo $objConfig->getsiteName(); ?>
            </h1>
            <?php echo '</a>'; ?>
        </div>
        <div class='floathead' id='floathead_content3'><?php echo $banner3; ?></div>
        <div class='floathead' id='floathead_content2'><?php echo $banner2; ?></div>
        <div class='floathead' id='floathead_content1'><?php echo $banner1; ?></div>
        <div class='floathead' id='floathead_content0'><?php echo $banner0; ?></div>
        <?php
}

if (!isset($pageSuppressBanner)) {
    echo "</div>";
    if (!isset($pageSuppressToolbar)) {
        $simulate = $this->getParam('simulate', NULL);
        if (!$this->objUser->isLoggedIn() || ($simulate == 'prelogintoolbar')) {
            if ($isInstalled) {
                echo "\n\n<div id='prelogin_nav'>$plMenu</div>\n\n";
            }
        } else {
            echo "\n\n<div id='navigation'>\n\n" . $toolbar . "\n</div>\n\n";
        }
    }
    echo '<div class="Canvas_Content_Head_After"></div>';
}

$license = '
<div class="small"><br /><a rel="license" href="http://creativecommons.org/licenses/by-nd/3.0/deed.en_GB">
<img alt="Creative Commons Licence" style="border-width:0" 
src="http://i.creativecommons.org/l/by-nd/3.0/88x31.png" /></a>
<br /><span xmlns:dct="http://purl.org/dc/terms/" property="dct:title">
Web content</span> by <a xmlns:cc="http://creativecommons.org/ns#" 
href="http://thumbzup.com" property="cc:attributionName" rel="cc:attributionURL">
thumbzup.com</a><br />is licensed under a <a rel="license" 
href="http://creativecommons.org/licenses/by-nd/3.0/deed.en_GB">
Creative Commons<br />Attribution-NoDerivs 3.0 Unported License</a><br /></div>
';


// Render the layout content as supplied from the layout template
echo "<div class='Canvas_Content_Body_Before'></div>\n"
   . "<div id='Canvas_Content_Body'>\n"
   . $this->getLayoutContent()
   . "</div>\n<div class='Canvas_Content_Body_After'></div>\n"
   .'<br id="footerbr" />';


// If the footer is not suppressed, render it out.
if (!isset($suppressFooter)) {
    // Add the footer string if it is set
    if (isset($footerStr)) {
       $footerStr = $footerStr;
    } else if ($objUser->isLoggedIn()) {
        $this->loadClass('link', 'htmlelements');
        $link = new link ($this->URI(array('action'=>'logoff'),'security'));
        $link->link=$objLanguage->languageText("word_logout");
        $str=$objLanguage->languageText("mod_context_loggedinas", 'context').' <strong>'.$objUser->fullname().'</strong>  ('.$link->show().')';
        $footerStr= $str;
    } else {
        $footerStr = "<h3>With the Payment Pebble API, <br />now you can make things easy too!</h3>";
    }

    // Do the rendering here.
    echo "<div class='Canvas_Content_Footer_Before'></div>"
      . "<div class='Canvas_Content_Footer'><div id='footer'>"
      . $footerStr;
    // Put in the link to the top of the page
    if (!isset($pageSuppressBanner)) {
        echo ' (' . GOTOTOP . ')';
    }
    echo "</div>\n</div>\n<div class='Canvas_Content_Footer_After'>$license</div>";
}
// Render the container's closing div if the container is not suppressed
if (!isset($pageSuppressContainer)) {
    echo "</div><div class='Canvas_AfterContainer'></div>\n</div>\n</div></div>";
}



// Render any messages available.
$this->putMessages();

// Close up the body and HTML and finish up.
/*echo '
<script type="text/javascript" src="' . $canvas . '/js/jquery.imageScale.js"></script>
<script type="text/javascript" src="' . $canvas . '/js/helper.js"></script>
';*/

/**
 *
 * Throw in my social networking icons. Probably could make this a
 * Chisimba object but for now it is just for my own skin.
 *
 * @param stgring $skinName The name of the skin to look in
 * @param string $canvas The name of the canvas to look in inside the skin
 * @return string The rendered icons
 * 
 */
function getIcons($skinName, $canvas)
{
    $available=array(
        'tu' => 'http://thumbzup.com'//,
        //'facebook' => '#',
        //'flickr' => '#',
        //'twitter' => '#',
        //'youtube' => '#'
    );

    $ret ="";
    foreach ($available as $img=>$link) {
        $img = '<img class="social_icon" src="' . $canvas . '/resources/img/' . $img . '.png" alt="' . $img . '">';
        $ret .= '<a href="' . $link . '" target="_blank">' . $img . "</a>";
    }
    return "<div class='social_icon_container'>" . $ret . "</div>";
}
?>
</body>
</html>