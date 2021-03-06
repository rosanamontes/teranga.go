<?php

/**
* 	Plugin: Valoraciones linguisticas con HFLTS
*	Author: Rosana Montes Soldado
*			Universidad de Granada
*	Licence: 	CC-ByNCSA
*	Reference:	CEI BioTIC Micro.proyect Ref. 11-2015
* 	Project coordinator: @rosanamontes
*	Website: http://lsi.ugr.es/rosana
*	
*	File: onia Hajlaoui IEEE'13
*		IEEE 2013 – Hesitant Fuzzy Promethee method
*
* 	@package DecisionMaking
*
*/


class PrometheeHF extends MCDM
{
	var $label;//shortname

	var $relative;		//relative importance of criteria
	var $preference;	//preference functions for each criterion

	var $positiveFlow;	//entering flow for each alternative
	var $negativeFlow;	//outgoing flow for each alternative
	var $netFlow;		//difference flow for each alternative

	var $ranking; //alternatives ranked array

	public function	PrometheeHF($username)
	{
		$this->N=1;
		$this->M=4;
		$this->P=$this->num=0;
		$this->label="promethee";

		$this->alternatives = array($username);
		$this->W = array(1.0, 1.0, 1.0,1.0); //same importance by default
	}

	public function run()
	{
		parent::run();
		//method not implemented 
		
		return $this->ranking[0]['promethee']['label'];
	}



	private function ranking()
	{
		if ($this->information)
		{
			echo('<br>Ranking <pre>');	print_r($this->ranking);	echo('</pre>');
		}
		return $this->ranking;
	}

}
