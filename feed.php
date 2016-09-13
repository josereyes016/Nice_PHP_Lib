<?php

  /* CURRENTLY IS ALGORITHM IS VERY INEFFCIENT SINCE ALL POST ARE
  ** RETRIVED AT ONCE, IT SHOULD BE A FEW AT A TIME OR UNTIL THE
  ** USER SCROLLS DOWN ENOUGH
  */

  /****************/
  /* GET ALL POST */
  /****************/


  $id = $_SESSION['id'];

  // GET YOUR POSTS - ARRAY OF ASSOCIATIVE ARRAYS
  $posts = query("SELECT text,photo,timestamp FROM posts WHERE user_id = ?", $id);

  // FIND OUT WHO ARE YOUR FRIENDS
  $my_friends = query("SELECT friend_id FROM friends WHERE user_id = ?", $id);

  // GET YOUR FRIENDS POSTS
  foreach( $my_friends as $my_friend){
    $p = query("SELECT text,photo,timestamp FROM posts where user_id = ?",$my_friend['friend_id']);

    // ADD POST TO $posts
    foreach( $p as $new_post){
      $posts[] = $new_post;
    }
  }

  /*************************/
  /**** FEED GENERATION ****/
  /*************************/

  // OUTPUT STRING
  $html = "";
  foreach( $posts as $post){

    // So with just text
    if( $post['photo'] == ''){

      $temp = "<div>
      <h3>name</h3>
      <div>$post['time']</div>
      <div>$post['text']</div>
      </div>";
    }
    // with photo
    else{
      $temp = "<div>
      <h3>name</h3>
      <div>$post['time']</div>
      <div>$post['photo']</div>
      <div>$post['text']</div>
      </div>";
    }

    $html = $html . $temp;
  }

  echo($html);

 ?>
