<?php

require_once dirname(__FILE__) .'/dezyna/base.php';

// You should define and include your own Dezyna subclass otherwise we fall back to the default.
if ( ! class_exists('Dezyna')) {
	require_once dirname(__FILE__) .'/dezyna.php';
}