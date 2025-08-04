<?php include 'components/js-scripts.php' ?>

<?php if (isset($_SESSION['FlashMessage'])) : ?>
  <?php
  $flash = $_SESSION['FlashMessage'];
  unset($_SESSION['FlashMessage']);
  ?>
  <script>
    runToast(<?= json_encode($flash['message']) ?>, <?= json_encode($flash['status']) ?>);
  </script>
<?php endif; ?>
<!--
<div class="position-fixed opacity-25" style="bottom:0px;right:0px;">
  <a href="https://bhardwaj.netlify.app/" target="_blank"><span class="badge text-bg-light"> Made By Abhishek Bhardwaj</span></a>
</div>
-->
</body>

</html>