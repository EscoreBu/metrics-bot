<?php

class Submenu extends ActiveRecord
{
	public function setup()
	{
		$this->validates_presence_of('name');
		
		$this->belongs_to('Menu as menu');
		$this->has_many('users', array('through' => 'users_submenus', 'local_field' => 'submenu_id'));
	}

	public function get_menu_name()
	{
		return $this->menu->name;
	}
}
