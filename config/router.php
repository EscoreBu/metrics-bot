<?php

class UserRouter extends Ra_Router
{
	public function setup()
	{
		//admin routes
		$this->map_admin_change_password('admin/change_password', array("controller" => "admin_personal", "action" => "changepassword"));
		$this->map_admin_login('admin/login', array("controller" => "admin_main", "action" => "login"));
		$this->map_admin_logout('admin/logout', array("controller" => "admin_main", "action" => "logout"));
		$this->map_connect('admin/self', array("controller" => "admin_user", "action" => "self"));

		$this->map_namespace('admin');
		$this->map_connect('admin', array("controller" => "admin_main"));

		// ROUTES DO SITE
		$this->map_connect('graph', array("controller" => "main", "action" => "graph"));
		$this->map_connect('rec', array("controller" => "main", "action" => "rec"));


		$this->map_connect(':controller/:action/:id');
		$this->map_connect(':controller/:action/:id.:format');
		$this->map_connect(':controller/:action');
		$this->map_connect(':controller/:action.:format');
		$this->map_connect(':controller');
		$this->map_connect('', array("controller" => "main"));
	}
	
	public function map_namespace($namespace)
	{
		$path_form = str_replace('_', '/', $namespace);
		
		$this->map_connect("$path_form/:controller/all/:page", array("namespace" => "$namespace", "action" => "all"));
		$this->map_connect("$path_form/:controller/:action/:id", array("namespace" => "$namespace"));
		$this->map_connect("$path_form/:controller/:action", array("namespace" => "$namespace"));
		$this->map_connect("$path_form/:controller", array("namespace" => "$namespace"));
	}
}