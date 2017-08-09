<?php

use Nette\Application\UI\Control;
use Nette\Application\UI\BadSignalException;

class TestFifteenControl extends Control
{

	/** @var int  */
	public $width = 4;

	/** @var  callable[] function($sender) */
	public $onAfterClick;

	/** @persistent array  */
	public $order = [];

	/** @var callable[] function($sender, $round) */
	public $onGameOver;

	/** @persistent int  */
	public $round = 0;

	public $text = '';

	public function __construct()
	{

		parent::__construct();
		$this->order = range(0, $this->width * $this->width - 1);

	}

	public function getRound()
	{

		return $this->round;

	}

	public function render()
	{

		$this->template->width = $this->width;
		$this->template->order = $this->order;
		$this->template->text = $this->text;
		$this->template->render(__DIR__ . '/TestFifteenControl.latte');

	}

	public function handleClick($x, $y)
	{

		if (!$this->isClickable($x, $y)) {
			throw new BadSignalException('Action not alloewd!');
		}

		$this->move($x, $y);
		$this->round++;
		$this->onAfterClick($this);

		if ($this->order == range(0, $this->width * $this->width - 1)) {
			$this->onGameOver($this, $this->round);
		}

	}

	public function isClickable($x, $y, &$rel = null)
	{

		$rel = null;
		$pos = $x + $y * $this->width;
		$empty = $this->searchEmpty();
		$y = (int) ($empty / $this->width);
		$x = $empty % $this->width;

		if ($x > 0 && ($pos == $empty -1)) {
			$rel = ',-1';
			return true;
		}

		if (($x < $this->width - 1) && ($pos === $empty + 1)) {
			$rel = ',+1';
			return true;
		}

		if ($y > 0 && ($pos === $empty - $this->width)) {
			$rel = ',-1';
			return true;
		}

		if (($y < $this->width - 1) && ($pos === $empty + $this->width)) {
			$rel = ',+1';
			return true;
		}

		return false;

	}

	private function move($x, $y)
	{

		$pos = $x + $y * $this->width;
		$emptyPos = $this->searchEmpty();
		$this->order[$emptyPos] = $this->order[$pos];
		$this->order[$pos] = 0;

	}

	private function searchEmpty()
	{

		return array_search(0, $this->order);

	}

	public function handleShuffle()
	{

		$i = 100;
		while ($i) {
			$x = rand(0, $this->width - 1);
			$y = rand(0, $this->width - 1);
			if ($this->isClickable($x, $y)) {
				$this->move($x, $y);
				$i--;
			}
		}

		$this->round = 0;

	}

	public function handleChangeVariable()
	{

		if ($this->presenter->isAjax()) {
			$this->presenter->redrawControl('fifteen');
		}

	}

}
