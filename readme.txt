=== Betaout-loveit ===
Contributors: Betaout
Donate link: 
Tags: like, rating, postratings, postrating, vote, digg, ajax, post,loveit
Requires at least: 2.8
Tested up to: 3.3.2
Stable tag: 1.1

Adds an AJAX like system for your WordPress blog's post/page.

== Description ==


= Donations =
* I spent most of my free time creating, updating, maintaining and supporting these plugins, if you really love my plugins and could spare me a couple of bucks, I will really appericiate it. If not feel free to use it without any obligations.


= Version 1.1 (22-08-2012) =
* NEW: Initial Release

== Installation ==

1. Open `wp-content/plugins` Folder
2. Put: `Folder: betaout-loveit`
3. Activate `Betaout-loveit` Plugin`

= Usage =
1. Open `wp-content/themes/<YOUR THEME NAME>/index.php`
2. You may place it in archive.php, single.php, post.php or page.php also.
3. If you are using the_content(); Its add love it in end of content
4. Find: `<?php while (have_posts()) : the_post(); ?>`
5. Add Anywhere Below It (The Place You Want The Ratings To Show): `<?php if(function_exists('betaout_ratings')) { betaout_ratings(); } ?>`
6.If you want to show love it in between content add short code [loveit] in content .


