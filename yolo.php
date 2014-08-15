
<!doctype html>

<html>

	<head>
		<link rel="stylesheet" href="web/static/css/lightbox.css">
		<link rel="stylesheet/less" href="web/static/less/bootstrap.less">
		<link rel="stylesheet/less" href="web/static/less/galeria.less">
		<script src="web/static/js/less.js"></script>
	</head>
	
	<body>
	
		<nav>
		
		</nav>
		
		<section style="width:calc(100% - 300px); height:100%;" id="gallery">
			
			<?php
				
				$dir = scandir('photos');
				unset($dir[0]);
				unset($dir[1]);
				shuffle($dir);

				for($i = 2; $i < count($dir); $i++) {
					
					
					/*
					$source_image = imagecreatefromjpeg(__DIR__ . '/photos/' . $dir[$i]);
					$width = imagesx($source_image);
					$height = imagesy($source_image);
					
					$desired_height = floor($height * (300 / $width));
					$virtual_image = imagecreatetruecolor(300, $desired_height);
					imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, 300, $desired_height, $width, $height);
					imagejpeg($virtual_image, __DIR__ . '/swag/' . $dir[$i]);
					*/
					
					
					?>
			
						<a data-title="<?= $dir[$i]; ?>" data-lightbox="randomizer" 
						    href="photos/<?= $dir[$i]; ?>">
							<img style="box-shadow:inset 0 0 10px black;" alt="<?= $dir[$i]; ?>" src="swag/<?= $dir[$i]; ?>" />
						</a>
			
					<?php
				}

			?>
			
		</section>
		
	</body>
	
	<script src="web/static/js/jquery.js"></script>
	<script src="web/static/js/justified-gallery.js"></script>
	<script src="web/static/js/lightbox.js"></script>
	<script src="web/static/js/galeria.js"></script>
	
	<script type="text/javascript">
		
		$('#gallery').justifiedGallery({
			
			margins: 1,
			randomize:true,
			lastRow: 'hide',
			rowHeight: 200
			
		});
		
	</script>

</html>