<!-- 【１】各記事の投稿機能オンオフ確認 -->
<?php if( comments_open() ){ ?>

<div id="comments">

	<!-- 【２】投稿されたコメントの有無確認 -->
	<?php if( have_comments() ): ?>

	<ul class="commets-list">
		<!-- 【３】コメントリストの表示 -->
		<?php
        /*
				$args = array(
            'reverse_top_level' => true,
        );
				wp_list_comments($args);
				*/
      wp_list_comments(array(
			'callback' => 'my_comment_template','reverse_top_level' => true
			));
		?>
	</ul>

	<?php endif; ?>

	<!-- 【４】コメント記入のフォーム -->
	<?php

    // コメントフォームの設定
    $args = array(
    'title_reply' => 'コメントの登録',
    'comment_field' => '<p class="comment-form-comment"><textarea id="comment" name="comment" placeholder="コメント" cols="45" rows="8" aria-required="true">' . '</textarea></p>',
    'label_submit' => 'コメントを送信する'
    );

    // コメントフォームの呼び出し
    comment_form( $args );
    ?>

</div>

<?php } ?>