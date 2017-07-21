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
    console.log("planario_table")
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
    })
}