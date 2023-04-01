<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\{
    Auth,
    View
};
use Smarty;

/**
 * BaseController
 */
class BaseController
{
    /**
     * @var Smarty
     */
    protected $view;

    /**
     * @var User
     */
    protected $user;

    /**
     * Construct page renderer
     */
    public function __construct()
    {
        $this->view = View::getSmarty();
        $this->user = Auth::getUser();
    }

    public function view()
    {
        return $this->view;
    }
}
