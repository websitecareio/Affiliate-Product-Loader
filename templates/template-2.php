<?php

$output .= "<div class=\"wcioApl-item wcioApl-item-equalheight product-id-".$product["id"]."\">";
$output .= "<a href=\"".$product["productUrl"]."\">";
$output .= "<div class=\"wcioApl-item-inner\">";

if($product["productPriceOld"] > 0 && $product["productPriceOld"] != $product["productPrice"]) {
	$output .= "<span class=\"small-price-discount\">-". number_format((($product["productPrice"]/$product["productPriceOld"])*100), 0, ',', '.')."%</span>";
}
	$output .= "<div class=\"img-box\">";
		$output .= "<img src=\"".$product["productImage"]."\" class=\"sizedImg\">";
	$output .= "</div>";

	$output .= "<div class=\"category-box\">";
	$output .= "<h3>".$product["productName"]."</h3>";
	$output .= "</div>";
	$output .= "<div class=\"price-box\">";
		$output .= "<span class=\"price new-price\">".$wcio_apl->wcio_apl_displayPrice($product["productPrice"])." kr.";

		if($product["productPriceOld"] > 0 && $product["productPriceOld"] != $product["productPrice"]) {
			$output .= "<span class=\"small-price\">".$wcio_apl->wcio_apl_displayPrice($product["productPriceOld"])." kr</span>";
		}

		$output .= "</span>";
	$output .= "</div>";
	$output .= "<div class=\"whiteSpace\" style=\"display:block;width:100%;height:30px;\">&nbsp;</div>";




			$output .= "<div class=\"button\">
					<a href=\"".$product["productUrl"]."\" class=\"productLink fusion-button button-flat fusion-button-default-size button-default button-1 fusion-button-span-yes fusion-button-default-type\" target=\"_blank\">
							<span class=\"fusion-button-text\">Til butik</span>
					</a>
					</div>";





$output .= "</div>";

$output .= "</a>";

$output .= "</div>";

if($i == "4") {
	$output .= "</div>";
	$output .= "<div class=\"wcioApl-wrapper template-".$atts['template']."\">";
	$i="0";
}
$output .= "<script type=\"text/javascript\">//equalHeights(\"wcioApl-item-equalheight\");</script>"
?>
