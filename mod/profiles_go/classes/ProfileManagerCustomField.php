<?php
/**
 * ProfileManagerCustomField
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

abstract class ProfileManagerCustomField extends ElggObject {
	
	/**
	 * initializes the default class attributes
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		
		$this->attributes['access_id'] = ACCESS_PUBLIC;
		$this->attributes['owner_guid'] = elgg_get_site_entity()->getGUID();
		$this->attributes['container_guid'] = elgg_get_site_entity()->getGUID();
	}
	
	/**
	 * Returns an array of options to be used in input views
	 *
	 * @param boolean $add_blank_option optional boolean if there should be an extra empty option added
	 *
	 * @return string
	 */
	public function getOptions($add_blank_option = false) {
		$options = "";
		
		// get options
		if (empty($this->metadata_options)) {
			return $options;
		}
			
		$options = explode(",", $this->metadata_options);
		
		if ($this->blank_available == "yes") {
			// if field has a blank option available, always add the blank option
			$add_blank_option = true;
		}
		
		if ($this->metadata_type != "multiselect" && $add_blank_option) {
			// optionally add a blank option to the field options
			array_unshift($options, "");
		}
		//para Teranga
		for($i = 0; $i <= count($options); $i++)
		{
			$value = $options[$i];
			if (strpos($value,'custom:package:') !== false)
			{
				$value = elgg_echo($value);//solucion anterior para traducir los multicheck 
				//system_message("class PM_CF " . $value);
				$options[$i] = $value;
			}
		}
		$options = array_combine($options, $options); // add values as labels for deprecated notices

		return $options;
	}
	
	/**
	 * Returns the hint text
	 *
	 * @return string
	 */
	public function getHint() 
	{
		/* Make hint for Teranga
		$result = $this->metadata_hint;
		
		if (empty($result)) {
			if (elgg_language_key_exists("profile:hint:{$this->metadata_name}")) {
				$result = elgg_echo("profile:hint:{$this->metadata_name}");system_message("hint " . $result);
			}
		}*/
		$result = elgg_echo("profile:hint:{$this->metadata_name}");
		return $result;
	}

	/**
	 * Returns the placeholder text
	 *
	 * @return string
	 */
	public function getPlaceholder() {
		$result = $this->metadata_placeholder;
		
		if (empty($result)) {
			if (elgg_language_key_exists("profile:placeholder:{$this->metadata_name}")) {
				$result = elgg_echo("profile:placeholder:{$this->metadata_name}");
			}
		}
		return $result;
	}
}
