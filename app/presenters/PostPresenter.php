<?php

namespace App\Presenters;

use Nette\Application\UI\Presenter;
use Nette\Application\UI\Form;
use Nette\Database\Context;
use App\Forms\PostFormFactory;
use App\Forms\CommentFormFactory;


class PostPresenter extends Presenter
{

	private $database;

	public function __construct(Context $database)
	{

		parent::__construct();
		$this->database = $database;

	}

	public function actionCreate()
	{

		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Sign:in');
		}

	}

	protected function createComponentPostForm()
	{

		$form = (new PostFormFactory)->create();
		$form->onSuccess[] = [$this, 'postFormSucceeded'];
		$form->addProtection('Security token has expired, please submit the form again');

		return $form;

	}

	public function postFormSucceeded(Form $form, $values)
	{

		if (!$this->getUser()->isLoggedIn()) {
			$this->error('You need to log in to create or edit posts');
		}

		$postId = $this->getParameter('postId');

		if ($postId) {
			$post = $this->database->table('posts')->get($postId);
			$post->update($values);
		} else {
			$post = $this->database->table('posts')->insert($values);
		}

		$this->flashMessage('Post was published', 'success');
		$this->redirect('show', $post->id);

	}

	public function actionEdit($postId)
	{

		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Sign:in');
		}

		$post = $this->database->table('posts')->get($postId);
		if (!$post) {
			$this->error('Post not found');
		}

		$this['postForm']->setDefaults($post->toArray());

	}

	public function renderShow($postId)
	{

		$post = $this->database->table('posts')->get($postId);

		if (!$post) {
			$this->error('Post no found');
		}

		$this->template->post = $post;
		$this->template->comments = $post->related('comments')->order('created_at DESC')->limit(10);

	}

	protected function createComponentCommentForm()
	{

		$form = (new CommentFormFactory)->create();
		$form->onSuccess[] = [$this, 'commentFormSucceeded'];
		$form->addProtection('Security token has expired, please submit the form again');

		return $form;

	}

	public function commentFormSucceeded(Form $form, $values)
	{

		$postId = $this->getParameter('postId');

		$this->database->table('comments')->insert([
			'post_id' => $postId,
			'name' => $values->name,
			'email' => $values->email,
			'content' => $values->content,
		]);

		$this->flashMessage('Thank you for your comment', 'success');
		$this->redirect('this');

	}

}
