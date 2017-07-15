<?php

namespace App\Model;

use Nette\SmartObject,
	Nette\Database\Context;

class Comment
{

	use SmartObject;

	private $database;

	public function __construct(Context $database)
	{

		$this->database = $database;

	}

	public function createComment($postId, $values)
	{

		$this->database->table('comments')->insert([
			'post_id' => $postId,
			'name' => $values['name'],
			'email' => $values['email'],
			'content' => $values['content'],
		]);

	}

}