<?php
namespace App\Helpers;

use Core\Lib\Controller;

class Paginate extends Controller
{
	public $perPage, $currentPage, $total , $maxPage , $items , $skip;

	/**
	 * Paginate constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->setCurrentPage((isset($_GET['page']))?$_GET['page']:1);
	}

	/**
	 * @return mixed
	 */
	public function getItems()
	{
		return $this->items;
	}

	/**
	 * @return mixed
	 */
	public function getSkip()
	{
		return $this->skip;
	}

	/**
	 * @return mixed
	 */
	public function getCurrentPage()
	{
		return $this->currentPage;
	}

	/**
	 * @return mixed
	 */
	public function getMaxPages()
	{
		return $this->maxPage;
	}

	/**
	 * @return mixed
	 */
	public function getPerPage()
	{
		return $this->perPage;
	}

	/**
	 * @return mixed
	 */
	public function getTotal()
	{
		return $this->total;
	}

	/**
	 * @param $skip
	 */
	public function setSkip($skip)
	{
		$this->skip = $skip;
	}

	/**
	 * @param $items
	 */
	public function setItems($items)
	{
		$count 			= ceil($items->count()/$this->getPerPage());
		$maxPages 		= (!empty($this->getMaxPages()) && $count >$this->getMaxPages())?$this->getMaxPages():$count;
		$this->setTotal($maxPages);
		$this->setSkip(($this->getCurrentPage()*$this->getPerPage())-$this->getPerPage());
		$this->items 	= $items->skip($this->getSkip())->limit($this->getPerPage())->get();
	}

	/**
	 * @param $currentPage
	 */
	public function setCurrentPage($currentPage)
	{
		$this->currentPage = $currentPage;
	}

	/**
	 * @param $maxPage
	 */
	public function setMaxPages($maxPage)
	{
		$this->maxPage = $maxPage;
	}

	/**
	 * @param $perPage
	 */
	public function setPerPage($perPage)
	{
		$this->perPage = $perPage;
	}

	/**
	 * @param $total
	 */
	public function setTotal($total)
	{
		$this->total = $total;
	}


	public function paginate(){
		if($this->getTotal() != 1) {
			echo /** @lang text */ '<ul class="pagination">';
			if($this->getCurrentPage() > 1){
				echo /** @lang text */ '<li><a href="?page='.($this->getCurrentPage()-1).'" aria-label="Previous"><span aria-hidden="true">Anterior</span></a></li>';
			}else{
				echo /** @lang text */ '<li class="disabled"><a aria-label="Previous"><span aria-hidden="true">Anterior</span></a></li>';
			}

			for ($i = 1; $i <= $this->getTotal(); $i++) {

				$active = ($this->getCurrentPage() == $i) ? 'class="active"' : '';

				echo '<li ' . $active . '><a href="?page=' . $i . '">' . $i . '</a></li>';
			}
			if($this->getCurrentPage() < $this->getTotal()){
				echo /** @lang text */ '<li><a href="?page='.($this->getCurrentPage()+1).'" aria-label="Next"><span aria-hidden="true">Próxima</span></a></li>';
			}else{
				echo /** @lang text */ '<li class="disabled"><a aria-label="Next"><span aria-hidden="true">Próxima</span></a></li>';
			}
			echo '</ul>';
		}
	}
}