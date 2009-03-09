<?php

/**
* Core_Helper_Administration
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Core_Helper_Administration
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Core_Helper_Administration extends Core_Helper
{
	public $helpers = array('Form', 'Html');

	public function submenu($items, $selected = FALSE)
	{
		echo ENDL . '<ul id="submenu">' . ENDL;
		foreach ($items as $name => $item) {
			$class = $selected == $name ? ' class="selected"' : '';
			echo '<li><a href="'.$item['link'].'"' . $class . '>' . __($item['label']) . '</a></li>' . ENDL;
		}
		echo '<li class="cleaner"></li>' . ENDL;
 		echo '</ul>' . ENDL;
	}

	public function actions($items)
	{
		if (!iterable($items)) return;
		$r = Core_Request::factory();
		
		$id = $r->getGet('id');
		$table = $r->getGet('subevent');
		if ($id && $table == 'post') {
			$url = Core_Router::factory()->genUrl($table, '__default', $table, array('post' => $id));
			$items['show'] = $url;
		}

		echo ENDL . '<div id="actions">' . ENDL;
		$i = 0;
		foreach ($items as $name => $url) {
			if ($i) echo ' | ';

			if ($r->getGet('action') == $name) $selected = 'class="selected" ';
			else $selected = '';

			echo '<a ' . $selected . 'href="' . $url . '">' . __($name) . '</a>';

			$i++;
		}
 		echo ENDL . '</div>' . ENDL;
	}

	public function dump($table, $return = FALSE, $just_content = FALSE)
	{
		$class = 'Table_' . ucfirst($table);
		$model = new $class;
		$r = Core_Router::factory();
		$request = Core_Request::factory();

		$max = Core_Config::singleton()->administration->items_per_page;
		$limit = ($request->getPost('page') * $max) . ',' . $max;

		$rows = $model->getList($request->getPost('order'),
		                        $request->getPost('a'),
		                        $limit,
		                        $request->getPost('name'));
		$output = '';

		if (!$just_content) {
			$output .= '<h2>' . __($table) . '</h2>';
			$order = $request->getPost('order');
			$a     = $request->getPost('a');

			if (iterable($rows)) {
				$output .= ENDL . '<table border="1" class="dump">' . ENDL;
				$output .= '<thead><tr>';

				foreach ($rows[0] as $th => $v) {
					$class = '';
					if (is_numeric($th)) continue;
					if ($th == 'active') continue;
					if ($th == $order) {
						$class = 'class="'.$a.'" ';
					}
					$output .= '<th>';
					$output .= '<a href="#" ' . $class . 'title="' . $th . '">' . __($th) . '</a></th>';
				}

				$output .= '<th>' . __('actions') . '</th></tr></thead>';

				$output .= '<tbody>';
			}
		}
		if (iterable($rows)) {
			$i = 0;
			foreach ($rows as $row) {
				$edit_url      = $r->forward(array('id'=>$row[0], 'action'=>'edit'));
				$del_url       = $r->forward(array('id'=>$row[0], 'action'=>'del'), FALSE, TRUE);
				$activate_url  = $r->forward(array('id'=>$row[0], 'action'=>'activate'), FALSE, TRUE);
				$rowname       = isset($row['name']) ? clearOutput($row['name']) : '';

				$output .= '<tr';
				$class = FALSE;

				//active
				if (isset($row['active']) &&
				   (!$row['active'])
				    ) {
					$class = 'pink';
					$msg = 'activate';
				} else {
					$msg = 'deactivate';
				}

				//date
				if (isset($row['date'])) {
					$text_obj = new Core_Text();
					$date = $text_obj->dateToTimeStamp($row['date']);
					if ($date > time() || !$date ) { //future post
						$class = $class ? $class . ' green' : 'green';
					}
				}

				$output .= $class ? ' class="' . $class . '"' : '';
				$output .= '>' . ENDL;

				$j = 0;
				foreach ($row as $name=>$value) {
					if (is_numeric($name)) continue;
					if ($name == 'active') continue;

					$value = clearOutput($value);

					if (mb_strlen($value) > 30) {
						$value = mb_substr($value, 0, 31) . '<dfn title="' . $value . '">&hellip;</dfn>';
					}

					if (!$j) {
						$output .= TAB . '<td>' . ENDL;
						$output .= TAB . TAB . '<input type="checkbox" name="ids" value="' . $value . '" />' . ENDL;
						$output .= TAB . TAB . '<a href="' . $edit_url . '">' . $value . '</a>' . ENDL;
						$output .= TAB . '</td>' . ENDL;
					} else $output .= TAB . '<td>' . $value . '</td>' . ENDL;
					$j++;
				}

				$output .= TAB . '<td>' . ENDL;
				$output .= TAB . TAB . '<a href="' . $edit_url . '" title="' . __('edit') . ' ' . $rowname . '" ><img src="' . STYLE_URL . '/images/edit.gif" alt="' . __('edit') . '"/>&nbsp;' . __('edit') . '</a>' . ENDL;
				if (isset($row['active']))
					$output .= TAB . TAB . '<a href="' . $activate_url . '" title="' . __($msg) . ' ' . $rowname . '" ><img src="' . STYLE_URL . '/images/' . $msg . '.gif" alt="' . __($msg) . '"/>&nbsp;' . __($msg) . '</a>' . ENDL;
				$output .= TAB . TAB . '<a href="' . $del_url . '" onclick="javascript:return confirm(\'' . __('really_delete') . ' ' . clearOutput($rowname,1) . '?\');" title="' . __('delete') . ' ' . $rowname . '">' . ENDL;
				$output .= TAB . TAB . TAB . '<img src="' . STYLE_URL . '/images/delete.gif" alt="' . __('delete') . '"/>&nbsp;' . __('delete') . ENDL;
				$output .= TAB . TAB . '</a>' . ENDL;
				$output .= TAB . '</td>' . ENDL;
				$output .= '</tr>' . ENDL;
				$i++;
			}
			if (!$just_content) {
				$output .= '</tbody>';
				$output .= '</table>' . ENDL;
			}
		} else $output .= __('no_items');
		if ($return) return $output;
		echo $output;
	}

	public function form($action, $table, $id = 0, $return = FALSE)
	{
		$class = 'ActiveRecord_' . ucfirst($table);
		$model = new $class($id);
		$r     = Core_Request::factory();
		
		$f = $this->form->form(NULL, $action, __($table), __('save'), array('enctype' => 'multipart/form-data'));
		if ($id) $this->form->input(FALSE, 'id', FALSE, 'hidden', $id);

		//XSRF protection
		if ($r->getServer('REMOTE_ADDR') == 'unit') $key = 1; //unit tests
		else $key = rand(0, 100);

		$hash = md5($r->getSession('password') . $key . $r->sessionID());
		$this->form->input(FALSE, 'random_key', FALSE, 'hidden', $key);
		$this->form->input(FALSE, 'hashed_key', FALSE, 'hidden', $hash);

		$next_file_c = 1;
		foreach ($model->fields as $name=>$field) {
			if ($field['visibility'] == 0) continue;
			$type       = $field['type'];
			$type_class = 'Core_Type_' . ucfirst($type);
			$type_obj   = new $type_class;

			if ($model->$name) $value = $model->$name;
			else $value = $type_obj->getDefaultValue();

			switch ($type) {
				case 'datetime':
				case 'date':
				case 'int':
				case 'string':
				case 'url':
					$i = $this->form->input(FALSE, $name, __($name), 'text', $value);
					break;
				case 'bool':
					$i = $this->form->input(FALSE, $name, __($name), 'checkbox', 1);
					if ($value) $i->setParam('checked', 'checked');
					break;
				case 'password':
					$i = $this->form->input(FALSE, $name, __($name), 'password', '');
					break;
				case 'html':
					$i = $this->form->textarea(FALSE, $name, __($name), htmlspecialchars($model->$name));
					break;
				case 'file':
					$params = array('class' => 'file_upload', 'id' => $name . '_div');
					$i = $this->form->input(FALSE, $name, __($name), 'file');
					$title_name = str_replace('[]', '', $name) . '_title[]';
					$i2 = $this->html->input($i->getParent(), $title_name, array('style'=>'width: 70px;'));
					if (substr($name, -2) == '[]') {
						$this->html->button($i->getParent(),
						                    'next_file' . $next_file_c,
						                    'next_file',
						                    array('class' => 'next_file'));
						$next_file_c++;
					}
					$div = $this->html->div($this->form->root, $params);
					$component = new Component_FileManager(NULL, NULL);
					$content = $component->render(array('name' => $name, 'model' => $model));
					$div->setContent($content);
					break;
				case 'popup':
					if ($model->$name) $value = $model->$name;
					$i = $this->form->input(FALSE, $name, __($name), 'text', $value);
					$div = $i->getParent();
					$div->setContent(
						'<input alt="#TB_inline?height=500&width=200&inlineId=popup" title="'.__('services').'" class="thickbox" type="button" value="'.__('select').'" />'
					);

					$content =  '<div id="popup"><p>';

					$instance = new ActiveRecord_NabizeneSluzby();
					$sluzby = $instance->getAll('legenda', 'asc');
					foreach ($sluzby as $sluzba) {
						$input_id = 'service' . $sluzba['sluzba_sluzby_id'];
						$content .= '<input id="'.$input_id.'" name="'.$input_id.'" type="checkbox" value="'.$sluzba['legenda'].'" />';
						$content .= '<label for="'.$input_id.'">'.$sluzba['nazev_sluzby_sluzby_cz'].'</label><br />';
					}

					$content .= '<input id="select_services" type="button" value="OK" />';

					$content .= '</p></div>';

					$div->setContent($content);

				case 'table':
					$tbl = preg_replace('/^id_/', '', $name);

					switch ($tbl) {
						default:
							$id = 'id_' . $tbl;
							$rowname = 'name';
					}

					$i = $this->form->select(FALSE, $name, __($name));

					$class = 'Table_' . ucfirst($tbl);
					$model2 = new $class;

					$items = $model2->getAll($rowname, 'asc');

					$options = '<option value=""></option>';

					if (strpos($name, '[]')) {

						$i->setParam('multiple', 'multiple');
						$i->setParam('style', 'height: 100px;');

						if (is_array($model->$name)) {
							$arr = $model->$name;
						} else {
							$arr = explode('|', $model->$name);
						}

						if (iterable($items)) {
							foreach ($items as $item) {
								if (in_array($item[$id], $arr)) $selected = ' selected ';
								else $selected = '';
								$options .= '<option ' . $selected . 'value="' . $item[$id] . '">';
								$options .= $item[$rowname] . '</option>';
							}
						}

					} else {

						if (iterable($items)) {
							foreach ($items as $item) {
								if ($item->getID() == $model->$name) $selected = ' selected ';
								else $selected = '';
								$options .= '<option ' . $selected . 'value="' . $item->getID() . '">';
								$options .= $item->$rowname . '</option>';
							}
						}

					}
					$i->setContent($options);
					break;
			}

			//all inputs
			if (isset($field['required']) && $field['required']) {
				$i->setValidation();
			}

		}
		$this->form->input(FALSE, 'table', FALSE, 'hidden', $table);
		$this->form->input(FALSE, 'send_continue', FALSE, 'submit')
			->setParam('value', __('save_and_continue'))
			->setParam('class', 'submit');
		$output = $this->form->render(0, TRUE);

		if ($return) return $output;
		echo $output;
	}

	/**
	 * Generates tree
	 *
	 * @param string $table Name of table
	 * @param string $parent Name of column which acts like parent pointer
	 */
	public function tree($table, $parent)
	{
		$class = 'Table_' . ucfirst($table);
		$instance = new $class;
		$all = $instance->getAll('name', 'asc');

		$this->renderChilds(0, $all, $parent);
	}

	protected function renderChilds($id, $all, $parent)
	{
		echo '<ul>';
		foreach ($all as $key => $value) {
			if ($value->$parent == $id) {
				echo '<li>' . $value->name . '</li>';
				unset($all[$key]);
				$this->renderChilds($value->getID(), $all, $parent);
			}
		}
		echo '</ul>';
	}
}
