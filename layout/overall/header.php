<!DOCTYPE HTML>
<html>
<?php 
	$time = microtime();
	$time = explode(' ', $time);
	$time = $time[1] + $time[0];
	$start = $time;
include 'layout/head.php'; ?>
<body>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-50866761-1', 'honorandchaos.com');
  ga('send', 'pageview');

</script>
  <div id="main">
    <?php include 'layout/header.php'; ?>
    <div id="site_content">
      <?php include 'layout/aside.php'; ?>
      <div class="content">
