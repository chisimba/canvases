<?php
/*
 * CANVAS
 *
 * This is the page template in the Canvas skin, an attempt to develop a
 * skin - canvas approach to Chisimba. You can consider this skin a proof
 * of concept (POC) for the Chisimba canvas.
 *
 */

// Add navigation back to top of page.
define("PAGETOP", '<a name="pagetop"></a>');
define("GOTOTOP", '<a href="#pagetop">Top</a>'); // @todo change this to an icon

// Initialise the variable holding preferred canvas
$prefCanvas=FALSE;

// Define the name of this skin.
$skinName = "photoblog";

// Define the valid canvases for this skin as an array.
$validCanvases = array_map('basename', glob('skins/' . $skinName . '/canvases/*', GLOB_ONLYDIR));

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

        <link rel="stylesheet" type="text/css" href="skins/_common2/css/basecss.php">

        ';
     }


    // Render the javascript unless it is suppressed.
    if (!isset($pageSuppressJavascript)) {

?>

<?php
        echo $objSkin->putJavaScript($mime, $headerParams);
        // Load the JS file for stripe
        echo $this->getJavaScriptFile('stripe.js', 'stripe');
    }

    // Render the CSS for the current skin unless it is suppressed.
    if (!isset($pageSuppressSkin)) {
       echo '

       <link rel="stylesheet" type="text/css" href="skins/' . $skinName . '/stylesheet.css">
       <link rel="stylesheet" type="text/css" href="' . $canvas . '/stylesheet.css">
       


        ';
       //<script type="text/javascript" src="skins/' . $skinName . '/js/jquery.equalHeightColumns.js" />
    }
    ?>
</head>

<?php
// Get the Chisimba stripe
if (!isset($pageSuppressStripe)) {
    // Instantiate the modules class to check if stripe is registered
    $objModule = $this->getObject('modules','modulecatalogue');
    $isRegistered = $objModule->checkIfRegistered('stripe', 'stripe');
        if ($isRegistered) {
            // Get an instance of the buildad object
            $objStripe = $this->getObject("stripeui","stripe");
            $stripe = $objStripe->show();
        } else {
            $stripe = NULL;
        }
} else {
    $stripe = NULL;
}

// Render body parameters if they are set, otherwise render a plain body tag
if (isset($bodyParams)) {
    echo '<body '.$bodyParams.'>';
} else {
    echo '<body>';
}
echo $stripe;

// Render the container & canvas elements unless it is suppressed.
if (!isset($pageSuppressContainer)) {
    echo "<div id='OutermostWrapper'><div class='ChisimbaCanvas' id='_default'>\n"
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
            <?php
            //if (!isset($pageSuppressSearch)) {
            //    echo $objSkin->siteSearchBox();
            //}
}
if (!$this->objUser->isLoggedIn()) {
    echo "<div id='prelogin_links'>Hello world yippee</div>";
}

if (!isset($pageSuppressToolbar)) {
    echo "\n\n<div id='navigation'>\n\n" . $toolbar . "\n</div>\n\n";
}

if (!isset($pageSuppressBanner)) {
    // End of Canvas_Content_Head
    ?>
    </div>
    <div class="Canvas_Content_Head_After"></div>
    <?php
}


// Render the laout content as supplied from the layout template
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
    echo "</div><div class='Canvas_AfterContainer'></div>\n</div>\n</div></div>";
}



// Render any messages available.
$this->putMessages();

// Close up the body and HTML and finish up.
echo '
<script type="text/javascript" src="skins/' . $skinName . '/js/jquery.imageScale.js"></script>
<script type="text/javascript" src="skins/' . $skinName . '/js/helper.js"></script>
';

?>
</body>
</html>