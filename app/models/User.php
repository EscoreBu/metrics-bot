<?php

class User extends ActiveRecord
{	
	public function setup()
	{
		$this->validates_presence_of('name', 'Digite um nome');
		$this->validates_presence_of('login', 'Digite um login');
		
		$this->has_many('submenus', array('through' => 'users_submenus', 'local_field' => 'user_id'));
	}
	
	public function set_password($password)
	{
		$this->write_attribute('password', md5($password));
	}
	
	public function check_password($password)
	{
		return md5($password) == $this->password;
	}
	
	public function individual_submenus()
	{
		$submenus = array();
		
		foreach ($this->submenus as $submenu) {
			$submenus[] = (int) $submenu->id;
		}

		return $submenus;
	}

	public function has_admin()
	{
		$admin = true;

		$user_submenus = $this->individual_submenus();
		$submenus = ActiveRecord::model('Submenu')->all();
		$menu_submenus = array();

		foreach ($submenus as $submenu) {
			$menu_submenus[] = (int) $submenu->id;
		}

		foreach ($menu_submenus as $submenu_id) {
			if (!in_array($submenu_id, $user_submenus)) {
				$admin = false;
			}
		}

		return $admin;
	}

	public function allow_user()
	{
		$allow = false;

		foreach ($this->submenus as $submenu) {
			if ($submenu->controller == 'user') {
				$allow = true;

				break 1;
			}
		}
		
		return $allow;
	}
}