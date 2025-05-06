<footer>
    <div class="footer-top">
      <div class="footer-brand">
        <img src="/assets/photos/Logo.png" alt="Nebula Logo" />
        <h2>NEB<br>ULA<span>.com</span></h2>
      </div>
  
      <div class="footer-links">
        <div>
          <h4>Customer Success</h4>
          <ul>
            <li><a href="#">Shipping Policy</a></li>
            <li><a href="#">Refund Policy</a></li>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms of Service</a></li>
          </ul>
        </div>
        <div>
          <h4>About Nebula</h4>
          <ul>
            <li><a href="/about.php">About us</a></li>
            <li><a href="#">Our Blog</a></li>
            <li><a href="/contact.php">Contact us</a></li>
          </ul>
        </div>
        <div>
          <h4>Get Featured</h4>
          <ul>
            <li><a href="#">How to get featured in a book</a></li>
            <li><a href="#">Previously featured leaders</a></li>
            <li><a href="#">Representative work</a></li>
          </ul>
        </div>
        <div>
          <h4>We're here for you</h4>
          <p>Reach out to nebula@gmail.com for any questions or requests, and we'll get back to you within one business day.</p>
        </div>
        <div>
          <h4>Shop</h4>
          <ul>
            <li><a href="/about.php">About us</a></li>
            <li><a href="#">E Books</a></li>
            <li><a href="#">Bundles</a></li>
            <li><a href="#">Bestsellers</a></li>
          </ul>
        </div>
        <div>
          <h4>Editorial Services</h4>
          <ul>
            <li><a href="#">Book Writing</a></li>
            <li><a href="#">Book Editing</a></li>
            <li><a href="#">Book Printing</a></li>
            <li><a href="#">Book Marketing</a></li>
            <li><a href="#">Thesis Printing</a></li>
          </ul>
        </div>
      </div>
    </div>
  
    <div class="footer-bottom">
      <div class="socials">
        <a href="#"><img src="/assets/photos/fb.png" alt="Facebook"></a>
        <a href="#"><img src="/assets/photos/ig.png" alt="Instagram"></a>
        <a href="#"><img src="/assets/photos/x.png" alt="X"></a>
        <a href="#"><img src="/assets/photos/tiktok.png" alt="TikTok"></a>
      </div>
      <p>© <?php echo date('Y'); ?> All Rights Reserved</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
    crossorigin="anonymous"></script>
  <?php if (isset($additional_js)): ?>
    <script src="<?php echo $additional_js; ?>"></script>
  <?php endif; ?>
</body>
</html>