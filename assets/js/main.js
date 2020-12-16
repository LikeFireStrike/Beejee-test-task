import Task from './task.js'

(function ($) {
  $('.filter-select').select2({
    minimumResultsForSearch: -1
  })
  
  // Set sorting parameters and their default values
  const urlParams = new URLSearchParams(window.location.search)  
  window.props = {}
  window.isAdmin = 0
  window.editId = null
  props.page = parseInt(urlParams.get('page'))
  props.column = 'id'
  props.direction = 'DESC'
    
  window.htmlToElem = (html)=>{
    let temp = document.createElement('template')
    html = html.trim() // Never return a space text node as a result
    temp.innerHTML = html
    return temp.content.firstChild
  }
    
  // Object to array conversion
  window.objectToArray = (obj)=>{
    return Object.entries(obj).map(([,v]) => v)
  }
  
  window.authCheck = ()=>{
    return $.get('?controller=user&action=secure').done((response)=>{
      isAdmin = parseInt(response)
      if (isAdmin) {
        $('.btn-show-login').hide()
        $('.btn-logout').show()
      } else {
        $('.btn-show-login').show()
        $('.btn-logout').hide()
      }
    })
  }
  
  window.clickUpdate = (e)=>{
      let el = $(e.target)
      let task = el.parent().parent().parent().get(0).task
      let form = $('#updateModal')
      switch(task.props.status) {
        case '0':
          if ($('#status-progress').is(':checked') === false) {
            $('#status-progress').prop('checked', true).parent().addClass('active')
          }
          break;
        case '1':
          if ($('#status-done').is(':checked') === false) {
            $('#status-done').prop('checked', true).parent().addClass('active')
          }
          break;
      }
      form.find('input[name="name"]').val(htmlDecode(task.props.name))
      form.find('input[name="email"]').val(htmlDecode(task.props.email))
      form.find('textarea').val(htmlDecode(task.props.content))
      editId = task.props.id
      form.modal()
  }
  // Load the tasks for current sorting parameters
  Task.loadPage()
  
  function htmlDecode(input) {
    let doc = new DOMParser().parseFromString(input, "text/html");
    return doc.documentElement.textContent;
  }
  
  // Listen for creating task form submission
  $('#create-form').on('submit', function(e) {
    e.preventDefault()
    if (validateForm($(this))) {
        let data = $(this).serialize()
        $.post('?action=create', data) // Send data to backend
        .done(function(response) {
          try {
            response = JSON.parse(response)
            if (parseInt(response.success)) { // If OK
              $('#tasks-placeholder').hide()
              props.page = 1
              Task.loadPage() // Show the tasks items
              showMessageModal('The task was successfully created!', 'success') // Show success message
              // Reset the form
              $('#create-form').find('input, textarea').val('')
              // Reset sorting filters
              $('#column-select').val('id').trigger('change.select2')
              $('#order-select').val('DESC').trigger('change.select2')
            } else {
              // Show an error message
              showMessageModal('An error was occured while creating the task. </br> Reload the page please.', 'danger')
            }
          }catch(e) {
            showMessageModal('The server response is not a valid JSON. </br> Reload the page please.', 'danger')
          }
        }).fail(()=>{
          showMessageModal('The server error was occured. </br> Reload the page please.', 'danger')
        })
    } 
  })
  // Update the task
  $('#update-form').on('submit',(e)=>{
    e.preventDefault()
    $.when(authCheck()).done(()=>{
      let el = $(e.target) 
      if (isAdmin) {
        if (validateForm(el)) {
          let data = el.serialize() + '&id=' + editId
          $.post(el.attr('action'), data).done((response)=>{
            try {
              response = JSON.parse(response)
              if (parseInt(response.success)) { // If OK
                $('#updateModal').modal('hide')
                showMessageModal('The task was successfully updated!', 'success') // Show success message
                Task.loadPage()
              }
            }catch(e) {
              $('#updateModal').modal('hide')
              showMessageModal('The server response is not a valid JSON. </br> Reload the page please.', 'danger')
              Task.loadPage()
            }
          })
        }
      } else {
        // Reload the page if not Admin
        Task.loadPage()
        $('#updateModal').modal('hide')
        showMessageModal('You are loged out! Please sign in again!', 'danger')
      }
    })
    
    
  })
  // Send login form
  $('#login-form').on('submit', function(e) {
    e.preventDefault()
    if (validateForm($(this))) {
      let data = $(this).serialize()
      $.post($(this).attr('action'), data) // Send data to backend
      .done(function(response) {
        try {
          response = JSON.parse(response)
          let errorLabel = $('#admin-error')
          if (response['success'] == 1) {
            if (errorLabel.is(":visible")) {errorLabel.hide()} // Hide error if necessary
            Task.loadPage()
            $('#loginModal').modal('hide').find('input').val('') // Reset the form after modal closing
          } else {
            if (!errorLabel.is(":visible")) {$('#admin-error').show()}
          }
        } catch(e) {
          $('#loginModal').modal('hide')
          showMessageModal('The server error was occured. </br> Reload the page please.', 'danger')
        }
      })
    }
  })
  
  // Logout action
  $('.btn-logout').on('click', (e)=>{
    $.get('?controller=user&action=logout').done(()=>{
      Task.loadPage()
    })
  })
  // Show modal with success or error message
  function showMessageModal(message, type) {
    $('#messageModal').modal().find('.modal-body').find('span')
    .addClass('text-' + type)
    .html(message)
  }
  // Update the page on sorting change
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
  // Clean the modal message after closing 
  $('#messageModal').on('hide.bs.modal', function (e) {
    let modal = $(this)
    modal.find('.modal-body').find('span').removeClass().html('')
  })
  // Clean the update form after closing 
  $('#updateModal').on('hide.bs.modal', function (e) {
    let modal = $(this)
    $('.btn-radio').prop('checked', false)
    modal.find('.input').val('')
    modal.find('.active').removeClass('active')
  })
  /*==================================================================
  [ Validate ]*/
  function validateForm(form) {
    let input = form.find('.validate-input .input')
    let check = true
    for(var i=0; i<input.length; i++) {
      if (validate(input[i]) == false) {
        showValidate(input[i])
        check=false
      }
    }
    return check
  }
  
  $('.validate-form .input').each(function() {
    $(this).focus(function() {
      hideValidate(this)
      $(this).parent().removeClass('true-validate')
    })
  })
  
  function validate (input) {
    if ($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
      if ($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
        return false
      }
    }
    else {
      if ($(input).val().trim() == '') {
        return false
      }
    }
  }
  
  function showValidate(input) {
    var thisAlert = $(input).parent()
    $(thisAlert).addClass('alert-validate')
    $(thisAlert).append('<span class="btn-hide-validate">&#xf136</span>')
    $('.btn-hide-validate').each(function() {
      $(this).on('click',function() {
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