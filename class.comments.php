<?php
require_once('class.modulehandler.php');
class Comments extends Modulehandler{

	public $insComments = 0;
	public $skippedComments = 0;

	public function addComment($data_row){ 
		global $wpdb;
		$data_array = $this->formDataArray($data_row);
		$postid = $_POST['selectPosts']; 
		foreach($data_array as $data){
			$post_id = $data['comment_post_ID'];
			$post_exists = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE id = '" . $post_id . "' and post_status in ('publish','draft','future','private','pending')", 'ARRAY_A');
			if($post_exists){
				$commentid = wp_insert_comment($data);
				if($commentid)
					$this->insComments++;
				else
					$this->skippedComments++;
			}else{
				$this->skippedComments++;
			}
		}
	}
}
