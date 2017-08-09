<?php

namespace App\Presenters;

use Nette\Application\UI\Presenter;
use Nette\Database\Context;
use Nette\Utils\Paginator;
use App\Model\ArticleManager;

class HomepagePresenter extends Presenter
{

	private $articleManager;
	private $database;
	private $anyVariable = 'default value';

	public function __construct(ArticleManager $articleManager, Context $context)
	{

		parent::__construct();
		$this->articleManager = $articleManager;
		$this->database = $context;

	}

	public function renderDefault($page = 1)
	{

		$paginator = new Paginator();
		$paginator->setItemsPerPage(2);
		$paginator->setPage($page);

		$posts = $this->database->table('posts')
			->order('created_at DESC')
			->limit($paginator->getLength(), $paginator->getOffset());

		$totalPosts = $this->database->table('posts')->count();
		$paginator->setItemCount($totalPosts);

		$this->template->pageCount = $paginator->getPageCount();
		$this->template->posts = $posts;
		$this->template->page = $paginator->page;
		$this->template->anyVariable = $this->anyVariable;

	}

	public function handleChangeVariable()
	{
		if ($this->isAjax()) {
			$this->anyVariable = 'changed value via ajax';
			$this->redrawControl('ajaxChange');
		}
	}

}
