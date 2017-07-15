<?php

namespace App\Presenters;

use Nette\Application\UI\Presenter;
use Nette\Application\UI\Form;
use Nette\Database\Context;
use App\Forms\PostFormFactory;
use App\Forms\CommentFormFactory;
use App\Model\Comment;


class PostPresenter extends Presenter
{

	private $database;
	private $comment;

	public function __construct(Context $database, Comment $comment)
	{

		parent::__construct();
		$this->database = $database;
		$this->comment = $comment;

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

		$postId = $this->getParameter('postId');
		$form = (new CommentFormFactory($this->comment))->create($postId, function () {
			$this->flashMessage('Thank you for your comment', 'success');
			$this->redirect('this');
		});

		return $form;

	}

}
