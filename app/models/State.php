<?php
class State extends ActiveRecord
{
	public function seput()
	{
		$this->validates_presence_of('name');
		$this->validates_presence_of('uf');
	}

	public function get_site_users_count()
	{
		return ActiveRecord::model('SiteUser')->count(array('conditions' => 'state_id = ' . $this->id));
	}
}