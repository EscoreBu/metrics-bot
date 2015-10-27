<?php

/*
 * Copyright 2008 Wilker Lucio <wilkerlucio@gmail.com>
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License. 
 */

require_once RA_SYSTEM_HELPERS_PATH . '/StringHelper.php';

function ra_autoloader($classname)
{
	//check if class already exists
	if (class_exists($classname)) {
		return true;
	}
	//if($classname != 'Ra_Uri')
	
	//check if is a core library
	if (Ra_StringHelper::starts_with($classname, RA_SYSTEM_CLASS_PREFIX)) {
		$filename = substr($classname, strlen(RA_SYSTEM_CLASS_PREFIX));
		$path = RA_SYSTEM_LIBRARIES_PATH . '/' . $filename . '.php';
		
		if (file_exists($path)) {
			require_once $path;
			
			if (class_exists($classname)) {
				return true;
			}
		}
		
		$path = RA_SYSTEM_HELPERS_PATH . '/' . $filename . '.php';

		if (file_exists($path)) {
			require_once $path;

			if (class_exists($classname)) {
				return true;
			}
		}
	}
	
	//check if the class is a controller
	if (Ra_StringHelper::ends_with($classname, 'Controller')) {
		$base_paths = array(RA_CONTROLLERS_PATH);
		Ra_ArrayHelper::array_push($base_paths, glob(RA_SLICES_PATH . "/*/app/controllers"));

		foreach ($base_paths as $path) {
			$bits = explode('_', $classname);

			while (count($bits) > 1) {
				$path .= '/' . strtolower(array_shift($bits));
			}
			
			$path .= '/' . substr(array_shift($bits), 0, -strlen('Controller')) . '.php';
			
			if (file_exists($path)) {
				require_once $path;
				
				if (class_exists($classname)) {
					return true;
				}
			}
		}
	}
	
	//try to load user helper
	if (Ra_StringHelper::ends_with($classname, 'Helper')) {
		$path = RA_HELPERS_PATH;
		
		$bits = explode('_', $classname);
		
		while (count($bits) > 1) {
			$path .= '/' . strtolower(array_shift($bits));
		}
		
		$path .= '/' . array_shift($bits) . '.php';
		
		if (file_exists($path)) {
			require_once $path;
			
			if (class_exists($classname)) {
				return true;
			}
		}
	}
	
	//try to load model
	$base_paths = array(RA_MODELS_PATH);

	Ra_ArrayHelper::array_push($base_paths, glob(RA_SLICES_PATH . "/*/app/models"));
	
	foreach ($base_paths as $path) {
		$path = $path . '/' . $classname . '.php';
		
		if (file_exists($path)) {
			require_once $path;
			
			if (class_exists($classname)) {
				return true;
			}
		}
	}
	
	//try user library
	$path = RA_LIBRARIES_PATH . '/' . $classname . '.php';
	

	// CORRECAO PARA USAR CLASSES NO DIRETORIO library DENTRO DE app
	// PASTA EM MINUSCULO CLASSE COM O NOME DA PASTA
	if (file_exists($path)) {
		require_once $path;
		
		if (class_exists($classname)) {
			return true;
		}
	}
	else
	{
		// if(strpos($classname, "ReCaptcha") !== false) {
		// 	$temp = explode('\\', $classname);
		// 	$classname = $temp[count($temp) - 1];
		// 	$path = RA_LIBRARIES_PATH . "/recaptcha/" . $classname . ".php";
		// 	// de($path);
		// 	if(file_exists($path)) {
		// 		require_once $path;
		// 		return true;
		// 	} else {
		// 		$path = RA_LIBRARIES_PATH . "/recaptcha/requestmethod/" . $classname . ".php";
		// 		if(file_exists($path)) {
		// 			require_once $path;
		// 			return true;
		// 		}
		// 	}
		// 	// de($classname);
		// }

		$path = RA_LIBRARIES_PATH . "/" . strtolower($classname) . "/" . $classname . ".php";
		if (file_exists($path)) {
			require_once $path;
			if (class_exists($classname)) {
				return true;
			}
		}
		else
		{
			$path = RA_LIBRARIES_PATH . "/" . strtolower($classname) . "/" . strtolower($classname) . ".php";
			if (file_exists($path)) {
				require_once $path;
				if (class_exists($classname)) {
					return true;
				}
			}
			else
			{

				$path = RA_LIBRARIES_PATH . "/" . strtolower($classname) . "/" . ucfirst(strtolower($classname)) . ".php";
				if (file_exists($path)) {
					require_once $path;
					if (class_exists($classname)) {
						return true;
					}
				}
			}

		}
	}

	//CORRECAO PARA NOVA API DO FB ESSA PORRA
	if(strpos($classname, "Facebook") !== false) {
		$arr = explode("\\", $classname);
		// de($arr);
		$path = RA_LIBRARIES_PATH . "/facebook/" . $arr[1] . ".php";
		// de($path);
		if (file_exists($path)) {
			require_once $path;
			if (class_exists($classname)) {
				return true;
			}
		}
	}
	
	//sorry, i can't found the class :(
	return false;
}

//register autoloader
spl_autoload_register('ra_autoloader');