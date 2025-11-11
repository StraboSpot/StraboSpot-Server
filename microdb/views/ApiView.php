<?php
/**
 * File: ApiView.php
 * Description: ApiView class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class ApiView {
	protected function addCount($data) {
		if(!empty($data)) {
			// do nothing, this is added earlier
		} else {
			$data['meta']['count'] = 0;
		}
		return $data;
	}
}
