<?php

namespace App\Presenters;

use Nette\Application\UI\Presenter;

class GamePresenter extends Presenter
{

	public function renderDefault()
	{
		$this->redrawControl('round');
	}

	protected function createComponentFifteen()
	{

		$fifteen = new \TestFifteenControl();
		$fifteen->onGameOver[] = [$this, 'gameOver'];
		$fifteen->redrawControl();
		return $fifteen;

	}

	public function gameOver($sender, $round)
	{

		$this->template->flash = 'Congratulations!';
		$this->redrawControl();

	}

}
