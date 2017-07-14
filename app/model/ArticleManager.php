<?php

namespace App\Model;

use Nette\SmartObject;
use Nette\Database\Context;

class ArticleManager
{

	use SmartObject;

	private $database;

	public function __construct(Context $database)
	{

		$this->database = $database;

	}

	public function getPublicArticles()
	{

		return $this->database->table('posts')
			->where('created_at < ', new \DateTime('2017-06-15 11:18:53'))
			->order('created_at DESC');

	}

}
