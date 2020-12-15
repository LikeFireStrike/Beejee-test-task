<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="login-form validate-form" name="login-form" id="login-form" method="POST" action="{BASE_DIR}/?controller=user&action=login">
            <span class="task-form-title">
                Login form
            </span>
            <div class="wrap-input validate-input bg1" data-validate="Please Type Your Name">
                <span class="label-input">Login</span>
                <input class="input" type="text" name="login" placeholder="Please Type Your Login">
            </div>
            <div class="wrap-input validate-input bg1" data-validate = "Please Type Your Password">
                <span class="label-input">Password</span>
                <input class="input" type="text" name="email" placeholder="Please Type Your Password">
            </div>
            <div class="container-task-form-btn">
                <button class="task-form-btn">
                    <span>
                        Sign in
                        <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                    </span>
                </button>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>