class Task {
  static template
  constructor(props) {
    if (Task.template !== undefined) {
      console.log('Constructor');
      this.fetchHTML(props)
    } else {
      alert('Error')
    }
  }
  static loadTemplate() {
    return $.get('?action=template')
  }
  static loadPage() {
    $('#tasks-container').find('.wrap-task').remove()
    $('.loader').show()
    $.when(Task.loadTemplate()).done((template)=>{
      console.log('Load template');
      Task.template = template
      $.when(Task.createNavBar()).done(()=>{
        console.log('After navbar creation.');
        Task.setPageParam(props.page)
        Task.loadTasks()
      })
    })
  }
  static createNavBar() {
    return $.get('?action=count').done((cnt)=>{
      console.log('Start navbar creation page');
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
      console.log('Nav bar created');
    })
  }
  static setPageParam(number) {
    history.pushState({page: number}, '', '?page=' + number)
  }
  static loadTasks() {
    console.log('Tasks will be loaded.');
    $.post('?action=list', props).done(
      function( response ) {
        response = JSON.parse(response)
        let items = objectToArray(response)
        if (items.length) {
          $('#tasks-placeholder').hide()
          Object.values(items).map(function(item) {
            console.log('Let\'s create tasks')
            let task = new Task(item)
          })
        } else {
          console.log('Show placeholder')
          $('#tasks-placeholder').show()
          $('.loader').hide()
        }
      })
  }
  fetchHTML({
    'id': id,
    'name': name,
    'content': content,
    'email': email,
    'status': status,
    'moderated': moderated
  }) {
    status = parseInt(status) ? 'task is done.' : 'task in progress.'
    moderated = parseInt(moderated) ? 'Moderated by Admin.' : 'not moderated.'
    $.when(this.authCheck()).done((response) => {
      let isAdmin = parseInt(response)
      let edit = isAdmin ? '<a class="btn btn-info" href="">Edit task</a>' : ''
      let html = Task.template.replace('{name}', name).replace('{email}', email)
      .replace('{content}', content).replace('{status}', status)
      .replace('{id}', id).replace('{edit}', edit)
      let item = htmlToElem(html)
      console.log('Item ' + id + ' added!')
      document.getElementById('tasks-container').append(item)
    }).done(() => {
      $('.loader').hide()
    })
  }
  authCheck() {
      return $.get('?controller=user&action=secure')
  }
}

export default Task;