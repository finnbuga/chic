<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 */
?>
	</div><!-- #main .wrapper -->
	<footer id="colophon" role="contentinfo">

		<article class="contact">
			<header>
				<h2>Contact us</h2>
			</header>
			<section class="main">
				<div class="address">
					OTM Consulting Ltd<br>
					Great Burgh, Yew Tree Bottom Road<br>
					Epsom KT18 5XT<br>
				</div>
				<div class="phone">
					+44 1372 631950
				</div>
				<div class="email">
					<a href="mailto:networks@otmconsulting.com">networks@otmconsulting.com</a>
				</div>
			</section>
		</article>

		<article class="other-networks">
			<header>
				<h2>Other networks</h2>
			</header>
			<section class="main">
				<p>OTM helps companies to engage with sector and technology practitioners, through a unique combination of industry networks - AWES, DEA(e), ETF, GMN, PEA, ICT, PWRI, SEAFOM™, SEPs, MDIS, Oiltech, SIIS, SWiG, TMN and UMSIRE.</p>
			</section>
		</article>

		<article class="managed-by">
			<header>
				<h2>Managed by</h2>
			</header>
			<section class="main">
				<img style="float: right; margin: 0 0 .5rem .5rem;" src="<?php print get_stylesheet_directory_uri(); ?>/otm.png">
				<p>This network is managed by OTM. It was founded in 1989 and has continued to be strongly supported by the industry.</p>
			</section>
		</article>

		<div class="site-info">
			<p><a href="/cookies">Cookies</a> | <a href="/terms-of-use">Terms of use</a> | Copyright © <?php echo date("Y"); ?> OTM</p>
		</div>

	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
