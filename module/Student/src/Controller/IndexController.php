<?php 
namespace Student\Controller;

use Laminas\View\Model\ViewModel;
use Laminas\Mvc\Controller\AbstractActionController;

use Student\Model\Student;
use Student\Model\Entity;
use Student\Form\StudentForm;

class IndexController extends AbstractActionController
{

	private $studentDb;
	public function __construct(Student $studentDb, StudentForm $form)
	{
		$this->studentDb = $studentDb;
		$this->form = $form;
	}

	public function indexAction()
	{
		$students = $this->studentDb->getAll();
		return new ViewModel([
            'students' => $students->count()?$students->toArray():[],
        ]);
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

		$student = $this->form->getData();
		$this->studentDb->create($student);
		return $this->redirect()->toRoute('student');
	}

	public function deleteAction()
	{
		$id = $this->params()->fromRoute('id');

        if (! $id) {
            return $this->redirect()->toRoute('student');
        }

		$this->studentDb->delete($id);
		return $this->redirect()->toRoute('student');
	}

	public function editAction()
	{	
		$request   = $this->getRequest();
		$id = $this->params()->fromRoute('id');

        if (! $id) {
            return $this->redirect()->toRoute('student');
		}
		
		if ($request->isPost()) {
			$this->form->setData($request->getPost());
			if($this->form->isValid()){
				$student = $this->form->getData();
				$this->studentDb->update($student);
				return $this->redirect()->toRoute('student');
			}

		}	
		$student = $this->studentDb->get($id);
		$this->form->bind($student);
		return new ViewModel(['form' => $this->form]);
		
	}
}