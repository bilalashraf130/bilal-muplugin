
<style>
    .card-inner {
        display: flex;
        margin-bottom: 20px;
    }
    .userReviewRating {
        display: flex;
        width: 100%;
        justify-content: space-between;
        margin: 0 0 0 20px;
        border: 1px solid #e4e1e3;
        border-radius: 4px;
        padding: 1em 1em;
    }
    .user_image{
        width: 5%;
    }

    @media only screen and (max-width: 768px) {
        .user_image{
            width: 15%;
        }
        .userReviewRating
    }
    @media only screen and (max-width: 550px) {
        .userReviewRating {display:block;  }   .rate {padding:0px!important}}
    .user_image img {
        border-radius: 100px;
    }
    .userreview p strong {
        color: #f36f5b;
        font-size: 13px;
    }
    .userreview span {
        color: #f36f5b;
        font-size: 13px;
    }


    .rate {
        float: left;
        height: 46px;
        padding: 0 10px;
    }
    .rate:not(:checked) > input {
        position:absolute;
        top:-9999px;
    }
    .rate:not(:checked) > label {
        float:right;
        width:1em;
        overflow:hidden;
        white-space:nowrap;
        cursor:pointer;
        font-size:20px;
        color:#ccc;
    }
    .rate:not(:checked) > label:before {
        content: '★ ';
    }
    .rate > input:checked ~ label {
        color: #ffc700;
    }


</style>
<?php foreach($comments as $comment){
$rating = get_comment_meta($comment->comment_ID,"rating",true);

?>
<div class="main">
    <div class="card-inner">
        <div class="user_image">
            <img src="<?php echo bp_core_fetch_avatar (
				array(  'item_id' => $comment->user_id, // id of user for desired avatar
					'type'    => 'full',
					'html'   => FALSE     // FALSE = return url, TRUE (default) = return img html
				)
			);  ?>" alt="userImage">
        </div>
        <div class="userReviewRating">
            <div class="userreview">
                <p>
                    <strong><?php echo $comment->comment_author ?></strong> - <span><?php echo $comment->comment_date ?></span><br>
                    <?php echo $comment->comment_content  ?>
                </p>

            </div>
            <div class="userrating">
                <div class="rate">
                    <input type="radio" id="star5<?php echo $comment->comment_ID ?>" name="course_review_star<?php echo $comment->comment_ID ?>" value="5" <?php echo $rating ==5 ? "checked" : "" ?> />
                    <label for="star5" title="text">5 stars</label>
                    <input type="radio" id="star4<?php echo $comment->comment_ID ?>" name="course_review_star<?php echo $comment->comment_ID ?>" value="4" <?php echo $rating ==4 ? "checked" : "" ?> />
                    <label for="star4" title="text">4 stars</label>
                    <input type="radio" id="star3<?php echo $comment->comment_ID ?>" name="course_review_star<?php echo $comment->comment_ID ?>" value="3"  <?php echo  $rating ==3 ? "checked" : "" ?>/>
                    <label for="star3" title="text">3 stars</label>
                    <input type="radio" id="star2<?php echo $comment->comment_ID ?>" name="course_review_star<?php echo $comment->comment_ID ?>" value="2"  <?php echo  $rating ==2 ? "checked" : "" ?>/>
                    <label for="star2" title="text">2 stars</label>
                    <input type="radio" id="star1<?php echo $comment->comment_ID ?>" name="course_review_star<?php echo $comment->comment_ID ?>" value="1"  <?php echo $rating == 1 ? "checked" : "" ?>/>
                    <label for="star1" title="text">1 star</label>
                </div>
            </div>
        </div>
    </div>
<?php }


echo $comments->links();
?>


