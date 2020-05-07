<?php


namespace Juff\Controller\Form;


use Formr;

abstract class AbstractForm
{
    /**
     * @var string
     */
    protected $formFields = [];

    /**
     * array
     */
    protected $postData = [];

    /**
     * @var Formr
     */
    protected $formr;

    /**
     * AbstractForm constructor.
     * @param string $action
     */
    public function __construct(string $action)
    {
        $this->formr = new Formr('bootstrap');
        $this->formr->required = '*';
        $this->formr->action = $action;
    }

    public function getPostData(): array
    {
        return $this->postData;
    }

    public function isSubmitted(): bool
    {
        return $this->formr->submitted();
    }

    abstract public function isValid(): bool;

    public function html(): string
    {
        $html = $this->formr->form_open();
        $html .= $this->formr->create(implode(',', $this->formFields));
        $html .= $this->formr->csrf();
        $html .= $this->formr->messages();
        $html .= $this->formr->input_submit();
        $html .= $this->formr->form_close();

        return $html;
    }
}