<?php 
// Add comment email actions to Posting and Approval
add_action('comment_post', create_function('$a', 'ocmx_commentmeta_update($a);'));
add_action('wp_set_comment_status', create_function('$a', 'ocmx_comment_email($a);'));

// Comment Meta Update
function ocmx_commentmeta_update($cid)//, $comment_twitter, $comment_subscribe, $comment_author_email
	{
		global $wpdb;
		$commentId = (int) $cid;
		$comment_table = $wpdb->prefix . "ocmx_comment_meta";
		if($_POST['twitter'] == "undefined" && $_POST['twitter'] == "Twitter Name") :
			$use_twitter = "";
		else :
			$use_twitter = $_POST['twitter'];
		endif;
		
		if($_POST['email_subscribe'] == "true") :
			$subscribe_me = 1;
		else :
			$subscribe_me = 0;
		endif;
		
		$meta_update = $wpdb->query
			($wpdb->prepare
				("INSERT INTO $comment_table
					(commentId, twitter, email_subscribe)
				VALUES
					(%d, %s, %s);", 
        		$cid, $use_twitter, $subscribe_me)
			);
		ocmx_comment_email($cid);
	}	
// Comment Emailing
function ocmx_comment_email($cid)//, $comment_twitter, $comment_subscribe, $comment_author_email
	{
		global $wpdb;
		$commentId = (int) $cid;
		$comment_table = $wpdb->prefix . "ocmx_comment_meta";
		
		$comment = $wpdb->get_row("SELECT $wpdb->comments.*,  $comment_table.* FROM $wpdb->comments INNER JOIN $comment_table ON $wpdb->comments.comment_ID = $comment_table.commentId WHERE $wpdb->comments.comment_ID='".$cid."'");
		$post_details = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID='".$comment->comment_post_ID."' LIMIT 1");		
		
		
		if ($comment->comment_approved == "1") :
			$fetch_subscribers = $wpdb->get_results("SELECT $wpdb->comments.*,  $comment_table.*
			FROM $wpdb->comments INNER JOIN $comment_table
			ON $wpdb->comments.comment_ID = $comment_table.commentId
			WHERE $wpdb->comments.comment_post_ID = '".$comment->comment_post_ID."'
			AND $comment_table.email_subscribe = '1'
			GROUP BY $wpdb->comments.comment_author_email");
			
			foreach($fetch_subscribers as $subscriber) :
				$to = $subscriber->comment_author_email;
				$subject = $post_details->post_title;
	
				$headers  = "From: \"".get_bloginfo("name")."\" Comments";
				$headers .= "MIME-Version: 1.0\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1 \r\n";
				$body = "<style>body{margin: 20px;font-size: 9pt;font-family: Arial, Helvetica, sans-serif;color: ##333333;background-color: ##ffffff;}.articles_item{padding-bottom: 10px; border-bottom: 1px solid black; margin-bottom: 10px;}</style>";
				$body .=  "<h4>".$post->post_title."</h4>";
				$body .=  "<p><strong>".$comment->comment_author."</strong> has commented on <strong>". $post_details->post_title."</strong></p>";
				$body .=  "<p><strong>Link:</strong> <a href=\"".$comment->comment_author."\">".$comment->comment_author_url."</a></p>";
				$body .=  "<p><strong>Twitter Name:</strong> <a href=\"http://twitter.com/".$comment->twitter."\">".$comment->twitter."</a></p>";
				$body .=  "<p><strong>Comment:</strong></p>";
				$body .=  "<div class=\"articles_item\">";
				$body .=  "<p>".$comment->comment_content."</p>";
				$body .=  "</div>";  
				$body .=  "<p>Go go straight to the post <a href=\"".get_permalink($comment->comment_post_ID)."#comments\">".get_permalink($comment->comment_post_ID)."</a>.</p>";
				$body .=  "<p>Unsubscribe from this comment feed <a href=\"".get_bloginfo("template_url")."/unsubscribe.php?comment=".$cid."\">".get_bloginfo("template_url")."/unsubscribe.php?comment=".$cid."</a>.</p>";
				wp_mail($to, $subject, $body, $headers);
			endforeach;
		endif;
	}
function ocmx_unsubscribe($comment_id)//, $comment_twitter, $comment_subscribe, $comment_author_email
	{
		global $wpdb, $comment_id, $the_post;
		$comment_id = $_GET["comment"];	
		$comment_table = $wpdb->prefix . "ocmx_comment_meta";	
		$the_comment = get_comment($comment_id);	
		
		$subscriber_email = $the_comment->comment_author_email;
		
		$post_id = $the_comment->comment_post_ID;
		$the_post = get_post($the_comment->comment_post_ID);
		
		$comment_args = array("post_id" => $post_id);
		
		$post_comments = get_comments($comment_args);
		
		foreach($post_comments as $this_comment) :
			if($subscriber_email == $this_comment->comment_author_email)
			$wpdb->update($comment_table , array( 'email_subscribe' => '0'), array( 'commentId' => $this_comment->comment_ID ), array( '%d' ), array( '%d' ) );
		endforeach;
	}
?>