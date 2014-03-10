<?php
/*
 * CANVAS
 *
 * This is the page template in the Bengwa elearning, V 1.0 skin
 *
 */

// Add navigation back to top of page.
define("PAGETOP", '<a name="pagetop"></a>');
define("GOTOTOP", '<a href="#pagetop">Top</a>'); // @todo change this to an icon

// Initialise some variables
$prefCanvas=FALSE;

// Define the valid canvases for this skin as an array.
$validCanvases = array_map('basename', glob('skins/canvas/canvases/*', GLOB_ONLYDIR));

// Define the name of this skin.
$skinName = "cms";

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

// Get Header that goes into every skin.
require($objConfig->getsiteRootPath().'skins/_common/templates/skinpageheader3-0.php');

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




    // Render the javascript unless it is suppressed.
    if (!isset($pageSuppressJavascript)) {
    //Load cruvy corners
?>
<script type="text/javascript">
var curvyCornersVerbose = false;
</script>
<?php
        $curvy = $this->getJavascriptFile('curvycorners-2.0.4/curvycorners.js', 'canvas');
        echo $curvy;
        echo $objSkin->putJavaScript($mime, $headerParams, $bodyOnLoad);
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
        <div class="Canvas_Content_Head_Header" id="header">
            <?php echo '<a href="'.$objConfig->getSiteRoot().'">'; ?>
            <div class='floathead_left' id='floathead_content2'></div>
            <div class='floathead' id='floathead_content1'></div>
            </a>
            <?php
            if (!isset($pageSuppressSearch)) {
                echo $objSkin->siteSearchBox();
            }
            ?>
            <h1 id="sitename">
                <span>
                    <?php
                    echo '<a href="'.$objConfig->getSiteRoot().'">'.$objConfig->getsiteName().'</a>';
                    ?>
                </span>
            </h1>
        </div>

        <?php
}

$nlimessage = NULL;

$footer1 = '
    Kenga Solutions is based in Johannesburg, South Africa, and provides
    commercial Chisimba development, installation, extension and support.
    We do eLearning and numerous applications.
    ';
$footer2= '
    Bingwa is a careful blend of the earth’s nobility, the heart’s purity, 
    the wind’s strength, the water’s tranquillity and finally, the fire’s
    intensity. With this, Bingwa was sent forth to guide the children 
    into a better world.
    ';
$footer3= '
    <center>
    Copyright © 2012. Bingwa Magazine.<br />
    <table><tr>
    <td>Advertise</td><td>Terms of use</td>
    </tr><tr>
    <td>Privacy policy</td><td>International sites</td>
    </tr><table>
    </center>
    ';
$footerTable = '
    <div class="footer_content">
    <table><tr>
    <td width="33.3%">' . $footer1 . '</td>
    <td width="33.3%">' . $footer2 . '</td>
    <td width="33.3%">' . $footer3 . '</td>
    </tr></table>
    </div>

';
if (!$this->objUser->isLoggedIn()) {
    echo "\n\n<div id='navigation'>\n\n" . $nlimessage . "\n</div>\n\n";
} else {
    if (!isset($pageSuppressToolbar)) {
        echo "\n\n<div id='navigation'>\n\n" . $toolbar . "\n</div>\n\n";
    }
}

if (!isset($pageSuppressBanner)) {
    ?>
    </div>
    <div class="Canvas_Content_Head_After"></div>
    <?php
}

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
        $footerStr = $objLanguage->languageText("mod_security_poweredby", 'security', 'Powered by Chisimba');
    }
    $footerStr .= $footerTable;
    // Do the rendering here.
    echo "<div class='Canvas_Content_Footer_Before'></div>\n"
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
    echo "</div><div class='Canvas_AfterContainer'></div>\n</div>\n</div>";
}



// Render any messages available.
$this->putMessages();

$chJs = $this->getJavascriptFile('chisimba.js', 'skin');
echo $chJs;
// Close up the body and HTML and finish up.
?>
</body>
</html>