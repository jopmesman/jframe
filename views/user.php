<div id="user_login_block_wrapper">
<?php if ($loggedin == TRUE) { ?>
  <div id="user_login_block_loggedin">
    <h5>Welcome <?php print htmlspecialchars($username); ?></h5>
    <ul class="nav nav-list" id="user_loggedin_link_list">
      <li><a href="?page=user/logout">Logout</a>
    </ul>
  </div>
<?php } else { ?>

  <form class="navbar-form pull-left" id="user_login_form" action="<?php print $_SERVER['PHP_SELF']; ?>" method="POST">
    <input type="text" name="username" class="span2" placeholder="username" />
    <input type="password" name="password" class="span2" placeholder="password" />
    <input class="btn" type="submit" value="Login" name="user_submit" />
  </form>
<?php } ?>
</div>


