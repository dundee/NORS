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
    echo '<a href="'.gen_url(array('model'=>'administration','event'=>'edit','item'=>'group')).'">'.$data['locale']->add_group.'</a> '."\n";
    echo ' | <a href="'.gen_url(array('model'=>'administration','event'=>'dump','item'=>'group','csv'=>1)).'">'.$data['locale']->dump.'</a> '."\n";
    echo '</div>'."\n";
    
    if (iterable($data['groups'])){
    	echo '<table border="1" id="subcat" class="vypis">';
  		table_th($data['group_th'],2);
  		$num_of_items = 5;
  		$j = 0;
		$i = isset($_GET['page']) ? $_GET['page'] * $num_of_items : 0;
		$max = min($i + $num_of_items, count($data['groups']));
		for($i; $i < $max; $i++){
			$class .=  $j % 2 ? ' second' : '';
			$edit_url = gen_url(array('model'=>'administration','event'=>'edit','item'=>'group','id'=>$data['groups'][$i]['id_group']));
			$del_url = gen_url(array('model'=>'administration','event'=>'del','item'=>'group','id'=>$data['groups'][$i]['id_group']));
			echo "\n".'<tr'.($class ? ' class="'.$class.'"' : '').'>'."\n";
    		echo "\t".'<td><a href="'.$edit_url.'" title="'.$data['locale']->open.' '.output($data['groups'][$i]['name'],1).'" >'.$data['groups'][$i]['id_group'].'</a></td>'."\n";
    		echo "\t".'<td><a href="'.$edit_url.'" title="'.$data['locale']->open.' '.output($data['groups'][$i]['name'],1).'" >'.output($data['groups'][$i]['name']).'</a></td>'."\n";
    		echo "\t".'<td>'.output($data['groups'][$i]['created']).'</td>'."\n";
    		//echo "\t".'<td>'.output($data['groups'][$i]['group'],1).'</td>'."\n";
    		//echo "\t".'<td>'.output($data['groups'][$i]['comments'],1).'</td>'."\n";
    		//echo "\t".'<td>'.output($data['groups'][$i]['count'],1).'</td>'."\n";
    		//echo "\t".'<td>'.output($data['groups'][$i]['karma'],1).'</td>'."\n";
			echo "\t".'<td><a href="'.$edit_url.'" title="'.$data['locale']->edit.' '.$data['groups'][$i]['name'].'" ><img src="'.APP_URL.'/images/edit.gif" alt="'.$data['locale']->edit.'"/>&nbsp;'.$data['locale']->edit.'</a>
              	  	       <a href="'.$del_url.'" onclick="javascript:return confirm(\''.$data['locale']->really_delete.' '.output($data['groups'][$i]['name'],1).'?\');" title="'.$data['locale']->delete.' '.$data['groups'][$i]['name'].'"><img src="'.APP_URL.'/images/delete.gif" alt="'.$data['locale']->delete.'"/>&nbsp;'.$data['locale']->delete.'</a>
          		  	   </td>'."\n";
    		echo '</tr>'."\n";
    		$j++;
    	}
    	echo '</table>';	
    	paging($data['groups'],$num_of_items);
    } else echo $data['locale']->no_items;
?>
