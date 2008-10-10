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
    echo '<a href="'.gen_url(array('model'=>'administration','event'=>'edit','item'=>'user')).'">'.$data['locale']->add_user.'</a> '."\n";
    echo ' | <a href="'.gen_url(array('model'=>'administration','event'=>'dump','item'=>'user','csv'=>1)).'">'.$data['locale']->dump.'</a> '."\n";
    echo '</div>'."\n";
    
    if (iterable($data['users'])){
    	echo '<table border="1" id="subcat" class="vypis">';
  		table_th($data['user_th'],2);
  		$num_of_users = 5;
  		$j = 0;
		$i = isset($_GET['page']) ? $_GET['page'] * $num_of_users : 0;
		$max = min($i + $num_of_users, count($data['users']));
		for($i; $i < $max; $i++){
			$class = $data['users'][$i]['active'] ? '' : 'red';
			$class .=  $j % 2 ? ' second' : '';
			$edit_url = gen_url(array('model'=>'administration','event'=>'edit','item'=>'user','id'=>$data['users'][$i]['id_user']));
			$del_url = gen_url(array('model'=>'administration','event'=>'del','item'=>'user','id'=>$data['users'][$i]['id_user']));
			echo "\n".'<tr'.($class ? ' class="'.$class.'"' : '').'>'."\n";
    		echo "\t".'<td><a href="'.$edit_url.'" title="'.$data['locale']->open.' '.output($data['users'][$i]['name'],1).'" >'.$data['users'][$i]['id_user'].'</a></td>'."\n";
    		echo "\t".'<td><a href="'.$edit_url.'" title="'.$data['locale']->open.' '.output($data['users'][$i]['name'],1).'" >'.output($data['users'][$i]['name']).'</a></td>'."\n";
    		echo "\t".'<td>'.output($data['users'][$i]['created']).'</td>'."\n";
    		echo "\t".'<td>'.output($data['users'][$i]['group'],1).'</td>'."\n";
    		//echo "\t".'<td>'.output($data['users'][$i]['comments'],1).'</td>'."\n";
    		//echo "\t".'<td>'.output($data['users'][$i]['count'],1).'</td>'."\n";
    		//echo "\t".'<td>'.output($data['users'][$i]['karma'],1).'</td>'."\n";
			echo "\t".'<td><a href="'.$edit_url.'" title="'.$data['locale']->edit.' '.$data['users'][$i]['name'].'" ><img src="'.APP_URL.'/images/edit.gif" alt="'.$data['locale']->edit.'"/>&nbsp;'.$data['locale']->edit.'</a>
              	  	       <a href="'.$del_url.'" onclick="javascript:return confirm(\''.$data['locale']->really_delete.' '.output($data['users'][$i]['name'],1).'?\');" title="'.$data['locale']->delete.' '.$data['users'][$i]['name'].'"><img src="'.APP_URL.'/images/delete.gif" alt="'.$data['locale']->delete.'"/>&nbsp;'.$data['locale']->delete.'</a>
          		  	   </td>'."\n";
    		echo '</tr>'."\n";
    		$j++;
    	}
    	echo '</table>';	
    	paging($data['users'],$num_of_users);
    } else echo $data['locale']->no_items;
?>
