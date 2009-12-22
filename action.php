<?php
/**
 * Git Plugin
 *
 * This plugin syncs all content of a DokuWiki to a remote git-repository.
 *
 * @author     Zoran Zaric <zz@zoranzaric.de>
 */

if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC.'lib/plugins/');
require_once DOKU_PLUGIN.'action.php';

class action_plugin_git extends DokuWiki_Action_Plugin {

	function getInfo(){
		return array(
        'author' => 'Zoran Zaric',
        'email'  => 'zz@zoranzaric.de',
        'date'   => '2009-12-22',
        'name'   => 'Git',
        'desc'   => 'Syncs a DokuWiki to a Git-Repository',
        'url'    => 'http://zoranzaric.de',
		);
	}

	function register(&$controller) {
		$controller->register_hook('IO_WIKIPAGE_WRITE', 'AFTER', $this, '_handle_io_wikipage_write');
		$controller->register_hook('DOKUWIKI_DONE', 'AFTER', $this, '_handle_dokuwiki_done');
		$controller->register_hook('INDEXER_TASKS_RUN', 'AFTER', $this, '_handle_indexer_tasks_run');
	}

	function _handle_io_wikipage_write(&$event, $param) {
		global $USERINFO;
		$author = $USERINFO['name'] . ' <' . $USERINFO['mail'] . '>';
		$cmd = 'cd ' . DOKU_INC . 'data/;git add .;git commit -am "IO_WIKIPAGE_WRITE: ' . $event->data[2] . ' saved" --author "' . $author . '";git push master master';
		shell_exec($cmd);
	}

	function _handle_dokuwiki_done(&$event, $param) {
		global $USERINFO;
		$author = $USERINFO['name'] . ' <' . $USERINFO['mail'] . '>';
		$cmd = 'cd ' . DOKU_INC . 'data/;git add .;git commit -am "DOKUWIKI_DONE" --author "' . $author . '";git push master master';
		shell_exec($cmd);
	}

	function _handle_indexer_tasks_run(&$event, $param) {
		global $USERINFO;
		$author = $USERINFO['name'] . ' <' . $USERINFO['mail'] . '>';
		$cmd = 'cd ' . DOKU_INC . 'data/;git add .;git commit -am "INDEXER_TASKS_RUN" --author "' . $author . '";git push master master';
		shell_exec($cmd);
	}
}