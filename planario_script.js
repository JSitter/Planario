let delete_entry = function( event_id ){
    
    let target = ajaxurl
    let data = {
        'action': 'delete_event',
        'event_id': event_id
    }
    let req = jQuery.post( target, data, function (response){
        update_table(response.data)
    })
}

let update_table = function( html_table ){
    let planario_table = document.getElementById('planario_table')
    jQuery('#planario_table').replaceWith( html_table )
    
}

let add_entry = function(){
    let event_name = jQuery("input[name=event_title]").val()
    let start_time = jQuery("input[name=start_time]").val()
    let end_time = jQuery("input[name=end_time]").val()


    let data = {
        'action': 'add_event',
        'event_name' : event_name,
        'start_time' : start_time,
        'end_time': end_time
    }
    let request = jQuery.post( ajaxurl, data, function(response){
        update_table(response.data)
        jQuery("input[name=event_title").val("")
        jQuery("input[name=start_time]").val("")
        jQuery("input[name=end_time]").val("")
        
    })
}


let edit_entry = function( event_id, field_name, updated_data ){
    let data = {
        'action': 'edit_event',
        'id':   event_id,
        'field': field_name,
        'data': updated_data
    }

    let request = jQuery.post(ajaxurl, data, function(response){
        update_table(response.data)
    })
}


jQuery(document).on("click", function(event){
    //Edit Selected Cell
    if(event.target.getAttribute('class') === "planario_data_item"){
        let id = jQuery(event.target).data('id') 
        let field = jQuery(event.target).data('column')
        let input_value = jQuery(event.target).text()
        let input_field = "<input class='planario_edit_field' data-column='"+field+"' data-id='"+id+"' value='"+input_value+"'>"
        jQuery(event.target).replaceWith(input_field)
        jQuery('input.planario_edit_field').focus()
    }

    //Save edited data
    jQuery('input.planario_edit_field').on('blur', function(event){
        event.stopImmediatePropagation()
        let id = jQuery(event.target).data('id')
        let column = jQuery(event.target).data('column')
        let entry = jQuery(event.target).val()
        edit_entry(id, column, entry)
    })
})




//Submit new record on return/enter key press
jQuery(document).keypress(function(event){
    //add new entry
    if(event.which == 13 && jQuery("input[name=event_title").val() != ''){
        add_entry()
    }

    //edit existing entry if edit input box is in focus
   if(event.which == 13 && jQuery('input.planario_edit_field').is(':focus')){
       event.stopImmediatePropagation()
        let id = jQuery('input.planario_edit_field').data('id')
        let field = jQuery('input.planario_edit_field').data('column')
        let data = jQuery('input.planario_edit_field').val()
        edit_entry(id, field, data)
   }
           
})