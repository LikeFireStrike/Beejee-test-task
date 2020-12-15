<div class="wrap-task w-75 mx-auto text-right">
  <!-- Button trigger modal -->
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#loginModal">
    Sign in
  </button>
</div>
<div class="wrap-task w-75 mx-auto">
    <form class="task-form validate-form" name="create-form" id="create-form" method="POST" action="{BASE_DIR}/?controller=task&action=create">
        <span class="task-form-title">
            Create the task
        </span>
        <div class="wrap-input validate-input bg1" data-validate="Please Type Your Name">
            <span class="label-input">Name *</span>
            <input class="input" type="text" name="name" placeholder="Enter Your Name">
        </div>
        <div class="wrap-input validate-input bg1" data-validate = "Enter Your Email (e@a.x)">
            <span class="label-input">Email *</span>
            <input class="input" type="text" name="email" placeholder="Enter Your Email ">
        </div>
        <div class="wrap-input validate-input bg0 rs1-alert-validate" data-validate = "Please Type Your Message">
            <span class="label-input">Message *</span>
            <textarea class="input" name="content" placeholder="Your message here..."></textarea>
        </div>
        <div class="container-task-form-btn">
            <button class="task-form-btn">
                <span>
                    Submit
                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                </span>
            </button>
        </div>
    </form>
</div>
<div class="wrap-task w-75 mx-auto">
  <div class="row">
    <div class="col-6">
      <label class="font-weight-bold" for="column-select">Sorting by column</label>
      <select class="filter-select" id="column-select" name="column">
        <option value="id">Sort by default</option>
        <option value="name">Sort by name</option>
        <option value="email">Sort by email</option>
        <option value="status">Sort by status</option>
      </select>
    </div>
    <div class="col-6 text-right">
      <label class="font-weight-bold" for="column-select">Sorting order</label>
      <select class="filter-select" id="order-select" name="column">
        <option value="DESC">Descending</option>
        <option value="ASC">Ascending</option>
      </select>
    </div>
  </div>
</div>
<div class="loader"></div>
<div id="tasks-placeholder" class="text-center w-100 m-4">
  <h1>No tasks at the moment</h1>
</div>
<div id="tasks-container"></div>  
<div class="wrap-task w-75 mx-auto p-0">
    <nav aria-label="Page navigation">
      <ul id="nav-bar" class="pagination justify-content-center">
      </ul>
    </nav>
</div>