      </div>
    </div>
    <?php include 'layout/footer.php'; ?>
  </div>
  <!-- javascript at the bottom for fast page loading -->
  <script type="text/javascript">
    $(document).ready(function() {
      $('ul.sf-menu').sooperfish();
      $('.top').click(function() {$('html, body').animate({scrollTop:0}, 'fast'); return false;});
    });
  </script>
</body>
</html>
