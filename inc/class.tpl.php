<?php

class Tpl
{
	private $outputData;
	private $params = array();
	private $includeFiles = array();
	
	public function __construct()
	{
		$this->setParam('shortname', Core::getVar('logoname'));
		$this->setParam('fullname', Core::getVar('sitename'));
		$this->setParam('logoname', Core::getVar('logoname'));
		$this->setParam('body_id', '');
		$this->setParam('page_title', ' ');
		$this->setParam('flash_build', 'flash_51_45');
		$this->setParam('web_build', '63_1dc60c6d6ea6e089c6893ab4e0541ee0/160b');
		$this->setParam('web_build_str', '54-BUILD 45 - 18.05.2010 16:16 - de');
		$this->setParam('req_path', WWW);
		$this->setParam('www', WWW);
		$this->setParam('hotel_status_fig', Core::getSystemStatusString(true));
		$this->setParam('hotel_status', Core::getSystemStatusString(false));
		$this->setParam('users_online', Core::getUsersOnline());
		
		if(LOGGED_IN) {
			$this->setParam('habboLoggedIn', 'true');
			$this->setParam('habboName', USER_NAME);
		} else {
			$this->setParam('habboLoggedIn', 'false');
			$this->setParam('habboName', 'null');
		}
	}
	
	public function addIncludeSet($set)
	{
		switch (strtolower($set)) {
			case 'frontpage':
			
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/libs2.js'));
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/landing.js'));
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/static/styles/frontpage.css', 'stylesheet'));			
				break;
				
			case 'maintenance':
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/libs2.js'));
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/landing.js'));
				$this->addIncludeFile(new IncludeFile('text/css', 'http://images.habbo.com/habboweb/%web_build%/web-gallery/static/styles/frontpage.css', 'stylesheet'));			
				
				break;			
				
			case 'register':

				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/libs2.js'));
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/visual.js'));		
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/libs.js'));		
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/common.js'));
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/style.css', 'stylesheet'));		
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/buttons.css', 'stylesheet'));	
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/boxes.css', 'stylesheet'));	
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/tooltips.css', 'stylesheet'));
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/changepassword.css', 'stylesheet'));
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/forcedemaillogin.css', 'stylesheet'));
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/quickregister.css', 'stylesheet'));
				break;
		
		
			case 'process-template':
			
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/libs2.js'));
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/visual.js'));
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/libs.js'));
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/common.js'));
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/fullcontent.js'));
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/style.css', 'stylesheet'));		
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/buttons.css', 'stylesheet'));	
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/boxes.css', 'stylesheet'));	
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/tooltips.css', 'stylesheet'));	
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/process.css', 'stylesheet'));	
				break;
				
			case 'myhabbo':
			
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/libs2.js'));
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/visual.js'));
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/libs.js'));
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/common.js'));
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/fullcontent.js'));
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/style.css', 'stylesheet'));		
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/buttons.css', 'stylesheet'));	
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/boxes.css', 'stylesheet'));	
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/tooltips.css', 'stylesheet'));				
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/styles/myhabbo/myhabbo.css', 'stylesheet'));
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/styles/myhabbo/skins.css', 'stylesheet'));
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/styles/myhabbo/dialogs.css', 'stylesheet'));
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/styles/myhabbo/buttons.css', 'stylesheet'));
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/styles/myhabbo/control.textarea.css', 'stylesheet'));
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/styles/myhabbo/boxes.css', 'stylesheet'));
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/myhabbo.css', 'stylesheet'));
				$this->addIncludeFile(new IncludeFile('text/css', 'http://www.habbo.co.uk/myhabbo/styles/assets.css', 'stylesheet'));
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/homeview.js'));
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/lightwindow.css', 'stylesheet'));
				break;
				
			case 'identity':
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/libs2.js'));
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/visual.js'));
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/libs.js'));
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/common.js'));
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/fullcontent.js'));
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/style.css', 'stylesheet'));		
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/buttons.css', 'stylesheet'));	
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/boxes.css', 'stylesheet'));	
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/tooltips.css', 'stylesheet'));	
				break;
			
			default:
			
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/libs2.js'));
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/visual.js'));
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/libs.js'));
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/common.js'));
				$this->addIncludeFile(new IncludeFile('text/javascript', '%www%/web-gallery/static/js/fullcontent.js'));
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/style.css', 'stylesheet'));		
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/buttons.css', 'stylesheet'));	
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/boxes.css', 'stylesheet'));	
				$this->addIncludeFile(new IncludeFile('text/css', '%www%/web-gallery/v2/styles/tooltips.css', 'stylesheet'));		
				break;
		}
	}
	
	public function addTemplate($tplName)
	{
		$tpl = new Template($tplName);
		$this->outputData .= $tpl->getHtml();
	}
	
	public function setParam($param, $value)
	{
		$this->params[$param] = is_object($value) ? $value->fetch() : $value;
	}
	
	public function unsetParam($param)
	{
		unset($this->params[$param]);
	}
	
	public function addIncludeFile($incFile)
	{
		$this->includeFiles[] = $incFile;
	}
	
	public function writeIncludeFiles()
	{
		foreach($this->includeFiles as $f) {
			$this->write($f->getHtml() . LB);
		}
	}
	
	public function write($str)
	{
		$this->outputData .= $str;
	}
	
	public function filterParams($str)
	{
		foreach ($this->params as $param => $value)
			$str = str_ireplace('%' . $param . '%', $value, $str);
		return $str;
	}
	
	public function __toString()
	{
		global $core;
		$this->write(LB . LB . '<!-- Habbo: Took ' . (microtime(true) - $core->execStart) . ' to output this page -->' . LB . LB);
		return $this->filterParams($this->outputData);
	}
}

class Template
{
	private $params = Array();
	private $tplName = '';
	
	public function __construct($tplName)
	{
		$this->tplName = $tplName;
	}
	
	public function getHtml()
	{
		extract($this->params);
		
		$file = INCLUDES . '/data/tpl/' . $this->tplName . '.php';
		
		if(!file_exists($file) || !is_file($file) || !is_readable($file)) {
			Core::SystemError('Template system error', 'Could not load template: ' . $this->tplName);
		    exit;
        }
		
		ob_start();
		include $file;
		$data = ob_get_contents();
		ob_end_clean();	
		
		return $this->filterParams($data);
	}
	
	public function filterParams($str)
	{
		foreach($this->params as $param => $value) {
			if(is_object($value)) {
				continue;
			}
			$str = str_ireplace('%' . $param . '%', $value, $str);
		}
		
		return $str;
	}
	
	public function setParam($param, $value)
	{
		$this->params[$param] = $value;
	}
	
	public function unsetParam($param)
	{
		unset($this->params[$param]);
	}		
}

class IncludeFile
{
	private $type;
	private $src;
	private $rel;
	private $name;

	public function __construct($type, $src, $rel = '', $name = '')
	{
		$this->type = $type;
		$this->src = $src;
		$this->rel = $rel;
		$this->name = $name;
	}
	
	public function getHtml()
	{
		switch ($this->type)
		{
			case 'application/rss+xml':
			
				return '<link rel="' . $this->rel . '" type="' . $this->type . '" title="' . $this->name . '" href="' . $this->src . '" />';
		
			case 'text/javascript':
			
				return '<script src="' . $this->src . '" type="text/javascript"></script>';
				
			case 'text/css':
			
				return '<link rel="' . $this->rel . '" href="' . $this->src . '" type="' . $this->type . '" />';
			
			default:
			
				return 'about:blank';
		}
	}
}
