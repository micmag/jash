<?php   
if (!defined('_PS_VERSION_'))
  exit;

require_once(__DIR__ . '/includes/JashHelper.php');
require_once(__DIR__ . '/includes/JashAdminHelper.php');

class Jash extends Module
{
  
  public function __construct() {
    $this->name = 'Jash';
    $this->tab = 'custom_features';
    $this->version = '1.0.0';
    $this->author = 'Michał Magdziarek';
    $this->need_instance = 0;
    $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_); 
    $this->bootstrap = true;
 
    parent::__construct();
 
    $this->displayName = $this->l('Jash application module');
    $this->description = $this->l('Module written as a part of job recruitment process.');
 
    $this->confirmUninstall = $this->l('Are you sure you want to uninstall Jash module?');
  }
  
  /**
   * Install the module method
   */
  public function install() {
    if (!parent::install())
      return false;
    
    if(Shop::isFeatureActive())
      Shop::setContext(Shop::CONTEXT_ALL);

    foreach(JashHelper::getConfigVars() as $variable_name) {
      Configuration::updateValue($variable_name, '');
    }    
    $this->_clearCache('*');
    return (parent::install() && $this->registerHook('moduleRoutes'));
  }
  
  /**
   * Uninstall the module method
   */
  public function uninstall() {
    foreach(JashHelper::getConfigVars() as $variable_name) {
      Configuration::deleteByName($variable_name);
    }
    $this->_clearCache('*');
    return parent::uninstall();
  }
  
  /**
   * Generating subpage on install
   */
  public function hookModuleRoutes($params){
    return array(
      'module-jash-subpage' => array(
        'controller' => 'subpage',
        'rule' => 'jash-subpage',
        'keywords' => array(),
        'params' => array(
          'fc' => 'module',
          'module' => 'jash'
        )
      )
    );
  }
  
  /**
   * Main method invoked on page load
   */
  public function getContent() {
    $output = '';    
    if(Tools::isSubmit('submitJashconfig'))
      $output .= $this->processForm();
    
    $output .= $this->configForm();
    return $output;
  }
  
  /**
   * Returns the configuration form HTML to be displayed
   */
  private function configForm() {
    $config_vars = JashHelper::loadConfigVars();
    $admin_helper = new JashAdminHelper($config_vars);
    
    $this->context->smarty->assign(array(
      'js_path' => _PS_JS_DIR_,
      'module_path' => _MODULE_DIR_ . strtolower($this->name) . '/',
      'theme_css_path' => _THEME_CSS_DIR_,
      'admin_path' => _PS_ADMIN_DIR_,
      'lang' => $admin_helper->lang,
      'lang_iso' => Language::getIsoById($admin_helper->lang),
      'categories_tree' => $admin_helper->categories,
      'attributes_groups' => $admin_helper->attributes_groups,
      'attributes' => $admin_helper->attributes,
      'selected_attributes' => $admin_helper->selected_attributes,
      'features_groups' => $admin_helper->features_groups,
      'features' => $admin_helper->features,
      'selected_features' => $admin_helper->selected_features,
      'manufacturers' => $admin_helper->manufacturers,
      'title' => $config_vars['JASH_PAGE_TITLE'],
      'desc' => htmlspecialchars_decode($config_vars['JASH_PAGE_DESC']),
      'submit_path' => $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules')
    ));
    return $this->display(__FILE__, '/views/templates/admin/jashconfig.tpl');
  }
  
  /**
   * Gets invoked when the user submits the admin form.
   * Validates and updates all the fields.
   */
  private function processForm() {    
    $output = '';    
    $text_inputs = array(
      'JASH_PAGE_TITLE' => array('value' => Tools::getValue('page_title'), 'default' => '', 'html' => false),
      'JASH_PAGE_DESC' => array('value' => Tools::getValue('page_description'), 'default' => '', 'html' => true)
    );    
    $array_inputs = array(
      'JASH_CATEGORY' => array('value' => Tools::getValue('categoryBox'), 'default' => array()),
      'JASH_ATTRIBUTES' => array('value' => Tools::getValue('attribute_combination_list'), 'default' => array()),
      'JASH_FEATURES' => array('value' => Tools::getValue('feature_combination_list'), 'default' => array()),
      'JASH_MANUFACTURERS' => array('value' => Tools::getValue('manufacturer'), 'default' => array())
    );
    
    if(empty($array_inputs['JASH_CATEGORY']['value']))
      return $this->displayError($this->l('You have to choose at least one category'));
    
    foreach($text_inputs as $field_name => $input) {
      $value = !empty($input['value']) ? $input['value'] : $input['default'];
      if($input['html'] == true)
        $value = htmlspecialchars($value);
      Configuration::updateValue($field_name, $value);
    }
    foreach($array_inputs as $field_name => $input) {
      $value = !empty($input['value']) ? $input['value'] : $input['default'];
      Configuration::updateValue($field_name, implode(',', $value));
    }
    
    return $this->displayConfirmation($this->l('Settings has been updated'));    
  }
  
}