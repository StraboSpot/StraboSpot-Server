<?php
/**
 * File: microTabs.php
 * Description: Generates tab navigation for Strabo Micro interface
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


	// Tabs for Micro Project landing page.

?>

<script>
	function openTab(evt, tabId) {
	  // Declare all variables
	  var i, tabcontent, tablinks;

	  tabcontent = document.getElementsByClassName("tabcontent");
	  for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = "none";
	  }

	  tablinks = document.getElementsByClassName("tablinks");
	  for (i = 0; i < tablinks.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" active", "");
	  }

	  document.getElementById(tabId).style.display = "block";
	  evt.currentTarget.className += " active";
	}
</script>

<style type='text/css'>
	.tab {
	  overflow: hidden;
	  border: 1px solid #ccc;
	  background-color: #f1f1f1;
	}

	/* Style the buttons that are used to open the tab content */
	.tab button {
	  background-color: inherit;
	  float: left;
	  border: none;
	  border-right: 1px solid #ccc;
	  outline: none;
	  cursor: pointer;
	  padding: 2px 8px;
	  transition: 0.3s;
	  font-size:.9em;
	}

	/* Change background color of buttons on hover */
	.tab button:hover {
	  background-color: #ea6143;
	  color:#fff;
	}

	.tab button.active {
	  background-color: #ea6143;
	  color:#fff;
	}

	/* Style the tab content */
	.tabcontent {
	  display: none;
	  padding: 6px 12px;
	  border: 1px solid #ccc;
	  border-top: none;
	}
</style>