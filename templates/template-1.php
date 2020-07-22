<?php

$output .= "<div class=\"wcioApl-item product-id-".$product["id"]."\">";
$output .= "<div class=\"wcioApl-item-inner\">";
	$output .= "<div class=\"img-box\">";
		$output .= "<img src=\"".$product["productImage"]."\" class=\"sizedImg\">";

		$output .= "
				<a href=\"".$product["productUrl"]."\" class=\"productLink fusion-button button-flat fusion-button-default-size button-default button-1 fusion-button-span-yes fusion-button-default-type\" target=\"_blank\">
					<i class=\"fa-cart-arrow-down fas button-icon-left\"></i>
						<span class=\"fusion-button-text\">Gå til butik</span>
				</a>";

	$output .= "</div>";

	$output .= "<div class=\"price-box\">";
		$output .= "<span class=\"price new-price\">".$wcio_apl->wcio_apl_displayPrice($product["productPrice"])." kr.";
		if($product["productOldPrice"] !="") {
		"<span class=\"small-price\">(Før: ".$wcio_apl->wcio_apl_displayPrice($product["productOldPrice"])." kr.)</span>";
		}
		$output .= "</span>";
	$output .= "</div>";

	$output .= "<div class=\"category-box\">";
	$output .= "<h3>".$product["productName"]."</h3>";
	$output .= "</div>";



$output .= "</div>";
$output .= "</div>";

if($i == "4") {
	$output .= "</div>";
	$output .= "<div class=\"wcioApl-wrapper template-".$atts['template']."\">";
	$i="0";
}

?>
