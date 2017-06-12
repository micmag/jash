<?php

require_once(_PS_ROOT_DIR_ . '/modules/jash/includes/JashHelper.php');

class JashSubpageModuleFrontController extends ModuleFrontController
{  
  
  private $config_vars;
  private $lang_id;
  
  public function __construct() {    
    parent::__construct();
    $this->lang_id = JashHelper::getCurrentLang();
    $this->config_vars = JashHelper::loadConfigVars($this->lang_id);
  }
  
  /**
   * Method responsible for adding css and js
   */
  public function setMedia() {
    parent::setMedia();
    $this->addCSS(_MODULE_DIR_.'jash/views/css/jash.css');
    $this->addCSS(_THEME_CSS_DIR_.'product_list.css');
  }
  
  /**
   * Main method invoked on page load
   */
  public function initContent() {
    parent::initContent();
    
    if(empty($this->config_vars['JASH_CATEGORY'])) {
      $this->setTemplate('notconfigured.tpl');
      return;
    }
    
    $this->context->smarty->assign(array(
      'title' => $this->config_vars['JASH_PAGE_TITLE'],
      'desc' => htmlspecialchars_decode($this->config_vars['JASH_PAGE_DESC']),
      'products' => $this->getProducts()
    ));
    $this->setTemplate('subpage.tpl');
  }
  
  /**
   * Returns loaded info about the products, the list is fully filtered
   */
  private function productsQuery() {
    $query = '
      SELECT DISTINCT p.* , pl.`description_short`, pl.`link_rewrite`, pl.`name`, i.`id_image` AS image_id, t.`rate`
      FROM `'._DB_PREFIX_.'product` p
      LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = ' . $this->lang_id . ')
      LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
      LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = ' . $this->lang_id . ')
      LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = p.`id_tax_rules_group`)
      LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_product` = p.`id_product`)';
    if(!empty($this->config_vars['JASH_ATTRIBUTES'])) {
      $query .= 'LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (pa.`id_product` = p.`id_product`)
      LEFT JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON (pac.`id_product_attribute` = pa.`id_product_attribute`)';
    }
    if(!empty($this->config_vars['JASH_FEATURES'])) {
      $query .= 'LEFT JOIN `'._DB_PREFIX_.'feature_product` fp ON (pa.`id_product` = fp.`id_product`)';
    }
    $query .= 'WHERE cp.`id_category` IN(' . $this->config_vars['JASH_CATEGORY'] . ')';
    if(!empty($this->config_vars['JASH_ATTRIBUTES'])) {
      $query .= 'AND pac.`id_attribute` IN(' . $this->config_vars['JASH_ATTRIBUTES'] . ')';
    }
    if(!empty($this->config_vars['JASH_FEATURES'])) {
      $query .= 'AND fp.`id_feature_value` IN(' . $this->config_vars['JASH_FEATURES'] . ')';
    }
    if(!empty($this->config_vars['JASH_MANUFACTURERS'])) {
      $query .= 'AND p.`id_manufacturer` IN(' . $this->config_vars['JASH_MANUFACTURERS'] . ')';
    }
    $result = Db::getInstance()->ExecuteS($query);
    return Product::getProductsProperties($this->lang_id, $result);
  }
  
  /**
   * Returns ready to use in template list of products
   */
  private function getProducts() {
    $products = array();
    $currencies = CurrencyCore::getCurrencies();
    
    $result = $this->productsQuery();
    foreach($result as $row) {
      $link = new Link;
      $image_path = $link->getImageLink($row['link_rewrite'], $row['image_id'], 'home_default');
      
      $products[] = array(
        'id' => $row['id_product'],
        'image' => $image_path,
        'name' => $row['name'],
        'desc' => $row['description_short'],
        'price' => Tools::displayPrice($row['price_without_reduction'], $currencies[0], true),
        'link' => $row['link']
      );
    }
    return $products;
  }
}