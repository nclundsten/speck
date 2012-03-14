<?php
namespace Application\Extra;

class Container 
{
    protected $pages = array();
    protected $pageSelfTerminates = false;

    public $containerTag = 'ul';
    public $containerAttributes = array();

    public $hereditaryOptions;

    public function __construct($options = null)
    {
        if($options){
            $this->setOptions($options);
        }
    }

    public function addPage($page)
    {
        if($this->hereditaryOptions){
            $page->setOptions($this->hereditaryOptions);
        }

        $this->pages[] = $page;
        return $this;
    }
    public function addPages($pages)
    {
        foreach($pages as $page){
            $this->addPage($page);
        }
        return $this;
    }

    public function hasPages()
    {
        if(count($this->pages) > 0){
            return true;
        }
    }

    public function __toString()
    {
        if($this->getUrl()){
            return $this->getUrl();
        }
        return '';
    }

    public function currentOptionsHereditary()
    {
        $currentOptions = $this->getOptions();
        $this->hereditaryOptions = $this->setOptions($currentOptions);
        return $this;
    }

	public function getOptions()
	{
		$getOptions = function($obj) { return get_object_vars($obj); };
		return $getOptions($this);
	}

    protected function setOptions($options)
    {
        $return = array();
        foreach($options as $i => $option){
            $return[$i] = $option;
            $this->$i = $option;
        }
        return $return;
    }

    public function getContainerTag()
    {
        return $this->containerTag;
    }

    public function setContainerTag($containerTag)
    {
        $this->containerTag = $containerTag;
        return $this;
    }
 
    public function getPages()
    {
        return $this->pages;
    }

    public function renderAttributes($el)
    {
        $elAttribs = $el . 'Attributes';
        $return = '';
        foreach($this->$elAttribs as $key => $val){
            $return .= ' ' . $key . '="' . $val . '"';
        }
        return $return;
    }

    public function setAttributes($attributes)
    {
        foreach($attributes as $element => $attribs){
            foreach($attribs as $attr => $val){
                $this->setAttribute($element, $attr, $val);
            }
        }
        return $this;
    }

    public function setAttribute($el, $attr, $val, $merge=false)
    {
        $elAttributes = $el . 'Attributes';
        $attribs = $this->$elAttributes;
        if($merge){
            $attribs[$attr] = $attribs[$attr] .' ' . $val;
        }else{
            $attribs[$attr] = $val;
        }
        $this->$elAttributes = $attribs;
        return $this;
    }

    public function mergeAttribute($el, $attr, $val)
    {
        return $this->setAttribute($el, $attr, $val, true);
    } 

}
