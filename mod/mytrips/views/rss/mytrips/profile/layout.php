<?php
/**
 * profile RSS view
 * Displays a list of the latest content in the group
 *
 * @uses $vars['entity'] ElggGroup object
* 	Plugin: mytripsTeranga
*	Author: Rosana Montes Soldado from previous version of @package ElggGroups
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
*/

$entities = elgg_get_config('registered_entities');

if (!empty($entities['object'])) {
	echo elgg_list_entities(array(
		'type' => 'object',
		'subtypes' => $entities['object'],
		'container_guid' => $vars['entity']->getGUID(),
	));
}
