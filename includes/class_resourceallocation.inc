<?php

class ResourceAllocation extends GObject
{
    public function __construct()
    {
        $this->table = 'planning_resource_allocation';

        $this->fields['allocation_id'] = new GInteger('planning_resource_allocation', 'allocation_id', 11, FALSE, 0);
		$this->fields['resource_id'] = new GString('planning_resource_allocation', 'resource_id', 30, FALSE, '');
		$this->fields['user_id'] = new GString('planning_resource_allocation', 'user_id', 20, FALSE, '');
        $this->fields['allocated_date'] = new GDate('planning_resource_allocation', 'allocated_date', 19, FALSE, NULL);
        $this->fields['created_by'] = new GString('planning_resource_allocation', 'created_by', 20, TRUE, NULL);
		$this->fields['created_date'] = new GDatetime('planning_resource_allocation', 'created_date', 19, TRUE, NULL);
		$this->fields['modified_by'] = new GString('planning_resource_allocation', 'modified_by', 20, TRUE, NULL);
		$this->fields['modified_date'] = new GDatetime('planning_resource_allocation', 'modified_date', 19, TRUE, NULL);

		$this->primaryKeys = array('allocation_id');

		parent::__construct();
    }
}

?>