<?php
/**
 * File: microLandingClass.php
 * Description: MicroLanding class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

class MicroLanding
{

	 public function MicroLanding($json){
		 $this->json=$json;
		 $this->loadData($json);
	 }

	public function dumpVar($var){
		echo "<pre>";
		print_r($var);
		echo "</pre>";
	}

	public function loadMicroFields(){

		$this->microFields = [];
		$data = file_get_contents($_SERVER['DOCUMENT_ROOT']."/microdb/micro_fields.csv");
		$rows = explode("\n",$data);

		foreach($rows as $row){
			$thisPart = new stdClass();
			$parts = explode(",", $row);
			$thisPart->rawname = $parts[0];
			$thisPart->fixedName = $parts[1];
			if(trim($parts[2]) == "y"){
				$thisPart->show = "no";
			}else{
				$thisPart->show = "yes";
			}
			$this->microFields[] = $thisPart;
		}

		foreach($this->microFields as $f){
		}
	}

	public function fixMicroHeader($header){
		$header = strtolower($header);
		foreach($this->microFields as $field){
			if($field->rawname == $header){
				if($field->show == "yes"){
					return $field->fixedName;
				}else{
					return "";
				}
			}
		}

		return "";
	}

	public function showBasicHeaders($json, $excludes = []){

		foreach($json as $key=>$value){
			if(!in_array(strtolower($key), $excludes)){
				$fixedLabel = $this->fixMicroHeader($key);
				if($fixedLabel != "" && $value != ""){
					?>
					<div><strong><?php echo $fixedLabel?>:</strong> <?php echo $value?></div>
					<?php
				}
			}
		}

	}

	public function showArray($array, $label){
		if($array != ""){
			$parts = [];
			foreach($array as $part){
				$parts[] = $part->type;
			}
			$vals = implode(", ", $parts);
			?>
			<div><strong><?php echo $label?>:</strong> <?php echo $vals?></div>
			<?php
		}
	}

	public function loadData($json){
		$this->pkey = $json->pkey;
		$this->loadMicroFields();
		$this->loadAllMicrographs($json);
	}

	public function sideBarHTML(){
		foreach($this->allMicrographs as $m){
			if($m->parentID == ""){
				$this->recursiveShowSideMicrograph($m);
			}
		}
	}

	public function lineToThick($x1, $y1, $x2, $y2, $thick = 10){

		if ($thick == 1) {
			return "$x1, $y1, $x2, $y2";
		}
		$t = $thick / 2 - 0.5;
		if ($x1 == $x2 || $y1 == $y2) {
			return round(min($x1, $x2) - $t).", ".round(min($y1, $y2) - $t).", ".round(max($x1, $x2) + $t).", ".round(max($y1, $y2) + $t);
		}
		$k = ($y2 - $y1) / ($x2 - $x1); //y = kx + q
		$a = $t / sqrt(1 + pow($k, 2));
		$points = array(
			round($x1 - (1+$k)*$a), round($y1 + (1-$k)*$a),
			round($x1 - (1-$k)*$a), round($y1 - (1+$k)*$a),
			round($x2 + (1+$k)*$a), round($y2 - (1-$k)*$a),
			round($x2 + (1-$k)*$a), round($y2 + (1+$k)*$a),
		);

		return implode(", ", $points);

	}

	public function showMicrograph($id){
		$imagePath = "/straboMicroFiles/".$this->pkey."/images/".$id.".jpg";

		$useMap = "";
		$mapHtml = "";

		foreach($this->allMicrographs as $m){
			if($m->id == $id){

				$origWidth = $m->width;
				list($fileWidth, $fileHeight, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/".$this->pkey."/images/".$id.".jpg");

				$ratio = 750 / $origWidth;

				

				if($m->spots){
					$id = $m->id;
					$useMap = " usemap=\"#$id\"";
					$mapHtml = "<map name=\"$id\">\n";
					foreach($m->spots as $spot){
						if($spot->geometryType == "polygon"){
							$polyPoints = [];
							foreach($spot->points as $point){
								$polyPoints[] = floor($point->X * $ratio);
								$polyPoints[] = floor($point->Y * $ratio);
							}
							$mapHtml .= '<area shape="poly" coords="'.implode(", ", $polyPoints).'" href="javascript:showSpotDetails('.$this->pkey.', '.$spot->id.')">'."\n";
						}elseif($spot->geometryType == "point"){
							$x = floor($spot->points[0]->X * $ratio);
							$y = floor($spot->points[0]->Y * $ratio);
							$mapHtml .= '<area shape="circle" coords="'.$x.', '.$y.', 5" href="javascript:showSpotDetails('.$this->pkey.', '.$spot->id.')">'."\n";
						}elseif($spot->geometryType == "line"){
							$spotPoints = $spot->points;
							for($p = 1; $p < count($spotPoints); $p++ ){
								$thickPoints = $this->lineToThick($spotPoints[$p-1]->X * $ratio, $spotPoints[$p-1]->Y * $ratio, $spotPoints[$p]->X * $ratio, $spotPoints[$p]->Y * $ratio );
								$mapHtml .= '<area shape="poly" coords="'.$thickPoints.'" href="javascript:showSpotDetails('.$this->pkey.', '.$spot->id.')">'."\n";
							}
						}
					}
					$mapHtml .= "</map>\n";
					echo $mapHtml;

				}
			}
		}

		//figure out what percent is equal to 750px

		echo "<img width=\"750px\" src=\"/getMicroImage?pkey=$this->pkey&id=$id\" $useMap>";

		echo "<div><img src=\"/microLandingScaleBar?pkey=$this->pkey&id=$id\"></div>";

	}

	

	public function showFirstMicrograph(){
		$this->showMicrograph($this->allMicrographs[0]->id);
	}

	

	public function getFirstMicrographId(){
		return $this->allMicrographs[0]->id;
	}

	public function recursiveShowSideMicrograph($m, $padding = 0){
		$imagePath = "/straboMicroFiles/".$this->pkey."/images/".$m->id.".jpg";
		?>
		<div style="padding-left: <?php echo $padding?>px;">
		<strong><?php echo $m->name?></strong>
		<a href="javascript:switchToMicrograph('<?php echo $this->pkey?>', '<?php echo $m->id?>')";>
			<img width="<?php echo 200-$padding?>" src="<?php echo $imagePath?>">
		</a>
		</div>
		<?php

		foreach($this->allMicrographs as $nm){
			if($nm->parentID == $m->id){
				$this->recursiveShowSideMicrograph($nm, $padding + 10);
			}
		}

	}

	public function loadAllMicrographs($json){
		$this->allMicrographs = [];
		foreach($json->datasets as $d){
			foreach($d->samples as $s){
				foreach($s->micrographs as $m){
					$this->allMicrographs[] = $m;
				}
			}
		}
	}

	public function showSideDetails($type, $json, $sampleData, $excludes = [], $projectData){

		?>
		<h3>Sample Details</h3>
		<div>
		<?php
		$this->showBasicHeaders($sampleData, $excludes);
		?>
		</div>
		<?php

		if($type == "micrograph"){
			$header = "Micrograph Details";
		}else{
			$header = "Spot Details";
		}

		?>
		<h3><?php echo $header?></h3>
		<div>
		<?php
		$this->showBasicHeaders($json, $excludes);
		?>
		</div>
		<?php

		if($json->orientationInfo){
			?>
			<h3>Orientation Info:</h3>
			<div>
			<?php
			$this->showBasicHeaders($json->orientationInfo);
			?>
			</div>
			<?php
		}

		if($json->instrument){
			?>
			<h3>Instrument:</h3>
			<div>
			<?php
			$this->showBasicHeaders($json->instrument);
			if($json->instrument->instrumentDetectors){
				?>
				<strong>Detectors:</strong>
				<div style="padding-left:35px;">
				<ol>
				<?php
				foreach($json->instrument->instrumentDetectors as $dt){
					echo "<li>";
					echo $dt->detectorType;
					if($dt->detectorMake != "") echo ", ".$dt->detectorMake;
					if($dt->detectorModel != "") echo ", ".$dt->detectorModel;
					echo "</li>";
				}
				?>
				</ol>
				</div>
				<?php
			}
			?>
			</div>
			<?php
		}

		if($json->mineralogy || $json->lithologyInfo){
			?>
			<h3>Mineralogy/Lithology Info:</h3>
			<div>
			<?php
			if($json->mineralogy){
			?>

				<strong>Mineralogy:</strong>
				<div style="padding-left:5px;">
				<?php
				$this->showBasicHeaders($json->mineralogy);
				if($json->mineralogy->minerals){
					?>
					<strong>Minerals:</strong>
					<div style="padding-left:35px;">
					<ol>
					<?php
					foreach($json->mineralogy->minerals as $dt){
						echo "<li>";
						echo $dt->name;
						if($dt->operator != "") echo " ".$dt->operator;
						if($dt->percentage != "") echo " ".$dt->percentage;
						echo "</li>";
					}
					?>
					</ol>
					</div>
					<?php
				}
			?>
				</div>
			<?php
			}
			?>

			<?php
			if($json->lithologyInfo){
			?>

				<strong>Lithology:</strong>
				<div style="padding-left:5px;">
				<?php

				if($json->lithologyInfo->lithologies){
					?>
					<div style="padding-left:35px;">
					<ol>
					<?php
					foreach($json->lithologyInfo->lithologies as $dt){
						echo "<li>";

						$level1 = "";
						$level2 = "";
						$level3 = "";
						$maxLevel = "";
						$showString = "";

						$outString = "";
						if($dt->level1!=""){
							if($dt->level1=="Other"){
								if($dt->level1Other!=""){
									$level1 .= "Other:".$dt->level1Other;
								}else{
									$level1 .= "Other";
								}
							}else{
								$level1.= $dt->level1;
							}
							$maxLevel = "level1";
						}

						if($dt->level2!=""){
							if($dt->level2=="Other"){
								if($dt->level2Other!=""){
									$level2 = "Other:".$dt->level2Other;
								}else{
									$level2 = "Other";
								}
							}else{
								$level2 = $dt->level2;
							}
							$maxLevel = "level2";
						}

						if($dt->level3!=""){
							if($dt->level3=="Other"){
								if($dt->level3Other!=""){
									$level3 .= "Other:".$dt->level3Other;
								}else{
									$level3 .= "Other";
								}
							}else{
								$level3 .= $dt->level3;
							}
							$maxLevel = "level3";
						}

						if($maxLevel == "level3"){
							$showString = $level3 . " ($level1, $level2)";
						}elseif($maxLevel == "level2"){
							$showString = $level2 . " ($level1)";
						}else{
							$showString = $level1;
						}

						echo $showString;

						echo "</li>";
					}
					?>
					</ol>
					</div>
					<?php
				}
				$this->showBasicHeaders($json->lithologyInfo);
			?>
				</div>
			<?php
			}
			?>

			</div>
			<?php
		}

		if($json->grainInfo){
			?>
			<h3>Grain Info:</h3>
			<div>
			<?php
			if($json->grainInfo->grainSizeInfo){
				?>
				<strong>Grain Size Info:</strong>
				<div style="padding-left:25px;">
				<?php
					foreach($json->grainInfo->grainSizeInfo as $g){
						$this->showBasicHeaders($g);
						if($g->phases){
							$showPhases = implode(", ", $g->phases);
							?><div><strong>Phases:</strong> <?php echo $showPhases?></div><?php
						}
					}
				?>
				</div>
				<?php
			}

			if($json->grainInfo->grainShapeInfo){
				?>
				<strong>Grain Shape Info:</strong>
				<div style="padding-left:25px;">
				<?php
					foreach($json->grainInfo->grainShapeInfo as $g){
						$this->showBasicHeaders($g);
						if($g->phases){
							$showPhases = implode(", ", $g->phases);
							?><div><strong>Phases:</strong> <?php echo $showPhases?></div><?php
						}
					}
				?>
				</div>
				<?php
			}

			if($json->grainInfo->grainOrientationInfo){
				?>
				<strong>Grain Orientation Info:</strong>
				<div style="padding-left:25px;">
				<?php
					foreach($json->grainInfo->grainOrientationInfo as $g){
						$this->showBasicHeaders($g);
						if($g->phases){
							$showPhases = implode(", ", $g->phases);
							?><div><strong>Phases:</strong> <?php echo $showPhases?></div><?php
						}
					}
				?>
				</div>
				<?php
			}

			$this->showBasicHeaders($json->grainInfo);
			?>
			</div>
			<?php

		}

		if($json->fabricInfo){
			?>
			<h3>Fabric Info:</h3>
			<div>
			<?php

			foreach($json->fabricInfo->fabrics as $f){
				?>
				<strong>Fabric:</strong>
				<div style="padding-left:25px;">
				<?php
					$this->showBasicHeaders($f);
					if($f->fabricDefinedBy){
						$showPhases = implode(", ", $f->fabricDefinedBy);
						?><div><strong>Defined By:</strong> <?php echo $showPhases?></div><?php
					}

					if($f->fabricGrainSizeInfo){
						?>
						<strong>Fabric Grain Size Info:</strong>
						<div style="padding-left:25px;">
						<?php
							if($f->fabricGrainSizeInfo->layers){
								?>
								<strong>Layers:</strong>
								<ol>
								<?php
									foreach($f->fabricGrainSizeInfo->layers as $l){
										?>
										<li style="margin-left:25px;">Grain Size: <?php echo $l->grainSize?>, Thickness: <?php echo $l->thickness?> <?php echo $l->thicknessUnits?></li>
										<?php
									}
								?>
								</ol>
								<?php
							}
							$this->showBasicHeaders($f->fabricGrainSizeInfo);
						?>
						</div>
						<?php
					}

					if($f->fabricCompositionInfo){
						?>
						<strong>Fabric Composition Info:</strong>
						<div style="padding-left:25px;">
						<?php
							if($f->fabricCompositionInfo->layers){
								?>
								<strong>Layers:</strong>
								<ol>
								<?php
									foreach($f->fabricCompositionInfo->layers as $l){
										?>
										<li style="margin-left:25px;">Composition: <?php echo $l->composition?>, Thickness: <?php echo $l->thickness?> <?php echo $l->thicknessUnits?></li>
										<?php
									}
								?>
								</ol>
								<?php
							}
							$this->showBasicHeaders($f->fabricCompositionInfo);
						?>
						</div>
						<?php
					}

					if($f->fabricGrainShapeInfo){
						?>
						<strong>Fabric Grain Shape Info:</strong>
						<div style="padding-left:25px;">
						<?php
							$this->showBasicHeaders($f->fabricGrainShapeInfo);
							if($f->fabricGrainShapeInfo->phases){
								$showPhases = implode(", ", $f->fabricGrainShapeInfo->phases);
								?><div><strong>Phases:</strong> <?php echo $showPhases?></div><?php
							}
						?>
						</div>
						<?php
					}

					if($f->fabricCleavageInfo){
						?>
						<strong>Fabric Cleavage Info:</strong>
						<div style="padding-left:25px;">
						<?php
							$this->showBasicHeaders($f->fabricCleavageInfo);
							if($f->fabricCleavageInfo->geometryOfSeams){
								$showPhases = implode(", ", $f->fabricCleavageInfo->geometryOfSeams);
								?><div><strong>Geometry of Seams:</strong> <?php echo $showPhases?></div><?php
							}
						?>
						</div>
						<?php
					}

				?>
				</div>
				<?php
			}

			$this->showBasicHeaders($json->fabricInfo);
			?>
			</div>
			<?php
		}

		if($json->extinctionMicrostructureInfo){
			?>
			<h3>Extinction Microstructure Info:</h3>
			<div>
			<?php

			if($json->extinctionMicrostructureInfo->extinctionMicrostructures){

				foreach($json->extinctionMicrostructureInfo->extinctionMicrostructures as $v){

					?>
					<strong>Extinction Microstructure:</strong>
					<div style="padding-left:25px;">
					<?php
					//show details here

					if($v->phase != "") {
						?>
						<div><strong>Phase:</strong><?php echo $v->phase?></div>
						<?php
					}

					$this->showArray($v->dislocations, "Dislocations");
					$this->showArray($v->subDislocations, "Dislocation Types");
					$this->showArray($v->heterogeneousExtinctions, "Heterogeneous Extinctions");
					$this->showArray($v->subGrainStructures, "SubGrain Structures");
					$this->showArray($v->extinctionBands, "Extinction Bands");
					$this->showArray($v->subWideExtinctionBands, "Wide Extinction Bands");
					$this->showArray($v->subFineExtinctionBands, "Fine Extinction Bands");

					?>
					</div>
					<?php

				}
			}

			$this->showBasicHeaders($json->extinctionMicrostructureInfo);
			?>
			</div>
			<?php
		}

		if($json->clasticDeformationBandInfo){
			?>
			<h3>Clastic Deformation Band Info:</h3>
			<div>
			<?php

			foreach($json->clasticDeformationBandInfo->bands as $b){
				?>
				<strong>Band:</strong>
				<div style="padding-left:25px;">
				<?php
				$types = [];
				foreach($b->types as $t){
					$types[] = $t->type;
				}
				$types = implode(", ", $types);
				?><div><strong>Types:</strong> <?php echo $types?></div><?php
				$this->showBasicHeaders($b);
				?>
				</div>
				<?php
			}

			$this->showBasicHeaders($json->clasticDeformationBandInfo);
			?>
			</div>
			<?php
		}

		if($json->grainBoundaryInfo){
			?>
			<h3>Grain Boundary Info:</h3>
			<div>
			<?php

			foreach($json->grainBoundaryInfo->boundaries as $b){
				?>
				<strong>Boundary:</strong>
				<div style="padding-left:25px;">
				<?php

				$types = [];
				foreach($b->morphologies as $t){
					$types[] = $t->type;
				}
				$types = implode(", ", $types);
				?><div><strong>Morphologies:</strong> <?php echo $types?></div><?php

				if($b->descriptors){
					?>
					<div>
					<strong>Descriptors:</strong><br>
					<ol style="padding-left:25px;">
					<?php
					foreach($b->descriptors as $d){
						$thisD = "Type: ".$d->type." Subtypes: ";
						$types = [];
						foreach($d->subTypes as $t){
							$types[] = $t->type;
						}
						$types = implode(", ", $types);
						$thisD .= $types;
						?>
						<li><?php echo $thisD?></li>
						<?php
					}
					?>
					</ol>
					</div>
					<?php
				}

				$this->showBasicHeaders($b);
				?>
				</div>
				<?php
			}

			$this->showBasicHeaders($json->grainBoundaryInfo);
			?>
			</div>
			<?php
		}

		if($json->intraGrainInfo){
			?>
			<h3>IntraGrain Info:</h3>
			<div>
			<?php

			if($json->intraGrainInfo->grains){

				foreach($json->intraGrainInfo->grains as $g){

					?>
					<strong>Grain:</strong>
					<div style="padding-left:25px;">
						<div><strong>Mineral: <?php echo $g->mineral?></strong></div>
					<?php
					$textures = [];
					foreach($g->grainTextures as $gt){
						$textures[] = $gt->type;
					}
					$textures = implode(", ", $textures);
						?>
						<div><strong>Textures: <?php echo $textures?></strong></div>
						<?php
					?>
					</div>
					<?php

				}

			}

			$this->showBasicHeaders($json->intraGrainInfo);
			?>
			</div>
			<?php
		}

		if($json->veinInfo){
			?>
			<h3>Vein Info:</h3>
			<div>
			<?php

			if($json->veinInfo->veins){

				foreach($json->veinInfo->veins as $v){

					?>
					<strong>Vein:</strong>
					<div style="padding-left:25px;">
						<div><strong>Mineralogy: <?php echo $v->mineralogy?></strong></div>
					<?php

					$parts = [];
					foreach($v->crystalShapes as $bb){
						$parts[] = $bb->type;
					}
					$parts = implode(", ", $parts);

					?><div><strong>Crystal Shapes: <?php echo $parts?></strong></div><?php

					$parts = [];
					foreach($v->growthMorphologies as $bb){
						$parts[] = $bb->type;
					}
					$parts = implode(", ", $parts);

					?><div><strong>Growth Morphologies: <?php echo $parts?></strong></div><?php

					$show = "";
					foreach($v->inclusionTrails as $bb){
						$parts = [];
						if($bb->type) $parts[] = $bb->type;
						if($bb->subType) $parts[] = $bb->subType;
						if($bb->numericValue) $parts[] = $bb->numericValue;
						if($bb->unit) $parts[] = $bb->unit;
						$parts = implode(", ", $parts);
						$parts = "(".$parts.")";
						$show .= $parts;

					}

					?><div><strong>Inclusion Trails: <?php echo $show?></strong></div><?php

					$parts = [];
					foreach($v->kinematics as $bb){
						$parts[] = $bb->type;
					}
					$parts = implode(", ", $parts);

					?><div><strong>Kinematics: <?php echo $parts?></strong></div><?php

					?>
					</div>
					<?php

				}

			}

			$this->showBasicHeaders($json->veinInfo);
			?>
			</div>
			<?php
		}

		if($json->pseudotachylyteInfo){
			?>
			<h3>Pseudotachylyte Info:</h3>
			<div>
			<?php

			if($json->pseudotachylyteInfo->pseudotachylytes){

				foreach($json->pseudotachylyteInfo->pseudotachylytes as $v){

					?>
					<strong>Psudotachylyte:</strong>
					<div style="padding-left:25px;">
					<?php
					$this->showBasicHeaders($v);
					?>
					</div>
					<?php

				}
			}

			$this->showBasicHeaders($json->pseudotachylyteInfo);
			?>
			</div>
			<?php
		}

		if($json->foldInfo){
			?>
			<h3>Fold Info:</h3>
			<div>
			<?php

			if($json->foldInfo->folds){

				foreach($json->foldInfo->folds as $f){

					?>
					<strong>Fold:</strong>
					<div style="padding-left:25px;">
					<?php
					$this->showBasicHeaders($f);
					if($f->interLimbAngle){
						?>
						<div><strong>Inter-Limb Angle:</strong> <?php echo implode(", ", $f->interLimbAngle)?></div>
						<?php
					}
					?>
					</div>
					<?php

				}
			}

			$this->showBasicHeaders($json->foldInfo);
			?>
			</div>
			<?php
		}

		if($json->fractureInfo){

			?>
			<h3>Fracture Info:</h3>
			<div>
			<?php

			if($json->fractureInfo->fractures){

				foreach($json->fractureInfo->fractures as $f){

					?>
					<strong>Fracture:</strong>
					<div style="padding-left:25px;">
					<?php

					$show = "";
					$show .= "$f->granularity; ";
					$show .= "$f->mineralogy; ";
					$show .= "$f->kinematicType; ";

					if($f->openingAperture != ""){
						$show .= "Aperture: $f->openingAperture $f->openingApertureUnit; ";
					}

					if($f->shearOffset != ""){
						$show .= "Offset: $f->shearOffset $f->shearOffsetUnit; ";
					}

					if($f->hybridAperture != ""){
						$show .= "Aperture: $f->hybridAperture $f->hybridApertureUnit; ";
					}
					if($f->hybridOffset != ""){
						$show .= "Offset: $f->hybridOffset $f->hybridOffsetUnit; ";
					}

					if($f->sealedHealed == 1){
						$show .= "Sealed/Healed: Yes;";
					}else{
						$show .= "Sealed/Healed: No;";
					}

					//Show info for each fracture here.
					echo $show;

					?>
					</div>
					<?php

				}
			}

			$this->showBasicHeaders($json->fractureInfo);
			?>
			</div>
			<?php
		}

		if($json->faultsShearZonesInfo){
			?>
			<h3>Faults Shear Zones:</h3>
			<div>
			<?php

			if($json->faultsShearZonesInfo->faultsShearZones){

				foreach($json->faultsShearZonesInfo->faultsShearZones as $f){

					?>
					<strong>Fault Shear Zone:</strong>
					<div style="padding-left:25px;">
					<?php

					$label = "";

					if($f->shearSenses){
						$ssdelim = "";
						foreach($f->shearSenses as $ss){
							$label .= $ssdelim . $ss->type;
							$ssdelim = ", ";
						}
						$label .= "; ";
					}

					if($f->indicators){
						$ssdelim = "";
						foreach($f->indicators as $ss){
							$label .= $ssdelim . $ss->type;
							$ssdelim = ", ";
						}
						$label .= "; ";
					}

					if($f->offset){
						$label .= "Offset: $f->offset$f->offsetUnit; ";
					}

					if($f->width){
						$label .= "Width: $f->width$f->widthUnit;";
					}

					if($label != ""){
						echo $label;
					}

					?>
					</div>
					<?php

				}
			}

			$this->showBasicHeaders($json->faultsShearZonesInfo);
			?>
			</div>
			<?php
		}

		if($json->tags){
			?>
			<h3>Tags:</h3>
			<div>
			<ol>
			<?php
				foreach($json->tags as $tag){
						foreach($projectData->tags as $ptag){
							if($ptag->id == $tag){
								echo "<li>$ptag->name</li>";
							}
						}
				}
			?>
			</ol>
			</div>
			<?php
		}

		if($json->associatedFiles){
			?>
			<h3>Associated Files:</h3>
			<div>
			<ol>
			<?php
				foreach($json->associatedFiles as $af){
					?>
					<li>
						<a href="/straboMicroFiles/<?php echo $this->pkey?>/associatedFiles/<?php echo $af->fileName?>" target="_blank"><?php echo $af->fileName?></a>
					</li>
					<?php
				}
			?>
			</ol>
			</div>
			<?php
		}

		if($json->links){
			?>
			<h3>Links:</h3>
			<div>
			<ol>
			<?php
				foreach($json->links as $af){
					?>
					<li>
						<a href="<?php echo $af->url?>" target="_blank"><?php echo $af->label?></a>
					</li>
					<?php
				}
			?>
			</ol>
			</div>
			<?php
		}

	}

}
?>