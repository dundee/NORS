<?php

/**
*
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/


echo "\n";
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
	echo '<div id="actions">'."\n";
    echo '<a href="'.gen_url(array('model'=>'administration','event'=>'edit','item'=>'category','parent'=>$id)).'">'.$data['locale']->add_category.'</a>  '."\n";
    echo ' | <a href="'.gen_url(array('model'=>'administration','event'=>'edit','item'=>'text','id_category'=>$id)).'">'.$data['locale']->add_text.'</a> '."\n";
    echo ' | <a href="'.gen_url(array('model'=>'administration','event'=>'dump','item'=>'text','csv'=>1)).'">'.$data['locale']->dump.'</a> '."\n";
    echo ' | <a href="'.gen_url(array('model'=>'administration','event'=>'category_tree','id'=>$id)).'">'.$data['locale']->category_tree.'</a> '."\n";
    echo '</div>'."\n";
    
    echo $data['breadcrumbs'];

    echo '<h2>'.$data['locale']->categories.'</h2>'."\n";

    if (iterable($data['categories'])){
    	echo '<table border="1" id="subcat" class="vypis">'."\n";
  		table_th($data['category_th']);
		$i = 0;
		foreach($data['categories'] as $category){
			$open_url = gen_url(array('model'=>'administration','event'=>'text','id'=>$category['id_category']));
			$edit_url = gen_url(array('model'=>'administration','event'=>'edit','item'=>'category','id'=>$category['id_category']));
			$del_url = gen_url(array('model'=>'administration','event'=>'del','item'=>'category','id'=>$category['id_category']));
			echo "\n".'<tr'.($i % 2 ? ' class="second"' : '').'>'."\n";
			echo "\t".'<td><a href="'.$open_url.'" title="'.$data['locale']->open.' '.output($category['name'],1).'" >'.$category['id_category'].'</a></td>'."\n";
    		echo "\t".'<td><a href="'.$open_url.'" title="'.$data['locale']->open.' '.output($category['name'],1).'" >'.output($category['name']).'</a></td>'."\n";
    		echo "\t".'<td><a href="'.$edit_url.'" title="'.$data['locale']->edit.' '.$category['name'].'" ><img src="'.APP_URL.'/images/edit.gif" alt="'.$data['locale']->edit.'"/>&nbsp;'.$data['locale']->edit.'</a>
              	  	  <a href="'.$del_url.'" onclick="javascript:return confirm(\''.$data['locale']->really_delete.' '.output($category['name'],1).'?\');" title="'.$data['locale']->delete.' '.$category['name'].'"><img src="'.APP_URL.'/images/delete.gif" alt="'.$data['locale']->delete.'"/>&nbsp;'.$data['locale']->delete.'</a>
          		  </td>'."\n";
    		echo '</tr>'."\n";
    		$i++;
    	}
    	echo '</table>';
    } else echo $data['locale']->no_items;


    echo '<br /><h2>'.$data['locale']->texts.'</h2><br />';

    if (iterable($data['texts'])){
    	echo '<table border="1" id="subcat" class="vypis">';
  		table_th($data['text_th'],2);
  		$num_of_texts = 5;
  		$j = 0;
		$i = isset($_GET['page']) ? $_GET['page'] * $num_of_texts : 0;
		$max = min($i + $num_of_texts, count($data['texts']));
		for($i; $i < $max; $i++){
			$class = $data['texts'][$i]['active'] ? '' : 'red';
			$class .=  $j % 2 ? ' second' : '';
			$edit_url = gen_url(array('model'=>'administration','event'=>'edit','item'=>'text','id'=>$data['texts'][$i]['id_text']));
			$del_url = gen_url(array('model'=>'administration','event'=>'del','item'=>'text','id'=>$data['texts'][$i]['id_text']));
			echo "\n".'<tr'.($class ? ' class="'.$class.'"' : '').'>'."\n";
    		echo "\t".'<td><a href="'.$edit_url.'" title="'.$data['locale']->open.' '.output($data['texts'][$i]['name'],1).'" >'.$data['texts'][$i]['id_text'].'</a></td>'."\n";
    		echo "\t".'<td><a href="'.$edit_url.'" title="'.$data['locale']->open.' '.output($data['texts'][$i]['name'],1).'" >'.output($data['texts'][$i]['name']).'</a></td>'."\n";
    		echo "\t".'<td>'.output($data['texts'][$i]['date']).'</td>'."\n";
    		echo "\t".'<td>'.output($data['texts'][$i]['category'],1).'</td>'."\n";
    		echo "\t".'<td>'.output($data['texts'][$i]['user'],1).'</td>'."\n";
    		//echo "\t".'<td>'.output($data['texts'][$i]['comments'],1).'</td>'."\n";
    		//echo "\t".'<td>'.output($data['texts'][$i]['count'],1).'</td>'."\n";
    		//echo "\t".'<td>'.output($data['texts'][$i]['karma'],1).'</td>'."\n";
			echo "\t".'<td><a href="'.$edit_url.'" title="'.$data['locale']->edit.' '.$data['texts'][$i]['name'].'" ><img src="'.APP_URL.'/images/edit.gif" alt="'.$data['locale']->edit.'"/>&nbsp;'.$data['locale']->edit.'</a>
              	  	       <a href="'.$del_url.'" onclick="javascript:return confirm(\''.$data['locale']->really_delete.' '.output($data['texts'][$i]['name'],1).'?\');" title="'.$data['locale']->delete.' '.$data['texts'][$i]['name'].'"><img src="'.APP_URL.'/images/delete.gif" alt="'.$data['locale']->delete.'"/>&nbsp;'.$data['locale']->delete.'</a>
          		  	   </td>'."\n";
    		echo '</tr>'."\n";
    		$j++;
    	}
    	echo '</table>';	
    	paging($data['texts'],$num_of_texts);
    } else echo $data['locale']->no_items;
?>
