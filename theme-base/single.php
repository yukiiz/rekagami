<?php
//is_user_logged_in でユーザーがログイン済みか判断する
if (!is_user_logged_in()) {
  //未ログインの場合、auth_redirect() でログインページにリダイレクト
  wp_redirect(home_url('/login/'));
}
?>
<?php acf_form_head(); ?>
<?php get_header(); ?>

<?php
$user = wp_get_current_user();
// echo $user->ID; //ユーザーID
// echo $user->user_login; //ログインID
// echo $user->display_name; //氏名
?>
<section class="sec-single">
  <div class="sec-inner">

    <div class="sec-headline">
      <h1 class="page-tit text-center"><?php the_title(); ?>様　カルテ詳細</h1>
    </div>


    <div class="user-name">
      <p class="link-btn">▼ 来店記録の登録</p>
      <div class="update">
        <?php
        acf_form(array(
          'post_id'       => 'new_post',
          'new_post'      => array(
            'post_type'     => 'visit',
            'post_status'   => 'publish'
          ),
          'submit_value'  => '登録'
        ));
        ?>
      </div>
    </div>

    <?php
    $post_id = $post->ID; //現在表示している記事のIDを取得
    $posts = get_posts(array(
      'posts_per_page'    => -1, //全表示
      'post_type'         => 'visit',
      'orderby' => 'meta_value',
      'meta_key'      => 'visit_date',
      'meta_query' => array(
        array(
          'key' => 'visit_karte',
          'value' => $post_id,
          'compare' => 'LIKE',
        ),
      )
    ));
    if ($posts) : ?>
      <ul class="record-list">
        <?php
        foreach ($posts as $post) {
          setup_postdata($post);
        ?>
          <li>
            <!--<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>-->
            <?php
            $date = get_field('visit_date');
            if ($date) {
              echo '<p class="record-date">' . $date . '</p>';
            }
            ?>
            <?php
            $comment = get_field('visit_comment');
            if ($comment) {
              echo '<p class="record-comment">' . $comment . '</p>';
            }
            ?>

            <?php // ACF Gallery Field の表示
            $images = get_field('visit_image');
            if ($images) {
            ?>
              <ul class="gallery-list">
                <?php foreach ($images as $image) { ?>
                  <li class="item">
                    <a href="<?php echo $image['url']; ?>">
                      <img src="<?php echo $image['sizes']['large']; ?>" alt="<?php echo $image['alt']; ?>" />
                    </a>
                  </li>
                <?php } ?>
              </ul>
            <?php } ?>
            <div class="user-name">
              <p class="link-btn">▼ 来店記録の編集</p>
              <div class="update">
                <?php
                acf_form(array(
                  'new_post'  => array(
                    'post_type'  => 'visit',
                    'post_status'  => 'publish'
                  ),
                  'submit_value'  => '編集'
                ));
                ?>
              </div>
            </div>
          </li>
        <?php } ?>
      </ul>

      <?php wp_reset_postdata(); ?>
    <?php endif; ?>








  </div>
</section>
<section class="user-area">
  <div class="sec-inner">
    <!-- <a href="<?php echo esc_url(home_url('/change_password/')); ?>" class="btn-block">パスワード変更</a> -->
  </div>
</section>
<?php get_footer(); ?>