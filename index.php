<?php

require "vendor/autoload.php";

use App\Post;

$post = new Post;
$post->title = 'Post title';
$post->image = '011.jpg'; 

echo $post->imageTag();