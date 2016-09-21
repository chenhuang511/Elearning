				</div>
			</div>
			<footer class="footer" id="footer">
			<div class=" container">
				<div class="footer-inner">
					<ul class="nav nav-pills footer-nav">
						<li class="">
							<a href="#">Xác nhận quyền riêng tư</a>
						</li>
						<li class="">
							<a href="#">Giới thiệu</a>
						</li>
						<li class="">
							<a href="#">Liên hệ chúng tôi</a>
						</li>
						<li><a href="<?php echo base_url('admin'); ?>" title="Administer your site!">Admin area</a></li>
					</ul>
					<div id="powered-by" class="mahara-logo logo-area bhxh-table">
						<div class="logo-section bhxh-td">
							<a href="<?php echo base_url(); ?>" class="logo">
								<img src="<?php echo theme_url('/img/site-logo.png'); ?>" alt="qldt">
							</a>
						</div>
						<div class="logo-section bhxh-td">
							<h2 class="logo-title no-padding">
								Trường Đào Tạo Nghiệp Vụ <br> Bảo Hiểm Xã Hội Việt Nam
							</h2>
						</div>
					</div>
				</div>
			</div>
		</footer>
		<script src="<?php echo asset_url('/js/bootstrap.min.js'); ?>"></script>
		<script src="<?php echo theme_url('/js/main.js'); ?>"></script>
		<script>
			var nav = document.getElementById('header');
			var height = nav.offsetHeight ;
			console.log(height);
			window.onscroll = function (e) {
				var top = (window.pageYOffset || document.documentElement.scrollTop)  - (document.documentElement.clientTop || 0);
				if(top > height){
					if(nav.className.indexOf('f-nav') == -1){
						nav.className += " f-nav";
					}
				} else {
					nav.className = nav.className.replace(/\b f-nav\b/,'');
				}
			}
		</script>
    </body>
</html>
