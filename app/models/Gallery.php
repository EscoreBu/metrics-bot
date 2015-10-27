<?php

class Gallery extends ActiveRecord
{
	public function setup()
	{
		$this->has_many('Object as objects');
	}
}
