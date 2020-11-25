<div class="search_container">
	<form action="/search.php" method="get">
		<div class="search_input_box">
			<input type="text" name="s" class="search_input" placeholder="Search..." required>
			<input type="submit" value="Go" class="search_submit">
		</div>
	</form>
</div>

<div class="latest_posts_container sidebar_prop">
	<div class="sidebar_widget_title_block">
		<span class="sidebar_widget_title">Latest Posts</span>
	</div>
	<?php echo sidebarLatestPosts();?>
	
</div>

<div class="latest_posts_container sidebar_prop">
	<div class="sidebar_widget_title_block">
		<span class="sidebar_widget_title">Posts By Category</span>
	</div>
	<?php echo sidebarCategorySort();?>
	
</div>