<?php
/**
 * pjDependencyManager
 * 
 * @author Dimitar Ivanov
 * @version 0.0.1
 */
class pjDependencyManager
{
/**
 * Holds the basePath
 *
 * @var string
 */
	private $basePath = NULL;
/**
 * Holds dependencies
 * 
 * @var array
 */
	private $dependencies = array();
/**
 * Dependency patterns
 * 
 * @var array
 */
	private $patterns = array(
		'any' => '@^(\*)?$@',
		'single' => '@^(~|\^|=|>|>=|<|<=)?(\d+\.\d+\.\d+)$@',
		'range1' => '@^(>|>=|<|<=)?(\d+\.\d+\.\d+)\s+(>|>=|<|<=)?(\d+\.\d+\.\d+)$@',
		'range2' => '@^(\d+\.\d+\.\d+)\s*\-\s*(\d+\.\d+\.\d+)$@',
	);
/**
 * Holds the result of resolve
 * 
 * @var array
 */
	private $result = array();
/**
 * Constructor
 * 
 * @param string $basePath
 * @return pjDependencyManager
 */
	public function __construct($basePath=NULL)
	{
		$this->basePath = $basePath;
		
		return $this;
	}
/**
 * Gets currently loaded dependencies
 * 
 * @return array
 */
	public function getDependencies()
	{
		return $this->dependencies;
	}
/**
 * Gets path to given library
 * 
 * @param string $library
 * @throws Exception
 * @return string
 */
	public function getPath($library)
	{
		try {
			$version = $this->getVersion($library);

			return $this->basePath . $library . '/' . $version . '/';
		} catch (Exception $e) {
			throw new Exception("Path to $library not found");
		}
	}
/**
 * Gets the result from resolve
 * 
 * @return array
 */
	public function getResult()
	{
		return $this->result;
	}
/**
 * Extract version from the result by given library
 * 
 * @param string $library
 * @throws Exception
 * @return string
 */
	public function getVersion($library)
	{
		if (!isset($this->result[$library]))
		{
			throw new Exception("$library is not in dependency list");
		}
		
		return $this->result[$library];
	}
/**
 * Loads dependencies into current instance
 * 
 * @param array|string $value
 * @return pjDependencyManager
 */
	public function load($value)
	{
		if (is_array($value))
		{
			$dependencies = $value;
				
		} elseif (is_string($value) && is_file($value)) {
				
			$dependencies = include $value;
		}
	
		if (isset($dependencies) && is_array($dependencies) && !empty($dependencies))
		{
			$this->dependencies = $dependencies;
			$this->result = $dependencies;
				
			foreach ($this->result as &$item)
			{
				$item = FALSE;
			}
		}
	
		return $this;
	}
/**
 * Reset current instance
 * 
 * @return pjDependencyManager
 */
	public function reset()
	{
		$this->dependencies = array();
		$this->result = array();
		
		return $this;
	}
/**
 * Resolve dependencies
 * 
 * @return pjDependencyManager
 */
	public function resolve()
	{
		foreach ($this->dependencies as $library => $operator_version)
		{
			$library_root = $this->basePath . $library;
			if (!is_dir($library_root))
			{
				continue;
			}
				
			$found = array();
			if ($handle = opendir($library_root))
			{
				while (false !== ($entry = readdir($handle)))
				{
					if ($entry != "." && $entry != ".."
						&& is_dir($library_root . DIRECTORY_SEPARATOR . $entry)
						&& preg_match('@^\d+\.\d+\.\d+$@', $entry))
					{
						$found[] = $entry;
					}
				}
				closedir($handle);
			}

			if (empty($found))
			{
				continue;
			}

			foreach ($this->patterns as $k => $pattern)
			{
				preg_match($pattern, $operator_version, $match);
		
				if ($match && $k === 'range2')
				{
					$match = array(
						$match[0],
						'>=',
						$match[1],
						'<=',
						$match[2],
					);
				}

				if ($match)
				{
					break;
				}
			}

			if (!$match)
			{
				continue;
			}

			if ($k === 'any')
			{
				$this->result[$library] = $found[0];
				continue;
			}

			$operator1 = $match[1];
			$version1 = $match[2];

			$isRange = FALSE;
			if (count($match) === 5 && in_array($k, array('range1', 'range2')))
			{
				$isRange = TRUE;
				$operator2 = $match[3];
				$version2 = $match[4];
			}

			# Support tilde & caret
			if ($k === 'single' && in_array($operator1, array('~', '^')))
			{
				list($major, $minor, $patch) = explode('.', $version1);
				switch ($operator1)
				{
					case '~':
						$version2 = sprintf('%u.%u.0', $major, (int) $minor + 1);
						break;
					case '^':
						$version2 = sprintf('%u.0.0', (int) $major + 1);
						break;
				}

				$isRange = TRUE;
				$operator1 = '>=';
				$operator2 = '<';
			}
				
			// Fix for version_compare
			if (empty($operator1))
			{
				$operator1 = '=';
			}
				
			if ($isRange)
			{
				foreach ($found as $item)
				{
					if (version_compare($item, $version1, $operator1) && version_compare($item, $version2, $operator2))
					{
						$this->result[$library] = $item;
						break;
					}
				}
			} else {
				switch ($operator1)
				{
					case '>':
					case '>=':
					case '<':
					case '<=':
					case '=':
						foreach ($found as $item)
						{
							if (version_compare($item, $version1, $operator1))
							{
								$this->result[$library] = $item;
								break;
							}
						}
						break;
				}
			}
		}
		
		return $this;
	}
/**
 * Sets the 'basePath' configuration option
 * 
 * @param string $value The new value for the 'basePath'
 * @return pjDependencyManager
 */
	public function setBasePath($value)
	{
		$this->basePath = (string) $value;
	
		return $this;
	}
}
?>