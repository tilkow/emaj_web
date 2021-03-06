<?php

	/*
	 * List tables in a database
	 */

	// Include application functions
	include_once('./libraries/lib.inc.php');

	$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';

	/**
	 * Show default list of tables in the database
	 */
	function doDefault($msg = '') {
		global $data, $conf, $misc, $data;
		global $lang;

		$misc->printHeader('schema', 'schema', 'tables');
		$misc->printMsg($msg);
		$misc->printTitle(sprintf($lang['strtableslist'], $_REQUEST['schema']));

		$tables = $data->getTables();

		$columns = array(
			'table' => array(
				'title' => $lang['strtable'],
				'field' => field('relname'),
				'url'	=> "tblproperties.php?subject=table&amp;{$misc->href}&amp;",
				'vars'  => array('table' => 'relname'),
			),
			'actions' => array(
				'title' => $lang['stractions'],
			),
			'owner' => array(
				'title' => $lang['strowner'],
				'field' => field('relowner'),
			),
			'tablespace' => array(
				'title' => $lang['strtablespace'],
				'field' => field('tablespace')
			),
			'tuples' => array(
				'title' => $lang['strestimatedrowcount'],
				'field' => field('reltuples'),
				'type'  => 'numeric'
			),
			'comment' => array(
				'title' => $lang['strcomment'],
				'field' => field('relcomment'),
			),
		);

		$actions = array(
			'browse' => array(
				'content' => $lang['strbrowse'],
				'attr'=> array (
					'href' => array (
						'url' => 'display.php',
						'urlvars' => array (
							'subject' => 'table',
							'return' => 'schema',
							'table' => field('relname')
						)
					)
				)
			),
		);

		if (!$data->hasTablespaces()) unset($columns['tablespace']);

		$misc->printTable($tables, $columns, $actions, 'tables-tables', $lang['strnotables'], null, array('sorter' => true, 'filter' => true));

	}
	
	/**
	 * Generate XML for the browser tree.
	 */
	function doTree() {
		global $misc, $data;

		$tables = $data->getTables();

		$reqvars = $misc->getRequestVars('table');

		$attrs = array(
			'text'   => field('relname'),
			'icon'   => 'Table',
			'iconAction' => url('display.php',
							$reqvars,
							array('table' => field('relname'))
						),
			'toolTip'=> field('relcomment'),
			'action' => url('redirect.php',
							$reqvars,
							array('table' => field('relname'))
						)
		);

		$misc->printTree($tables, $attrs, 'tables');
		exit;
	}

	if ($action == 'tree') doTree();

	$misc->printHtmlHeader($lang['strtables']);
	$misc->printBody();

	switch ($action) {
		default:
			doDefault();
			break;
	}

	$misc->printFooter();

?>
