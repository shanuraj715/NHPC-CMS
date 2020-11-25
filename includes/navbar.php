<div class="navbar_container">
	<div class="menu_btn">
		<i class="fas fa-bars nav_menu_btn" id="nav_toggle_btn" onclick="show_navbar()"></i>
	</div>
	<div class="navbar_left" id="toggle">
		<div class="toggler">
			<div class="close_btn_block">
				<i class="fas fa-times" onclick="show_navbar()"></i>
			</div>
			<a href="<?php echo $base_url;?>">Home</a>
			<?php navigationLinks();
				 function navigationLinks(){
					global $connection;
					global $base_url;
					$sql = "SELECT * from `navigation` where `parent` = 1 order by list_order asc";
					$navigation_parent_query = mysqli_query($connection,$sql);
					while($navigation_parent_data = mysqli_fetch_assoc($navigation_parent_query)){
						if($navigation_parent_data['child']==0){ ?>
							<a href="<?php echo $navigation_parent_data['url'];?>"><?php echo $navigation_parent_data['title'];?></a>
							<?php
						}
						elseif($navigation_parent_data['child']==1){
							$id = $navigation_parent_data['id'];
							$sql = "SELECT * from `navigation` where `parent_id` = $id order by list_order asc";
							$navigation_child_query = mysqli_query($connection,$sql);
							$navigation_child_data = mysqli_fetch_assoc($navigation_child_query); ?>
							<div class="dropdown_container">
								<button class="dropdownbtn"><?php echo $navigation_parent_data['title'];?>
									<i class="fa fa-caret-down"></i>
								</button>
								<div class="dropdown-content">
								<?php
								do{ ?>
									<a href="<?php echo $navigation_child_data['url'];?>"><?php echo $navigation_child_data['title'];?></a>
									<?php
								}while($navigation_child_data = mysqli_fetch_assoc($navigation_child_query)); ?>
							</div>
						</div> 
							<?php
						}
					};
				} ?>
				<div class="dropdown_container">
					<button class="dropdownbtn">Categories
						<i class="fa fa-caret-down"></i>
					</button>
					<div class="dropdown-content">
						<?php
						$category_string = "SELECT distinct cat_title, cat_id from category";
						$category_query = mysqli_query($connection,$category_string);
						while($category = mysqli_fetch_assoc($category_query)){ ?>
							<a href="<?php echo $base_url . '?category=' . $category['cat_id'];?>"><?php echo ucwords($category['cat_title']);?></a>
							<?php
						} ?>
					</div>
				</div>
			</div> 
		</div>
	</div>
<div class="line"></div>

<!-- Blue Print Of Navigation Bar is Written Below -->
<!-- <div class="navbar_container">
	<div class="navbar_left">
		<a href="#home">Home</a>
		<a href="#news">News</a>
		<div class="dropdown_container">
			<button class="dropdownbtn">Dropdown
				<i class="fa fa-caret-down"></i>
			</button>
			<div class="dropdown-content">
				<a href="#">Link 1</a>
				<a href="#">Link 2</a>
				<a href="#">Link 3</a>
			</div>
		</div> 
	</div>
</div>
<div class="line"></div> -->