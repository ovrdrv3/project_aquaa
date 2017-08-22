function build(mode, scope, rowID = null){

    // If arg 2, rowID, is missing, associate whatever is going to be loaded with the whole table.
    if (rowID) {
        idForQuery = rowID.replace('plant', '');
        $.get('log.php?mode=' + mode + '&scope=' + scope + '&id=' + idForQuery, function(data) {
            $('#' + rowID).replaceWith(data);
            $('#list-of-plants').find('#' + rowID).each(setRowAttributes);
        });

    } else {
        $('#list-of-plants').load('log.php?mode=' + mode + '&scope=' + scope , function(){
            $('#list-of-plants').find(' > tr').each(setRowAttributes);
        })
    }
}

function setRowAttributes() {
    // console.log("setRowAttributes started");
    var trClass = $(this).attr('class');
    var trID = $(this).attr('id').replace('plant', '');
    $(this)
    // remove class after it has been set to a variable.
    .removeClass(trClass)
    .addClass('loaded-for-animation')
    .mouseover(function(){
            $(this).addClass(trClass);
        })
    .mouseout(function(){
            var tempThis = $(this)
            setTimeout(function(){
                tempThis.removeClass(trClass);
            }, 2000)
        })
    .find(' > td > button.water-button')
    .addClass(trClass)
    .click(function(e){
        e.preventDefault();

        $.ajax({
            // Update function
            url:'log.php?mode=water',
            data: {id : trID},
            success: function(data){
                build('build','row' + edit, 'plant' + trID);
                // console.log(data);
            }
        });
    })
}
//declare global variable - edit
edit = "";

$( document ).ready(function() {
    var editToggle = false;
    $("#new-plant-tr").hide();

    build('build','all');

    $("#edit_button_row").on('click', function(){
        $("#new-plant-tr").toggle();
        // globals for the rest of the scope
        editToggle = !editToggle;
        edit = (editToggle) ? "_edit":"";
        build('build','all' + edit);
    });

    $("#list-of-plants").on('click', '.remove-plant-button', function(){
        var id = $(this).data('id');
        $.ajax({
            url: 'log.php?mode=remove_plant&id=' + id,
            success: function() {
                console.log("plant removed");
                build('build','all' + edit);
            },
            error: function (jqXHR, textStatus, errorThrown){
                // Log the error to the console
                console.error(
                    "The following error occurred: "+
                    textStatus, errorThrown
                );
            }
        })
    });


    $("#list-of-plants").on('click', '.add-day-button, .remove-day-button', function(){
        var id = $(this).data('id');
        var direction = $(this).data('direction');
        $.ajax({
            url: 'log.php?mode=modify_frequency&direction=' + direction + '&id=' + id,
            success: function() {
                build('build','row' + edit,'plant' + id );
            },
            error: function (jqXHR, textStatus, errorThrown){
                // Log the error to the console
                console.error(
                    "The following error occurred: "+
                    textStatus, errorThrown
                );
            }
        })
    });

    var request;
    // Add plant AJAX
    $("#new-plant-form").submit(function(e){
        e.preventDefault();

        // Abort any pending request
        if (request) {
            request.abort();
        }
        var $form = $(this);
        var $inputs = $form.find("input, select, button");
        var serializedData = $form.serialize();
        // console.log(serializedData);

        // Disable the inputs for the duration of the Ajax request.
        $inputs.prop("disabled", true);

        request = $.ajax({
            url: "log.php?mode=add_plant",
            type: "post",
            data: serializedData
        });

        request.done(function (response, textStatus, jqXHR){
            // response is the db id of the newly created plant
            var newID = "plant" + response;

            $('#list-of-plants').append("<tr id='" + newID + "'></tr>");
            if($("#no-plants-placeholder").length > 0) {
              $("#no-plants-placeholder").remove();
            }

            build('build','row_edit', newID);
            $form.each(function(){
                this.reset();
            });

            $('#new-plant-form-name').focus();
        });

        request.fail(function (jqXHR, textStatus, errorThrown){
            console.error(
                "The following error occurred: " +
                textStatus, errorThrown
            );
        });

        request.always(function () {
            // Reenable the inputs
            $inputs.prop("disabled", false);
        });

    }); // end of submit function for the form
});
