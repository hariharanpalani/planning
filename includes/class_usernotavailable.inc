<?php

class UserNotavailable extends GObject
{
    public function __construct()
    {
        $this->table = 'planning_user_unavailability';

        $this->fields['id'] = new GInteger('planning_user_unavailability', 'allocation_id', 11, FALSE, 0);
		$this->fields['user_id'] = new GString('planning_user_unavailability', 'user_id', 20, FALSE, '');
        $this->fields['type_id'] = new GInteger('planning_user_unavailability', 'type_id', 11, FALSE, 0);
        $this->fields['start_date'] = new GDate('planning_user_unavailability', 'start_date', 19, FALSE, NULL);
        $this->fields['end_date'] = new GDate('planning_user_unavailability', 'end_date', 19, FALSE, NULL);
        $this->fields['created_by'] = new GString('planning_user_unavailability', 'created_by', 20, TRUE, NULL);
		$this->fields['created_date'] = new GDatetime('planning_user_unavailability', 'created_date', 19, TRUE, NULL);
		$this->fields['modified_by'] = new GString('planning_user_unavailability', 'modified_by', 20, TRUE, NULL);
		$this->fields['modified_date'] = new GDatetime('planning_user_unavailability', 'modified_date', 19, TRUE, NULL);

		$this->primaryKeys = array('id');

		parent::__construct();
    }
}

?>