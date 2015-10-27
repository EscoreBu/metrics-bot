<?php

class ApplicationController extends Ra_Controller
{

	private $_active_user;


	public function initialize()
	{
		if (isset($_SERVER['PATH_INFO']) || isset($_SERVER['ORIG_PATH_INFO'])) {
			$this->_server_url = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['ORIG_PATH_INFO'];
		}

		$this->_layout = 'main';
	}





	public function date_format($date, $format_in, $format_out)
	{
		if($date)
		{
			$date_ = DateTime::createFromFormat($format_in, $date);
			return $date_->format($format_out);//date($format_out, $dateT->getTimestamp());
		}
		return false;
	}




	/**
	// CONFIGURA A PAGINACAO PARA O BLOG
	*/
	protected function configure_pagination($pagination)
	{
		$pagination->set_config(array(
			'num_links'       => 10,
			'base_url'		  => '',
		
			'full_tag_open'   => '<div class="navigation">',
			'full_tag_close'  => '</div>',
			
			'first_link'      => '',
			'first_class'      => '',
			'first_tag_open'  => '',
			'first_tag_close' => '',
			
			'last_link'       => '',
			'last_class'       => '',
			'last_tag_open'   => '',
			'last_tag_close'  => '',
			
			'next_link'       => ' ',
			'next_class'       => 'next',
			'next_tag_open'   => '',
			'next_tag_close'  => '',
			
			'prev_link'       => ' ',
			'prev_class'       => 'prev',
			'prev_tag_open'   => '',
			'prev_tag_close'  => '',
			
			'first_inactive_link'       => '',
			'first_inactive_class'       => '',
			'first_inactive_tag_open'   => '',
			'first_inactive_tag_close'  => '',
			
			'last_inactive_link'       => '',
			'last_inactive_class'       => '',
			'last_inactive_tag_open'   => '',
			'last_inactive_tag_close'  => '',
			
			'next_inactive_link'       => ' ',
			'next_inactive_class'       => ' ',
			'next_inactive_tag_open'   => '<a title="Próxima página" class="next disabled">',
			'next_inactive_tag_close'  => '</a>',
			
			'prev_inactive_link'       => ' ',
			'prev_inactive_class'       => '',
			'prev_inactive_tag_open'   => '<a title="Página anterior" class="prev disabled">',
			'prev_inactive_tag_close'  => '</a>',
			
			'cur_tag_open'   => '<a class="current">',
			'cur_tag_close'  => '</a>',
			
			'num_tag_open'   => '',
			'num_tag_close'  => ''
		));
	}



	public function set_messenge($text = null, $type = 'error')
	{
		if($text == null)
			unset($_SESSION['siteMessage']);
		else
		{
			$_SESSION['siteMessage']['message'] = $text;
			$_SESSION['siteMessage']['type'] = $type;
		}
	}
	
	
	public function get_messenge()
	{
		return $_SESSION['siteMessage'];
	}
	
	
	public function get_messenge_text()
	{
		return $_SESSION['siteMessage']['message'];
	}
	
	
	public function get_messenge_type()
	{
		return $_SESSION['siteMessage']['type'];
	}
	
}
