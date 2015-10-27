<?php

class ImageModel extends ActiveRecord
{
	public function after_destroy()
	{
		$string = $this->image;
		// de($string, null, null);
		$path = substr($string, 0, strrpos($string, "/") + 1);
		$file_full = substr($string, strrpos($string, "/") + 1);
		// de($path, null, null);
		$file = explode('*', $file_full);
		// de($file, null, null);
		$path = RA_PUBLIC_PATH . "/uploads/$path";
		// de($path);

		if(is_dir($path))
		{
			if ($handle = opendir($path))
			{
				while (false !== ($entry = readdir($handle))) {
					if(strpos($entry, $file[0]) !== false)
					{
						if(is_file($path.$entry))
							unlink($path.$entry);
					}
				}

				closedir($handle);
			}
		}
	}
}