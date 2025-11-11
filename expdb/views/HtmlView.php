<?php
/**
 * File: HtmlView.php
 * Description: HtmlView class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class HtmlView extends ApiView {
	public function render($content) {
		header('Content-Type: application/html; charset=utf8');
		print_r($content);
		return true;
	}
}
