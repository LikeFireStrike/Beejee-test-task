<!DOCTYPE html>
<html lang="en">
<head>
    <title>Beejee task</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<!--------------------------------------------------------------------------------------------------->
    <link rel="icon" type="image/png" href="assets/images/icons/favicon.ico"/>
<!--------------------------------------------------------------------------------------------------->
    <link rel="stylesheet" type="text/css" href="assets/vendor/bootstrap/css/bootstrap.min.css">
<!--------------------------------------------------------------------------------------------------->
    <link rel="stylesheet" type="text/css" href="assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--------------------------------------------------------------------------------------------------->
    <link rel="stylesheet" type="text/css" href="assets/fonts/iconic/css/material-design-iconic-font.min.css">
<!--------------------------------------------------------------------------------------------------->
    <link rel="stylesheet" type="text/css" href="assets/vendor/select2/select2.min.css">
<!--------------------------------------------------------------------------------------------------->
    <link rel="stylesheet" type="text/css" href="assets/css/util.css">
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
<!--------------------------------------------------------------------------------------------------->
</head>
<body>
    <div class="container-task">
        {content}
    </div>
    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="loginModalLabel">Login form</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form class="login-form validate-form" name="login-form" id="login-form" method="POST" action="{BASE_DIR}/?controller=user&action=login">
            <div class="modal-body">
                <div class="wrap-input validate-input bg1" data-validate="Please Type Your Name">
                    <span class="label-input">Login</span>
                    <input class="input" type="text" name="login" placeholder="Please Type Your Login">
                </div>
                <div class="wrap-input validate-input bg1" data-validate = "Please Type Your Password">
                    <span class="label-input">Password</span>
                    <input class="input" type="text" name="email" placeholder="Please Type Your Password">
                </div>
              </div>
              <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary">Sign in</button>
              </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Information Modal -->
    <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="messageModallLabel">Information message: </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <div class="modal-body font-weight-bold">
                <span></span>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
      </div>
    </div>
<!--------------------------------------------------------------------------------------------------->
    <script src="assets/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--------------------------------------------------------------------------------------------------->
    <script src="assets/vendor/bootstrap/js/popper.js"></script>
<!--------------------------------------------------------------------------------------------------->
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--------------------------------------------------------------------------------------------------->
	  <script src="assets/vendor/select2/select2.min.js"></script>
<!--------------------------------------------------------------------------------------------------->
    <script type="module" src="./assets/js/main.js"></script>
</body>
</html>