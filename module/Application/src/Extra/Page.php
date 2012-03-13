<?php
namespace Extra;

class Page extends Container
{
    protected $title;
    
    public $pageSelfTerminates = false;
    
    public $pageTag = 'a';
    public $pageAttributes = array();
    
    public $wrapTag = 'li';
    public $wrapAttributes = array();

    public function __toString()
    {
        if($this->getUrl()){
            return $this->getUrl();
        }
        return '';
    }
 
    public function getTitle()
    {
        return $this->title;
    }
 
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function pageSelfTerminates()
    {
        return $this->pageSelfTerminates;
    }

    public function setPageSelfTerminates($pageSelfTerminates)
    {
        $this->pageSelfTerminates = $pageSelfTerminates;
        return $this;
    }

    public function getWrapTag()
    {
        return $this->wrapTag;
    }

    public function setWrapTag($wrapTag)
    {
        $this->wrapTag = $wrapTag;
        return $this;
    }
 
    public function getPageTag()
    {
        return $this->pageTag;
    }
 
    public function setPageTag($pageTag)
    {
        $this->pageTag = $pageTag;
        return $this;
    }

    public function setUrl($url)
    {
        $this->pageAttributes['href'] = $url;
        return $this;
    }

    public function getUrl()
    {
        if(isset($this->pageAttributes['href'])){
            return $this->pageAttributes['href'];
        }
    }

}
