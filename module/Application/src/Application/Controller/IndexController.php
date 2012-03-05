<?php

namespace Application\Controller;

use Zend\Mvc\Controller\ActionController,
    Zend\View\Model\ViewModel,
    Zend\Navigation;

class IndexController extends ActionController
{
    public function indexAction()
    {
        $home =  new Navigation\Page\Uri();
        $home->setUri('/')->setLabel('home');
        $navigation = new Navigation\Navigation(array($home));
        return new ViewModel(array('navigation' => $navigation));
    }
}
