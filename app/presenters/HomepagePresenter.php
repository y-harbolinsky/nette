<?php

namespace App\Presenters;

use Nette\Application\UI\Presenter;
use App\Model\ArticleManager;

class HomepagePresenter extends Presenter
{

	private $articleManager;

	public function __construct(ArticleManager $articleManager)
	{

		parent::__construct();
		$this->articleManager = $articleManager;

	}

	public function renderDefault()
	{

		$this->template->posts = $this->articleManager->getPublicArticles()->limit(3);

	}

}
