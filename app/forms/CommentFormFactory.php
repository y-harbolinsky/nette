<?php

namespace App\Forms;

use Nette\Application\UI\Form;

class CommentFormFactory
{

	/** @return Form */
	public function create(): Form
	{

		$form = new Form();
		$form->addText('name', 'Your name:')->setRequired('Please fill your name!');
		$form->addEmail('email', 'Email:');
		$form->addTextArea('content', 'Comment:');
		$form->addSubmit('send', 'Publish comment');

		return $form;

	}

}