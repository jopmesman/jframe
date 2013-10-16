<script src="views/js/google_maps.js"></script>
<?php $count = count($news);?>
<?php foreach ($news as $newsitem) { ?>
<div>
  <div class="newsitem_title">
    <h4><a href="?page=news/view/<?php print $newsitem['news_id']; ?>"><?php print htmlspecialchars($newsitem['news_title']); ?></a>
    <?php if ($loggedin) { ?>
      <a href="?page=news/edit/<?php print $newsitem['news_id']; ?>"><i class="icon-edit"></i></a>
      <a onClick="return confirm('Are you sure you want to delete \'<?php print htmlspecialchars($newsitem['news_title']); ?>\'?');"
        href="?page=news/delete/<?php print $newsitem['news_id']; ?>"><i class="icon-trash"></i></a>
    <?php } ?>
    </h4>
  </div>
  <div class="newsitem_text">
    <?php print nl2br(htmlspecialchars($newsitem['news_message'])); ?>
  </div>
  <div class="newsitem_extrainfo_wrapper well">
    <div class="newsitem_extrainfo newsitem_date">
      Date: <?php print date("d-m-Y H:i:s", strtotime($newsitem['news_date'])); ?>
    </div>
    <?php if ($loggedin) { ?>
    <div class="newsitem_extrainfo newsitem_published">
      Status: <?php if ($newsitem['news_published']) { ?>
        <?php print 'Published'; ?>
        <a onClick="return confirm('Are you sure you want to unpublish \'<?php print htmlspecialchars($newsitem['news_title']); ?>\'?');"
          href="?page=news/unpub/<?php print $newsitem['news_id']; ?>"><i class="icon-ban-circle"></i></a>
      <?php } else { ?>
        <?php print 'Unplublished'; ?>
        <a onClick="return confirm('Are you sure you want to publish \'<?php print htmlspecialchars($newsitem['news_title']); ?>\'?');"
          href="?page=news/pub/<?php print $newsitem['news_id']; ?>"><i class="icon-ok-circle"></i></a>
      <?php } ?>
    </div>
    <div class="newsitem_extrainfo newsitem_admin_seen">
      Admin seen: <?php ($newsitem['news_admin_seen']) ? print 'Yes' : print '<string>No</strong>'; ?>
    </div>
    <?php } ?>
  </div>
</div>
<?php if ($count == 1) { //we have 1 newsitem. So the user is viewing the details ?>
<script>window.onload = loadGoogleMapsScript('<?php print MAPSAPIKEY; ?>');</script>
    <div id="news_google_maps">
      <div id="map-canvas" style="width: 700px; height: 300px"></div>
    </div>
<?php } ?>
<hr />
<?php } ?>
