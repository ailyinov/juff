<?php

namespace Juff\Controller;

use Juff\Controller\Form\AddTaskForm;
use Juff\Controller\Form\EditTaskForm;
use Juff\Controller\Utils\PaginatorWIthSorting;
use Juff\Entity\Task;

class TaskController extends AbstractController
{
    public function tasksList()
    {
        $page = $this->request->get('page', 1);
        $sort = $this->request->get('sort', 'user_name');
        $order = $this->request->get('order', 'asc');

        $paginator = new PaginatorWIthSorting();
        $paginator->setPage(max(1, $page));
        $paginator->setSortField($sort);
        $paginator->setOrder($order);

        $tasks = Task::query()
            ->limit($paginator->getItemsPerPageCount())
            ->offset($paginator->getOffset())
            ->orderBy($paginator->getSortField(), $paginator->getOrder())
            ->get();
        $count = Task::query()->count();

        $paginator->setItemsCount($count);

        return $this->render('tasks-list.html.twig', [
            'tasks' => $tasks,
            'nav' => 'tasks',
            'paginator' => $paginator,
        ]);
    }

    public function edit()
    {
        $task = Task::query()
            ->where('id', '=', $this->request->get('task_id'))
            ->first(['id', 'description', 'is_completed']);

        $form = new EditTaskForm($this->getCurrentPath(), $task->description, $task->is_completed);

        if ($form->isSubmitted() && $form->isValid()) {
            $values = $form->getPostData();
            $task->fill($values);
            if ($task->isDirty(['description'])) {
                $task->was_edited = 1;
            }
            $task->save();

            $this->redirect('/');
        }

        return $this->render('edit-task.html.twig', ['form' => $form]);
    }

    public function add()
    {
        $form = new AddTaskForm($this->getCurrentPath());

        if ($form->isSubmitted() && $form->isValid()) {
            $task = new Task($form->getPostData());
            $task->save();

            $this->redirect('/');
        }

        return $this->render('add-task.html.twig', ['nav' => 'add', 'form' => $form]);
    }
}