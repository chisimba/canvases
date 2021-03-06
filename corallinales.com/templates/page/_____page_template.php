<?php
/*
 * corallinales.com canvase page template
 *
 * This is the page template in the corallinales.com skin
 * 
 * Notes: 
 *   1. There is no headerwrapper in this skin
 *
 */
// Add navigation back to top of page.
define("PAGETOP", '<a name="pagetop"></a>');
define("GOTOTOP", '<a href="#pagetop">Top</a>'); // @todo change this to an icon

// Cache the CSS files
$objCssCache = $this->getObject('csscache', 'skin');
$objCssCache->cacheCommon();

// Initialise the variable holding preferred canvas
$prefCanvas=FALSE;

// Initialise the layout settings
$setCanvas = FALSE;

// Define the name of this skin.
$skinName = "corallinales.com";

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
if (!isset($og_title)) {
    $og_title = $pageTitle;
}
if (!isset($og_image)) {
    $og_image = 'skins/' . $skinName . '/default.png';
}
if (!isset($og_content)) {
    $og_content = 'An introduction to the Coralline red algae';
} else {
    $og_content = strip_tags($og_content);
}

// Render the head section of the page. Note that there can be no space or
// blank lines between the PHP closing tag and the HTML head tag. It must be
// exactly as below.
?><head>
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

        <link rel="stylesheet" type="text/css" href="cache.css">
        <link rel="icon" type="image/png" href="skins/' . $skinName . '/favicon.png" />
            
        ';
     }


    // Render the javascript unless it is suppressed.
    if (!isset($pageSuppressJavascript)) {
        echo $objSkin->putJavaScript($mime, $headerParams);
        // Load the helper JS from the current skin
        $helperJs = 'skins/' . $skinName . '/javascript/skinhelper.js';
        echo "\n<script type='text/javascript' src='" . $helperJs . "'></script>\n\n";
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
            <?php echo '<a id="sitehead_link" href="'.$objConfig->getSiteRoot().'">'; ?>
            <div id="sitename"> </div>
            <?php echo '</a>'; ?>
        </div>
        <?php
}

if (!isset($pageSuppressBanner)) {
    echo "</div>";
    if (!isset($pageSuppressToolbar)) {
        $simulate = $this->getParam('simulate', NULL);
        if (!$this->objUser->isLoggedIn() || ($simulate == 'prelogintoolbar')) {

        } else {
            echo "\n\n<div id='navigation'>\n\n" . $toolbar . "\n</div>\n\n";
        }
    }
    echo '<div class="Canvas_Content_Head_After"></div>';
}


// Render the layout content as supplied from the layout template
echo "<div class='Canvas_Content_Body_Before'></div>\n"
   . "<div id='Canvas_Content_Body'>\n"
   . $this->getLayoutContent()
   . "</div>\n<div class='Canvas_Content_Body_After'></div>\n"
   .'<br id="footerbr" />';

$footerStr2 = '<br />Authored by <a href="mailto:gmaneveldt@uwc.ac.za">Gavin W. Maneveldt</a>, <a href="mailto:derek@dkeats.com">Derek W. Keats</a> and <a href="mailto:W.Woelkerling@latrobe.edu.au">William J. Woelkerling</a>';
$footerStr3 = '<br />' . $objLanguage->languageText("mod_security_poweredby", 'security', 'Powered by ') . ' Chisimba';

// If the footer is not suppressed, render it out.
if (!isset($suppressFooter)) {
    // Add the footer string if it is set
    if (isset($footerStr)) {
       $footerStr = $footerStr;
       $footerStr .= $footerStr2;
    } else if ($objUser->isLoggedIn()) {
        $this->loadClass('link', 'htmlelements');
        $link = new link ($this->URI(array('action'=>'logoff'),'security'));
        $link->link=$objLanguage->languageText("word_logout");
        $str=$objLanguage->languageText("mod_context_loggedinas", 'context').' <strong>'.$objUser->fullname().'</strong>  ('.$link->show().')';
        $footerStr= $str;
        $footerStr .= $footerStr2;
    } else {
        $footerStr = $footerStr2;
    }
    $footerStr .= $footerStr3;
    // Do the rendering here.
    echo "<div class='Canvas_Content_Footer_Before'></div>"
      . "<div class='Canvas_Content_Footer'><div id='footer'>"
      . $footerStr;
    // Put in the link to the top of the page
    if (!isset($pageSuppressBanner)) {
        echo ' (' . GOTOTOP . ')';
    }
    echo "</div>\n</div>\n<div class='Canvas_Content_Footer_After'></div>";
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
        'flkr' => 'http://www.flickr.com/photos/dkeats/sets/72157610262558941/'
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