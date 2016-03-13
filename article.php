<?php

define('TAB_ID', 5);
define('PAGE_ID', 17);
require_once 'inc/global.php';

$articleData = null;

if(isset($_GET['mostRecent'])) {
	$getData = $db->Query('SELECT * FROM site_news ORDER BY timestamp DESC LIMIT 1');
	
	if($db->NumRows($getData) > 0) {
		$articleData = $db->FetchAssoc($getData);
	}
} elseif(isset($_GET['rel'])) {
	$rel = $_GET['rel'];
	
	if(strrpos($rel, '-') >= 1) {
		$bits = explode('-', $rel);
		$id = $bits[0];
		
		$getData = $db->Query('SELECT * FROM site_news WHERE id = "' . $id . '" LIMIT 1');
		
		if($db->NumRows($getData) > 0) {
			$articleData = $db->FetchAssoc($getData);
		}
	}
}

$tpl = new Tpl();

$tpl->addTemplate('head-init');

$tpl->addIncludeSet('generic');
$tpl->writeIncludeFiles();

$tpl->addTemplate('head-overrides-generic');
$tpl->addTemplate('head-bottom');
$tpl->addTemplate('generic-top');
	
$tpl->write('<div id="column1" class="column">');

if(isset($_GET['archiveMode'])) {
	$tpl->setParam('mode', 'archive');
} elseif(isset($_GET['category']) && is_numeric($_GET['category'])) {
	$tpl->setParam('mode', 'category');
	$tpl->setParam('category_id', $_GET['category']);
} else {
	$tpl->setParam('mode', 'recent');
}

$tpl->addTemplate('comp-newslist');

$tpl->write('</div>');

$tpl->write('<div id="column2" class="column">');

if ($articleData != null) {
	$tpl->setParam('news_article_id', $articleData['id']);
	$tpl->setParam('news_article_title', clean($articleData['title']));
	$tpl->setParam('news_article_date', 'Posted ' . clean($articleData['datestr']));
	$tpl->setParam('news_category', '<a href="/articles/category/' . $articleData['category_id'] . '">' . clean(mysql_result(dbquery("SELECT caption FROM site_news_categories WHERE id = '" . $articleData['category_id'] . "' LIMIT 1"), 0)) . '</a>');
	$tpl->setParam('news_article_summary', clean($articleData['snippet']));
	$tpl->setParam('news_article_body', clean($articleData['body'], true));
	
	$tpl->setParam('page_title', 'News - ' . clean($articleData['title']));
} else {
	$tpl->setParam('news_article_id', 0);
	$tpl->setParam('news_article_title', 'News article not found');
	$tpl->setParam('news_article_date', '');
	$tpl->setParam('news_category', '');
	$tpl->setParam('news_article_summary', '');
	$tpl->setParam('news_article_body', "The article you were looking for could not be retrieved. Please press the 'back' button on your browser to return to your previous page.");	
	
	$tpl->setParam('page_title', 'News - News Article not found');
}

$tpl->addTemplate('comp-newsarticle');
$tpl->write('</div>');

$tpl->addTemplate('generic-column3');
$tpl->addTemplate('footer');

$tpl->setParam('body_id', 'news');

echo $tpl;