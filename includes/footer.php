<!-- Footer -->
<footer class="site-footer">
    <div class="container">
        <div class="row">
            <!-- About -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="footer-brand">
                    <span class="brand-land">Land</span><span class="brand-scape">Scape</span>
                    <small>Travel</small>
                </div>
                <p class="footer-about mt-3">
                    شركة لاند سكيب للسفر والسياحة - نقدم أفضل برامج الحج والعمرة بأسعار تنافسية وخدمات عالية الجودة.
                </p>
                <div class="footer-social">
                    <?php if ($fb = getSetting('facebook')): ?>
                        <a href="<?= clean($fb) ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <?php endif; ?>
                    <?php if ($tw = getSetting('twitter')): ?>
                        <a href="<?= clean($tw) ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                    <?php endif; ?>
                    <?php if ($ig = getSetting('instagram')): ?>
                        <a href="<?= clean($ig) ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                    <?php endif; ?>
                    <?php if ($yt = getSetting('youtube')): ?>
                        <a href="<?= clean($yt) ?>" target="_blank"><i class="fab fa-youtube"></i></a>
                    <?php endif; ?>
                    <?php if ($wa = getSetting('whatsapp')): ?>
                        <a href="https://wa.me/<?= clean($wa) ?>" target="_blank"><i class="fab fa-whatsapp"></i></a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="footer-title">روابط سريعة</h5>
                <ul class="footer-links">
                    <li><a href="<?= BASE_URL ?>">الرئيسية</a></li>
                    <li><a href="<?= BASE_URL ?>umrah">برامج العمرة</a></li>
                    <li><a href="<?= BASE_URL ?>hajj">برامج الحج</a></li>
                    <li><a href="<?= BASE_URL ?>about">من نحن</a></li>
                    <li><a href="<?= BASE_URL ?>blog">المدونة</a></li>
                    <li><a href="<?= BASE_URL ?>contact">اتصل بنا</a></li>
                </ul>
            </div>

            <!-- Programs -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="footer-title">برامجنا</h5>
                <ul class="footer-links">
                    <li><a href="<?= BASE_URL ?>umrah">عمرة اقتصادية</a></li>
                    <li><a href="<?= BASE_URL ?>umrah">عمرة VIP</a></li>
                    <li><a href="<?= BASE_URL ?>hajj">حج VIP</a></li>
                    <li><a href="<?= BASE_URL ?>hajj">حج اقتصادي</a></li>
                    <li><a href="<?= BASE_URL ?>hajj">حج مباشر</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="footer-title">تواصل معنا</h5>
                <ul class="footer-contact">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?= clean(getSetting('address')) ?></span>
                    </li>
                    <li>
                        <i class="fas fa-phone-alt"></i>
                        <a href="tel:<?= clean(getSetting('phone')) ?>"><?= clean(getSetting('phone')) ?></a>
                    </li>
                    <li>
                        <i class="fab fa-whatsapp"></i>
                        <a href="https://wa.me/<?= clean(getSetting('whatsapp')) ?>"><?= clean(getSetting('whatsapp')) ?></a>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:<?= clean(getSetting('email')) ?>"><?= clean(getSetting('email')) ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0"><?= clean(getSetting('footer_text', 'جميع الحقوق محفوظة © 2024 Land Scape Travel')) ?></p>
                </div>
                <div class="col-md-6 text-start">
                    <p class="mb-0">تصميم وتطوير بواسطة <strong>Land Scape Travel</strong></p>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- WhatsApp Float Button -->
<?php if ($wa = getSetting('whatsapp')): ?>
<a href="https://wa.me/<?= clean($wa) ?>" class="whatsapp-float" target="_blank" title="تواصل معنا عبر واتساب">
    <i class="fab fa-whatsapp"></i>
</a>
<?php endif; ?>

<!-- Back to Top -->
<a href="#" class="back-to-top" id="backToTop">
    <i class="fas fa-chevron-up"></i>
</a>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<!-- Swiper -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<!-- Custom JS -->
<script src="<?= BASE_URL ?>assets/js/main.js"></script>

</body>
</html>
