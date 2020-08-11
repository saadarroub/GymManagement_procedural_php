 <section class="footer-section">
 <div class="container">
 <div class="row">
 <div class="col-md-6">
 <p>© Copyright 2020 - <strong>SMS</strong><p>
 </div>
 <div class="col-md-6 text-right my-social">
 <a href=""><i class="fa fa-linkedin-square"></i></a>
 <a href=""><i class="fa fa-xing-square"></i></a>
 <a href=""><i class="fa fa-github-square"></i></a>
 </div>
 </div>
 </div>
 </section>




 <!-- Bootstrap links-->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<!-- my script.js -->
<script src="<?= url_for('stylesheets/script.js'); ?>"></script>
 </body>
</html>

 <?php
  db_disconnect($db);
?>