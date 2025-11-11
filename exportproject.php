<?php
/**
 * File: exportproject.php
 * Description: Exports project data in various formats
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("includes/header.php");
?>
<style type='text/css'>
.howtostep {
	padding-top: 0px;
	font-size: 1.2em;
}
.olsteps {
	padding-left: 20px;
}

</style>

<div style="text-align:center;">
	<h2 class="wsite-content-title">EXPORTING STRABO PROJECT TO PC OR MAC</h2>
</div>

<div>
From time to time, it might be necessary or desirable to export a StraboMobile project from your
mobile device to a PC or Mac. You might want to keep a permanent offline copy of your projects' data,
or you might want to share a project with another user or a member of the Strabo development team.
Whatever the reason, the following steps should allow you to export a StraboMobile project to your PC or Mac.
</div>

<div style="text-align:center;padding-top:15px;">
	<h2 class="wsite-content-title">FIRST, EXPORT PROJECT TO MOBILE DEVICE</h2>
</div>

<div class="howtostep">
	<ol class="olsteps">
		<li>
			With the desired project and datasets active within StraboMobile, click on the upper-left menu and choose Project -> Manage.
		</li>
		<li>
			From the Manage Project page, click on the three-dot menu in the upper-right corner and choose Export Project to Device.
		</li>
		<li>
			Confirm or change the folder name you would like to export to. It is likely safe to keep the default value.
		</li>
		<li>
			Click Save to export the folder to your device.
		</li>
	</ol>
</div>

<div style="padding-top:15px;">
Now that the project has been exported to your mobile device, you will need to transfer this project
to your PC or Mac.
</div>

<div style="text-align:center;padding-top:15px;">
	<h2 class="wsite-content-title">ANDROID</h2>
</div>

<div class="howtostep">
	<ol class="olsteps">
		<li>
			If using a Mac, download and install <a href="http://www.android.com/filetransfer/" target="_blank">Android File Transfer</a> on your computer.
			Open Android File Transfer. The next time that you connect your mobile device, it will open automatically.
		</li>
		<li>
			Unlock your mobile device.
		</li>
		<li>
			With a USB cable, connect your mobile device to your computer.
		</li>
		<li>
			On your mobile device, tap the "Charging this device via USB" notification.
		</li>
		<li>
			Under "Use USB for," select File Transfer.
		</li>
		<li>
			An Android File Transfer window will open on your computer. Use it to drag files.
		</li>
		<li>
			The folder you exported from StraboMobile will be located in the root of your device in
			the StraboSpotProjects folder. You should transfer the folder created earlier to your computer.
		</li>
		<li>
			When you’re done, unplug the USB cable.
		</li>
		<li>
			Once the folder is on your local computer, it will be necessary to ZIP the folder in order
			to share the data with others.
		</li>
	</ol>
</div>

<div style="text-align:center;padding-top:15px;">
	<h2 class="wsite-content-title">iOS with Mac</h2>
</div>

<div class="howtostep">
	<ol class="olsteps">
		<li>
			Unlock your mobile device.
		</li>
		<li>
			With a USB cable, connect your mobile device to your Mac.
		</li>
		<li>
			Open finder on your Mac. Click on your mobile device located under Locations in the left-side bar.
		</li>
		<li>
			You should now see a list of applications on your iOS mobile device. Click on the arrow to the left
			of StraboSpot.
		</li>
		<li>
			Drag and drop the StraboSpotProjects folder to any location on your Mac. (for example, the Desktop)
		</li>
		<li>
			Your exported StraboMobile project data will be located in the StraboSpotProjects folder.
		</li>
		<li>
			When you’re done, unplug the USB cable.
		</li>
		<li>
			Once the folder is on your local computer, it will be necessary to ZIP the folder in order
			to share the data with others.
		</li>
	</ol>
</div>

<div style="text-align:center;padding-top:15px;">
	<h2 class="wsite-content-title">iOS with Windows and iTunes</h2>
</div>

<div class="howtostep">
	<ol class="olsteps">
		<li>
			Make sure that you have <a href="https://www.apple.com/itunes/" target="_blank">installed iTunes</a>.
		</li>
		<li>
			Unlock your mobile device.
		</li>
		<li>
			With a USB cable, connect your mobile device to your PC.
		</li>
		<li>
			Open iTunes, click the “Files” tab.
		</li>
		<li>
			Expand the StraboMobile application and click the StraboSpotProjects folder.
		</li>
		<li>
			Click “Sync” to transfer the StraboSpotProjects folder to your PC.
		</li>
		<li>
			Your exported StraboMobile project data will be located in the StraboSpotProjects folder.
		</li>
		<li>
			When you’re done, unplug the USB cable.
		</li>
		<li>
			Once the folder is on your local computer, it will be necessary to ZIP the folder in order
			to share the data with others.
		</li>
	</ol>
</div>

<?php
include("includes/footer.php");
?>