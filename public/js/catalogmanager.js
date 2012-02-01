function doSort(){
    $('.list-items').sortable({ 
        opacity: 0.6, 
        placeholder: 'sortable-placeholder', 
        handle: '.sort-handle', 
        axis: 'y',          
        forcePlaceholderSize: true,
        update: function() {
            //var order = $(this).sortable("serialize") + '&action=updateRecordsListings' 
            //$.post("updateDB.php", order, function(theResponse){
            //    $("#contentRight").html(theResponse)
            //}) 															 
        }
    })
}       

//search the corresponding table(class) for rows matching the input text
function searchClass(trigger){
    var data = {
        className:       $(trigger).attr('className'),
        parentClassName: $(trigger).attr('parentClassName'),
        parentId:        $(trigger).attr('parentId'),
        value:           $(trigger).val(),
    }
    $.post("/catalogmanager/search-class", data, function(html) {
        populateModal(html, 'Results')
        targetListItems(trigger)
        showModal()
        popovers() 
    })
}


//search all classes for rows matching the input text
function searchClasses(trigger){
    var data = {
        value:     $(trigger).val(),
    }
    $.post("/catalogmanager/search-classes", data, function(html) {
        populateModal(html, 'Results')
        showModal()
        popovers()
    })
}

function populateModal(html, title){ 
    $('.modal-header h3').text(title) 
        $('.modal-body').html(html) 
}

//do some fancy stuff to make the 'target' class appear 
function appear(){
    $('.target').children().last().hide().addClass('appearing').slideDown(200, function(){
        $(this).removeClass('appearing', 200)
    })
    $('.remove').remove()
    clearTarget()
    doSort()
}

//request a new partial for corresponding class, then append it to the list
function newPartial(ele){
    targetListItems(ele)
    var data = {
        isNew:       1,
        className:   $(ele).attr('className'), 
        parentId:    $(ele).attr('parentId'),
        parentClassName: $(ele).attr('parentClassName'),
    }
    $.post("/catalogmanager/fetch-partial", data, function(html) {
        $('#modal-box').modal('hide')
        $('.target').append(html)
        appear()
    }) 
}

function executeOption(option, trigger){
    if(option === 'save'){
        var form = getForm(trigger)
        var parts = form.attr('id').split('-')
        targetTitle(trigger)
        $.post('/catalogmanager/update-record?className='+parts[0]+'&id='+parts[1], form.serializeArray(), function(title){
            $('.target').html('&nbsp; '+title)
        })
    }
}

function fetchPartial(ele){
    var data = { 
        parentId: $(ele).attr('parentid'), 
        entityId: $(ele).attr('entityId'), 
        className: $(ele).attr('className'),
        parentClassName: $(ele).attr('parentClassName')
    }
    $.post("/catalogmanager/fetch-partial", data, function(html) {
            hideModal()
            $('.target').append(html)
            appear()
    })
}  

function remove(ele){
    var parts = getForm($(ele)).attr('id').split('-')
    if($(ele).attr('parentId')){
        var action = 'unlink'
    }else{
        var action = 'delete'
    }
    var data = {
        model:  parts[0],
        id:     parts[1],
        action: action,
    }
    $.post('/catalogmanager/remove', data, function(response){
        console.log(response)
    })
    getBoundary($(ele)).addClass('removing').fadeOut(function(){$(ele).remove()})
}

function collapseRecursively(ele, action){
    var headers = getBoundary(ele).children().find('.entity-header')
    var collapsing = $(headers).siblings('.entity-content')
    var collapsers = $(headers).find('.collapser')
    if('expand' === action){
        $(collapsing).show()
        var add = 'ui-icon-triangle-1-s'
        var rem = 'ui-icon-triangle-1-e'
        $(collapsers).removeClass(rem).addClass(add)
    }else{
        $(collapsing).hide()
        var add = 'ui-icon-triangle-1-e'
        var rem = 'ui-icon-triangle-1-s' 
        $(collapsers).removeClass(rem).addClass(add)
    }
}

function targetListItems(trigger){
    clearTarget()
    $(trigger).parentsUntil('.list-wrap').parent().children('.list-items').first().addClass('target')
}

function targetTitle(ele){
    clearTarget()
    getBoundary(ele).find('.title').first().addClass('target');
}

function collapse(trigger){
    getBoundary(trigger).find('span.collapser').first().toggleClass('ui-icon-triangle-1-s ui-icon-triangle-1-e').parent().parent().siblings().slideToggle(100)
}

function initialCollapse(){
    $('.initialCollapse').toggleClass('ui-icon-triangle-1-s ui-icon-triangle-1-e')
        .removeClass('initialCollapse').parent().parent().siblings().hide()
}    

function clearTarget(){ $('.target').removeClass('target') }

function showModal(){ $('#modal-box').modal('show') }

function hideModal(){ $('#modal-box').modal('hide') }

function popovers(){ $("a[rel=popover]").popover({offset: 10}) }
    
function getBoundary(ele){ return $(ele).parentsUntil('.boundary').parent().first() }

function getForm(ele){ return getBoundary(ele).find('form').first() }

$('.collapser').live("click", function(){ collapse(this) })

$('.live-form input, .live-form textarea, .live-form select').live('change', function(){ executeOption('save', this) })

$('.addButton').live('click', function(){ newPartial(this) })

$('.modal-search-result').live('click', function(){ fetchPartial(this) })

$('.expand-all').live('click', function(){
    collapseRecursively(this, 'expand')    
})

$('.collapse-all').live('click', function(){
    collapseRecursively(this)    
})

$('.import-modal').live("change", function(){
    searchClass($(this)) 
    $(this).val('')
    return false
})                                                                                   

$('.find-modal').live("change", function(){
    searchClasses($(this)) 
    $(this).val('')
}) 

$('.entity-header').live({
    mouseenter:function(){$(this).children('.remover').children().removeClass('hide')},
    mouseleave:function(){$(this).children('.remover').children().addClass('hide')}
})

$('.live-form').live({
    mouseenter:function(){$(this).css({opacity: 1.0})},
    mouseleave:function(){$(this).css({opacity: 0.7})},
})

$('.list-wrap').live({
    mouseenter:function(){$(this).children('.list-items-helper').animate({opacity: 1.0}, 100)},
    mouseleave:function(){$(this).children('.list-items-helper').animate({opacity: 0.2}, 100)},
})


$('.remover').live("dblclick", function(){ remove(this) })

$('.import-one-modal').live("change", function(){
    entitySearch($(this)) 
    $(this).val('')
})  

$(document).ready(function(){
    initialCollapse()
    doSort()
})   
