<?php


namespace Juff\Controller\Form;


class EditTaskForm extends AbstractForm
{
    /**
     * @var array
     */
    protected $formFields = ['Description', 'Completed'];

    /**
     * @var string
     */
    private $description;

    /**
     * @var bool
     */
    private $isChecked;

    /**
     * EditTaskForm constructor.
     * @param string $action
     * @param string $description
     * @param bool $isChecked
     */
    public function __construct(string $action, string $description, bool $isChecked)
    {
        parent::__construct($action);

        $this->description = $description;
        $this->isChecked = $isChecked;
        $this->formr->required = 'description';
    }

    public function html(): string
    {
        $html = $this->formr->form_open();
        $html .= $this->formr->input_textarea('description', 'Task Description', $this->description, 'description');
        $html .= $this->formr->input_checkbox('is_completed', 'Completed', 'is_completed', 'is_completed', '', '', $this->isChecked);
        $html .= $this->formr->csrf();
        $html .= $this->formr->messages();
        $html .= $this->formr->input_submit();
        $html .= $this->formr->form_close();

        return $html;
    }

    public function isValid(): bool
    {
        $result = [
            'description' => $this->formr->post('description', 'Description', 'allow_html'),
            'is_completed' => $this->formr->post('is_completed') ? 1 : 0,
        ];
        $this->postData = $result;

        return $this->formr->errors() == false;
    }
}