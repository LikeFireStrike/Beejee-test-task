class Task {
  static template
  
  // Create a task item
  constructor(props) {
    if (Task.template !== undefined) {
      this.props = props
      this.fetchHTML()
    } else {
      alert('Error')
    }
  }
  // Get a template
  static loadTemplate() {
    return $.get('?action=template')
  }
  // Load current page
  static loadPage() {
    $.when(authCheck()).done(()=>{
      $('#tasks-container').find('.wrap-task').remove()
      $('.loader').show()
      $.when(Task.loadTemplate()).done((template)=>{
        Task.template = template
        $.when(Task.createNavBar()).done(()=>{
          Task.setPageParam(props.page)
          Task.loadTasks()
        })
      })
    })
  }
  // Create a pagination
  static createNavBar() {
    return $.get('?action=count').done((cnt)=>{
      let pager = $('#nav-bar').html('')
      cnt = objectToArray(JSON.parse(cnt))
      let countPages = Math.ceil(cnt[0]/3)
      if (isNaN(props.page) || props.page < 1 ) {
        props.page = 1
      } else if (props.page > countPages) {
        props.page = countPages
      }
      while (countPages) {
        let button = $('<button class="page-link" data-page="'
                       + countPages + '">' + countPages + '</button>')
        countPages == props.page ? button.addClass('btn-active') : ''
        let node = $('<li class="page-item"></li>').append(button)
        pager.prepend(node)
        countPages--
      }
      $('.page-link').on('click', (e)=>{
        props.page = $(e.target).data('page')
        Task.loadPage()
      })
    })
  }
  // Change the URL page parameter
  static setPageParam(number) {
    if(number == 0) {number=1}
    history.pushState({page: number}, '', '?page=' + number)
  }
  // Load the tasks
  static loadTasks() {
    $.post('?action=list', props).done(
      function( response ) {
        response = JSON.parse(response)
        let items = objectToArray(response)
        if (items.length) {
          $('#tasks-placeholder').hide()
          Object.values(items).map(function(item) {
            let task = new Task(item)
          })
        } else {
          $('#tasks-placeholder').show()
          $('.loader').hide()
        }
      })
  }
  // Draw the task
  fetchHTML() {
    let id, name, content, email, status, moderated
    ({ id, name, content, email, status, moderated } = this.props)
    status = parseInt(status) ? '<span class="text-success">Status: task is done.</span>' : '<span class="text-warning">Status: task in progress.</span>'
    moderated = parseInt(moderated) ? '<span class="text-success">Moderated by Admin.</span>' : ''
    let edit = isAdmin ? '<button class="btn btn-info btn-edit">Edit task</button>' : ''
    let html = Task.template.replace('{name}', name).replace('{email}', email)
    .replace('{content}', content).replace('{status}', status).replace('{id}', id)
    .replace('{edit}', edit).replace('{moderated}', moderated)
    let item = htmlToElem(html)
    let res = $(item).appendTo('#tasks-container')
    res.get(0).task = this // attach Task object to DOM element
    let button = $(res).find('.btn-edit')
    button.on('click', (e)=>{clickUpdate(e)})
    $('.loader').hide()
  }
}

export default Task