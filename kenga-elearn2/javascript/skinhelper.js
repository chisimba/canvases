/*
 * Javascript to support the kenga-elearn2 skin
 *
 * Written by Derek Keats derek@dkeats.com
 * Started on: April 28, 2012, 12:19 PM
 *
 *
 */
jQuery(function() {
    // Things to do on loading the page.
    jQuery(document).ready(function() {
        // Set equal height for the columns
        var maxHeight = Math.max(jQuery('#Canvas_Content_Body_Region1').height(),jQuery('#Canvas_Content_Body_Region2').height(),jQuery('#Canvas_Content_Body_Region3').height());
        jQuery('#Canvas_Content_Body_Region1').height(maxHeight);
        jQuery('#Canvas_Content_Body_Region2').height(maxHeight);
        jQuery('#Canvas_Content_Body_Region3').height(maxHeight);
    });
});