var cptChecked = 0;
var tLineSelected = new Array();

/**
 * Enable remove button
 */
function enableBtnRemove() {
    $('#btn-remove').removeClass('disabled').html('<a href="#" onclick="del();">Remove</a>');
}

/**
 * Disable remove button
 */
function disableBtnRemove() {
    $('#btn-remove').addClass('disabled').html('Remove');
}

/**
 * Enable edit button
 */
function enableBtnEdit() {
    $('#btn-edit').removeClass('disabled').html('<a href="#" onclick="edit();">Edit</a>');
}

/**
 * Disable edit button
 */
function disableBtnEdit() {
    $('#btn-edit').addClass('disabled').html('Edit');
}

/**
 * Enable set as bought button
 */
function enableBtnBought() {
    $('#btn-bought').removeClass('disabled').html('<a href="#" onclick="setAsBought();">Set as bought</a>');
}

/**
 * Disable set as bought button
 */
function disableBtnBought() {
    $('#btn-bought').addClass('disabled').html('Set as bought');
}

/**
 * Edit line
 */
function edit() {
    if(tLineSelected.length > 0) {
        var id_shopping_list = tLineSelected[0];
        window.location = "/tedemis/shoppinglist/edit/" + id_shopping_list;
    }
}
/**
 * Use to delete product with ajax
 */
function del() {
    if(confirm('Do you really want to delete this products')) {
    jQuery.ajax({
            type: "POST",
            url: '/tedemis/shoppinglist/ajaxDelete',
            cache: false,
            async: true,
            data:{
                'list[]': tLineSelected
            },
            dataType: "json",
            success: function(dataReceive) {
                if(dataReceive["result"] == "OK") {
                    window.location.reload();
                } else {
                    alert('An error occured. Contact you administrator');
                }
            },
            error: function(){
                alert("An error occured. Contact you administrator or try again later");
            }
        });
    }
}

/**
 * Set as bought selected line(s) using ajax
 */
function setAsBought() {
    if(confirm('Do you really want to set as bought this products')) {
    jQuery.ajax({
            type: "POST",
            url: '/tedemis/shoppinglist/ajaxBought',
            cache: false,
            async: true,
            data:{
                'list[]': tLineSelected
            },
            dataType: "json",
            success: function(dataReceive) {
                if(dataReceive["result"] == "OK") {
                    window.location.reload();
                } else {
                    alert('An error occured. Contact you administrator');
                }
            },
            error: function(){
                alert("An error occured. Contact you administrator or try again later");
            }
        });
    }
}

/**
 * Select line
 */
function checkSelection() {;
    cptChecked = 0; tLineSelected = new Array();;
    $('.entity-check').each(function(py) {;
        var t_id = $(this).attr('id').split('-');
        if($(this).is(":checked")) {
            cptChecked++;
            // Store id line selected
            tLineSelected.push(t_id[1]);
            $('#line-' + t_id[1]).addClass('line-pair');
        } else {
            $('#line-' + t_id[1]).removeClass('line-pair');
        }
    });
    if(cptChecked >= 1) {
        if(cptChecked == 1) enableBtnEdit();
        else disableBtnEdit();
        enableBtnRemove();
        enableBtnBought()

    } else {
        disableBtnEdit();
        disableBtnRemove();
        disableBtnBought();
        
    }
};
