<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminReports extends pjAdmin
{
	public function pjActionIndex()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor())
		{
			$pjBookingModel = pjBookingModel::factory();
			$pjBookingExtraModel = pjBookingExtraModel::factory();
			
			if (isset($_GET['date_from']) && !empty($_GET['date_from']))
			{
				$date_from = pjUtil::formatDate($_GET['date_from'], $this->option_arr['o_date_format']);
			}
			if (isset($_GET['date_to']) && !empty($_GET['date_to']))
			{
				$date_to = pjUtil::formatDate($_GET['date_to'], $this->option_arr['o_date_format']);
			}
			$dates = NULL;
			if (isset($date_from) && isset($date_to))
			{
				$dates = sprintf(" AND (
							('%1\$s' <= DATE(`from`) AND '%2\$s' >= DATE(`to`)) OR
							('%1\$s' >= DATE(`from`) AND '%2\$s' <= DATE(`to`)) OR
							('%1\$s' BETWEEN DATE(`from`) AND DATE(`to`)) OR
							('%2\$s' BETWEEN DATE(`from`) AND DATE(`to`))
					)", $date_from, $date_to);
			} else {
				if (isset($date_from))
				{
					$dates = sprintf(" AND DATE(`from`) >= '%s'", $date_from);
				}
				if (isset($date_to))
				{
					$dates = sprintf(" AND DATE(`to`) <= '%s'", $date_to);
				}
			}
			
			$cnt_confirmed = $pjBookingModel
				->where("t1.status = 'confirmed'" . $dates)
				->findCount()
				->getData();
			$cnt_cancelled = $pjBookingModel
				->reset()
				->where("t1.status = 'cancelled'" . $dates)
				->findCount()
				->getData();
			$cnt_pending = $pjBookingModel
				->reset()
				->where("t1.status = 'pending'" . $dates)
				->findCount()
				->getData();
			$confirmed_arr = $pjBookingModel
				->reset()
				->select("SUM(total) AS amount")
				->where("t1.status = 'confirmed'" . $dates)
				->limit(1)
				->findAll()
				->getData();
			$total_confirmed = !empty($confirmed_arr) ? $confirmed_arr[0]['amount'] : 0;
			$cancelled_arr = $pjBookingModel
				->reset()
				->select("SUM(total) AS amount")
				->where("t1.status = 'cancelled'" . $dates)
				->limit(1)
				->findAll()
				->getData();
			$total_cancelled = !empty($cancelled_arr) ? $cancelled_arr[0]['amount'] : 0;
			$pending_arr = $pjBookingModel
				->reset()
				->select("SUM(total) AS amount")
				->where("t1.status = 'pending'" . $dates)
				->limit(1)
				->findAll()
				->getData();
			$total_pending = !empty($pending_arr) ? $pending_arr[0]['amount'] : 0;
			
			$space_arr = pjSpaceModel::factory()
				->select("t1.*, t2.content AS name, 
						(SELECT COUNT(*) FROM `".$pjBookingModel->getTable()."` AS TB WHERE TB.space_id=t1.id AND TB.status='confirmed'".$dates.") AS cnt_confirmed,
						(SELECT COUNT(*) FROM `".$pjBookingModel->getTable()."` AS TB WHERE TB.space_id=t1.id AND TB.status='cancelled'".$dates.") AS cnt_cancelled,
						(SELECT COUNT(*) FROM `".$pjBookingModel->getTable()."` AS TB WHERE TB.space_id=t1.id AND TB.status='pending'".$dates.") AS cnt_pending,
						(SELECT SUM(total) FROM `".$pjBookingModel->getTable()."` AS TB WHERE TB.space_id=t1.id AND TB.status='confirmed'".$dates.") AS total_confirmed,
						(SELECT SUM(total) FROM `".$pjBookingModel->getTable()."` AS TB WHERE TB.space_id=t1.id AND TB.status='cancelled'".$dates.") AS total_cancelled,
						(SELECT SUM(total) FROM `".$pjBookingModel->getTable()."` AS TB WHERE TB.space_id=t1.id AND TB.status='pending'".$dates.") AS total_pending")
				->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjSpace' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
				->orderBy("name ASC")
				->findAll()
				->getData();
			
			$extra_arr = pjExtraModel::factory()
				->select("t1.*, t2.content AS name,
						(SELECT SUM(cnt) FROM `".$pjBookingExtraModel->getTable()."` AS TBE WHERE TBE.extra_id=t1.id AND TBE.booking_id IN (SELECT TB.id FROM `".$pjBookingModel->getTable()."` AS TB WHERE TB.status='confirmed'".$dates.")) AS cnt_confirmed,
						(SELECT SUM(cnt) FROM `".$pjBookingExtraModel->getTable()."` AS TBE WHERE TBE.extra_id=t1.id AND TBE.booking_id IN (SELECT TB.id FROM `".$pjBookingModel->getTable()."` AS TB WHERE TB.status='cancelled'".$dates.")) AS cnt_cancelled,
						(SELECT SUM(cnt) FROM `".$pjBookingExtraModel->getTable()."` AS TBE WHERE TBE.extra_id=t1.id AND TBE.booking_id IN (SELECT TB.id FROM `".$pjBookingModel->getTable()."` AS TB WHERE TB.status='pending'".$dates.")) AS cnt_pending,
						(SELECT SUM(price) FROM `".$pjBookingExtraModel->getTable()."` AS TBE WHERE TBE.extra_id=t1.id AND TBE.booking_id IN (SELECT TB.id FROM `".$pjBookingModel->getTable()."` AS TB WHERE TB.status='confirmed'".$dates.")) AS total_confirmed,
						(SELECT SUM(price) FROM `".$pjBookingExtraModel->getTable()."` AS TBE WHERE TBE.extra_id=t1.id AND TBE.booking_id IN (SELECT TB.id FROM `".$pjBookingModel->getTable()."` AS TB WHERE TB.status='cancelled'".$dates.")) AS total_cancelled,
						(SELECT SUM(price) FROM `".$pjBookingExtraModel->getTable()."` AS TBE WHERE TBE.extra_id=t1.id AND TBE.booking_id IN (SELECT TB.id FROM `".$pjBookingModel->getTable()."` AS TB WHERE TB.status='pending'".$dates.")) AS total_pending")
				->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjExtra' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
				->orderBy("name ASC")
				->findAll()
				->getData();
			
			$this->set('cnt_confirmed', $cnt_confirmed);
			$this->set('cnt_cancelled', $cnt_cancelled);
			$this->set('cnt_pending', $cnt_pending);
			$this->set('total_confirmed', $total_confirmed);
			$this->set('total_cancelled', $total_cancelled);
			$this->set('total_pending', $total_pending);
			$this->set('space_arr', $space_arr);
			$this->set('extra_arr', $extra_arr);
			
			$this->appendJs('pjAdminReports.js');
		} else {
			$this->set('status', 2);
		}
	}
}
?>