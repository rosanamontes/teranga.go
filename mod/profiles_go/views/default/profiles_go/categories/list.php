<?php
/**
* Category list view
*
* 	Plugin: profiles_go from previous version of @package profile_manager of Coldtrick IT Solutions 2009
*	Author: Rosana Montes Soldado 
*			Universidad de Granada
*	Licence: 	CC-ByNCSA
*	Reference:	Microproyecto CEI BioTIC Ref. 11-2015
* 	Project coordinator: @rosanamontes
*	Website: http://lsi.ugr.es/rosana
* 	Project colaborator: Antonio Moles 
*	
*   Project Derivative:
*	TFG: Desarrollo de un sistema de gestión de paquetería para Teranga Go
*   Advisor: Rosana Montes
*   Student: Ricardo Luzón Fernández
*
*/

$options = array(
		"type" => "object",
		"subtype" => CUSTOM_PROFILE_FIELDS_CATEGORY_SUBTYPE,
		"limit" => false,
		"pagination" => false,
		"owner_guid" => elgg_get_site_entity()->getGUID(),
		"order_by_metadata" => array("name" => "order", "as" => "integer"),
	);

$categories = elgg_list_entities_from_metadata($options);

if (!empty($categories)) {
	$list = $categories;
} else {
	$list = elgg_echo("profiles_go:categories:list:no_categories");
}
	
?>
<div class="elgg-module elgg-module-inline">
	<div class="elgg-head">
		<?php echo elgg_view("output/url", array("text" => elgg_echo("add"), "href" => "ajax/view/forms/profiles_go/category", "class" => "elgg-button elgg-button-action profile-manager-popup elgg-lightbox")); ?>
		<h3>
			<?php echo elgg_echo('profiles_go:categories:list:title'); ?>
			<span class='custom_fields_more_info' id='more_info_category_list'></span>
		</h3>
	</div>
	<div class="elgg-body" id="custom_fields_category_list_custom">
		<div id="custom_profile_field_category_all" class="custom_fields_category"><a href="javascript:void(0);" onclick="elgg.profiles_go.filter_custom_fields();"><?php echo elgg_echo("all"); ?></a></div>
		<div id="custom_profile_field_category_0" class="custom_fields_category"><a href="javascript:void(0);" onclick="elgg.profiles_go.filter_custom_fields(0);"><?php echo elgg_echo("profiles_go:categories:list:default"); ?></a></div>
		<?php echo $list; ?>
	</div>
</div>

<div class="hidden" id="text_more_info_category"><?php echo elgg_echo("profiles_go:tooltips:category");?></div>
<div class="hidden" id="text_more_info_category_list"><?php echo elgg_echo("profiles_go:tooltips:category_list");?></div>

