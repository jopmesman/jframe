          <div id="news-block">
            <div id="news-nav-block">
              <ul class="nav nav-list" id="news_nav_link_list">
                <li>
                  <a href="?page=news">News
                  <?php if ($loggedin and $unseen > 0) { ?>
                    <span class="badge badge-important"><?php print $unseen;?> new</span>
                  <?php } ?>
                  </a>
                </li>
                <li><a href="?page=news/add">Add news</a></li>
              </ul>
            </div>
          </div>