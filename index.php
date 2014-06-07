<?php require_once 'engine/init.php'; include 'layout/overall/header.php';
	if ($config['allowSubPages'] && file_exists("layout/sub/index.php")) include 'layout/sub/index.php';
	else {
		$cache = new Cache('engine/cache/news');
		if ($cache->hasExpired()) {
			$news = fetchAllNews();
			
			$cache->setContent($news);
			$cache->save();
		} else {
			$news = $cache->load();
		}
		
		// Design and present the list
		if ($news) {
			function TransformToBBCode($string) {
				$tags = array(
					'[center]{$1}[/center]' => '<center>$1</center>',
					'[b]{$1}[/b]' => '<b>$1</b>',
					'[size={$1}]{$2}[/size]' => '<font size="$1">$2</font>',
					'[img]{$1}[/img]'    => '<a href="$1" target="_BLANK"><img src="$1" alt="image" style="width: 100%"></a>',
					'[link]{$1}[/link]'    => '<a href="$1">$1</a>',
					'[link={$1}]{$2}[/link]'   => '<a href="$1" target="_BLANK">$2</a>',
					'[color={$1}]{$2}[/color]' => '<font color="$1">$2</font>',
					'[*]{$1}[/*]' => '<li>$1</li>',
				);
				foreach ($tags as $tag => $value) {
					$code = preg_replace('/placeholder([0-9]+)/', '(.*?)', preg_quote(preg_replace('/\{\$([0-9]+)\}/', 'placeholder$1', $tag), '/'));
					$string = preg_replace('/'.$code.'/i', $value, $string);
				}
				return $string;
			}
			?>

			<?php
			$i = 1;
			foreach ($news as $n) {
				if ($i != 1)
					echo '<div class="well well-sm">';

			?>
					<h2>
						<?php echo TransformToBBCode($n['title'])."</h2> <small>".date($config['date'], $n['date'])."</small>"; ?>
		
					<p><?php echo TransformToBBCode(nl2br($n['text'])); ?></p>
			</div>
			<?php
			$i = $i + 1;
			}
			?>
			
			<?php 
		} else {
			echo '<p>No news exist.</p>';
		}
	}
include 'layout/overall/footer.php'; ?>