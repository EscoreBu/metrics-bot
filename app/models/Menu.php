<?php

class Menu extends ActiveRecord
{
	public function setup()
	{
		$this->validates_presence_of('name');

		$this->belongs_to('Glyphicon as glyphicon');
		$this->has_many('Submenu as submenus');
	}
}
