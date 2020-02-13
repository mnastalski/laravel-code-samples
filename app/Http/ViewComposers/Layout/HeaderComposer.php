<?php

namespace App\Http\ViewComposers\Layout;

use Illuminate\Auth\AuthManager;
use Illuminate\View\View;

class HeaderComposer
{
    /**
     * @var \Illuminate\Auth\AuthManager
     */
    private $authManager;

    /**
     * @param \Illuminate\Auth\AuthManager $authManager
     */
    public function __construct(
        AuthManager $authManager
    ) {
        $this->authManager = $authManager;
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('user', $this->authManager->user());
    }
}
