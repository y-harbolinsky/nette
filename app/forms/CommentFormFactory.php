<?php

namespace App\Forms;

use Nette\Application\UI\Form;
use App\Model\Comment;

class CommentFormFactory
{

	/** @var Comment */
	private $comment;

	public function __construct(Comment $comment)
	{

		$this->comment = $comment;

	}

	public function create($postId, callable $onSuccess): Form
	{

		$form = new Form();
		$form->addText('name', 'Your name:')->setRequired('Please fill your name!');
		$form->addEmail('email', 'Email:');
		$form->addTextArea('content', 'Comment:');
		$form->addSubmit('send', 'Publish comment');
		$form->addProtection('Security token has expired, please submit the form again');
		$form->onSuccess[] = function (Form $form, array $values) use ($postId, $onSuccess) {

			$this->comment->createComment($postId, $values);

			$onSuccess();
		};

		return $form;

	}

}