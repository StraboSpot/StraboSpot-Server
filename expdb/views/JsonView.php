<?php
/**
 * File: JsonView.php
 * Description: JsonView class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class JsonView extends ApiView {
	public function render($content) {
		header('Content-Type: application/json');
		echo json_encode($content);
		return true;
	}
}
