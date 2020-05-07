<?php


namespace Juff\Controller\Form;


class AddTaskForm extends AbstractForm
{
    /**
     * @var array
     */
    protected $formFields = ['User name', 'Email address', 'Description|textarea'];

    public function isValid(): bool
    {
        $result = [
            'user_name' => $this->formr->post('user_name', 'User name', 'min_length[3]|allow_html'),
            'user_email' => $this->formr->post('email_address', 'Email address', 'allow_html'),
            'description' => $this->formr->post('description', 'Description', 'allow_html'),
        ];

        $this->postData = $result;

        return $this->formr->errors() == false;
    }
}