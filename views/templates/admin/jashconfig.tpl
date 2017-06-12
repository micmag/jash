<form id="jash_config" class="defaultForm form-horizontal Jash" method="post" action="{$submit_path}">
    
    <div class="panel">
        
        <div class="panel-heading">{l s='Settings'}</div>
        
        <div class="form-wrapper">
            
            <div class="form-group">
                <label class="control-label col-lg-3 required">
                    <span class="label-tooltip" data-html="true" data-toggle="tooltip" data-original-title="{l s='Please choose a language.'}">{l s='Language'}</span>
                </label>
                <div class="col-lg-9">
                    <select name="language_choice" id="language_choice">
                        {foreach from=$lang_list key=lang_id item=lang_name}
                            <option value="{$lang_id}">{$lang_name|escape:'html':'UTF-8'}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
                    
            <hr />
            
            <div class="form-group">
                <label class="control-label col-lg-3 required">
                    <span class="label-tooltip" data-html="true" data-toggle="tooltip" data-original-title="{l s='Please choose a category.'}">{l s='Product categories'}</span>
                </label>
                <div class="col-lg-9">{$categories_tree}</div>
            </div>
            
            <hr />
            
            <div class="form-group">
                <div class="form-group">
                    <label class="control-label col-lg-3" for="attribute_group">
                        <span class="label-tooltip" data-html="true" data-toggle="tooltip" data-original-title="{l s='Select an attribute.'}">{l s='Attribute'}</span>
                    </label>
                    <div class="col-lg-6">
                        <select name="attribute_group" id="attribute_group" onchange="populate_options('attr');">
                            {if isset($attributes_groups)}
                                {foreach from=$attributes_groups key=attr_group_id item=attr_group_name}
                                    <option value="{$attr_group_id}">{$attr_group_name|escape:'html':'UTF-8'}</option>
                                {/foreach}
                            {/if}
                        </select>
                    </div>
                </div>
                <div class="row">
                    <label class="control-label col-lg-3" for="attribute">
                        <span class="label-tooltip" data-html="true" data-toggle="tooltip" data-original-title="{l s='Select a value for the attribute.'}">{l s='Value'}</span>
                    </label>
                    <div class="col-lg-9">
                        <div class="form-group">
                            <div class="col-lg-8">
                                <select name="attribute" id="attribute">
                                    <option value="0">-</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <button type="button" class="btn btn-default btn-block" onclick="add_attr();"><i class="icon-plus-sign-alt"></i> {l s='Add'}</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-8">
                                <select id="product_att_list" name="attribute_combination_list[]" multiple="multiple" >
                                    {foreach from=$selected_attributes key=attr_id item=attr}
                                        <option value="{$attr_id}">{$attr.group_name|escape:'html':'UTF-8'} : {$attr.name|escape:'html':'UTF-8'}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <button type="button" class="btn btn-default btn-block" onclick="del_attr()"><i class="icon-minus-sign-alt"></i> {l s='Delete'}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                            
            <hr />
            
            <div class="form-group">
                <div class="form-group">
                    <label class="control-label col-lg-3" for="feature_group">
                        <span class="label-tooltip" data-html="true" data-toggle="tooltip" data-original-title="{l s='Select a feature.'}">{l s='Feature'}</span>
                    </label>
                    <div class="col-lg-6">
                        <select name="feature_group" id="feature_group" onchange="populate_options('feature');">
                        {if isset($features_groups)}
                                {foreach from=$features_groups key=feature_group_id item=feature_group_name}
                                    <option value="{$feature_group_id}">{$feature_group_name|escape:'html':'UTF-8'}</option>
                                {/foreach}
                        {/if}
                        </select>
                    </div>
                </div>
                <div class="row">
                    <label class="control-label col-lg-3" for="feature">
                        <span class="label-tooltip" data-html="true" data-toggle="tooltip" data-original-title="{l s='Select a value for the feature.'}">{l s='Value'}</span>
                    </label>
                    <div class="col-lg-9">
                        <div class="form-group">
                            <div class="col-lg-8">
                                <select name="feature" id="feature">
                                    <option value="0">-</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <button type="button" class="btn btn-default btn-block" onclick="add_feature();"><i class="icon-plus-sign-alt"></i> {l s='Add'}</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-8">
                                <select id="product_feature_list" name="feature_combination_list[]" multiple="multiple" >
                                    {foreach from=$selected_features key=feature_id item=feature}
                                        <option value="{$feature_id}">{$feature.group_name|escape:'html':'UTF-8'} : {$feature.name|escape:'html':'UTF-8'}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <button type="button" class="btn btn-default btn-block" onclick="del_feature()"><i class="icon-minus-sign-alt"></i> {l s='Delete'}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr />
            
            <div class="form-group">
                <label class="control-label col-lg-3">
                    <span class="label-tooltip" data-html="true" data-toggle="tooltip" data-original-title="{l s='Please choose manufacturers.'}">{l s='Manufacturers'}</span>
                </label>
                <div class="col-lg-9">
                    {foreach from=$manufacturers key=manufacturer_id item=manufacturer}
                        <div>
                            <input type="checkbox" name="manufacturer[]" value="{$manufacturer_id}" {if isset($manufacturer.checked)}checked{/if}></input>
                            <label>{$manufacturer.name}</label>
                        </div>
                    {/foreach}
                </div>
            </div>
            
            <hr />
        
            <div class="form-group">
                <label class="control-label col-lg-3">
                    <span class="label-tooltip" data-html="true" data-toggle="tooltip" data-original-title="{l s='You can specify your page title here.'}">{l s='Page title'}</span>
                </label>
                <div class="col-lg-9">
                    <input type="text" name="page_title" size="20" value="{$title}"></input>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3">
                    <span class="label-tooltip" data-html="true" data-toggle="tooltip" data-original-title="{l s='You can add some text here that will be visible on your subpage.'}">{l s='Page description'}</span>
                </label>
                <div class="col-lg-9">
                    <textarea id="page_description" class="rte autoload_rte" name="page_description">{$desc}</textarea>
                </div>
            </div>
        
        </div>
                
        <div class="panel-footer">
            <button id="submit-jash" type="submit" name="submitJashconfig" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save'}</button>
        </div>
            
    </div>
    
</form>

<script type="text/javascript" src="{$js_path}tiny_mce/tinymce.min.js"></script>
<script type="text/javascript" src="{$js_path}admin/tinymce.inc.js"></script>
<script type="text/javascript">
    var msg_no_attr = '{l s='Please choose an attribute.'}';
    var msg_no_feature = '{l s='Please choose a feature.'}';
    var msg_no_val = '{l s='Please choose a value.'}';
    var msg_duplicate_val = '{l s='This value has already been chosen.'}';
    var attrs = new Array();
    attrs[0] = new Array(0, "---");
    {foreach from=$attributes key=idgrp item=group}
            attrs[{$idgrp}] = new Array(0
            , '---'
            {foreach from=$group key=idattr item=attrname}
                    , {$idattr|strval|json_encode}, {$attrname|trim|json_encode}
            {/foreach}
            );
    {/foreach}
        
    var features = new Array();
    features[0] = new Array(0, "---");
    {foreach from=$features key=idgrp item=group}
            features[{$idgrp}] = new Array(0
            , '---'
            {foreach from=$group key=idfeature item=featurename}
                    , {$idfeature|strval|json_encode}, {$featurename|trim|json_encode}
            {/foreach}
            );
    {/foreach}
        
    var iso = '{$lang_iso}';
    var pathCSS = '{$theme_css_path}';
    var ad = '{$admin_path}';
    tinySetup({
        editor_selector: "autoload_rte"
    });
</script>
<script type="text/javascript" src="{$module_path}views/js/admin/admin.js"></script>