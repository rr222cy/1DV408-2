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

        $this->baseView = $baseView;
        $this->user =$user;
    }

    /**
     * @return bool
     */
    public function isAuthenticatingUser() {
        return isset($this->variables['logoutButton']);
    }

    public function setRegisterSucceeded() {
        $this->setVariable('status', 'Registrering av ny användare lyckades');
    }

    public function onRender() {
        $this->baseView->setTitle('Registrering av ny användare');
    }
}