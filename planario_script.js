let delete_entry = function( $id ){
    
    let target = ajaxurl
    let data = {
        action: 'delete_event',
        'event_id': $id
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

