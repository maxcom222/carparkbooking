<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
/**
 * PHP Framework
 *
 * @copyright Copyright 2016, StivaSoft, Ltd. (https://www.stivasoft.com)
 * @link      https://www.phpjabbers.com/
 * @package   framework.components
 * @version   1.5.2
 */
/**
 * Paginator component
 *
 * @package framework.components
 *
 */
class pjPaginator
{
/**
 * Prints paginator
 *
 * @param boolean $isAjax
 * @param int $pages
 * @static
 * @access public
 * @return void
 */
	public static function display($isAjax, $pages)
	{
		?>
		<ul class="paginator">
		<?php
		if ($isAjax)
		{
			$url = parse_url($_SERVER['HTTP_REFERER']);
			$query_string = $url['query'];
		} else {
			$query_string = $_SERVER['QUERY_STRING'];
		}
	
		$sort = NULL; //'col_name='. (isset($_GET['col_name']) && !empty($_GET['col_name']) ? $_GET['col_name'] : 'listing_title'). '&amp;direction='. (isset($_GET['direction']) && in_array($_GET['direction'], array('asc', 'desc')) ? $_GET['direction'] : 'asc') . '&amp;';
		
		if (preg_match('/page=\d+/', $query_string))
		{
			$query_string = preg_replace('/page=\d+/', $sort . 'page=%u', $query_string);
		} else {
			$query_string .= '&'.$sort.'page=%u';
		}
		for ($i = 1; $i <= $pages; $i++)
		{
			if ((isset($_GET['page']) && (int) $_GET['page'] == $i) || (!isset($_GET['page']) && $i == 1))
			{
				?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?<?php printf(htmlentities($query_string), $i); ?>" class="focus"><?php echo $i; ?></a></li><?php
			} else {
				?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?<?php printf(htmlentities($query_string), $i); ?>"><?php echo $i; ?></a></li><?php
			}
		}
		?>
		</ul>
		<?php
	}
/**
 * Get query string
 *
 * @param int $page
 * @param boolean $isAjax
 * @static
 * @access public
 * @return string
 */
	public static function getQueryString($page, $isAjax)
	{
		if ($isAjax)
		{
			$url = parse_url($_SERVER['HTTP_REFERER']);
			$query_string = $url['query'];
		} else {
			$query_string = $_SERVER['QUERY_STRING'];
		}
		$query_string = preg_replace('/controller=(\w+)&(amp;)?action=(\w+)/', '', $query_string);
		$sort = NULL;
		if (preg_match('/page=\d+/', $query_string))
		{
			$query_string = preg_replace('/page=\d+/', $sort . 'page=%u', $query_string);
		} else {
			$query_string .= '&' . $sort . 'page=%u';
		}
		return sprintf($query_string, $page);
	}
/**
 * Build pagination numbers
 *
 * @param int $records Total records
 * @param int $per_page Per page records
 * @param int $current Current page
 * @param int $delta Number of pages to show in the middle
 * @param int $first_last Number of pages to show at the bigining and at the end
 * @static
 * @access public
 * @return array
 */
	public static function numbers($records, $per_page, $current, $delta, $first_last)
	{
		$total = ceil($records / $per_page);
		
		$current = ($current > $total) ? $total : $current;
		$current = ($current < 1) ? 1 : $current;
	
		for ($i=1; $i <= $total; $i++)
		{
			if (($i == $first_last+1 && $current > $first_last+$delta+1) || ($i == $total-$first_last and $current < $total-$first_last-$delta))
			{
				$pages[] = "...";
			}
			if ($i <= $first_last || $i > $total-$first_last)
			{
				$pages[] = $i;
			} elseif ($i >= $current-$delta and $i <= $current+$delta) {
				$pages[] = $i;
			}
	
		}
		
		return $pages;
	}
/**
 * Render pagination
 *
 * @param int $pages Pages array. Get it from pjPaginator::numbers
 * @param int $current Current page
 * @param string $url Links location URL
 * @param array $urlParams Params that are need to be passed in the URL
 * @param string $paramName
 * @static
 * @access public
 * @return string
 */
	public static function render($pages, $current, $url, $urlParams = array(), $paramName='page')
	{
		$pagination = '';
		$params = array();
		
		foreach ($urlParams as $key => $val)
		{
			if (!in_array($key, array($paramName)))
			{
				$params[] = $key . '=' . $val;
			}
		}
		
		$sep1 = strpos($url, '?') === false ? '?' : '&amp;';
		$sep2 = count($params) > 0 ? '&amp;' : NULL;
		$params = join('&amp;', $params);
		
		if ($pages)
		{
			$page = isset($urlParams[$paramName]) && (int) $urlParams[$paramName] > 0 ? intval($urlParams[$paramName]) : 1;
			$totalPages = count($pages);
			if ($totalPages > 1 && $page > 1)
			{
				$pagination .= '<li><a href="'.$url.$sep1.$params.$sep2.$paramName.'='.($page-1).'" class="focus"><abbr></abbr>'. __('front_index_prev_page', true).'</a></li>';
			}
			foreach ($pages as $value)
			{
				if ($value == $current)
				{
					$pagination .= '<li><span class="current"><abbr></abbr>'.$value.'</span></li>';
				} elseif ($value>0) {
					$pagination .= '<li><a href="'.$url.$sep1.$params.$sep2.$paramName.'='.$value.'" class="focus" title="Go to page '.$value.'"><abbr></abbr>'.$value.'</a></li>';
				} else {
					$pagination .= '<li><span class="dots"><abbr></abbr>'.$value.'</span></li>';
				}
			}
			if ($totalPages > 1 && $page < $totalPages)
			{
				$pagination .= '<li><a href="'.$url.$sep1.$params.$sep2.$paramName.'='.($page+1).'" class="focus"><abbr></abbr>'. __('front_index_next_page', true).'</a></li>';
			}
		}
		
		return $pagination;
	}
}
?>