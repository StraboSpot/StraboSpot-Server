<?php
/**
 * File: footer.php
 * Description: Page footer template
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */
?>
<!-- begin footer -->
<!-- Matomo -->
<script>
  var _paq = window._paq = window._paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
	var u="//stats.strabospot.org/";
	_paq.push(['setTrackerUrl', u+'matomo.php']);
	_paq.push(['setSiteId', '1']);
	var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
	g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Matomo Code -->

				</div>
			</div>
		</div>

	<div class="push"></div>
</div>
</div>
<div class="footer">
	<span class = "footerpart"><a href="https://www.nsf.gov/" target="_blank"><img src="/includes/images/NSFLogo.png" height="40px">Funded by the National Science Foundation</a></span>
	<span class = "footerpart">&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;</span>
	<span class = "footerpart"><a href="https://www.mapbox.com/" target="_blank"><img src="/includes/images/mapboxLogo.png" height="40px"> Maps provided by Mapbox</a></span>
	<span class = "footerpart">&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;</span>
	<span class = "footerpart"><a href="https://www.earthcube.org/" target="_blank"><img src="/includes/images/earthCubeLogo.png" height="40px"> EarthCube Partner</a></span>
</div>
</body>
</html>