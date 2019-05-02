<?

function myWKT($data, $shape_type)
{
	if (!$data) return null;
	switch ($shape_type) {
		case 1:
			return 'POINT('.$data['x'].' '.$data['y'].')';

		case 11:
			return 'POINT('.$data['x'].' '.$data['y'].')';

		case 8:
			return 'MULTIPOINT'.myImplodePoints($data['points']);
		
		case 3:
			$wkt = array();
			if($data['parts']!=""){
				foreach ($data['parts'] as $part) {
					$wkt[] = myLineImplodeParts($part);
				}
				
				if ($data['numparts'] > 1) {
					return 'MULTILINESTRING('.implode(', ', $wkt).')';
				} else {
					return 'LINESTRING'.implode(', ', $wkt);
				}
				
			}else{
				return "";
			}
			
			/*
			if ($data['numparts'] > 1) {
				return 'MULTILINESTRING('.$this->ImplodeParts($data['parts']).')';
			} else {
				return 'LINESTRING'.ImplodeParts($data['parts']);
			}
			*/
		
		case 5:
			$wkt = array();
			foreach ($data['parts'] as $part) {
				$wkt[] = myImplodeParts($part);
			}
			if ($data['numparts'] > 1) {
				return 'MULTIPOLYGON('.implode(', ', $wkt).')';
			} else {
				return 'POLYGON'.implode(', ', $wkt).'';
			}
	}
}

function myImplodeParts($parts)
{
	$wkt = array();
	foreach ($parts as $part) {
		$wkt[] = '('.myImplodePoints($part).')';
	}
	return implode(', ', $wkt);
}

function myLineImplodeParts($parts)
{
	$wkt = array();
	foreach ($parts as $part) {
		$wkt[] = myImplodePoints($part);
	}
	return implode(', ', $wkt);
}

function myImplodePoints($points)
{
	$wkt = array();
	foreach ($points as $point) {
		$wkt[] = $point['x'].' '.$point['y'];
	}
	return '('.implode(', ', $wkt).')';
}

?>