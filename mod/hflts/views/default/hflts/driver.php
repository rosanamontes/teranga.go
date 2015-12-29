<?php

/**
* 	Plugin: Valoraciones linguisticas con HFLTS
*	Author: Rosana Montes Soldado
*			Universidad de Granada
*	Licence: 	CC-ByNCSA
*	Reference:	Microproyecto CEI BioTIC Ref. 11-2015
* 	Project coordinator: @rosanamontes
*	Website: http://lsi.ugr.es/rosana
*	
*	File: private zone to get all info about the valorations of a user (mainly for drivers)
*/


$valorationlist = $vars['valorations'];//system_message(" Size\n " . sizeof($valorationlist));
	
if (sizeof($valorationlist) > 0) 
{

?>

<div class="evaluation-content evaluation-content-archived">
	<div class="clearfix">

<?php
	$count=0;
	$data = array('_','_');

	foreach ($valorationlist as $evaluation) 
	{
		$person_link = elgg_view('output/text', array(
				'text' => $evaluation->name,
				'href' => $evaluation->url,
				'is_trusted' => true,
				'class' => 'elgg-evaluation-content-address elgg-lightbox',
				'data-colorbox-opts' => json_encode([
					'width' => '85%',
					'height' => '85%',
					'iframe' => true,
				]),
		));

		$hesitant = "#".$count." => G=" .  $evaluation->granularity;
		$hesitant .= " H1=".$evaluation->criterio1;
		$hesitant .= " H2=".$evaluation->criterio2;
		$hesitant .= " H3=".$evaluation->criterio3;
		$data[$count] = [	$evaluation->criterio1,$evaluation->criterio1, 
							$evaluation->criterio2,$evaluation->criterio2,
							$evaluation->criterio3,$evaluation->criterio3];
		$count++;
		?>
		<h3 class="mbm"><?php echo $person_link; ?></h3>
		<p><?php echo $hesitant; ?></p>
		<?php
	}	
?>	
	</div>
</div>

<?php

} else {
	echo elgg_echo('hflts:evaluation:not:found');
}

//To work with the objects we get the entities
$method_list = elgg_get_entities_from_metadata([
	'type' => 'object',
	'subtype' => 'mcdm',
	'pagination' => false,
]);

if (!$method_list) {
	$method_list = '<p class="mtm">' . elgg_echo('hflts:evaluation:not:found') . '</p>';
}	
else {
	foreach ($method_list as $entity) 
	{
		$model = get_entity($entity->guid);

		if (!$model || $model->getSubtype() !== "mcdm" || !$model->canEdit()) 
		{
			register_error(elgg_echo("hflts:evaluation:not:found"));
			forward(REFERER);
		}

		if ($model->label == "classic")
		{
			$method = new AggregationHFLTS; 
		
			//$title=$method->getTitle();
			//$description=$method->getDescription();
			$method->setData($data,$count,$evaluation->granularity);
			//$N = $method->getAlternatives();
			//$M = $method->getCriteria();
			//$P = $method->getExperts();
			$model->collectiveValoration = $method->run();
			unset($method);//destroys the object 
			?>	
			<p><?php //echo $title . " (" . $description .") " . $N."x".$M."x".$P; ?></p>
			<?php
		}
		else system_message("nooorrrr");
	}
}

//To show objects we get the list
$method_list = elgg_list_entities_from_metadata([
	'type' => 'object',
	'subtype' => 'mcdm',
	'pagination' => false,
]);

if (!$method_list) {
	$method_list = '<p class="mtm">' . elgg_echo('hflts:evaluation:not:found') . '</p>';
}
else {
	echo $method_list;
}
