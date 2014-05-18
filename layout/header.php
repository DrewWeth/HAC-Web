<header>
  <div id="logo">
	<div id="logo_text">
	  <!-- class="logo_colour", allows you to change the colour of the text -->
	  <?php
	  
	  $title = explode(" ", $config['site_title']);
	  
	  ?>
	  <h1><a href="index.php"><?php
	  
	  if (count($title) > 1) {
		foreach ($title as $word) {
			if ($word !== $title[count($title) - 1]) echo $word .' ';
		}
	  }
	  ?><span class="logo_colour"><?php
	  
	  if (count($title) > 1) {
		echo ($title[count($title) - 1]);
	  }
	  
	  ?></span></a></h1>
	  <h2><?php echo $config['site_title_context']; ?></h2>
	</div>
	  <nav class="navbar navbar-default" role="navigation">
	  	<div class="container-fluid">
	  		<div class="navbar-header">
		      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		    </div>
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		  		<ul class="nav navbar-nav">
			        <li><a href="index.php">Home</a></li>
					<li><a href="downloads.php">Downloads</a></li>
					<li><a href="serverinfo.php">Server Information</a></li>

			        <li class="dropdown">
			          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Community <b class="caret"></b></a>
			          <ul class="dropdown-menu">
			         	<li><a href="houses.php">Houses</a></li>
						<li><a href="deaths.php">Deaths</a></li>
						<li><a href="killers.php">Killers</a></li>
			          </ul>
			        </li>

			      	<li><a href="forum.php">Forum</a></li>

			      	<li class="dropdown">
			          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Shop <b class="caret"></b></a>
			          <ul class="dropdown-menu">
			         	<li><a href="buypoints.php">Buy Points</a></li>
						<li><a href="shop.php">Shop Offers</a></li>
			          </ul>
			        </li>

			        <li><a href="guilds.php">Guilds</a></li>
			    </ul>
			    <form type="submit" action="characterprofile.php" method="get" class="navbar-form navbar-left" role="search">
			        <div class="form-group">
			          <input type="text" class="form-control" name="name" class="search" placeholder="Search">
			        </div>
			    </form>
		    </div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
		</nav>
	</div>


<style>

body {
  margin: 0;
  background: #222;
  min-width: 960px;
}

rect {
  fill: none;
  pointer-events: all;
}

circle {
  fill: none;
  stroke-width: 2.5px;
}

</style>
<script src="http://d3js.org/d3.v3.min.js"></script>
<script>

var width = Math.max(960, innerWidth),
    height = Math.max(500, innerHeight);

var i = 0;

var svg = d3.select("site_content").append("svg")
    .attr("width", width)
    .attr("height", height);

svg.append("rect")
    .attr("width", width)
    .attr("height", height)
    .on("ontouchstart" in document ? "touchmove" : "mousemove", particle);

function particle() {
  var m = d3.mouse(this);

  svg.insert("circle", "rect")
      .attr("cx", m[0])
      .attr("cy", m[1])
      .attr("r", 1e-6)
      .style("stroke", d3.hsl((i = (i + 1) % 360), 1, .5))
      .style("stroke-opacity", 1)
    .transition()
      .duration(2000)
      .ease(Math.sqrt)
      .attr("r", 100)
      .style("stroke-opacity", 1e-6)
      .remove();

  d3.event.preventDefault();
}

</script>
</header>