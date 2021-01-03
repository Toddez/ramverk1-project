<?php

namespace Teca\User\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Teca\User\User;

class ProfileForm extends FormModel
{
    public function __construct(ContainerInterface $di)
    {
        $user = new User();
        $user->currentUser($di);

        parent::__construct($di);
        $this->form->create(
            [
                "id" => "ProfileForm",
                "legend" => "Profil",
            ],
            [
                "avatar" => [
                    "type" => "text",
                    "label" => "Avatar (hämtas från gravatar)",
                    "value" => $user->avatar
                ],
                "submit" => [
                    "type" => "submit",
                    "value" => "Spara ändringar",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }

    public function callbackSubmit() : bool
    {
        $avatar = $this->form->rawValue("avatar");

        $user = new User();
        $user->currentUser($this->di);
        $user->avatar = $avatar;
        $user->save();

        return true;
    }

    public function callbackSuccess()
    {
        $this->di->get("response")->redirectSelf();
    }
}
