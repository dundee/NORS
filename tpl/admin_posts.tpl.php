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
    echo ' | <a href="'.gen_url(array('model'=>'administration','event'=>'edit','item'=>'post','id_category'=>$id)).'">'.$data['locale']->add_post.'</a> '."\n";
    echo ' | <a href="'.gen_url(array('model'=>'administration','event'=>'dump','item'=>'post','csv'=>1)).'">'.$data['locale']->dump.'</a> '."\n";
    echo ' | <a href="'.gen_url(array('model'=>'administration','event'=>'category_tree','id'=>$id)).'">'.$data['locale']->category_tree.'</a> '."\n";
    echo '</div>'."\n";
    
    echo $data['breadcrumbs'];

    echo '<h2>'.$data['locale']->categories.'</h2>'."\n";

    if (iterable($data['categories'])){
    	echo '<table border="1" id="subcat" class="vypis">'."\n";
  		table_th($data['category_th']);
		$i = 0;
		foreach($data['categories'] as $category){
			$open_url = gen_url(array('model'=>'administration','event'=>'post','id'=>$category['id_category']));
			$edit_url = gen_url(array('model'=>'administration','event'=>'edit','item'=>'category','id'=>$category['id_category']));
			$del_url = gen_url(array('model'=>'administration','event'=>'del','item'=>'category','id'=>$category['id_category']));
			echo "\n".'<tr'.($i % 2 ? ' class="second"' : '').'>'."\n";
			echo "\t".'<td><a href="'.$open_url.'" title="'.$data['locale']->open.' '.output($category['name'],1).'" >'.$category['id_category'].'</a></td>'."\n";
    		echo "\t".'<td><a href="'.$open_url.'" title="'.$data['locale']->open.' '.output($category['name'],1).'" >'.output($category['name']).'</a></td>'."\n";
    		echo "\t".'<td>'.output($category['location']).'</td>'."\n";
    		echo "\t".'<td><a href="'.$edit_url.'" title="'.$data['locale']->edit.' '.$category['name'].'" ><img src="'.APP_URL.'/images/edit.gif" alt="'.$data['locale']->edit.'"/>&nbsp;'.$data['locale']->edit.'</a>
              	  	  <a href="'.$del_url.'" onclick="javascript:return confirm(\''.$data['locale']->really_delete.' '.output($category['name'],1).'?\');" title="'.$data['locale']->delete.' '.$category['name'].'"><img src="'.APP_URL.'/images/delete.gif" alt="'.$data['locale']->delete.'"/>&nbsp;'.$data['locale']->delete.'</a>
          		  </td>'."\n";
    		echo '</tr>'."\n";
    		$i++;
    	}
    	echo '</table>';
    } else echo $data['locale']->no_items;


    echo '<br /><h2>'.$data['locale']->posts.'</h2><br />';

    if (iterable($data['posts'])){
    	echo '<table border="1" id="subcat" class="vypis">';
  		table_th($data['post_th'],2);
  		$num_of_posts = 5;
  		$j = 0;
		$i = isset($_GET['page']) ? $_GET['page'] * $num_of_posts : 0;
		$max = min($i + $num_of_posts, count($data['posts']));
		for($i; $i < $max; $i++){
			$class = $data['posts'][$i]['active'] ? '' : 'red';
			$class .=  $j % 2 ? ' second' : '';
			$edit_url = gen_url(array('model'=>'administration','event'=>'edit','item'=>'post','id'=>$data['posts'][$i]['id_post']));
			$del_url = gen_url(array('model'=>'administration','event'=>'del','item'=>'post','id'=>$data['posts'][$i]['id_post']));
			echo "\n".'<tr'.($class ? ' class="'.$class.'"' : '').'>'."\n";
    		echo "\t".'<td><a href="'.$edit_url.'" title="'.$data['locale']->open.' '.output($data['posts'][$i]['name'],1).'" >'.$data['posts'][$i]['id_post'].'</a></td>'."\n";
    		echo "\t".'<td><a href="'.$edit_url.'" title="'.$data['locale']->open.' '.output($data['posts'][$i]['name'],1).'" >'.output($data['posts'][$i]['name']).'</a></td>'."\n";
    		echo "\t".'<td>'.output($data['posts'][$i]['date']).'</td>'."\n";
    		echo "\t".'<td>'.output($data['posts'][$i]['category'],1).'</td>'."\n";
    		echo "\t".'<td>'.output($data['posts'][$i]['user'],1).'</td>'."\n";
    		echo "\t".'<td>'.output($data['posts'][$i]['comments'],1).'</td>'."\n";
    		echo "\t".'<td>'.output($data['posts'][$i]['count'],1).'</td>'."\n";
    		echo "\t".'<td>'.output($data['posts'][$i]['karma'],1).'</td>'."\n";
			echo "\t".'<td><a href="'.$edit_url.'" title="'.$data['locale']->edit.' '.$data['posts'][$i]['name'].'" ><img src="'.APP_URL.'/images/edit.gif" alt="'.$data['locale']->edit.'"/>&nbsp;'.$data['locale']->edit.'</a>
              	  	       <a href="'.$del_url.'" onclick="javascript:return confirm(\''.$data['locale']->really_delete.' '.output($data['posts'][$i]['name'],1).'?\');" title="'.$data['locale']->delete.' '.$data['posts'][$i]['name'].'"><img src="'.APP_URL.'/images/delete.gif" alt="'.$data['locale']->delete.'"/>&nbsp;'.$data['locale']->delete.'</a>
          		  	   </td>'."\n";
    		echo '</tr>'."\n";
    		$j++;
    	}
    	echo '</table>';	
    	paging($data['posts'],$num_of_posts);
    } else echo $data['locale']->no_items;
?>
