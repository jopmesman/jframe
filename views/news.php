<div id="news-wrapper">
  <?php if (isset($unseen) and $unseen > 0) { ?>
    <span class="badge badge-important"><?php print sprintf("There is %d unseen newsitem%s", $unseen, ($unseen > 1) ? 's' : '' );?></span>
  <?php } ?>
  <?php print $content; ?>
</div>