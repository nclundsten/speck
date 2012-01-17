function doSort(){
    $('.list-items').sortable({ 
        opacity: 0.6, 
        placeholder: 'sortable-placeholder', 
        handle: '.sort-handle', 
        axis: 'y', 
        update: function() {
            //var order = $(this).sortable("serialize") + '&action=updateRecordsListings' 
            //$.post("updateDB.php", order, function(theResponse){
            //    $("#contentRight").html(theResponse)
            //}) 															 
        }
    })
}       

function entitySearch(trigger){
    var data = {
        className: $(trigger).attr('className'),
        parentId:  $(trigger).attr('parentId'),
        value:     $(trigger).val(),
    }
    $.post("/catalogmanager/entity-search", data, function(html) {
        populateModal(html, 'Results')
        targetListItems(trigger); showModal(); popovers(); 
    })
}

function targetListItems(trigger){
    clearTarget(); 
    $(trigger).parentsUntil('.list-wrap').parent().children('.list-items').first().addClass('target')
}

function getBoundary(ele){ return $(ele).parentsUntil('.boundary').parent() }
function showModal(){ $('#modal-box').modal('show') }
function hideModal(){ $('#modal-box').modal('hide') }
function clearTarget(){ $('.target').removeClass('target') }
function popovers(){ $("a[rel=popover]").popover({offset: 10}) }
function populateModal(html, title){ $('.modal-header h3').text(title); $('.modal-body').html(html) }



function appear(){
    initialCollapse()
    $('.target').children().last().hide().addClass('imported').slideDown(200, function(){
        $(this).removeClass('imported', 200)
    })
    $('.target').parent().prev('.import-one').removeClass('hide') //display the stuff that goes with the imported
    $('.target').next('.remove').remove()
    clearTarget()
    doSort();
}

function appendPartial(ele){
    alert('not this');
    targetListItems(ele)
    $(ele).parentsUntil('.list-wrap').parent().children('.hide').first().clone().removeClass('hide') // clone
          .appendTo($('.target'))       // append it
    appear()
}   

function newPartial(ele){
    targetListItems(ele);
    var data = {
        isNew:       1,
        className:   $(ele).attr('className'), 
        parentId:    $(ele).attr('parentId'),
        parentClassName: $(ele).attr('parentClassName'),
    }
    $.post("/catalogmanager/fetch-partial", data, function(html) {
        $('#modal-box').modal('hide');
        $('.target').append(html);
        appear();
    }); 
};

function liveFormChanged(trigger){
    var title = getBoundary(trigger).children('.entity-header').find('.entity-title')
    $(title).addClass('notice')
}

function entityOptions(trigger){
    $.get("/catalogmanager/entity-options-ajax", function(html) {
        $('#myMenu').html(html).data('trigger', trigger);
    })   
    $('#myMenu').css({ top: $(trigger).offset().top+10, left: $(trigger).offset().left+20 }).show()
}
$('#myMenu').find('.menuItem').live('click', function(){
   executeOption($(this).attr('id'), $('#myMenu').data('trigger'));
   $('#myMenu').hide();
})

function executeOption(option, trigger){
    if(option === 'save'){
        var form = $(trigger).parents('.boundary').first().find('form');
        var parts = form.attr('id').split('-');
        $.post('/catalogmanager/update-record?className='+parts[0]+'&id='+parts[1], form.serializeArray(), function(){
            $(trigger).removeClass('notice')
        })
    }
};

function initialCollapse(){
    $('.initialCollapse').toggleClass('ui-icon-triangle-1-s ui-icon-triangle-1-e')
        .removeClass('initialCollapse').parent().parent().siblings().hide()
}    

$('.modal-search-result').live('click', function(){
    var data = { 
        parentId: $(this).attr('parentid'), 
        entityId: $(this).attr('entityId'), 
        className: $(this).attr('className')
    }
    $.post("/catalogmanager/fetch-partial", data, function(html) {
            hideModal();
            $('.target').append(html)
            appear()
    })
})  

$('.collapser').live("click", function(){ collapse(this) })
function collapse(trigger){
    $(trigger).toggleClass('ui-icon-triangle-1-s ui-icon-triangle-1-e').parent().parent().siblings().slideToggle()
}

$('.live-form input, .live-form textarea, .live-form select').live('change', function(){ liveFormChanged(this); })
$('.addButton').live('click', function(){ newPartial(this) })
$('.addButtonAjax').live('click', function(){ appendPartialAjax(this) })

$('.import-modal').live("change", function(){
    entitySearch($(this)) 
    $(this).val('')
})

$('.entity-header').live({
    mouseenter:function(){$(this).children().find('.remover').removeClass('hide')},
    mouseleave:function(){$(this).children().find('.remover').addClass('hide')}
})

$('.entity-title').live('contextmenu', function(e){
    e.preventDefault();
    entityOptions(this);
});

$('.remover').live("dblclick", function(){
    getBoundary($(this)).addClass('deported').fadeOut(function(){$(this).remove()})
})

$('.import-one-modal').live("change", function(){
    entitySearch($(this)) 
    $(this).val('')
})  

$(document).ready(function(){
    initialCollapse()
    doSort()
})   
