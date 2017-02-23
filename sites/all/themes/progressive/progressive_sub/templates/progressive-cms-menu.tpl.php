<?php $layout = _nikadevs_cms_get_active_layout();
$one_page = isset($layout['settings']['one_page']) && $layout['settings']['one_page'] ? 1 : 0; ?>
<?php if(theme_get_setting('header_top_menu') && !$one_page): global $user; ?>
  <div id="top-box">
    <div class="container">
    <div class="row">
      <div class="col-xs-9 col-sm-5">

        <?php if(theme_get_setting('language') && module_exists('locale') && drupal_multilingual()):
          global $language;
        ?>
          <div class="btn-group language btn-select">
            <a class="btn dropdown-toggle btn-default" role="button" data-toggle="dropdown" href="#">
              <span class="hidden-xs"><?php print t('Language'); ?></span><span class="visible-xs"><?php print t('Lang');?></span><!-- 
              -->: <?php print $language->name; ?>
              <span class="caret"></span>
            </a>
            <?php
              $path = drupal_is_front_page() ? '<front>' : $_GET['q'];
              $links = language_negotiation_get_switch_links('language', $path);
              if(isset($links->links)) {
                foreach($links->links as $i => $link) {
                  $links->links[$i]['attributes']['lang'] = $links->links[$i]['attributes']['xml:lang'];
                }
                $variables = array('links' => $links->links, 'attributes' => array('class' => array('dropdown-menu')));
                print theme('links__locale_block', $variables);
              }
            ?>
          </div>
        <?php endif; ?>
      </div>
      
      <div class="col-xs-3 col-sm-7">
      <div class="navbar navbar-inverse top-navbar top-navbar-right" role="navigation">
        <button type="button" class="navbar-toggle btn-navbar collapsed" data-toggle="collapse" data-target=".top-navbar .navbar-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>

        <nav class="collapse collapsing navbar-collapse" style="width: auto;">
          <ul class="nav navbar-nav navbar-right">
            <?php if(theme_get_setting('account_login') && $user->uid): ?>
              <li><?php print l(t('My Account'), 'user'); ?></li>
            <?php endif; ?>
            <?php if(module_exists('flag')):
              $flags = flag_get_user_flags('node');
              if(theme_get_setting('comparelist')): ?>
                <li>
                  <a href="<?php print url('product-compare'); ?>">
                    <i class="fa fa-exchange"></i> <?php print t('Compare List'); ?><span class = "flag-counter flag-count-compare count"><?php print !isset($flags['compare']) ? 0 : count($flags['compare']); ?></span>
                  </a>
                </li>
              <?php endif;
              if(theme_get_setting('wishlist')): ?>
                <li>
                  <a href="<?php print url('shop/wishlist'); ?>">
                    <i class="fa fa-heart"></i> <?php print t('My Wishlist'); ?><span class = "flag-counter flag-count-wishlist count"><?php print !isset($flags['wishlist']) ? 0 : count($flags['wishlist']); ?></span>
                  </a>
                </li>
              <?php endif; ?>
            <?php endif; ?>            
            <?php if(theme_get_setting('cart_checkout') && module_exists('uc_cart')): ?>
              <li><?php print l(t('My Cart <span class = "count cart-count">%number</span>', array('%number' => count(uc_cart_get_contents()))), 'cart', array('html' => TRUE)); ?></li>
              <li><?php print l(t('Checkout'), 'cart/checkout'); ?></li>
            <?php endif; ?>
            <?php if(theme_get_setting('account_login') && !$user->uid): ?>
              <li><?php print l(t('Log In  <i class="fa fa-lock after"></i>'), 'user/login', array('html' => TRUE)); ?></li>
            <?php endif; ?>
            <?php if(theme_get_setting('account_login') && $user->uid): ?>
              <li><?php print l(t('Log Out  <i class="fa fa-unlock after"></i>'), 'user/logout', array('html' => TRUE)); ?></li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
      </div>
    </div>
    </div>
  </div>
<?php endif; ?>

<header class="header<?php print theme_get_setting('header_top_menu') && !$one_page ? '' : ' header-two'; ?>">
  <div class = "header-wrapper">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-md-12 col-lg-12 logo-box">
        <div class="logo">
          <a href="<?php print url('<front>'); ?>">
           <img src="<?php print theme_get_setting('logo'); ?>" class="logo-img" alt="">
          </a>
        </div>
        </div><!-- .logo-box -->
        
        <div class="col-xs-12 col-md-12 col-lg-12 right-box">
        
          
          <div class="primary">
          <div class="navbar navbar-default" role="navigation">
            <button type="button" class="navbar-toggle btn-navbar collapsed" data-toggle="collapse" data-target=".primary .navbar-collapse">
              <span class="text"><?php print t('Menu'); ?></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
      
            <nav class="collapse collapsing navbar-collapse">
              <ul class="nav navbar-nav navbar-center">
                <?php
                  if($one_page) {
                    foreach($layout['rows'] as $row):
                      $path = '#' . preg_replace('/[^\p{L}\p{N}]/u', '-', $row['name']);
                      if(isset($row['settings']['dropdown_links']) && $row['settings']['dropdown_links']) { ?>
                        <li class="parent">
                          <a class = "scroll" href = "<?php print $path; ?>"><?php print t($row['name']); ?></a>
                          <ul class="sub"> 
                          <?php
                            foreach ($row['settings'] as $key => $value):
                              if(strpos($key, 'menu_link_url') !== FALSE) {
                                $i = str_replace('menu_link_url_', '', $key);
                                $path = strpos($row['settings']['menu_link_url_' . $i], '#') === FALSE ? url($row['settings']['menu_link_url_' . $i]) : $row['settings']['menu_link_url_' . $i];
                                $class = strpos($row['settings']['menu_link_url_' . $i], '#') === 0 ? 'class = "scroll"' : '';
                                print '<li><a href="' . $path . '" ' . $class .'>' . t($row['settings']['menu_link_' . $i]) . '</a></li>';
                              }
                            endforeach;
                          ?>
                          </ul>
                        </li>
                      <?php }
                      elseif(!isset($row['settings']['hide_menu']) || !$row['settings']['hide_menu']) {
                        $path = '#' .  preg_replace('/[^\p{L}\p{N}]/u', '-', $row['name']);
                        print '<li><a href = "' . $path . '"  class = "scroll">' . t($row['name']) . '</a></li>';
                      }
                    endforeach;
                  }
                  elseif(module_exists('tb_megamenu')) {
                    print theme('tb_megamenu', array('menu_name' => variable_get('menu_main_links_source', 'main-menu')));
                  }
                  else {
                    $main_menu_tree = module_exists('i18n_menu') ? i18n_menu_translated_tree(variable_get('menu_main_links_source', 'main-menu')) : menu_tree(variable_get('menu_main_links_source', 'main-menu'));
                    print drupal_render($main_menu_tree);
                  }
                ?>
              </ul>
            </nav>
          </div>
          </div><!-- .primary -->
        </div>
        </div>
        
        <div class="phone-active col-sm-9 col-md-9">
          <a href="#" class="close"><span><?php print t('close'); ?></span>×</a>
          <?php $phone = explode("\n", theme_get_setting('phones')); ?>
          <span class="title"><?php print t('Call Us'); ?></span> <strong><?php print is_array($phone) ? array_shift($phone) : ''; ?></strong>
        </div>
        <div class="search-active col-sm-9 col-md-9">
          <a href="#" class="close"><span><?php print t('close'); ?></span>×</a>
          <?php
            $search_form_box = module_invoke('search', 'block_view');
            print render($search_form_box);
          ?>
        </div>
      </div><!--.row -->
    </div>
  </div>
</header><!-- .header -->