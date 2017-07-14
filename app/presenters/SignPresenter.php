<?php

namespace App\Presenters;

use Nette\Application\UI\Presenter;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;


class SignPresenter extends Presenter
{

	protected function createComponentSignInForm()
	{

		$form = new Form();
		$form->addText('username', 'Username:')->setRequired('Please enter your username');
		$form->addPassword('password', 'Password:')->setRequired('Please enter your password');
		$form->addSubmit('send', 'Sign in');
		$form->onSuccess[] = [$this, 'signInFormSucceeded'];

		return $form;

	}

	public function signInFormSucceeded($form, $values)
	{

		try {
			$this->getUser()->login($values->username, $values->password);
			$this->redirect('Homepage:');
		} catch (AuthenticationException $authenticationException) {
			$form->addError('Please check your username and password');
		}

	}

	public function actionOut()
	{

		$this->getUser()->logout();
		$this->flashMessage('You have been signed out.');
		$this->redirect('Homepage:');

	}

}
