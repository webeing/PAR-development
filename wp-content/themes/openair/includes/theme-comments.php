<?php
// Custom comment loop
function custom_comment($comment, $args, $depth) {	
       $GLOBALS['comment'] = $comment; ?>
       
<li id="comment-<?php comment_ID() ?>" class="clearfix">
    <div class="comment-meta">

        <span class="gravatar"><?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?></span>

        <div class="large darkblue comment-author"><?php comment_author_link() ?></div>
        <!--[if lt IE 7]><div class="clearfix"></div><![endif]-->
        <div class="small arial"><?php comment_date('M jS') ?><br /><?php echo comment_reply_link(array('before' => '<span class="reply">', 'after' => '</span>', 'reply_text' => 'Reply ', 'depth' => $depth, 'max_depth' => $args['max_depth'] ));  ?>
</div>
    </div>
    <div class="comment-content">	
        <?php if ($comment->comment_approved == '0') : ?>
            <strong>Your comment is awaiting moderation.</strong>
        <?php endif; ?>	
        
        <?php comment_text() ?>
    </div>
    <div class="fix"></div>
        
<?php } ?>