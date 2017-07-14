<?php

namespace App\Forms;

use Nette\Application\UI\Form;

class PostFormFactory
{

	/** @return Form */
	public function create(): Form
	{

		$form = new Form();
		$form->addText('title', 'Title:')->setRequired();
		$form->addTextArea('content', 'Content:')->setRequired();
		$form->addSubmit('send', 'Save and publish');

		return $form;

	}

}