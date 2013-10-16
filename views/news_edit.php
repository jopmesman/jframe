<script src="views/js/google_maps.js"></script>
<div id="news_edit_form_wrapper">
  <form action="<?php print $_SERVER['PHP_SELF']; ?>?page=<?php print $page; ?>" method="POST" id="addedit_news_form">
    <div id="addedit_news_form_title">
      <input type="text" name="news_title"
             placeholder="News title" class="span4"
             value="<?php (isset($news_title)) ? print $news_title : print ''; ?>"/>
    </div>
    <div id="addedit_news_form_message">
<textarea rows="10" cols="50" placeholder="News text" class="span5" name="news_message">
<?php (isset($news_message)) ? print $news_message : print ''; ?>
</textarea>
    </div>
    <?php if ($loggedin) { ?>
      <div id="addedit_news_form_pubunpub">
        <select name="news_pubunpub">
          <option value="0" <?php ($news_published == 0) ? print "SELECTED" : print ''; ?>>Unpublished</option>
          <option value="1" <?php ($news_published == 1) ? print "SELECTED" : print ''; ?>>Published</option>
        </select>
      </div>
    <?php } ?>
    <div id="news_google_maps">
      <div id="map-canvas" style="width: 500px; height: 300px"></div>
    </div>
    <div id="addedit_news_form_submit">
      <input type="submit" value="Save" name="addedit_news_submit" />
    </div>
  </form>
</div>
<script>window.onload = loadGoogleMapsScript('<?php print MAPSAPIKEY; ?>');</script>