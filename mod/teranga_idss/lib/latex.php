<?php

/**
* 	Plugin: Valoraciones linguisticas con HFLTS
*	Author: Rosana Montes Soldado
*			Universidad de Granada
*	Licence: 	CC-ByNCSA
*	Reference:	HFLTS in a ELGG community: Teranga Go! CEI BioTIC project
* 	Project coordinator: @rosanamontes
*	Website: http://lsi.ugr.es/rosana
*	Date: july 2016
*	
*	File: Export data to LaTeX
*/


function set2latex($data, $sample_term, $M)
{
	//echo($sample_term . '<br>Dt from .csv <pre>');	print_r($data);	echo('</pre><hr>');	
	//ref,co_codigo,U1,L1,U2,L2,U3,L3,U4,L4,U5,L5,U6,L6,U7,L7,U8,L8,U9,L9	
	$n = count($data);
	$output = table_header($M+2,2);
	$length = $delta = 0;

	for ($z=0;$z<$n;$z++)//for each line line
	for ($x=-1;$x<$M;$x++)//print a line
	{
		if ($x < 0)
		{
			$output .= "\begin{scriptsize}\$" . $data[$z]['ref'] . "\$\\end{scriptsize} & ";
			$output .= "\begin{scriptsize}\$TC_{" . $data[$z]['co_codigo'] . "}\$\\end{scriptsize} ";
		}
		else
		{
			$inf = "L".($x+1);
			$sup = "U".($x+1);
			$envelope = array ("inf" => $data[$z][$inf], "sup" => $data[$z][$sup]);

			//If output is an interval
			$rangeTxt = "[ $s_" . $envelope['inf'] . "$, $s_" . $envelope['sup'] . "$ ]"; 

			//If output is a hesitant
			$h = toHesitant($envelope,$length,$delta);
			$hTxt = "$\{";
			for ($i=0;$i<$length;$i++)
			{
				$hTxt .= "s_".$h[$i];
				if ($i == $length-1)
					$hTxt .= "\}$";
				else
					$hTxt .= ", ";
			}			

			//now choose.... 
			//$output .= $rangeTxt;
			$output .= $hTxt;
		}

		if ($x < $M-1)
			$output .= " & ";
		else
			$output .= "\\\\ <br>";
	}

	$output .= table_footer("$sample_term data set", $sample_term);

	echo $output . "<hr>";
}


function weight2latex($data, $sample_term, $M)
{
	//echo($sample_term . '<br>W from .csv <pre>');	print_r($data);	echo('</pre><hr>');	
	//expert,we,C1,C2,C3,C4,C5,C6,C7,C8,C9	

	$output = table_header($M+2,2);
	$n = count($data);

	for ($z=0;$z<$n;$z++)//for each line line
	for ($x=-1;$x<$M;$x++)//print a line
	{
		if ($x < 0)
		{
			$output .= "\begin{scriptsize}\$E_{" .$data[$z]['expert']. "}\$\\end{scriptsize} & ";
			$output .= "\begin{scriptsize}" .$data[$z]['we']. "\\end{scriptsize}";
		}
		else
		{
			$y = "C".($x+1);
			$output .= $data[$z][$y];
		}

		if ($x < $M-1)
			$output .= " & ";
		else
			$output .= "\\\\ <br>";
	}

	$output .= table_footer("$sample_term example weights", $sample_term);
	
	echo $output . "<hr>";
}


	//..-..-..-..-..-..-..-..-..-..-..-..-..- SUPPORTING FUNCTIONS -..-..-..-..-..-..-..-..-..-..-..-..-..-..-..-
	function table_header($nColumn, $nBlank)
	{
		$string = "%done from Teranga.Go! by Rosana Montes<br>
			\begin{table}[htp]<br>
			\begin{center}<br>
			\begin{tabular}{|";

		$m = $nBlank-1;
		for ($x=0;$x<$nColumn;$x++)
		{
			if ($m>0)
				$string .=  "c";
			else
				$string .=  "c|";
			$m--;
		}

		$string = $string ."}<br>
			\hline \hline <br>
			&	";

		$n = $nColumn-$nBlank; 
		$m = $nBlank-1;

		for ($x=-$m;$x<$n;$x++)//print a line
		{
			if ($x < 0)
				$string .= " & ";
			else
			{
				$string .= "\$C_". ($x+1). "$";

				if ($x < $n-1)
					$string .= " & ";
				else
					$string .= "\\\\ <br>";
			}
		}		

		$string .= "\hline \hline<br>";

		return $string;
	}

	function table_footer($caption, $label)
	{
		$string = "\hline \hline <br>
		\\end{tabular}<br>
		\\end{center}<br>
		\caption{".$caption."}<br>
		\label{".$label."}<br>
		\\end{table}";

		return $string;
	}

	//..-..-..-..-..-..-..-..-..-..-..-..-..- SUPPORTING FUNCTIONS -..-..-..-..-..-..-..-..-..-..-..-..-..-..-..-
