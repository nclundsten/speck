<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class MenuHelper extends AbstractHelper
{
    protected $pages;
    protected $tag = "ul";
    protected $html;
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
        $this->depth++;

        if($page->getWrapTag()){
            $this->html .= $this->indent() . "<" . $page->getWrapTag() . $page->renderAttributes('wrap') . ">\n";
        }
        
        if($page->getPageTag()){
            $this->depth++;
        
            $this->html .= $this->indent() . "<" . $page->getPageTag() . $page->renderAttributes('page');
            if($page->pageSelfTerminates()){
                $this->html .= "/>\n";
            } else {
                $this->html .= ">" . $page->getTitle() . "</" . $page->getPageTag() . ">\n";
            }
        
            $this->depth--;
        }

        if($page->hasPages()){
            $this->depth++;
        
            $this->html .= $this->indent() . "<" . $page->getContainerTag() . $page->renderAttributes('container') . ">\n";
            foreach($page->getPages() as $childPage){
                $this->renderLink($childPage);
            }
            $this->html   .= $this->indent() . "</" . $page->getContainerTag() . ">\n";
        
            $this->depth--;
        }

        if($page->getWrapTag()){
            $this->html    .= $this->indent() . "</" . $page->getWrapTag() . ">\n";
        }

        $this->depth--;   
    }

    public function render()
    {
        if(!isset($this->pages)){
            die('oops');
        }

        if($this->tag){
            $this->html .= "<" . $this->tag . ">\n";
        }else{
            $this->html .= "\n";
        }

        foreach ($this->pages as $page){
            $this->renderLink($page);
        }

        return $this->html . "\n";
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

    public function getDepth()
    {
        return $this->depth;
    }

    public function setDepth($depth)
    {
        $this->depth = $depth;
        return $this;
    }
}   
