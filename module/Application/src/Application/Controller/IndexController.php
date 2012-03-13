<?php

namespace Application\Controller;

use Zend\Mvc\Controller\ActionController,
    Zend\View\Model\ViewModel,
    Zend\Navigation;

class IndexController extends ActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
}
