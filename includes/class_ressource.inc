<?php

class Ressource extends GObject
{

	public function __construct()
	{
		$this->table = 'planning_ressource';
		
		
		$this->fields['ressource_id'] = new GString('planning_ressource', 'ressource_id', 10, FALSE, '');
		$this->fields['nom'] = new GString('planning_ressource', 'nom', 50, FALSE, '');
		$this->fields['commentaire'] = new GString('planning_ressource', 'commentaire', 255, TRUE, NULL);
		$this->fields['exclusif'] = new GFloat('planning_ressource', 'exclusif', 4, TRUE, 0);
		$this->fields['lieu_id'] = new GString('planning_ressource', 'lieu_id', 10, TRUE, '');
		$this->fields['user_groupe_id'] = new GInteger('planning_ressource', 'user_groupe_id', 11, TRUE, 0);
		$this->primaryKeys = array('ressource_id');

		parent::__construct();
	}

	

}

?>