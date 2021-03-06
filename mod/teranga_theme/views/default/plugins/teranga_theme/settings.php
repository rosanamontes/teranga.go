<p class='et-admin-red'><strong>You need to complete this form, and 'SAVE', before EasyTheme is ready to use.</strong></p>

<h2>[1] Header Image</h2><br />
<img  style="width:100%; height: auto; padding: 5px; border: 1px dashed #000;" src="<?php echo elgg_get_site_url();?>mod/teranga_theme/graphics/headimg.jpg" alt="" />	


<p>First choose your <strong>HEADER IMAGE</strong> and save it as headimg.jpg in 'mod/teranga_theme/graphics'. <em> You need to choose your image carefully - make sure it is the right width and height for the header of your page.</em></p><p><strong>For this theme your image needs to be 1240px wide - and 370px high</strong></p> 

<h2>[2] Menu Bar</h2><br />
<img style="padding: 5px; border: 1px dashed #000;" src="<?php echo elgg_get_site_url();?>mod/teranga_theme/graphics/header.png" alt="" />	

<br /><br /><p><strong> Menu Bar ~ Colour '3' on the image above</strong> <em>(Theme default = #363636).</em></p>
<?php


	if(empty($vars['entity']->et2menu)){
	$vars['entity']->et2menu = "#363636";
	}

echo elgg_view('input/text', array(     'name' => 'params[et2menu]', 
                                        'value' => $vars['entity']->et2menu,
                                        'class' => 'teranga_theme', ) );  
                                        
                                        
echo "<br /><br /><p><strong> Top Bar ~ Colour '1' on the image above</strong> <em>(Theme default = #aba).</em><p>";                                        
	if(empty($vars['entity']->et2menu1)){
	$vars['entity']->et2menu1 = "#aba";
	}

echo elgg_view('input/text', array(     'name' => 'params[et2menu1]', 
                                        'value' => $vars['entity']->et2menu1,
                                        'class' => 'teranga_theme', ) );   

echo "<p><strong> Top Bar Border ~ Colour '2' on the image above</strong> <em>(Theme default = #d1d3d1).</em><p>";                                        
	if(empty($vars['entity']->et2menu2)){
	$vars['entity']->et2menu2 = "#d1d3d1";
	}

echo elgg_view('input/text', array(     'name' => 'params[et2menu2]', 
                                        'value' => $vars['entity']->et2menu2,
                                        'class' => 'teranga_theme', ) );        
echo "<p><strong>Menu Link Colour</strong> - Choose between Black or White <em>(Theme default = #fff).</em><p>";                                        
	if(empty($vars['entity']->et2menua)){
	$vars['entity']->et2menua = "#fff";
	}


echo elgg_view('input/text', array(     'name' => 'params[et2menua]', 
                                        'value' => $vars['entity']->et2menua,
                                        'class' => 'teranga_theme', ) );   
                                        
                                        
                                  
                                                                                     
?> 
	                                       
<br /><br /><h2>[3] The page background image</h2> 
<img  style="padding: 5px; border: 1px dashed #000; margin: 20px;" src="<?php echo elgg_get_site_url();?>mod/teranga_theme/graphics/bkgr.jpg" alt="" />
<br />The background image is found here: <strong>'mod/teranga_theme/graphics/bkgr.jpg'</strong>. Replace this image with a repeating image of your choice - any size - Remember to name your image <strong> 'bkgr.jpg'</strong>.</em></p><br />  
<br /><h2>[4] Forms</h2><br />                                                                                                            
<?php
echo '<div>';
echo 'Show <strong>Log In</strong> and <strong>Register Forms</strong> on the front page?';
echo ' ';
echo elgg_view('input/select', array(
	'name' => 'params[et2forms]',
	'options_values' => array(
		'no' => elgg_echo('option:no'),
		'yes' => elgg_echo('option:yes')
	),
	'value' => $vars['entity']->et2forms,
));
echo '</div>';
?>




<br /><br /><h2>[5] The Front Page Text Areas</h2> 
<?php



echo "<br /><p><strong>Short Site Introduction</strong> Write a short, 2 line, introduction to your site - <em>(This will appear at the top of the custom index page.  Be careful to copy/paste from a text file - otherwise it's easy to paste unwanted mark-up into the box.)</em></p>";

$myFile = elgg_get_data_path() . "teranga_theme/intro.php";
$fh = fopen($myFile, 'r');
if ($fh)
    $et2introfile = fread($fh, filesize($myFile));
else
	$et2introfile="<p><em>Teranga significa hospitalidad</em></p>";
fclose($fh);

echo elgg_view('input/longtext', array( 'name' => 'params[et2intro]', 
                                        'value' => $et2introfile,
                                        'class' => 'teranga_theme', ) ); 
                                        
                                        
echo "<br /><br /><p><strong>Full Site Introduction Text.</strong> Left Hand side.</p>";
$myFile = elgg_get_data_path() . "teranga_theme/textleft.php";
$fh2 = fopen($myFile, 'r');
$et2txtl = fread($fh2, filesize($myFile));
fclose($fh2);

echo elgg_view('input/longtext', array( 'name' => 'params[et2textleft]', 
                                        'value' => $et2txtl,
                                        'class' => 'teranga_theme', ) ); 
                                        
echo "<br /><br /><p><strong>Full Site Introduction Text.</strong> Right Hand side.</p>";
$myFile = elgg_get_data_path() . "teranga_theme/textright.php";
$fh3 = fopen($myFile, 'r');
$et2txtr = fread($fh3, filesize($myFile));
fclose($fh3);

echo elgg_view('input/longtext', array( 'name' => 'params[et2textright]', 
                                        'value' => $et2txtr,
                                        'class' => 'teranga_theme', ) ); 
                                        


echo "<br /><br /><h2>[6] The Search Box </h2><br /><p> You might need to move the search box up or down <em>(Theme default = -27px)</em></p> ";          

	if(empty($vars['entity']->et2search)){
	$vars['entity']->et2search = "-27px";
	}
                              
echo elgg_view('input/text', array(     'name' => 'params[et2search]', 
                                        'value' => $vars['entity']->et2search,
                                        'class' => 'teranga_theme', ) ); 

                                       
                                     

                                        
echo "<br /><br /><h2>[7] Heading and Link Colours</h2><br /><p>Link Colour (1) <em>(Theme default = #363636)</em></p> ";   

	if(empty($vars['entity']->et2color1)){
	$vars['entity']->et2color1 = "#363636";
	}
                                              
echo elgg_view('input/text', array(     'name' => 'params[et2color1]', 
                                        'value' => $vars['entity']->et2color1,
                                        'class' => 'teranga_theme', ) ); 

echo "<br /><br /><p>Link Colour (2) <em>(Theme default = #a95e27)</em></p> ";          

	if(empty($vars['entity']->et2color2)){
	$vars['entity']->et2color2 = "#a95e27";
	}
                                        
echo elgg_view('input/text', array(     'name' => 'params[et2color2]', 
                                        'value' => $vars['entity']->et2color2,
                                        'class' => 'teranga_theme', ) );                                         



                                   

                                  
echo "<br /><br /><h2>[8] Footer</h2><br /><p>Footer :: height <em>(Theme default = 100px)</em></p> ";    

	if(empty($vars['entity']->et2footh)){
	$vars['entity']->et2footh = "100px";
	}
                                    
echo elgg_view('input/text', array(     'name' => 'params[et2footh]', 
                                        'value' => $vars['entity']->et2footh,
                                        'class' => 'teranga_theme', ) ); 


echo "<br /><br /><p>Footer :: background colour <em>(Theme default = #eee)</em></p> ";   

	if(empty($vars['entity']->et2footbk)){
	$vars['entity']->et2footbk = "#eee";
	}
                                     
echo elgg_view('input/text', array(     'name' => 'params[et2footbk]', 
                                        'value' => $vars['entity']->et2footbk,
                                        'class' => 'teranga_theme', ) ); 


                                        
echo "<br /><br /><p>Footer :: text colour <em>(Theme default = #999)</em></p> ";      

	if(empty($vars['entity']->et2foottext)){
	$vars['entity']->et2foottext = "#999";
	}
                                  
echo elgg_view('input/text', array(     'name' => 'params[et2foottext]', 
                                        'value' => $vars['entity']->et2foottext,
                                        'class' => 'teranga_theme', ) ); 



echo "<br /><br /><p>Footer :: link colour <em> (Theme default = #ccc)</em></p> ";             

	if(empty($vars['entity']->et2footlink)){
	$vars['entity']->et2footlink = "#ccc";
	}
                           
echo elgg_view('input/text', array(     'name' => 'params[et2footlink]', 
                                        'value' => $vars['entity']->et2footlink ,
                                        'class' => 'teranga_theme', ) ); 


echo "<br /><br /><p>Footer :: link hover colour <em>(Theme default = #666)</em></p> ";  

	if(empty($vars['entity']->et2foothov)){
	$vars['entity']->et2foothov = "#666";
	}
                                                             
echo elgg_view('input/text', array(     'name' => 'params[et2foothov]', 
                                        'value' => $vars['entity']->et2foothov,
                                        'class' => 'teranga_theme', ) ); 




echo"<br /><p class='et-admin-red'><strong>Now save your settings.</strong></p>";
