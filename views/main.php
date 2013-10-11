<html>
  <head>
    <title><?php print $title; ?> | News</title>
    <link href="views/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="views/css/main.css" rel="stylesheet" media="screen">
  </head>
  <body>
    <div id="overal_div">
      <div id="header-wrapper">
        <div id="header">
          &nbsp;
        </div>
      </div>
      <div id="main-wrapper">
        <div id="blocks-wrapper">
          <?php if (count($blocks) > 0) { ?>
          <?php foreach ($blocks as $block) { ?>
            <?php print $block;?>
          <?php } ?>
          <?php } ?>
          <div id="login-block">
            <?php print $userblock; ?>
          </div>
        </div>
        <div id="content-wrapper">
          <?php if (isset($messageserror)) { ?>
          <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Warning!</strong>
            <ul>
              <?php foreach ($messageserror as $message) { ?>
              <li><?php print $message; ?></li>
              <?php } ?>
            </ul>
          </div>
          <?php } ?>
          <?php if (isset($messagessuccess)) { ?>
          <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong>
            <ul>
              <?php foreach ($messagessuccess as $message) { ?>
              <li><?php print $message; ?></li>
              <?php } ?>
            </ul>
          </div>
          <?php } ?>
          <div id="content">
            <h1><?php print $title; ?></h1>
            <?php print $content; ?>
          </div>
        </div>
      </div>
      <div class="clearfix">&nbsp;</div>
      <div id="footer-wrapper">
        <div id="footer">
          <hr />
          &copy; <?php print date("Y"); ?>
        </div>
      </div>
    </div>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="views/bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>