(function($){
    
    var chosen_attrs = new Array();
    var chosen_features = new Array();
    
    populate_options = function(type) {        
        var group_select_id, name_select_id, options_array;
        switch(type) {
            case 'attr':
                group_select_id = 'attribute_group';
                name_select_id = 'attribute';
                options_array = attrs;
                break;
            case 'feature':
                group_select_id = 'feature_group';
                name_select_id = 'feature';
                options_array = features;
                break;
        }
        var group_select = document.getElementById(group_select_id);
        if (!group_select)
            return;
        var name_select = document.getElementById(name_select_id);
        var number = group_select.options.length ? group_select.options[group_select.selectedIndex].value : 0;

        if (!number) {
            name_select.options.length = 0;
            name_select.options[0] = new Option('---', 0);
            return;
        }

        var list = options_array[number];
        name_select.options.length = 0;
        if (typeof list !== 'undefined') {
            for(i = 0; i < list.length; i += 2)
                name_select.options[i / 2] = new Option(list[i + 1], list[i]);
        }
    }

    add_attr = function() {
        var attr_group = $('#attribute_group option:selected');
        if (attr_group.val() == 0)
            return jAlert(msg_no_attr);

        var attr_name = $('#attribute option:selected');
        if (attr_name.val() == 0)
            return jAlert(msg_no_val);
        
        if($.inArray(attr_name.val(), chosen_attrs) !== -1)
            return jAlert(msg_duplicate_val);        

        chosen_attrs.push(attr_name.val());        
        $('<option></option>')
                .attr('value', attr_name.val())
                .attr('groupid', attr_group.val())
                .text(attr_group.text() + ' : ' + attr_name.text())
                .appendTo('#product_att_list');
    }

    del_attr = function() {
        $('#product_att_list option:selected').each(function() {
            chosen_attrs.splice($.inArray($(this).val(), chosen_attrs), 1);
            $(this).remove();
        });
    }
    
    add_feature = function() {
        var feature_group = $('#feature_group option:selected');
        if (feature_group.val() == 0)
            return jAlert(msg_no_feature);

        var feature_name = $('#feature option:selected');
        if (feature_name.val() == 0)
            return jAlert(msg_no_val);
        
        if($.inArray(feature_name.val(), chosen_features) !== -1)
            return jAlert(msg_duplicate_val);        

        chosen_features.push(feature_name.val());        
        $('<option></option>')
                .attr('value', feature_name.val())
                .attr('groupid', feature_group.val())
                .text(feature_group.text() + ' : ' + feature_name.text())
                .appendTo('#product_feature_list');
    }
    
    del_feature = function() {
        $('#product_feature_list option:selected').each(function() {
            chosen_features.splice($.inArray($(this).val(), chosen_features), 1);
            $(this).remove();
        });
    }
    
    init_selected_options = function() {
        $('#product_att_list option').each(function() {
            chosen_attrs.push($(this).val()); 
        });
        $('#product_feature_list option').each(function() {
            chosen_features.push($(this).val()); 
        });
    }
    
    $('#submit-jash').click(function() {
        $('#product_att_list option').prop('selected', true);
        $('#product_feature_list option').prop('selected', true);
    });
    init_selected_options();
    populate_options('attr');
    populate_options('feature');
    
})(jQuery)