/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(function(){
    jQuery(document).ready(function(){
        jQuery(".toolbar_left, .featurebox,  .currentstory, #header,#footer, #nav-secondary").css("background-image","-webkit-gradient(linear, 0% 0%, 0% 100%, from(#ffffff), to(#EFEFEF))");
        jQuery(".toolbar_left, .featurebox,  .currentstory, #header,#footer, #nav-secondary").css("background-image","-moz-linear-gradient(-10% 90% 90deg,#EFEFEF, #ffffff)");
        jQuery("h5.featureboxheader, div.featurebox, .currentstory, ul#nav-secondary, div#navigation, h1#sitename, ul#menuList li").css("border-radius","3px");
        jQuery("input[type=text], input[type=password], input[type=search]").css("border-radius","2px");
        jQuery(".toolbar_left,  #header,#footer, .featurebox, ul#nav-secondary,  .currentstory").css("border", "3px solid #fff");
        jQuery(".toolbar_left, div.featurebox, ul#nav-secondary, .currentstory").css("box-shadow", "0 1px 2px #666");
    })
});