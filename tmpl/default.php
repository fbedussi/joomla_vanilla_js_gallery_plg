<?php
defined('_JEXEC') or die;
?>
<div class="gallery-wrapper">
	<figure class="big-picture" >
		<div class="loading"><i class="icon-loop"></i></div>
		<div class="prev-next">
			<div class="prev">
				<a><i class="icon-chevron-left"></i></a>
			</div>
			<div class="next">
				<a><i class="icon-chevron-right"></i></a>
			</div>
		</div>
		<!--the img tag is non positioned so it is renderd on bottom of the stack, even if it comes after the prev-next block-->
		<img src="<?php print $imagesData->bigImageUrls[0] ?>" alt="<?php $imagesData->alts[0] ?>">
		<div class="curtain"></div>
	</figure>
	<nav>
		<ul>
			<li class="active">
				<div class="loading"><i class="icon-loop"></i></div>
				<a target="_blank" href="<?php print $imagesData->bigImageUrls[0] ?>">
					
					<img src="<?php print $imagesData->thumbUrls[0] ?>" alt="<?php $imagesData->alts[0] ?>">
				</a>
			</li>
			<li>
				<div class="loading"><i class="icon-loop"></i></div>
				<a target="_blank" href="<?php print $imagesData->bigImageUrls[1] ?>">
					<img src="<?php print $imagesData->thumbUrls[1] ?>" alt="<?php $imagesData->alts[1] ?>">
				</a>
			</li>
			<li>
				<div class="loading"><i class="icon-loop"></i></div>
				<a target="_blank" href="<?php print $imagesData->bigImageUrls[2] ?>">
					<img src="<?php print $imagesData->thumbUrls[2] ?>" alt="<?php $imagesData->alts[2] ?>">
				</a>
			</li>
			<li>
				<div class="loading"><i class="icon-loop"></i></div>
				<a target="_blank" href="<?php print $imagesData->bigImageUrls[3] ?>">
					<img src="<?php print $imagesData->thumbUrls[3] ?>" alt="<?php $imagesData->alts[3] ?>">
				</a>
			</li>
			<li>
				<div class="loading"><i class="icon-loop"></i></div>
				<a target="_blank" href="<?php print $imagesData->bigImageUrls[4] ?>">
					<img src="<?php print $imagesData->thumbUrls[4] ?>" alt="<?php $imagesData->alts[4] ?>">
				</a>
			</li>
		</ul>
	</nav>
</div>