<?php
/**
 * Pattern content.
 */
return array(
	'title'      => __( 'Core Home Article', 'echelon-fse' ),
	'categories' => array( 'echelon-fse-core' ),
	'content'    => '<!-- wp:group {"tagName":"main","style":{"spacing":{"padding":{"top":"100px","bottom":"140px","right":"10px","left":"10px"},"blockGap":"24px"}},"layout":{"contentSize":"1200px","type":"constrained"}} -->
<main class="wp-block-group" style="padding-top:100px;padding-right:10px;padding-bottom:140px;padding-left:10px"><!-- wp:heading {"textAlign":"center","level":3,"className":"echelon-fse-animate echelon-fse-move-up","style":{"typography":{"textTransform":"uppercase","fontStyle":"normal","fontWeight":"400","letterSpacing":"4px","fontSize":"15px"}},"textColor":"theme-1","fontFamily":"lato"} -->
<h3 class="wp-block-heading has-text-align-center echelon-fse-animate echelon-fse-move-up has-theme-1-color has-text-color has-lato-font-family" style="font-size:15px;font-style:normal;font-weight:400;letter-spacing:4px;text-transform:uppercase">Latest News</h3>
<!-- /wp:heading -->

<!-- wp:heading {"textAlign":"center","className":"echelon-fse-animate echelon-fse-move-up echelon-fse-delay-3","style":{"typography":{"fontStyle":"normal","fontWeight":"700"}},"textColor":"theme-0","fontSize":"heading-2","fontFamily":"lato"} -->
<h2 class="wp-block-heading has-text-align-center echelon-fse-animate echelon-fse-move-up echelon-fse-delay-3 has-theme-0-color has-text-color has-lato-font-family has-heading-2-font-size" style="font-style:normal;font-weight:700">Our News &amp; Article</h2>
<!-- /wp:heading -->

<!-- wp:columns {"className":"echelon-fse-animate echelon-fse-move-up echelon-fse-delay-5","style":{"spacing":{"blockGap":{"top":"40px","left":"40px"}}}} -->
<div class="wp-block-columns echelon-fse-animate echelon-fse-move-up echelon-fse-delay-5"><!-- wp:column {"width":"70%"} -->
<div class="wp-block-column" style="flex-basis:70%"><!-- wp:query {"queryId":71,"query":{"perPage":"2","pages":0,"offset":"0","postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"tagName":"main"} -->
<main class="wp-block-query"><!-- wp:group {"textColor":"third"} -->
<div class="wp-block-group has-third-color has-text-color"><!-- wp:post-template {"style":{"spacing":{"blockGap":"30px"}},"layout":{"type":"grid","columnCount":2}} -->
<!-- wp:post-featured-image {"isLink":true,"height":"280px","align":"wide"} /-->

<!-- wp:post-terms {"term":"category","style":{"typography":{"fontSize":"14px"},"elements":{"link":{"color":{"text":"var:preset|color|theme-2"}}}},"textColor":"theme-2"} /-->

<!-- wp:post-title {"level":4,"isLink":true,"style":{"elements":{"link":{"color":{"text":"var:preset|color|theme-0"}}},"typography":{"fontStyle":"normal","fontWeight":"700"}},"textColor":"theme-0","fontSize":"heading-4","fontFamily":"lato"} /-->

<!-- wp:post-date {"style":{"typography":{"fontSize":"14px"},"color":{"text":"#a2a2a2"}}} /-->

<!-- wp:post-excerpt {"excerptLength":15,"style":{"typography":{"fontSize":"15px"},"spacing":{"margin":{"top":"10px"}}},"textColor":"theme-4"} /-->
<!-- /wp:post-template --></div>
<!-- /wp:group --></main>
<!-- /wp:query --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"30%","style":{"spacing":{"padding":{"top":"0px"},"blockGap":"24px"}}} -->
<div class="wp-block-column" style="padding-top:0px;flex-basis:30%"><!-- wp:heading {"textAlign":"left","level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"700"}},"textColor":"theme-0","fontSize":"heading-3","fontFamily":"lato"} -->
<h3 class="wp-block-heading has-text-align-left has-theme-0-color has-text-color has-lato-font-family has-heading-3-font-size" style="font-style:normal;font-weight:700">Recent News</h3>
<!-- /wp:heading -->

<!-- wp:separator {"className":"is-style-wide","style":{"color":{"background":"#e7eaf1"}}} -->
<hr class="wp-block-separator has-text-color has-alpha-channel-opacity has-background is-style-wide" style="background-color:#e7eaf1;color:#e7eaf1"/>
<!-- /wp:separator -->

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"0px","right":"0px","bottom":"0px","left":"0px"}},"color":{"text":"#ffffff"}}} -->
<div class="wp-block-group alignfull has-text-color" style="color:#ffffff;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:query {"queryId":64,"query":{"perPage":"3","pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
<div class="wp-block-query"><!-- wp:post-template {"style":{"spacing":{"blockGap":"20px"}},"layout":{"type":"default","columnCount":3}} -->
<!-- wp:group {"style":{"spacing":{"blockGap":"7px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:post-title {"isLink":true,"style":{"typography":{"fontStyle":"normal","fontWeight":"700","lineHeight":1.6},"color":{"link":"#ffffff"},"elements":{"link":{"color":{"text":"var:preset|color|primary"}}}},"textColor":"theme-0","fontSize":"heading-6","fontFamily":"lato"} /-->

<!-- wp:post-date {"style":{"color":{"text":"#a2a2a2"},"typography":{"fontSize":"14px"}}} /--></div>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:group -->

<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"40px"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons" style="margin-top:40px"><!-- wp:button {"width":100,"className":"is-style-custombuttonone","style":{"border":{"radius":"0px"},"typography":{"fontSize":"15px"},"spacing":{"padding":{"top":"13px","bottom":"13px","left":"30px","right":"30px"}}}} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100 has-custom-font-size is-style-custombuttonone" style="font-size:15px"><a class="wp-block-button__link wp-element-button" href="#" style="border-radius:0px;padding-top:13px;padding-right:30px;padding-bottom:13px;padding-left:30px">View All</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></main>
<!-- /wp:group -->',
	'images'      => '',
	'is_sync' => false,
);
