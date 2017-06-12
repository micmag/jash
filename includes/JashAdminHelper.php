<?php

class JashAdminHelper
{
  
  public $lang;
  public $categories;
  public $attributes_groups;
  public $attributes;
  public $selected_attributes;
  public $features_groups;
  public $features;
  public $selected_features;
  public $manufacturers;
  
  private $config_vars;
  private $attributes_list;
  private $features_list;
  
  public function __construct($config_vars){
    $this->config_vars = $config_vars;
    $this->setLang();
    $this->getAttributesList();
    $this->getFeaturesList();
    $this->setCategories();
    $this->setAttributesGroups();
    $this->setAttributes();
    $this->setSelectedAttributes();
    $this->setFeaturesGroups();
    $this->setFeatures();
    $this->setSelectedFeatures();
    $this->setManufacturers();
  }
  
  /**
   * Sets the language variable in form of an INT variable
   */  
  private function setLang() {
    $this->lang = (int)$this->config_vars['PS_LANG_DEFAULT'];
  }
  
  /**
   * Sets the rendered category tree with currently selected options
   */
  private function setCategories() {
    $tree_categories_helper = new HelperTreeCategories('categories-treeview');
    $tree_categories_helper->setRootCategory((Shop::getContext() == Shop::CONTEXT_SHOP ? Category::getRootCategory()->id_category : 0))
      ->setSelectedCategories(explode(',', $this->config_vars['JASH_CATEGORY']))
      ->setUseCheckBox(true);
    $this->categories = $tree_categories_helper->render();
  }
  
  /**
   * Sets the list of attributes groups as an array
   */
  private function setAttributesGroups() {
    $attributes_groups = array();
    $query = '
      SELECT agl.* FROM `'._DB_PREFIX_.'attribute_group_lang` agl
      WHERE agl.`id_lang` = ' . $this->lang;
    $result = Db::getInstance()->ExecuteS($query);
    foreach($result as $row) {
      $attributes_groups[$row['id_attribute_group']] = $row['name'];
    }
    $this->attributes_groups = $attributes_groups;
  }
  
  /**
   * Gets the list of all attributes
   */
  private function getAttributesList() {
    $this->attributes_list = Attribute::getAttributes($this->lang);
  }
  
  /**
   * Sets the list of all attributes as an array
   */
  private function setAttributes() {
    $attributes = array();
    foreach($this->attributes_list as $attribute) {
      $attributes[$attribute['id_attribute_group']][$attribute['id_attribute']] = $attribute['name'];
    }
    $this->attributes = $attributes;
  }
  
  /**
   * Sets the list of selected attributes as an array
   */
  private function setSelectedAttributes() {    
    $attributes = array();
    foreach($this->attributes_list as $attribute) {
      $attributes[$attribute['id_attribute']] = array(
        'name' => $attribute['name'],
        'group_name' => $attribute['attribute_group']
      );
    }
    
    $chosen_ids = explode(',', $this->config_vars['JASH_ATTRIBUTES']);
    $filtered = array_intersect_key($attributes, array_flip($chosen_ids));    
    $this->selected_attributes =  $filtered;
  }
  
  /**
   * Sets the list of features as an array
   */
  private function setFeaturesGroups() {
    $features_groups = array();
    foreach(Feature::getFeatures($this->lang) as $feature) {
      $features_groups[$feature['id_feature']] = $feature['name'];
    }
    $this->features_groups = $features_groups;
  }
  
  /**
   * Gets the list of all features
   */
  private function getFeaturesList() {
    $query = '
      SELECT fvl.*, fv.id_feature, fl.name FROM `'._DB_PREFIX_.'feature_value_lang` fvl
      LEFT JOIN `'._DB_PREFIX_.'feature_value` fv ON (fvl.`id_feature_value` = fv.`id_feature_value`)
      LEFT JOIN `'._DB_PREFIX_.'feature_lang` fl ON (fv.`id_feature` = fl.`id_feature`)
      WHERE fvl.`id_lang` = ' . $this->lang;
    $this->features_list = Db::getInstance()->ExecuteS($query);
  }
  
  /**
   * Sets the list of all features values as an array
   */
  private function setFeatures() {
    $features = array();
    foreach($this->features_list as $feature) {
      $features[$feature['id_feature']][$feature['id_feature_value']] = $feature['value'];
    }
    $this->features = $features;
  }
  
  /**
   * Sets the list of all selected features values as an array
   */
  private function setSelectedFeatures() {
    $features = array();
    foreach($this->features_list as $feature) {
      $features[$feature['id_feature_value']] = array(
        'name' => $feature['value'],
        'group_name' => $feature['name']
      );
    }
    
    $chosen_ids = explode(',', $this->config_vars['JASH_FEATURES']);
    $filtered = array_intersect_key($features, array_flip($chosen_ids));    
    $this->selected_features = $filtered;
  }
  
  /**
   * Sets the list of all manufacturers as an array with info which ones are selected
   */
  private function setManufacturers() {
    $manufacturers = array();
    $chosen_ids = explode(',', $this->config_vars['JASH_MANUFACTURERS']);
    $manufacturers_list = Manufacturer::getManufacturers(false, $this->lang, true);
    foreach($manufacturers_list as $manufacturer) {
      $manufacturers[$manufacturer['id_manufacturer']]['name'] = $manufacturer['name'];
      if(in_array($manufacturer['id_manufacturer'], $chosen_ids)) {
        $manufacturers[$manufacturer['id_manufacturer']]['checked'] = 'yes';
      }
    }
    $this->manufacturers = $manufacturers;
  }
}