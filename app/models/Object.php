<?php

class Object extends ActiveRecord
{
	public function setup()
	{
		$this->belongs_to('Gallery as gallery');
		$this->belongs_to('ObjectType as type');
		
		$this->has_many('content', array('through' => 'content_objects', 'local_field' => 'object_id'));
	}
}
