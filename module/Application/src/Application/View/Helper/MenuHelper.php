<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class MenuHelper extends AbstractHelper
{
    protected $pages;
    protected $tag = "ul";
    protected $begin;
    protected $end;
    protected $depth = 0;
    protected $indent = '    ';


    public function __invoke()
    {
        return $this;
    }
 
    public function __construct($pages = null)    
    {
        if($pages){
            $this->pages = $pages;
        }
        return $this;    
    }

    public function __toString()
    {
        return $this->render();
    }

    public function indent(){
        return str_repeat($this->indent, $this->depth);
    }
    
    public function renderLink($page)
    {
        if($page->getPageTag()){
            if($page->getWrapTag()){
                $this->depth++;
                $this->begin .= $this->indent() . "<" . $page->getWrapTag() . $page->renderAttributes('wrap') . ">\n";
                $this->end    = $this->indent() . "</" . $page->getWrapTag() . ">\n" . $this->end;
            }
            $this->depth++;
            $this->begin .= $this->indent() . "<" . $page->getPageTag() . $page->renderAttributes('page');
            if($page->pageSelfTerminates()){
                $this->begin .= "/>\n";
            } else {
                $this->begin .= ">" . $page->getTitle() . "</" . $page->getPageTag() . ">\n";
            }
        }  

        if($page->hasPages()){
            $this->renderContainer($page);
            foreach($page->getPages() as $childPage){
                $this->renderLink($childPage);
            }
        }
    }

    public function renderContainer($container)
    {
        if ($container->getContainerTag()){
            $this->begin .= $this->indent() . "<" . $container->getContainerTag() . $container->renderAttributes('container') . ">\n";
            $this->end    = $this->indent() . "</" . $container->getContainerTag() . ">\n" . $this->end;
            
        }     
    }

    public function render()
    {
        if(!isset($this->pages)){
            die('oops');
        }

        if($this->tag){
            $this->begin .= "<" . $this->tag . ">\n";
            $this->end .= "</" . $this->tag . ">";
        }
        foreach($this->pages as $page){
            $this->renderLink($page);
        }

        return $this->begin . $this->end;
    }

    public function getIndent()
    {
        return $this->indent;
    }

    public function setIndent($indent)
    {
        $this->indent = $indent;
        return $this;
    }

 
    public function getTag()
    {
        return $this->tag;
    }

    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }

 
    public function getPages()
    {
        return $this->pages;
    }

    public function addPage($page)
    {
        $this->pages[] = $page;
        return $this;
    }
 
    public function setPages($pages)
    {
        $this->pages = $pages;
        return $this;
    }
}   
