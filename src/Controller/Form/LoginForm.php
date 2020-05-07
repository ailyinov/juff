<?php


namespace Juff\Controller\Form;


class LoginForm extends AbstractForm
{
    /**
     * @var array
     */
    protected $formFields = ['Login', 'Password|password'];

    public function isValid(): bool
    {
        $login = $this->formr->post('login');
        $password = $this->formr->post('password');

        if ($this->formr->errors() !== false || !$this->accessAllowed($login, $password)) {
            $this->formr->error_message('Password or login didn\'t match.', true);

            return false;
        }

        return true;
    }

    private function accessAllowed($login, $password)
    {
        return $login === 'admin' && $password === '123';
    }
}