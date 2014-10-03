<?php

namespace views;

require_once('src/loginSystem.php');

use models\User;
use Template\directives\InputDirective;
use Template\View;
use Template\ViewSettings;

class RegisterView extends View {
    protected $template = 'register.html';
    /**
     * @var BaseView
     */
    private $baseView;
    /**
     * @var User
     */
    private $user;

    public function __construct(BaseView $baseView, InputDirective $inputDirective, User $user,
                                ViewSettings $viewSettings) {
        parent::__construct($viewSettings);

        $inputDirective->registerInput($this, 'username');
        $inputDirective->registerInput($this, 'password');
        $inputDirective->registerInput($this, 'passwordRepeat');
        $inputDirective->registerInput($this, 'registerButton');

        $this->baseView = $baseView;
        $this->user =$user;
    }

    /**
     * @return string
     */
    public function getUsername() {
        return $this->variables['username'];
    }

    /**
     * @return string
     */
    public function getPassword() {
        return $this->variables['password'];
    }

    /**
     * @return string
     */
    public function getPasswordRepeat() {
        return $this->variables['passwordRepeat'];
    }

    /**
     * @return bool
     */
    public function isAddingUser() {
        return isset($this->variables['registerButton']);
    }

    /**
     * @param string $error
     */
    private function setError($error) {
        $this->setVariable('error', $error);
    }

    public function setRegisterSucceeded() {
        $this->setVariable('status', 'Registrering av ny användare lyckades');
    }

    public function validateUser() {
        $username = $this->getUsername();
        $password = $this->getPassword();
        $passwordRepeat = $this->getPasswordRepeat();
        $error = "";

        if (strlen($username) < 3) {
            $error .= "  -   Användarnamnet har för få tecken. Minst 3 tecken";
            $this->setError($error);
        }
        if (strlen($password) < 6)
        {
            $error .= "  -   Lösenorden har för få tecken. Minst 6 tecken  ";
            $this->setError($error);
        }
        if ($password != $passwordRepeat)
        {
            $error .= "  -   Lösenorden matchar inte  ";
            $this->setError($error);
        }
        if(strpbrk($username, '<>""./'))
        {
            $error .= "  -   Användarnamnet innehåller ogiltiga tecken  ";
            $this->setError($error);
        }
        if($username = $this->getUsername() == $this->user->getUsername())
        {
            $error .= "  -   Användarnamnet är redan upptaget  ";
            $this->setError($error);
        }

    }

    public function onRender() {
        $this->baseView->setTitle('Registrering av ny användare');

        if ($this->isAddingUser())
        {



        }
    }
}