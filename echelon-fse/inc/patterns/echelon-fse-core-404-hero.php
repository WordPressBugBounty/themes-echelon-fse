<?php
/**
 * Pattern content.
 */
return array(
	'title'      => __( 'Core 404 Hero', 'echelon-fse' ),
	'categories' => array( 'echelon-fse-core' ),
	'content'    => '<!-- wp:group {"tagName":"main","style":{"spacing":{"margin":{"top":"0px","bottom":"0px"}}},"layout":{"type":"default"}} -->
<main class="wp-block-group" style="margin-top:0px;margin-bottom:0px"><!-- wp:cover {"url":"' . esc_url( trailingslashit( get_template_directory_uri() ) ) . 'assets/img/StockSnap_4FZD5QXDDN.webp","id":38,"dimRatio":70,"overlayColor":"black","isUserOverlayColor":true,"focalPoint":{"x":"0.56","y":"0.38"},"contentPosition":"center center"} -->
<div class="wp-block-cover"><img class="wp-block-cover__image-background wp-image-38" alt="" src="' . esc_url( trailingslashit( get_template_directory_uri() ) ) . 'assets/img/StockSnap_4FZD5QXDDN.webp" style="object-position:56% 38%" data-object-fit="cover" data-object-position="56% 38%"/><span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim-70 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"spacing":{"padding":{"top":"260px","bottom":"260px"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group" style="padding-top:260px;padding-bottom:260px"><!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"style":{"spacing":{"blockGap":"20px"}}} -->
<div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"blockGap":"0px"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":1,"style":{"typography":{"fontSize":"112px"}},"textColor":"white"} -->
<h1 class="wp-block-heading has-white-color has-text-color" style="font-size:112px">404</h1>
<!-- /wp:heading -->

<!-- wp:heading {"textColor":"white","fontSize":"heading-2"} -->
<h2 class="wp-block-heading has-white-color has-text-color has-heading-2-font-size">Page Not Found</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"align":"center","textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color">It looks like nothing was found at this location. Maybe try a search?</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":"10px"} -->
<div style="height:10px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:search {"label":"Search...","showLabel":false,"width":50,"widthUnit":"%","buttonText":"Search","buttonUseIcon":true,"align":"center","style":{"border":{"radius":"0px","width":"0px","style":"none"}},"backgroundColor":"gv-color-primary","textColor":"white"} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover --></main>
<!-- /wp:group -->',
	'is_sync' => false,
);
