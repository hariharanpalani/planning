<?php

class User_groupe extends GObject
{

	public function __construct()
	{
		$this->table = 'planning_user_groupe';

		$this->fields['user_groupe_id'] = new GInteger('planning_user_groupe', 'user_groupe_id', 11, FALSE, 0);
		$this->fields['nom'] = new GString('planning_user_groupe', 'nom', 150, FALSE, '');
		$this->fields['lieu_id'] = new GString('planning_user_groupe', 'lieu_id', 10, FALSE, '');

		$this->primaryKeys = array('user_groupe_id');

		parent::__construct();
	}

	public function db_save($updateData = array(), $insertData = array())
	{
		if (!$this->saved) {
			$result = db_query('SELECT MAX(' . $this->primaryKeys[0] . ') AS max FROM ' . $this->table);
			$new = 1;
			while ($var = db_fetch_array($result)) {
				$new = $var['max'] + 1;
			}
			$this->data[$this->primaryKeys[0]] = $new;
		}
		return parent::db_save($updateData, $insertData);
	}

}

?>
