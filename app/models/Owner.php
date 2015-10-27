<?php
class Owner extends ActiveRecord
{
	public function setup() {
		$this->has_many('ViberGroup as groups');

		$this->validates_presence_of('phone');
		$this->validates_uniqueness_of('phone');
	}
}