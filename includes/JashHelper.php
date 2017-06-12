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
  
  static public function getCurrentLang() {
    global $cookie;
    return $cookie->id_lang;
  }
  
  static public function getLanguagesList($shop_id) {
    $langValues = array();
    $languages = Language::getLanguages(true, $shop_id);
    foreach($languages as $language) {
      $langValues[$language['id_lang']] = $language['name'];
    }
    return $langValues;
  }
  
  static public function loadConfigVars($lang_id) {
    $vars = self::getConfigVars();
    $config_vars = Configuration::getMultiple($vars, $lang_id);
    return $config_vars;
  }
  
}