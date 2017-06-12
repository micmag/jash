<?php

class JashHelper
{
 
  static public function getConfigVars() {
    return array(
      'JASH_CATEGORY',
      'JASH_ATTRIBUTES',
      'JASH_FEATURES',
      'JASH_MANUFACTURERS',
      'JASH_PAGE_TITLE',
      'JASH_PAGE_DESC'
    );
  }
  
  static public function loadConfigVars() {
    $vars = self::getConfigVars();
    $vars[] = 'PS_LANG_DEFAULT';
    $config_vars = Configuration::getMultiple($vars);
    return $config_vars;
  }
  
}