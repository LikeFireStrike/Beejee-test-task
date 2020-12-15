import Task from './task.js'

(function ($) {
  $('.filter-select').select2({
    minimumResultsForSearch: -1
  })
  const urlParams = new URLSearchParams(window.location.search)  
  window.props = {}
  props.page = parseInt(urlParams.get('page'))
  props.column = 'id'
  props.direction = 'DESC'
    
  window.htmlToElem = function(html) {
    let temp = document.createElement('template')
    html = html.trim() // Never return a space text node as a result
    temp.innerHTML = html
    return temp.content.firstChild
  }
    
  // Object to array conversion
  window.objectToArray = function(obj) {
    return Object.entries(obj).map(([,v]) => v)
  }

  Task.loadPage(props)
    
  $('#create-form').on('submit', function(e) {
    e.preventDefault()
    if (validateForm($(this))) {
        let data = $(this).serialize()
        $.post('?action=create', data)
        .done(function(response) {
          try {
            response = JSON.parse(response)
            if (parseInt(response.success)) {
              $('#tasks-placeholder').hide()
              props.page = 1
              Task.loadPage()
              $('#messageModal').modal().find('.modal-body').find('span')
              .addClass('text-success').text('The task was successfully created!')
              $('#column-select').val('id').trigger('change.select2');
              $('#order-select').val('DESC').trigger('change.select2');
            } else {
              $('#messageModal').modal().find('.modal-body').find('span')
              .addClass('text-danger')
              .html('An error was occured while creating the task. </br> Reload the page please.')
            }
          }catch(e) {
            $('#messageModal').modal().find('.modal-body').find('span')
            .addClass('text-danger').html('The server response is not a valid JSON. </br> Reload the page please.')
          }
        }).fail(()=>{
          $('#messageModal').modal().find('.modal-body').find('span')
          .addClass('text-danger')
          .html('The server error was occured. </br> Reload the page please.')
        })
    } 
  })

  $('#column-select').on('change',(e)=>{
    props.column = e.target.value
    $('#tasks-placeholder').hide()
    Task.loadPage()
  })
  $('#order-select').on('change',(e)=>{
    props.direction = e.target.value
    $('#tasks-placeholder').hide()
    Task.loadPage()
  })
  
  $('#messageModal').on('hide.bs.modal', function (e) {
    let modal = $(this)
    modal.find('.modal-body').find('span').removeClass().html('')
  })
    /*==================================================================
    [ Validate after type ]*/
    /*    
    $('.validate-input .input').each(function(){
        $(this).on('blur', function(){
            if(validate(this) == false){
                showValidate(this)
            }
            else {
                $(this).parent().addClass('true-validate')
            }
        })    
    })
    */
  
    /*==================================================================
    [ Validate ]*/
    function validateForm(form) {
      let input = form.find('.validate-input .input')
      let check = true
      for(var i=0; i<input.length; i++) {
          if(validate(input[i]) == false){
              showValidate(input[i])
              check=false
          }
      }
      return check
    }
    
    $('.validate-form .input').each(function(){
        $(this).focus(function(){
           hideValidate(this)
           $(this).parent().removeClass('true-validate')
        })
    })

     function validate (input) {
        if($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
            if($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
                return false
            }
        }
        else {
            if($(input).val().trim() == ''){
                return false
            }
        }
    }

    function showValidate(input) {
        var thisAlert = $(input).parent()
        $(thisAlert).addClass('alert-validate')
        $(thisAlert).append('<span class="btn-hide-validate">&#xf136</span>')
        $('.btn-hide-validate').each(function(){
            $(this).on('click',function(){
               hideValidate(this)
            })
        })
    }

    function hideValidate(input) {
        let thisAlert = $(input).parent()
        $(thisAlert).removeClass('alert-validate')
        $(thisAlert).find('.btn-hide-validate').remove()
    }
})(jQuery)