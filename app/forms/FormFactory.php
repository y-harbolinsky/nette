<?php

namespace App\Forms;

use Nette\Object;
use Nette\Application\UI\Form;

class FormFactory extends Object
{

	/** @return Form */
	public function create()
	{

		return new Form();

	}

}