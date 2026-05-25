<?php
/**
 * Pattern content.
 */
return array(
	'title'      => __( 'Core Header', 'echelon-fse' ),
	'categories' => array( 'echelon-fse-core' ),
	'content'    => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"0px","bottom":"0px","right":"10px","left":"10px"}}},"backgroundColor":"fourth","layout":{"contentSize":"1200px","type":"constrained"}} -->
<div class="wp-block-group has-fourth-background-color has-background" style="padding-top:0px;padding-right:10px;padding-bottom:0px;padding-left:10px"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group"><!-- wp:site-title {"level":2,"style":{"typography":{"fontSize":"28px"},"spacing":{"padding":{"top":"20px","bottom":"20px"}}},"textColor":"gv-color-primary"} /-->

<!-- wp:navigation {"className":"is-style-customnav","style":{"typography":{"fontSize":"15px"},"spacing":{"blockGap":"35px"}},"fontFamily":"primary","layout":{"type":"flex","orientation":"horizontal","justifyContent":"center"}} --><!-- wp:navigation-link {"label":"Home","url":"#","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-link {"label":"About Us","url":"#","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-submenu {"label":"Page","url":"#","kind":"custom","isTopLevelItem":true} -->
<!-- wp:navigation-link {"label":"Blog","url":"#","kind":"custom","isTopLevelLink":false} /-->

<!-- wp:navigation-link {"label":"Single Post","url":"#","kind":"custom"} /-->
<!-- /wp:navigation-submenu -->

<!-- wp:navigation-link {"label":"Contact Us","url":"#","kind":"custom","isTopLevelLink":true} /--><!-- /wp:navigation -->

<!-- wp:buttons {"className":"hide-in-tablet hide-in-mobile","layout":{"type":"flex","justifyContent":"right"}} -->
<div class="wp-block-buttons hide-in-tablet hide-in-mobile"><!-- wp:button {"className":"is-style-custombuttonone","style":{"border":{"radius":"0px"},"spacing":{"padding":{"top":"12px","bottom":"12px","left":"30px","right":"30px"}},"typography":{"fontSize":"15px"}}} -->
<div class="wp-block-button is-style-custombuttonone"><a class="wp-block-button__link has-custom-font-size wp-element-button" href="#" style="border-radius:0px;padding-top:12px;padding-right:30px;padding-bottom:12px;padding-left:30px;font-size:15px">Contact Us</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
	'is_sync' => false,
);
