<?php
namespace Blog\Controller;

use Blog\Form\PostForm;
use Blog\Model\Post;
use Blog\Model\PostCommandInterface;
use Blog\Model\PostRepositoryInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class WriteController extends AbstractActionController
{
    /*
        @var PostRepositoryInterface
    */
    private $repository;

    /**
     * @var PostCommandInterface
     */
    private $command;

    /**
     * @var PostForm
     */
    private $form;

    /**
     * @param PostCommandInterface $command
     * @param PostForm $form
     */
    public function __construct(
        PostCommandInterface $command, 
        PostForm $form,
        PostRepositoryInterface $repository
    )
    {
        $this->command = $command;
        $this->form = $form;
        $this->repository = $repository;
    }

    public function addAction()
    {
        $request   = $this->getRequest();
        $viewModel = new ViewModel(['form' => $this->form]);

        if (! $request->isPost()) {
            return $viewModel;
        }

        $this->form->setData($request->getPost());

        if (! $this->form->isValid()) {
            return $viewModel;
        }

        $post = $this->form->getData();

        try {
            $post = $this->command->insertPost($post);
        } catch (\Exception $ex) {
            // An exception occurred; we may want to log this later and/or
            // report it to the user. For now, we'll just re-throw.
            throw $ex;
        }

        return $this->redirect()->toRoute(
            'blog/detail',
            ['id' => $post->getId()]
        );
    }

    public function editAction()
    {
        $request   = $this->getRequest();
        $id = $this->params()->fromRoute('id');

        if (! $id) {
            return $this->redirect()->toRoute('blog');
        }

        

        if ($request->isPost()) {

            $this->form->setData($request->getPost());
            if($this->form->isValid()){
                $post = $this->form->getData();
                $this->command->updatePost($post);
            }

            return $this->redirect()->toRoute(
                'blog/edit',
                ['id' => $post->getId()]
            );
        }

        try {
            $post = $this->repository->findPost($id);
            $this->form->bind($post);
        } catch (\InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('blog');
        } 

        
        $viewModel = new ViewModel(['form' => $this->form]);
        return $viewModel;

    }

    public function deleteAction()
    {

        $id = $this->params()->fromRoute('id');

        if (! $id) {
            return $this->redirect()->toRoute('blog');
        }

        try {
            $post = $this->repository->findPost($id);
            $this->command->deletePost($post);
        } catch (\InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('blog');
        } 

        return $this->redirect()->toRoute('blog');

    }
}