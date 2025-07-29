<?php include 'components/js-scripts.php' ?>

<?php if (isset($_SESSION['FlashMessage'])) : ?>
  <script>
    runToast('<?= $_SESSION['FlashMessage']['message'] ?>', '<?= $_SESSION['FlashMessage']['status'] ?>');
  </script>
<?php endif; ?>
<!--
<div class="position-fixed opacity-25" style="bottom:0px;right:0px;">
  <a href="https://bhardwaj.netlify.app/" target="_blank"><span class="badge text-bg-light"> Made By Abhishek Bhardwaj</span></a>
</div>
-->
</body>

</html>