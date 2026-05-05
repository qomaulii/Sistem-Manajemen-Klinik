<div style="padding: 15px;">
  <?= form_open('account/login', ['id' => 'loginForm', 'role' => 'form']) ?>
    <fieldset>
      <legend>Login</legend>
      
      <?= !empty($error) ? $error : '' ?>
      
      <div class="form-group" style="margin-bottom: 15px;">
        <label for="username" class="sr-only">Username: </label>
        <input type="text" name="username" class="form-control" placeholder="User Name" title="User Name" required>
      </div>
      
      <div class="form-group" style="margin-bottom: 15px;">
        <label for="password" class="sr-only">Password: </label>
        <input type="password" name="password" class="form-control" placeholder="Password" title="Password" required>
      </div>
      
      <div class="checkbox" style="margin-bottom: 15px;">
        <label>
          <input type="checkbox" name="remember_me" value="1" id="remember_me"> Remember Me
        </label>
      </div>
      
      <div class="form-group" style="margin-top: 20px;">
        <input type="submit" name="login" value="Login" class="form-control btn btn-primary" style="height: 40px;">
      </div>
    </fieldset>
  <?= form_close() ?>
</div>