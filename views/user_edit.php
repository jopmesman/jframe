<div id="user_edit-wrapper">
  <form id="edit_form" method="POST" action="?page=<?php print $page; ?>">
    <div>
      <input type="password" name="password_1" placeholder="Enter new password" />
    </div>
    <div>
      <input type="password" name="password_2" placeholder="Re-enter new password" />
    </div>
    <div>
      <input type="submit" name="submit" value="Save user" />
    </div>
  </form>
</div>