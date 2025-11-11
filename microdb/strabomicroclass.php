<?php
/**
 * File: strabomicroclass.php
 * Description: StraboMicro class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class StraboMicro
{

	 public function StraboMicro($neodb,$userpkey,$db){
		 $this->neodb=$neodb;
		 $this->userpkey=$userpkey;
		 $this->db=$db;
	 }

	 public function setuserpkey($userpkey){
		 $this->userpkey=$userpkey;
	 }

	 public function setneohandler($neodb){
		 $this->neodb=$neodb;
	 }

	 public function setdbhandler($db){
		 $this->db=$db;
	 }

	 public function setuuid($uuid){
		 $this->uuid=$uuid;
	 }

	public function logToFile($var,$label=null){
		if(is_writable("uploadLog.txt")){
			if($label==""){$label="LogToFile";}
			$out = print_r($var, true);
			file_put_contents ("uploadLog.txt", "\n\n$label\n$out \n", FILE_APPEND);
		}
	}

	public function dumpVar($var){
		echo "<pre>";
		print_r($var);
		echo "<pre>";
	}

	function isJson($string) {
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}

	public function createId(){
		$time = time();
		$extra = rand(111,999);
		$id = (int) $time.$extra;
		return $id;
	}

	public function getProjectInfo($project_id){
		$project = $this->db->get_row("select * from micro_projectmetadata where userpkey = $this->userpkey and strabo_id='$project_id'");
		$out = new stdClass();
		if($project->id != ""){
			$out->count = 1;

			$p = json_decode($project->projectjson);

			foreach($p->datasets as $d){
				foreach($d->samples as $s){
					foreach($s->micrographs as $m){
						$m->imageURL = "https://strabospot.org/microdb/image/" . $m->id;
					}
				}
			}

			$out->projectDetails = $p;
		}else{
			$out->count = 0;
		}

		return $out;
	}

	public function getWebProject($project_id){
		$project = $this->db->get_row("select * from micro_projectmetadata where userpkey = $this->userpkey and strabo_id='$project_id'");
		$out = new stdClass();
		if($project->id != ""){
			$pkey = $project->id;
			$uuid = $this->uuid->v4();

			$docRoot = $_SERVER['DOCUMENT_ROOT'];

			mkdir("$docRoot/ziptemp/$uuid");
			mkdir("$docRoot/ziptemp/$uuid/$project_id");

			exec("cp -rp $docRoot/straboMicroFiles/$pkey/project.json $docRoot/ziptemp/$uuid/$project_id/");
			exec("cp -rp $docRoot/straboMicroFiles/$pkey/project.pdf $docRoot/ziptemp/$uuid/$project_id/");
			exec("cp -rp $docRoot/straboMicroFiles/$pkey/associatedFiles $docRoot/ziptemp/$uuid/$project_id/");
			exec("cp -rp $docRoot/straboMicroFiles/$pkey/webImages $docRoot/ziptemp/$uuid/$project_id/");
			exec("cp -rp $docRoot/straboMicroFiles/$pkey/webThumbnails $docRoot/ziptemp/$uuid/$project_id/");

			exec("cd $docRoot/ziptemp/$uuid; zip -r $project_id.zip $project_id");

			header("Content-Type: application/zip");
			header("Content-Disposition: attachment; filename=$project_id.zip");
			header("Content-Length: " . filesize("$docRoot/ziptemp/$uuid/$project_id.zip"));

			readfile("$docRoot/ziptemp/$uuid/$project_id.zip");

			exit();

		}else{
			$out->Error = "Project not found.";
		}

		return $out;
	}

	public function oldgetProjectPDF_20241122($project_id){
		$project = $this->db->get_row("select * from micro_projectmetadata where userpkey = $this->userpkey and strabo_id='$project_id'");
		$out = new stdClass();
		if($project->id != ""){
			$pkey = $project->id;
			$uuid = $this->uuid->v4();

			$docRoot = $_SERVER['DOCUMENT_ROOT'];

			mkdir("$docRoot/ziptemp/$uuid");
			mkdir("$docRoot/ziptemp/$uuid/$project_id");

			exec("cp -rp $docRoot/straboMicroFiles/$pkey/project.json $docRoot/ziptemp/$uuid/$project_id/");
			exec("cp -rp $docRoot/straboMicroFiles/$pkey/project.pdf $docRoot/ziptemp/$uuid/$project_id/");
			//Just using the PDF for now. We can re-implement these later if the web viewer is needed. JMA 20241121

			exec("cd $docRoot/ziptemp/$uuid; zip -r $project_id.zip $project_id");

			header("Content-Type: application/zip");
			header("Content-Disposition: attachment; filename=$project_id.zip");
			header("Content-Length: " . filesize("$docRoot/ziptemp/$uuid/$project_id.zip"));

			readfile("$docRoot/ziptemp/$uuid/$project_id.zip");

			exit();

		}else{
			$out->Error = "Project not found.";
		}

		return $out;
	}

	public function getProjectPDF($project_id){
		$project = $this->db->get_row("select * from micro_projectmetadata where userpkey = $this->userpkey and strabo_id='$project_id'");
		$out = new stdClass();
		if($project->id != ""){
			$pkey = $project->id;
			$uuid = $this->uuid->v4();

			$docRoot = $_SERVER['DOCUMENT_ROOT'];

			mkdir("$docRoot/ziptemp/$uuid");
			mkdir("$docRoot/ziptemp/$uuid/$project_id");

			$mod = $this->db->get_var("select round(extract(epoch from uploaddate)*1000) as modifiedtimestamp from micro_projectmetadata where strabo_id = '$project_id'");

			$json = file_get_contents("$docRoot/straboMicroFiles/$pkey/project.json");
			$json = json_decode($json);

			unset($json->modifiedTimestamp);
			$json->modifiedtimestamp = (int)$mod;

			$json = json_encode($json, JSON_PRETTY_PRINT);
			file_put_contents("$docRoot/ziptemp/$uuid/$project_id/project.json", $json);

			exec("cp -rp $docRoot/straboMicroFiles/$pkey/project.pdf $docRoot/ziptemp/$uuid/$project_id/");
			//Just using the PDF for now. We can re-implement these later if the web viewer is needed. JMA 20241121

			exec("cd $docRoot/ziptemp/$uuid; zip -r $project_id.zip $project_id");

			header("Content-Type: application/zip");
			header("Content-Disposition: attachment; filename=$project_id.zip");
			header("Content-Length: " . filesize("$docRoot/ziptemp/$uuid/$project_id.zip"));

			readfile("$docRoot/ziptemp/$uuid/$project_id.zip");

			exit();

		}else{
			$out->Error = "Project not found.";
		}

		return $out;
	}

	public function getProjectURL($project_id){
		$project = $this->db->get_row("select * from micro_projectmetadata where userpkey = $this->userpkey and strabo_id='$project_id'");
		$out = new stdClass();
		if($project->id != ""){

			$id = $project->id;
			$out->url = "/straboMicroFiles/".$id."/project.zip";
			$out->bytes = filesize($_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/".$id."/project.zip");

			$out->micrograph_count = $this->db->get_var("
				select count(mg.id)
				from
				micro_projectmetadata p,
				micro_datasetmetadata d,
				micro_samplemetadata s,
				micro_micrographmetadata mg
				where
				p.id = d.project_id and
				d.id = s.dataset_id and
				s.id = mg.sample_id
				and p.strabo_id = '$project_id'
			");

		}else{
			$out->bytes = 0;
		}

		return $out;
	}

	public function getProjectMicrographCount($project_id){
		$count = $this->db->get_var("
			select count(mg.id)
			from
			micro_projectmetadata p,
			micro_datasetmetadata d,
			micro_samplemetadata s,
			micro_micrographmetadata mg
			where
			p.id = d.project_id and
			d.id = s.dataset_id and
			s.id = mg.sample_id
			and p.strabo_id = '$project_id'
		");

		$out = new stdClass();
		if($project->id != ""){

			$out->count = $count;

		}else{
			$out->count = 0;
		}

		return $out;
	}

	public function getSharedURL($id){

		$out = new stdClass();

		$out->url = "/straboMicroFiles/".$id."/project.zip";
		$out->bytes = filesize($_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/".$id."/project.zip");

		return $out;
	}

	public function getProjectFolderFromImageId($image_id){
		$project_folder = $this->db->get_var("select proj.id from
											micro_projectmetadata proj,
											micro_datasetmetadata dat,
											micro_samplemetadata samp,
											micro_micrographmetadata mic
											where
											mic.sample_id = samp.id and
											samp.dataset_id = dat.id and
											dat.project_id = proj.id and
											proj.userpkey = $this->userpkey and
											mic.strabo_id = '$image_id';");

		return $project_folder;

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

	public function getMyProjects(){

		$projects = [];

		$rows = $this->db->get_results("select
										id,
										strabo_id,
										name,
										round(extract(epoch from uploaddate)*1000) as modifiedtimestamp,
										TO_CHAR(uploaddate, 'mm/dd/yyyy HH:MMPM TZ OF') as uploaddate
										from micro_projectmetadata where userpkey = $this->userpkey order by id desc");
		foreach($rows as $row){
			$p = new stdClass();
			$p->id = $row->strabo_id;
			$p->name = $row->name;
			$p->self = "https://strabospot.org/microdb/project/".$row->strabo_id;
			$p->modifiedtimestamp = (int)$row->modifiedtimestamp;
			$p->uploaddate = $row->uploaddate;
			$p->bytes = filesize($_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/".$row->id."/project.zip");

			$projects[] = $p;
		}

		$data['projects'] = $projects;

		return $data;

	}

//

	public function showTabbedDetails($json, $excludes = []){

		//use ob start to capture the output before showing headers

		//show all data for micrograph or spot, including mineralogy, folds, etc...

		$tabs = [];

		ob_start();

		if($json->orientationInfo){
			$tabs[] = "orientation";
			?>
			<div id="<?php echo $json->id?>-orientation" class="tabcontent">
				<strong>Orientation Info:</strong>
				<div style="padding-left:25px;">
				<?php
				$this->showBasicHeaders($json->orientationInfo);
				?>
				</div>
			</div>
			<?php
		}

		if($json->instrument){
			$tabs[] = "instrument";
			?>
			<div id="<?php echo $json->id?>-instrument" class="tabcontent">
				<strong>Instrument:</strong>
				<div style="padding-left:25px;">
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
			</div>
			<?php
		}

		if($json->mineralogy){
			$tabs[] = "mineralogy";
			?>
			<div id="<?php echo $json->id?>-mineralogy" class="tabcontent">
				<strong>Mineralogy Info:</strong>
				<div style="padding-left:25px;">
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
			</div>
			<?php
		}

		if($json->grainInfo){
			$tabs[] = "grain";
			?>
			<div id="<?php echo $json->id?>-grain" class="tabcontent">
				<strong>Grain Info:</strong>
				<div style="padding-left:25px;">
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
			$tabs[] = "fabric";
			?>
			<div id="<?php echo $json->id?>-fabric" class="tabcontent">
				<strong>Fabric Info:</strong>
				<div style="padding-left:25px;">
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
			</div>
			<?php
		}

		if($json->clasticDeformationBandInfo){
			$tabs[] = "clasticDeformation";
			?>
			<div id="<?php echo $json->id?>-clasticDeformation" class="tabcontent">
				<strong>Clastic Deformation Band Info:</strong>
				<div style="padding-left:25px;">
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
			</div>
			<?php
		}

		if($json->grainBoundaryInfo){
			$tabs[] = "grainBoundary";
			?>
			<div id="<?php echo $json->id?>-grainBoundary" class="tabcontent">
				<strong>Grain Boundary Info:</strong>
				<div style="padding-left:25px;">
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
			</div>
			<?php
		}

		if($json->intraGrainInfo){
			$tabs[] = "intraGrain";
			?>
			<div id="<?php echo $json->id?>-intraGrain" class="tabcontent">
			<strong>IntraGrain Info:</strong>
			<div style="padding-left:25px;">
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
			</div>
			<?php
		}

		if($json->veinInfo){
			$tabs[] = "vein";
			?>
			<div id="<?php echo $json->id?>-vein" class="tabcontent">
				<strong>Vein Info:</strong>
				<div style="padding-left:25px;">
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
			</div>
			<?php
		}

		if($json->pseudotachylyteInfo){
			$tabs[] = "pseudotachylyte";
			?>
			<div id="<?php echo $json->id?>-pseudotachylyte" class="tabcontent">
				<strong>Pseudotachylyte Info:</strong>
				<div style="padding-left:25px;">
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
			</div>
			<?php
		}

		if($json->foldInfo){
			$tabs[] = "fold";
			?>
			<div id="<?php echo $json->id?>-fold" class="tabcontent">
				<strong>Fold Info:</strong>
				<div style="padding-left:25px;">
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
			</div>
			<?php
		}

		if($json->associatedFiles){
			$tabs[] = "associatedFiles";
			?>
			<div id="<?php echo $json->id?>-associatedFiles" class="tabcontent">
				<strong>Associated Files:</strong>
				<div style="padding-left:25px;">
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
			</div>
			<?php
		}

		if($json->links){
			$tabs[] = "links";
			?>
			<div id="<?php echo $json->id?>-links" class="tabcontent">
			<strong>Links:</strong>
			<div style="padding-left:25px;">
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
			</div>
			<?php
		}

		//create the divs
		$divsHtml = ob_get_contents();
		ob_end_clean();

		$tabsHtml = "<strong>Details:</strong>";
		$tabsHtml .= "<div class=\"tab\">\n";

		foreach($tabs as $t){

			$tabsHtml .= "<button class=\"tablinks\" onclick=\"openTab(event, '".$json->id."-$t')\">$t</button>";

		}

		$tabsHtml .= "</div>\n";

		if(count($tabs) == 0){
			$divsHtml = "";
			$tabsHtml = "";
		}

		return array($divsHtml, $tabsHtml);

	}

	public function showDetails($json, $excludes = []){

		$this->showBasicHeaders($json, $excludes);

		list($divsHtml, $tabsHtml) = $this->showTabbedDetails($json, $excludes);

		echo $tabsHtml;
		echo $divsHtml;

	}

	public function oldshowDetails($json, $excludes = []){

		//show all data for micrograph or spot, including mineralogy, folds, etc...
		$this->showBasicHeaders($json, $excludes);

		if($json->orientationInfo){
			?>
			<strong>Orientation Info:</strong>
			<div style="padding-left:25px;">
			<?php
			$this->showBasicHeaders($json->orientationInfo);
			?>
			</div>
			<?php
		}

		if($json->instrument){
			?>
			<strong>Instrument:</strong>
			<div style="padding-left:25px;">
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

		if($json->mineralogy){
			?>
			<strong>Mineralogy Info:</strong>
			<div style="padding-left:25px;">
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

		if($json->grainInfo){
			?>
			<strong>Grain Info:</strong>
			<div style="padding-left:25px;">
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
			<strong>Fabric Info:</strong>
			<div style="padding-left:25px;">
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

		if($json->clasticDeformationBandInfo){
			?>
			<strong>Clastic Deformation Band Info:</strong>
			<div style="padding-left:25px;">
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
			<strong>Grain Boundary Info:</strong>
			<div style="padding-left:25px;">
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
			<strong>IntraGrain Info:</strong>
			<div style="padding-left:25px;">
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
			<strong>Vein Info:</strong>
			<div style="padding-left:25px;">
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
			<strong>Pseudotachylyte Info:</strong>
			<div style="padding-left:25px;">
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
			<strong>Fold Info:</strong>
			<div style="padding-left:25px;">
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

		if($json->associatedFiles){
			?>
			<strong>Associated Files:</strong>
			<div style="padding-left:25px;">
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
			<strong>Links:</strong>
			<div style="padding-left:25px;">
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

	public function recursiveShowMicrograph($m, $padding = 0){
		$imagePath = "/straboMicroFiles/".$this->pkey."/images/".$m->id.".jpg";
		?>
		<div style="padding-left: <?php echo $padding?>px;">
		<h2><?php echo $m->name?></h2>
		<img width="<?php echo 900-$padding?>" src="<?php echo $imagePath?>">
		<?php
		$this->showDetails($m, ['name']);

		//Now do spots (if they exist)
		if($m->spots){
			?><div style="padding-top:7px;"><strong>Spots:</strong></div><?php
			foreach($m->spots as $spot){
				?>
				<div style="padding-left:10px;">
				<strong><?php echo $spot->name?></strong>
				</div>
				<div style="padding-left:30px;">
					<?php $this->showDetails($spot, ['name','showlabel','color','modifiedtimestamp']);?>
				</div>
				<?php
			}
		}

		?>
		</div>
		<?php
		foreach($this->allMicrographs as $nm){
			if($nm->parentID == $m->id){
				$this->recursiveShowMicrograph($nm, $padding + 30);
			}
		}
	}

	public function loadData($json){
		$this->pkey = $json->pkey;
		$this->loadMicroFields();
		$this->loadAllMicrographs($json);
	}

	public function sideBarHTML($json){
		foreach($s->micrographs as $m){
			if($m->parentID == ""){
				$this->recursiveShowSideMicrograph($m);
			}
		}
	}

	public function htmlProject($json){
		$this->pkey = $json->pkey;
		$this->loadMicroFields();
		$this->loadAllMicrographs($json);
		?>
		<div style="text-align:center;"><h2><?php echo $json->name?></h2></div>
		<?php

		$this->showBasicHeaders($json, ['name']);

		foreach($json->datasets as $d){
		?>

			<h2>Dataset: <?php echo $d->name?></h2>
			<div style="padding-left:20px;">
				<?php
				foreach($d->samples as $s){
					?>
					<div style="padding-left:20px;">
						<h2>Sample: <?php echo $s->label?></h2>
						<?php
						$this->showBasicHeaders($s, ['label']);
						?>
						<div><h3>Micrographs:<h3></div>
						<?php
						foreach($s->micrographs as $m){
							if($m->parentID == ""){
								$this->recursiveShowMicrograph($m);
							}
						}
						?>
					</div>
					<?php
				}
				?>
			</div>

		<?php
		}

	}

	public function insertProject($post,$files){

		//First, check to see if project exists
		$strabo_project_id = $post['project_id'];

		$count = $this->db->get_var("select count(*) from micro_projectmetadata where userpkey = $this->userpkey and strabo_id='$strabo_project_id'");
		$overwrite = $post['overwrite'];

		$shareKey = $this->db->get_var("select sharekey from micro_projectmetadata where userpkey = $this->userpkey and strabo_id='$strabo_project_id'");

		if($strabo_project_id != ""){
			if($count > 0 && $overwrite=="no"){
				//error here
				$data = new stdClass();
				$data->Error = "Project already exists on server.";
			}else{
				//Delete project just in case
				$this->deleteProject($strabo_project_id);

				//OK, let's populate the project
				$project_metadata_id = $this->db->get_var("select nextval('micro_projectmetadata_id_seq')");

				//Next, create folder
				mkdir($_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/".$project_metadata_id);

				$filename = $files[tmp_name];
				exec("/usr/bin/unzip $filename -d /srv/app/www/straboMicroFiles/$project_metadata_id");

				//now move files up one directory
				exec("/bin/mv /srv/app/www/straboMicroFiles/$project_metadata_id/$strabo_project_id/* /srv/app/www/straboMicroFiles/$project_metadata_id");
				exec("/bin/rm -r /srv/app/www/straboMicroFiles/$project_metadata_id/$strabo_project_id/");

				$json = file_get_contents($_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/".$project_metadata_id."/project.json");
				$json = utf8_encode($json);

				$data = $this->loadProjectJSON($json, $project_metadata_id, $shareKey);

				$this->createProjectImages($json, $project_metadata_id, $strabo_project_id);
				$this->deleteTempFiles($project_metadata_id, $strabo_project_id);

				$this->db->query("update micro_projectmetadata set original_filename = '".$files['name']."' where userpkey = $this->userpkey and strabo_id='$strabo_project_id'");

				if($data->Error != ""){
					return $data;
				}

				//copy original file to folder
				copy($filename, $_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/".$project_metadata_id."/project.zip");

				//delete unzipped files

				$data = new stdClass();
				$data->status = "success";
				$data->message = "Project uploaded successfully.";

			}
		}else{
			$data = new stdClass();
			$data->Error = "No project id provided.";
		}

		return $data;
	}

	public function insertProjectWithoutFile($post){

		//First, check to see if project exists
		$strabo_project_id = $post['project_id'];

		$count = $this->db->get_var("select count(*) from micro_projectmetadata where userpkey = $this->userpkey and strabo_id='$strabo_project_id'");
		$overwrite = $post['overwrite'];

		$shareKey = $this->db->get_var("select sharekey from micro_projectmetadata where userpkey = $this->userpkey and strabo_id='$strabo_project_id'");

		if($strabo_project_id != ""){
			if($count > 0 && $overwrite=="no"){
				//error here
				$data = new stdClass();
				$data->Error = "Project already exists on server.";
			}else{
				//Delete project just in case
				$this->deleteProject($strabo_project_id);

				//OK, let's populate the project
				$project_metadata_id = $this->db->get_var("select nextval('micro_projectmetadata_id_seq')");

				//Next, create folder
				mkdir($_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/".$project_metadata_id);

				$filename = "/StraboData/bigDriveData/tempFiles/micro_".$strabo_project_id.".zip";
				exec("/usr/bin/unzip $filename -d /srv/app/www/straboMicroFiles/$project_metadata_id");

				//now move files up one directory
				exec("/bin/mv /srv/app/www/straboMicroFiles/$project_metadata_id/$strabo_project_id/* /srv/app/www/straboMicroFiles/$project_metadata_id");
				exec("/bin/rm -r /srv/app/www/straboMicroFiles/$project_metadata_id/$strabo_project_id/");

				$json = file_get_contents($_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/".$project_metadata_id."/project.json");
				$json = utf8_encode($json);

				$data = $this->loadProjectJSON($json, $project_metadata_id, $shareKey);

				$this->createProjectImages($json, $project_metadata_id, $strabo_project_id);
				$this->deleteTempFiles($project_metadata_id, $strabo_project_id);

				$this->db->query("update micro_projectmetadata set original_filename = '".$files['name']."' where userpkey = $this->userpkey and strabo_id='$strabo_project_id'");

				if($data->Error != ""){
					return $data;
				}

				//copy original file to folder
				rename($filename, $_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/".$project_metadata_id."/project.zip");

				//delete unzipped files

				$data = new stdClass();
				$data->status = "success";
				$data->message = "Project uploaded successfully.";

			}
		}else{
			$data = new stdClass();
			$data->Error = "No project id provided..";
		}

		return $data;
	}

		//First, check to see if project exists
				//error here
				//Delete project just in case
				//OK, let's populate the project
				//Next, create folder
				//now move files up one directory
				//copy original file to folder
				//delete unzipped files

	public function deleteTempFiles($project_metadata_id){
		exec("rm -rf ".$_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/".$project_metadata_id."/compositeImages/");
		exec("rm -rf ".$_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/".$project_metadata_id."/compositeThumbnails/");
		exec("rm -rf ".$_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/".$project_metadata_id."/thumbnailImages");
		exec("rm -rf ".$_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/".$project_metadata_id."/uiImages");
	}

	public function hexColorsToInteger($hexval){

		$redHex = substr($hexval, 2,2);
		$greenHex = substr($hexval, 4,2);
		$blueHex = substr($hexval, 6,2);

		$redDec = hexdec($redHex);
		$greenDec = hexdec($greenHex);
		$blueDec = hexdec($blueHex);

		return [$redDec, $greenDec, $blueDec];
	}

	public function imagelinethick($image, $x1, $y1, $x2, $y2, $color, $thick = 1){

		if ($thick == 1) {
			return imageline($image, $x1, $y1, $x2, $y2, $color);
		}
		$t = $thick / 2 - 0.5;
		if ($x1 == $x2 || $y1 == $y2) {
			return imagefilledrectangle($image, round(min($x1, $x2) - $t), round(min($y1, $y2) - $t), round(max($x1, $x2) + $t), round(max($y1, $y2) + $t), $color);
		}
		$k = ($y2 - $y1) / ($x2 - $x1); 
		$a = $t / sqrt(1 + pow($k, 2));
		$points = array(
			round($x1 - (1+$k)*$a), round($y1 + (1-$k)*$a),
			round($x1 - (1-$k)*$a), round($y1 - (1+$k)*$a),
			round($x2 + (1+$k)*$a), round($y2 - (1-$k)*$a),
			round($x2 + (1-$k)*$a), round($y2 + (1+$k)*$a),
		);
		imagefilledpolygon($image, $points, 4, $color);
		return imagepolygon($image, $points, 4, $color);
	}

	public function createProjectImages($string, $project_metadata_id){

		mkdir($_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/$project_metadata_id/images");

		$j = json_decode($string);

		//datasets samples micrographs
		//first, gather all micrograhps
		//then roll over each micrograph, and create base image
		//then look for any micrograph with parent of current and add to base image

		$allMicrographs = [];
		foreach($j->datasets as $d){
			foreach ($d->samples as $s){
				foreach($s->micrographs as $m){
					$allMicrographs[] = $m;
				}
			}
		}

		foreach($allMicrographs as $m){
			$maxDimension = 2000;
			$id = $m->id;
			$scale = $m->scalePixelsPerCentimeter;

			$origImage = imagecreatefrompng($_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/$project_metadata_id/uiImages/".$id);
			list($originalWidth, $originalHeight) = getimagesize($_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/$project_metadata_id/uiImages/".$id);

			if($originalWidth > $originalHeight){
				$newWidth = $maxDimension;
				$newHeight = floor($maxDimension * ($originalHeight/$originalWidth));
			}else{
				$newHeight = $maxDimension;
				$newWidth = floor($maxDimension * ($originalWidth/$originalHeight));
			}

			$baseRatio = $newHeight / $originalHeight;

			$newImage = imagecreatetruecolor($newWidth, $newHeight);

			imagecopyresampled($newImage, $origImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

			$spots = $m->spots;

			foreach($allMicrographs as $m){
				if($m->parentID == $id){
					//OK, add this image
					$subId = $m->id;
					$subScale = $m->scalePixelsPerCentimeter;
					$subImage = imagecreatefrompng($_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/$project_metadata_id/uiImages/".$subId);
					$subOrigWidth = imagesx($subImage);
					$subOrigHeight = imagesy($subImage);
					$subOffsetX = $m->offsetInParent->X * $baseRatio;
					$subOffsetY = $m->offsetInParent->Y * $baseRatio;

					//first scale, then rotate
					$subWidth = floor($scale/$subScale * $baseRatio * $subOrigWidth);
					$subHeight = floor($scale/$subScale * $baseRatio * $subOrigHeight);

					$rotation = $m->rotation * -1;

					$subImage = imagescale($subImage, $subWidth);

					$rotatedImage = imagecreatetruecolor($subWidth, $subHeight);

					imagesavealpha($rotatedImage , true);
					$pngTransparency = imagecolorallocatealpha($rotatedImage , 0, 0, 0, 127);
					imagefill($rotatedImage , 0, 0, $pngTransparency);

					imagecopyresampled($rotatedImage, $subImage, 0, 0, 0, 0, $subWidth, $subHeight, $subWidth, $subHeight);

					$rotatedImage = imagerotate($rotatedImage, $rotation, $pngTransparency);

					$rotatedWidth = imagesx($rotatedImage);
					$rotatedHeight = imagesy($rotatedImage);

					$deltaWidth = ($rotatedWidth - $subWidth) / 2;
					$deltaHeight = ($rotatedHeight - $subHeight) / 2;

					imagecopyresampled($newImage, $rotatedImage, $subOffsetX - $deltaWidth, $subOffsetY - $deltaHeight, 0, 0, $rotatedWidth, $rotatedHeight, $rotatedWidth, $rotatedHeight);

				}
			}

			if(count($spots) > 0){

				$shadowColor = imagecolorallocate($newImage, 25, 25, 25);

				$font = $_SERVER['DOCUMENT_ROOT']."/microdb/fonts/VeraBd.ttf";
				$fontSize = 36; //18

				foreach($spots as $spot){
					$values = [];
					$xTotal = 0;
					$yTotal = 0;
					$numPoints = 0;

					//move colors here
					list($red, $green, $blue) = $this->hexColorsToInteger($spot->labelColor);
					$fontColor = imagecolorallocate($newImage, $red, $green, $blue);

					list($red, $green, $blue) = $this->hexColorsToInteger($spot->color);
					$spotColor = imagecolorallocatealpha($newImage, $red, $green, $blue, 25);

					if($spot->geometryType=="polygon"){
						foreach($spot->points as $point){
							$values[] = floor($point->X * $baseRatio);
							$values[] = floor($point->Y * $baseRatio);
							$xTotal += floor($point->X * $baseRatio);
							$yTotal += floor($point->Y * $baseRatio);
							$numPoints ++;
						}

						imagefilledpolygon($newImage, $values, count($values)/2, $spotColor);

						$centerX = floor($xTotal / $numPoints);
						$centerY = floor($yTotal / $numPoints);

						imagettftext($newImage, $fontSize, 0, $centerX + 10 + 3, $centerY - 10 + 3, $shadowColor, $font, $spot->name);
						imagettftext($newImage, $fontSize, 0, $centerX + 10 , $centerY - 10, $fontColor, $font, $spot->name);

					}elseif($spot->geometryType=="line"){

						foreach($spot->points as $point){
							$xTotal += floor($point->X * $baseRatio);
							$yTotal += floor($point->Y * $baseRatio);
							$numPoints ++;
						}

						$centerX = floor($xTotal / $numPoints);
						$centerY = floor($yTotal / $numPoints);

						$points = $spot->points;
						for($z = 1; $z < count($points); $z++){
							$this->imagelinethick($newImage, floor($points[$z-1]->X * $baseRatio), floor($points[$z-1]->Y * $baseRatio), floor($points[$z]->X * $baseRatio), floor($points[$z]->Y * $baseRatio), $spotColor, 10);
						}

						imagettftext($newImage, $fontSize, 0, $centerX + 10 + 3, $centerY - 10 + 3, $shadowColor, $font, $spot->name);
						imagettftext($newImage, $fontSize, 0, $centerX + 10 , $centerY - 10, $fontColor, $font, $spot->name);

					}elseif($spot->geometryType=="point"){
						$centerX = floor($spot->points[0]->X * $baseRatio);
						$centerY = floor($spot->points[0]->Y * $baseRatio);

						imagefilledellipse($newImage, $centerX, $centerY, 20, 20, $spotColor);

						imagettftext($newImage, $fontSize, 0, $centerX + 10 + 3, $centerY - 10 + 3, $shadowColor, $font, $spot->name);
						imagettftext($newImage, $fontSize, 0, $centerX + 10 , $centerY - 10, $fontColor, $font, $spot->name);
					}
				}
			}

			ob_start();
			imagejpeg($newImage);
			$data = ob_get_clean();
			file_put_contents($_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/$project_metadata_id/images/".$id.".jpg",$data);

		}

	}

	public function getRandString(){
		$returnString = "";
		$chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 't', 'u', 'v', 'w', 'x', 'y', 'z', '2', '3', '4', '6', '7', '8', '9');
		for($x=0; $x<6; $x++){
			$returnString .= $chars[rand(0,count($chars)-1)];
		}

		$count = $this->db->get_var("select count(*) from micro_projectmetadata where sharekey = '$returnString'");

		if($count == 0){
			return $returnString;
		}else{
			return $this->getRandString();
		}
	}

	public function sharedProjectExists($inkey){

		$count = $this->db->get_var("select count(*) from micro_projectmetadata where sharekey = '$inkey'");

		if($count==0){
			return false;
		}else{
			return true;
		}

	}

	public function getSharedProjectID($inkey){
		$row = $this->db->get_row("select * from micro_projectmetadata where sharekey = '$inkey'");

		$out = $row->id;

		return $out;
	}

	public function getKeywords($var){

		$ignoreVars = array(
			"id",
			"date",
			"strat_section_id",
			"modified_timestamp",
			"self",
			"time",
			"image_type",
			"lineColor",
			"fillColor"
		);

		if(is_object($var)){
			foreach($var as $key=>$value){
				if(!in_array($key, $ignoreVars)){
					$outval = print_r($value, true);
					$out .= " " . $this->getKeywords($value);
				}
			}
		}elseif(is_array($var)){
			if(array_keys($var) !== range(0, count($var) - 1)){
				//associative
				foreach($var as $key=>$value){
					if(!in_array($key, $ignoreVars)){
						$out .= " " . $this->getKeywords($value);
					}
				}
			}else{
				foreach($var as $v){
					$out .= " " . $this->getKeywords($v);
				}
			}

		}else{
			if(!is_numeric($var) && !is_bool($var)){
				$out .= " " . $var;
			}
		}

		return $out;
	}

	public function loadProjectJSON($string, $project_metadata_id, $shareKey) {

		$micrographcount = 0;

		$userpkey = $this->userpkey;

		$thisprojectmetadata = json_decode($string);

		if($thisprojectmetadata->id==""){

			$data = new stdClass();
			$data->Error = "Invalid file detectedd.";
			return $data;
		}

		//Project
		$query = "";
		$vars = ['id','userpkey','projectjson'];
		$vals = [$project_metadata_id, $userpkey, "'".pg_escape_string(utf8_encode($string))."'"];

		if($thisprojectmetadata->id!="") {$vars[]='strabo_id'; $vals[]= "'".pg_escape_string($thisprojectmetadata->id)."'"; }
		if($thisprojectmetadata->name!="") {$vars[]='name'; $vals[]= "'".pg_escape_string($thisprojectmetadata->name)."'"; }
		if($thisprojectmetadata->startDate!="") {$vars[]='startdate'; $vals[]= "'".pg_escape_string($thisprojectmetadata->startDate)."'"; }
		if($thisprojectmetadata->endDate!="") {$vars[]='enddate'; $vals[]= "'".pg_escape_string($thisprojectmetadata->endDate)."'"; }
		if($thisprojectmetadata->purposeOfStudy!="") {$vars[]='purposeofstudy'; $vals[]= "'".pg_escape_string($thisprojectmetadata->purposeOfStudy)."'"; }
		if($thisprojectmetadata->otherTeamMembers!="") {$vars[]='otherteammembers'; $vals[]= "'".pg_escape_string($thisprojectmetadata->otherTeamMembers)."'"; }
		if($thisprojectmetadata->areaOfInterest!="") {$vars[]='areaofinterest'; $vals[]= "'".pg_escape_string($thisprojectmetadata->areaOfInterest)."'"; }
		if($thisprojectmetadata->instrumentsUsed!="") {$vars[]='instrumentsused'; $vals[]= "'".pg_escape_string($thisprojectmetadata->instrumentsUsed)."'"; }
		if($thisprojectmetadata->gpsDatum!="") {$vars[]='gpsdatum'; $vals[]= "'".pg_escape_string($thisprojectmetadata->gpsDatum)."'"; }
		if($thisprojectmetadata->magneticDeclination!="") {$vars[]='magneticdeclination'; $vals[]= "'".pg_escape_string($thisprojectmetadata->magneticDeclination)."'"; }
		if($thisprojectmetadata->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisprojectmetadata->notes)."'"; }
		if($thisprojectmetadata->date!="") {$vars[]='date'; $vals[]= "'".pg_escape_string($thisprojectmetadata->date)."'"; }
		if($thisprojectmetadata->modifiedTimestamp!="") {$vars[]='modifiedtimestamp'; $vals[]= "'".pg_escape_string($thisprojectmetadata->modifiedTimestamp)."'"; }
		if($thisprojectmetadata->projectLocation!="") {$vars[]='projectlocation'; $vals[]= "'".pg_escape_string($thisprojectmetadata->projectLocation)."'"; }

		//sharekey for sharing project
		if($shareKey != ""){
			$vars[]='sharekey'; $vals[]= "'".$shareKey."'";
		}else{
			$vars[]='sharekey'; $vals[]= "'".$this->getRandString()."'";
		}

		$userrow = $this->db->get_row("select * from users where pkey = $userpkey");
		$firstname = $userrow->firstname;
		$lastname = $userrow->lastname;

		$keyjson = json_decode($string);
		$keywords = $this->getKeywords($keyjson);

		$keywords .= " ".$firstname;
		$keywords .= " ".$lastname;

		$keywords = pg_escape_string($keywords);

		$vars[]='keywords'; $vals[]= "to_tsvector('".$keywords."')";

		$query = "insert into micro_projectmetadata (\n";
		$query .= implode(",\n", $vars);
		$query .= ") values (\n";
		$query .= implode(",\n", $vals);
		$query .= ")\n";

		$this->db->query($query);

		$collectedTags = [];
		if($thisprojectmetadata->tags != ""){
			$this->logToFile($thisprojectmetadata->tags, "tags");
			foreach($thisprojectmetadata->tags as $tag){
				$tag_id = $this->db->get_var("select nextval('micro_tag_id_seq')");
				$query = "";
				$vars = ['id','project_id', 'json'];
				$vals = [$tag_id, $project_metadata_id, "'".pg_escape_string(json_encode($tag))."'"];
				if($tag->id!="") {$vars[]='strabo_id'; $vals[]= "'".pg_escape_string($tag->id)."'"; }
				if($tag->name!="") {$vars[]='name'; $vals[]= "'".pg_escape_string($tag->name)."'"; }
				if($tag->tagType!="") {$vars[]='tagtype'; $vals[]= "'".pg_escape_string($tag->tagType)."'"; }
				if($tag->tagSubtype!="") {$vars[]='tagsubtype'; $vals[]= "'".pg_escape_string($tag->tagSubtype)."'"; }
				if($tag->otherConcept!="") {$vars[]='otherconcept'; $vals[]= "'".pg_escape_string($tag->otherConcept)."'"; }
				if($tag->otherDocumentation!="") {$vars[]='otherdocumentation'; $vals[]= "'".pg_escape_string($tag->otherDocumentation)."'"; }
				if($tag->otherTagType!="") {$vars[]='othertagtype'; $vals[]= "'".pg_escape_string($tag->otherTagType)."'"; }
				if($tag->lineColor!="") {$vars[]='linecolor'; $vals[]= "'".pg_escape_string($tag->lineColor)."'"; }
				if($tag->fillColor!="") {$vars[]='fillcolor'; $vals[]= "'".pg_escape_string($tag->fillColor)."'"; }
				if($tag->transparency!="") {$vars[]='transparency'; $vals[]= "'".pg_escape_string($tag->transparency)."'"; }
				if($tag->tagSize!="") {$vars[]='tagsize'; $vals[]= "'".pg_escape_string($tag->tagSize)."'"; }
				if($tag->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($tag->notes)."'"; }

				$query = "insert into micro_tag (\n";
				$query .= implode(",\n", $vars);
				$query .= ") values (\n";
				$query .= implode(",\n", $vals);
				$query .= ")\n";
				$this->db->query($query);

				$this->logToFile($query, "query");

				//Add to collectedTags strabo_id, pg_id, json
				$collectedTag = new stdClass();
				$collectedTag->strabo_id = $tag->id;
				$collectedTag->pg_id = $tag_id;
				$collectedTag->json = json_encode($tag);
				$collectedTags[] = $collectedTag;

			}
		}

		$this->logToFile($collectedTags, "collectedTags");

		if($thisprojectmetadata->datasets != ""){

			foreach($thisprojectmetadata->datasets as $thisdatasetmetadata){

				$datasetmetadata_id = $this->db->get_var("select nextval('micro_datasetmetadata_id_seq')");
				$query = "";
				$vars = ['id','project_id'];
				$vals = [$datasetmetadata_id, $project_metadata_id];
				if($thisdatasetmetadata->id!="") {$vars[]='strabo_id'; $vals[]= "'".pg_escape_string($thisdatasetmetadata->id)."'"; }
				if($thisdatasetmetadata->name!="") {$vars[]='name'; $vals[]= "'".pg_escape_string($thisdatasetmetadata->name)."'"; }
				if($thisdatasetmetadata->date!="") {$vars[]='date'; $vals[]= "'".pg_escape_string($thisdatasetmetadata->date)."'"; }
				if($thisdatasetmetadata->modifiedTimestamp!="") {$vars[]='modifiedtimestamp'; $vals[]= "'".pg_escape_string($thisdatasetmetadata->modifiedTimestamp)."'"; }
				$query = "insert into micro_datasetmetadata (\n";
				$query .= implode(",\n", $vars);
				$query .= ") values (\n";
				$query .= implode(",\n", $vals);
				$query .= ")\n";

				$this->db->query($query);

				if($thisdatasetmetadata->samples != ""){

					foreach($thisdatasetmetadata->samples as $thissamplemetadata){

						$samplemetadata_id = $this->db->get_var("select nextval('micro_samplemetadata_id_seq')");
						$query = "";
						$vars = ['id','dataset_id'];
						$vals = [$samplemetadata_id, $datasetmetadata_id];
						if($thissamplemetadata->id!="") {$vars[]='strabo_id'; $vals[]= "'".pg_escape_string($thissamplemetadata->id)."'"; }
						if($thissamplemetadata->existsOnServer!=""){ $vars[]='existsonserver'; $vals[]= ($thissamplemetadata->existsOnServer > 0 ? 'true' : 'false'); }
						if($thissamplemetadata->label!="") {$vars[]='label'; $vals[]= "'".pg_escape_string($thissamplemetadata->label)."'"; }
						if($thissamplemetadata->sampleID!="") {$vars[]='sampleid'; $vals[]= "'".pg_escape_string($thissamplemetadata->sampleID)."'"; }

						if($thissamplemetadata->longitude!="") {$vars[]='longitude'; $vals[]= "$thissamplemetadata->longitude"; }
						if($thissamplemetadata->latitude!="") {$vars[]='latitude'; $vals[]= "$thissamplemetadata->latitude"; }

						if($thissamplemetadata->mainSamplingPurpose!="") {$vars[]='mainsamplingpurpose'; $vals[]= "'".pg_escape_string($thissamplemetadata->mainSamplingPurpose)."'"; }
						if($thissamplemetadata->sampleDescription!="") {$vars[]='sampledescription'; $vals[]= "'".pg_escape_string($thissamplemetadata->sampleDescription)."'"; }
						if($thissamplemetadata->materialType!="") {$vars[]='materialtype'; $vals[]= "'".pg_escape_string($thissamplemetadata->materialType)."'"; }
						if($thissamplemetadata->inplacenessOfSample!="") {$vars[]='inplacenessofsample'; $vals[]= "'".pg_escape_string($thissamplemetadata->inplacenessOfSample)."'"; }
						if($thissamplemetadata->orientedSample!="") {$vars[]='orientedsample'; $vals[]= "'".pg_escape_string($thissamplemetadata->orientedSample)."'"; }
						if($thissamplemetadata->sampleSize!="") {$vars[]='samplesize'; $vals[]= "'".pg_escape_string($thissamplemetadata->sampleSize)."'"; }
						if($thissamplemetadata->degreeOfWeathering!="") {$vars[]='degreeofweathering'; $vals[]= "'".pg_escape_string($thissamplemetadata->degreeOfWeathering)."'"; }
						if($thissamplemetadata->sampleNotes!="") {$vars[]='samplenotes'; $vals[]= "'".pg_escape_string($thissamplemetadata->sampleNotes)."'"; }
						if($thissamplemetadata->sampleType!="") {$vars[]='sampletype'; $vals[]= "'".pg_escape_string($thissamplemetadata->sampleType)."'"; }
						if($thissamplemetadata->color!="") {$vars[]='color'; $vals[]= "'".pg_escape_string($thissamplemetadata->color)."'"; }
						if($thissamplemetadata->lithology!="") {$vars[]='lithology'; $vals[]= "'".pg_escape_string($thissamplemetadata->lithology)."'"; }
						if($thissamplemetadata->sampleUnit!="") {$vars[]='sampleunit'; $vals[]= "'".pg_escape_string($thissamplemetadata->sampleUnit)."'"; }
						if($thissamplemetadata->otherMaterialType!="") {$vars[]='othermaterialtype'; $vals[]= "'".pg_escape_string($thissamplemetadata->otherMaterialType)."'"; }
						if($thissamplemetadata->sampleOrientationNotes!="") {$vars[]='sampleorientationnotes'; $vals[]= "'".pg_escape_string($thissamplemetadata->sampleOrientationNotes)."'"; }
						if($thissamplemetadata->otherSamplingPurpose!="") {$vars[]='othersamplingpurpose'; $vals[]= "'".pg_escape_string($thissamplemetadata->otherSamplingPurpose)."'"; }
						$query = "insert into micro_samplemetadata (\n";
						$query .= implode(",\n", $vars);
						$query .= ") values (\n";
						$query .= implode(",\n", $vals);
						$query .= ")\n";

						$this->db->query($query);

						if($thissamplemetadata->micrographs != ""){

							foreach($thissamplemetadata->micrographs as $thismicrographmetadata){

								$micrographcount ++;

								$micrographmetadata_id = $this->db->get_var("select nextval('micro_micrographmetadata_id_seq')");
								$query = "";
								$vars = ['id','sample_id'];
								$vals = [$micrographmetadata_id, $samplemetadata_id];
								if($thismicrographmetadata->id!="") {$vars[]='strabo_id'; $vals[]= "'".pg_escape_string($thismicrographmetadata->id)."'"; }
								if($thismicrographmetadata->parentID!="") {$vars[]='parentid'; $vals[]= "'".pg_escape_string($thismicrographmetadata->parentID)."'"; }
								if($thismicrographmetadata->name!="") {$vars[]='name'; $vals[]= "'".pg_escape_string($thismicrographmetadata->name)."'"; }
								if($thismicrographmetadata->imageType!="") {$vars[]='imagetype'; $vals[]= "'".pg_escape_string($thismicrographmetadata->imageType)."'"; }
								if($thismicrographmetadata->width!=""){ $vars[]='width'; $vals[]= $thismicrographmetadata->width; }
								if($thismicrographmetadata->height!=""){ $vars[]='height'; $vals[]= $thismicrographmetadata->height; }
								if($thismicrographmetadata->scale!="") {$vars[]='scale'; $vals[]= "'".pg_escape_string($thismicrographmetadata->scale)."'"; }
								if($thismicrographmetadata->polish!=""){ $vars[]='polish'; $vals[]= ($thismicrographmetadata->polish == 1 ? 'true' : 'false'); }
								if($thismicrographmetadata->polishDescription!="") {$vars[]='polishdescription'; $vals[]= "'".pg_escape_string($thismicrographmetadata->polishDescription)."'"; }
								if($thismicrographmetadata->description!="") {$vars[]='description'; $vals[]= "'".pg_escape_string($thismicrographmetadata->description)."'"; }
								if($thismicrographmetadata->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thismicrographmetadata->notes)."'"; }
								if($thismicrographmetadata->scalePixelsPerCentimeter!=""){ $vars[]='scalepixelspercentimeter'; $vals[]= $thismicrographmetadata->scalePixelsPerCentimeter; }
								if($thismicrographmetadata->offsetInParent!="") {
									$vars[]='offsetinparent_x'; $vals[]= "'".$thismicrographmetadata->offsetInParent->X."'";
									$vars[]='offsetinparent_y'; $vals[]= "'".$thismicrographmetadata->offsetInParent->Y."'";
								}
								if($thismicrographmetadata->pointInParent!="") {
									$vars[]='pointinparent_x'; $vals[]= "'".$thismicrographmetadata->pointInParent->X."'";
									$vars[]='pointinparent_y'; $vals[]= "'".$thismicrographmetadata->pointInParent->Y."'";
								}
								if($thismicrographmetadata->rotation!=null){ $vars[]='rotation'; $vals[]= $thismicrographmetadata->rotation; }
								if($thismicrographmetadata->isMicroVisible!=""){ $vars[]='ismicrovisible'; $vals[]= ($thismicrographmetadata->isMicroVisible == 1 ? 'true' : 'false'); }

								if($thismicrographmetadata->tags!=""){

									$micrograph_tags = [];

									foreach($thismicrographmetadata->tags as $strabo_tag_id){
										foreach($collectedTags as $collectedTag){
											if($collectedTag->strabo_id == $strabo_tag_id){
												$query = "insert into micro_micrograph_tag (micrograph_id, tag_id, project_id) values ($micrographmetadata_id, $collectedTag->pg_id, $project_metadata_id)";
												$this->logToFile($query, "micro_microgaph_tag query");
												$this->db->query($query);
												$micrograph_tags[] = json_decode($collectedTag->json);
											}
										}
									}

									$this->logToFile($micrograph_tags, "micrograph pg tags");

									$vars[]='tags_json'; $vals[]= "'".json_encode($micrograph_tags)."'";
								}

								$query = "insert into micro_micrographmetadata (\n";
								$query .= implode(",\n", $vars);
								$query .= ") values (\n";
								$query .= implode(",\n", $vals);
								$query .= ")\n";

								$this->db->query($query);

								//}

								if($thismicrographmetadata->mineralogy != ""){

									$thismineralogy = $thismicrographmetadata->mineralogy;

									$mineralogy_id = $this->db->get_var("select nextval('micro_mineralogy_id_seq')");
									$query = "";
									$vars = ['id','micrograph_id'];
									$vals = [$mineralogy_id, $micrographmetadata_id];
									if($thismineralogy->percentageCalculationMethod!="") {$vars[]='percentagecalculationmethod'; $vals[]= "'".pg_escape_string($thismineralogy->percentageCalculationMethod)."'"; }
									if($thismineralogy->mineralogyMethod!="") {$vars[]='mineralogymethod'; $vals[]= "'".pg_escape_string($thismineralogy->mineralogyMethod)."'"; }
									if($thismineralogy->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thismineralogy->notes)."'"; }
									$query = "insert into micro_mineralogy (\n";
									$query .= implode(",\n", $vars);
									$query .= ") values (\n";
									$query .= implode(",\n", $vals);
									$query .= ")\n";

									$this->db->query($query);

									if($thismineralogy->minerals != ""){

										foreach($thismineralogy->minerals as $thismineral){

											$mineral_id = $this->db->get_var("select nextval('micro_mineral_id_seq')");
											$query = "";
											$vars = ['id','mineralogy_id'];
											$vals = [$mineral_id,$mineralogy_id];
											if($thismineral->name!="") {$vars[]='name'; $vals[]= "'".pg_escape_string($thismineral->name)."'"; }
											if($thismineral->operator!="") {$vars[]='operator'; $vals[]= "'".pg_escape_string($thismineral->operator)."'"; }
											if($thismineral->percentage!=""){ $vars[]='percentage'; $vals[]= $thismineral->percentage; }
											$query = "insert into micro_mineral (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

										}

									}

								}

								if($thismicrographmetadata->lithologyInfo != ""){

									$thislithology = $thismicrographmetadata->lithologyInfo;

									$lithologyinfo_id = $this->db->get_var("select nextval('micro_lithologyinfo_id_seq')");
									$query = "";
									$vars = ['id','micrograph_id'];
									$vals = [$lithologyinfo_id, $micrographmetadata_id];
									if($thislithology->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thislithology->notes)."'"; }
									$query = "insert into micro_lithologyinfo (\n";
									$query .= implode(",\n", $vars);
									$query .= ") values (\n";
									$query .= implode(",\n", $vals);
									$query .= ")\n";

									$this->db->query($query);

									if($thislithology->lithologies != ""){

										foreach($thislithology->lithologies as $lithology){

											$lithology_id = $this->db->get_var("select nextval('micro_lithology_id_seq')");
											$query = "";
											$vars = ['id','lithology_id'];
											$vals = [$lithology_id,$lithologyinfo_id];

											if($lithology->level1!="") {$vars[]='level1'; $vals[]= "'".pg_escape_string($lithology->level1)."'"; }
											if($lithology->level1Other!="") {$vars[]='level1other'; $vals[]= "'".pg_escape_string($lithology->level1Other)."'"; }
											if($lithology->level2!="") {$vars[]='level2'; $vals[]= "'".pg_escape_string($lithology->level2)."'"; }
											if($lithology->level2Other!="") {$vars[]='level2other'; $vals[]= "'".pg_escape_string($lithology->level2Other)."'"; }
											if($lithology->level3!="") {$vars[]='level3'; $vals[]= "'".pg_escape_string($lithology->level3)."'"; }
											if($lithology->level3Other!="") {$vars[]='level3other'; $vals[]= "'".pg_escape_string($lithology->level3Other)."'"; }

											$query = "insert into micro_lithology (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

										}

									}

								}

								if($thismicrographmetadata->grainInfo != ""){

									$thisgraininfo = $thismicrographmetadata->grainInfo;

									$graininfo_id = $this->db->get_var("select nextval('micro_graininfo_id_seq')");
									$query = "";
									$vars = ['id','micrograph_id'];
									$vals = [$graininfo_id, $micrographmetadata_id];
									if($thisgraininfo->grainSizeNotes!="") {$vars[]='grainsizenotes'; $vals[]= "'".pg_escape_string($thisgraininfo->grainSizeNotes)."'"; }
									if($thisgraininfo->grainShapeNotes!="") {$vars[]='grainshapenotes'; $vals[]= "'".pg_escape_string($thisgraininfo->grainShapeNotes)."'"; }
									if($thisgraininfo->grainOrientationNotes!="") {$vars[]='grainorientationnotes'; $vals[]= "'".pg_escape_string($thisgraininfo->grainOrientationNotes)."'"; }
									$query = "insert into micro_graininfo (\n";
									$query .= implode(",\n", $vars);
									$query .= ") values (\n";
									$query .= implode(",\n", $vals);
									$query .= ")\n";

									$this->db->query($query);

									if($thisgraininfo->grainSizeInfo != ""){

										foreach($thisgraininfo->grainSizeInfo as $thisgrainsize){

											$grainsize_id = $this->db->get_var("select nextval('micro_grainsize_id_seq')");
											$query = "";
											$vars = ['id','graininfo_id'];
											$vals = [$grainsize_id, $graininfo_id];
											if($thisgrainsize->phases!=""){ $vars[]='phases'; $vals[]= implode(", ", $thisgrainsize->phases); }
											if($thisgrainsize->mean!=""){ $vars[]='mean'; $vals[]= $thisgrainsize->mean; }
											if($thisgrainsize->median!=""){ $vars[]='median'; $vals[]= $thisgrainsize->median; }
											if($thisgrainsize->mode!=""){ $vars[]='mode'; $vals[]= $thisgrainsize->mode; }
											if($thisgrainsize->standardDeviation!=""){ $vars[]='standarddeviation'; $vals[]= $thisgrainsize->standardDeviation; }
											if($thisgrainsize->sizeUnit!="") {$vars[]='sizeunit'; $vals[]= "'".pg_escape_string($thisgrainsize->sizeUnit)."'"; }
											$query = "insert into micro_grainsize (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

										}

									}

									if($thisgraininfo->grainShapeInfo != ""){

										foreach($thisgraininfo->grainShapeInfo as $thisgrainshape){

											$grainshape_id = $this->db->get_var("select nextval('micro_grainshape_id_seq')");
											$query = "";
											$vars = ['id','graininfo_id'];
											$vals = [$grainshape_id, $graininfo_id];
											if($thisgrainshape->phases!=""){ $vars[]='phases'; $vals[]= implode(", ", $thisgrainshape->phases); }
											if($thisgrainshape->shape!="") {$vars[]='shape'; $vals[]= "'".pg_escape_string($thisgrainshape->shape)."'"; }
											$query = "insert into micro_grainshape (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

										}

									}

									if($thisgraininfo->grainOrientationInfo != ""){

										foreach($thisgraininfo->grainOrientationInfo as $thisgrainorientation){

											$grainorientation_id = $this->db->get_var("select nextval('micro_grainorientation_id_seq')");
											$query = "";
											$vars = ['id','graininfo_id'];
											$vals = [$grainorientation_id, $graininfo_id];
											if($thisgrainorientation->phases!=""){ $vars[]='phases'; $vals[]= implode(", ", $thisgrainorientation->phases); }
											if($thisgrainorientation->meanOrientation!=""){ $vars[]='meanorientation'; $vals[]= $thisgrainorientation->meanOrientation; }
											if($thisgrainorientation->relativeTo!="") {$vars[]='relativeto'; $vals[]= "'".pg_escape_string($thisgrainorientation->relativeTo)."'"; }
											if($thisgrainorientation->software!="") {$vars[]='software'; $vals[]= "'".pg_escape_string($thisgrainorientation->software)."'"; }
											if($thisgrainorientation->spoTechnique!="") {$vars[]='spotechnique'; $vals[]= "'".pg_escape_string($thisgrainorientation->spoTechnique)."'"; }
											if($thisgrainorientation->spoOther!="") {$vars[]='spoother'; $vals[]= "'".pg_escape_string($thisgrainorientation->spoOther)."'"; }
											$query = "insert into micro_grainorientation (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

										}

									}

								}

								if($thismicrographmetadata->fabricInfo != ""){

									$thisfabricinfo = $thismicrographmetadata->fabricInfo;

									$fabricinfo_id = $this->db->get_var("select nextval('micro_fabricinfo_id_seq')");
									$query = "";
									$vars = ['id','micrograph_id'];
									$vals = [$fabricinfo_id, $micrographmetadata_id];
									if($thisfabricinfo->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisfabricinfo->notes)."'"; }
									$query = "insert into micro_fabricinfo (\n";
									$query .= implode(",\n", $vars);
									$query .= ") values (\n";
									$query .= implode(",\n", $vals);
									$query .= ")\n";

									$this->db->query($query);

									if($thisfabricinfo->fabrics != ""){

										foreach($thisfabricinfo->fabrics as $thisfabric){

											$fabric_id = $this->db->get_var("select nextval('micro_fabric_id_seq')");
											$query = "";
											$vars = ['id','fabric_info_id'];
											$vals = [$fabric_id, $fabricinfo_id];
											if($thisfabric->fabricLabel!="") {$vars[]='fabriclabel'; $vals[]= "'".pg_escape_string($thisfabric->fabricLabel)."'"; }
											if($thisfabric->fabricElement!="") {$vars[]='fabricelement'; $vals[]= "'".pg_escape_string($thisfabric->fabricElement)."'"; }
											if($thisfabric->fabricCategory!="") {$vars[]='fabriccategory'; $vals[]= "'".pg_escape_string($thisfabric->fabricCategory)."'"; }
											if($thisfabric->fabricSpacing!="") {$vars[]='fabricspacing'; $vals[]= "'".pg_escape_string($thisfabric->fabricSpacing)."'"; }
											if($thisfabric->fabricDefinedBy!="") {$vars[]='fabricdefinedby'; $vals[]= "'".pg_escape_string(implode(", ", $thisfabric->fabricDefinedBy))."'"; }
											$query = "insert into micro_fabric (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

											if($thisfabric->fabricCompositionInfo != ""){

												$thisfabriccomposition = $thisfabric->fabricCompositionInfo;
												$fabriccomposition_id = $this->db->get_var("select nextval('micro_fabriccomposition_id_seq')");
												$query = "";
												$vars = ['id','fabric_id'];
												$vals = [$fabriccomposition_id, $fabric_id];
												if($thisfabriccomposition->compositionNotes!="") {$vars[]='compositionnotes'; $vals[]= "'".pg_escape_string($thisfabriccomposition->compositionNotes)."'"; }
												$query = "insert into micro_fabriccomposition (\n";
												$query .= implode(",\n", $vars);
												$query .= ") values (\n";
												$query .= implode(",\n", $vals);
												$query .= ")\n";

												$this->db->query($query);

												if($thisfabriccomposition->layers != ""){

													foreach($thisfabriccomposition->layers as $thisfabriccompositionlayer){

														$fabriccompositionlayer_id = $this->db->get_var("select nextval('micro_fabriccompositionlayer_id_seq')");
														$query = "";
														$vars = ['id','fabric_composition_id'];
														$vals = [$fabriccompositionlayer_id, $fabriccomposition_id];
														if($thisfabriccompositionlayer->composition!="") {$vars[]='composition'; $vals[]= "'".pg_escape_string($thisfabriccompositionlayer->composition)."'"; }
														if($thisfabriccompositionlayer->thickness!=""){ $vars[]='thickness'; $vals[]= $thisfabriccompositionlayer->thickness; }
														if($thisfabriccompositionlayer->thicknessUnits!="") {$vars[]='thicknessunits'; $vals[]= "'".pg_escape_string($thisfabriccompositionlayer->thicknessUnits)."'"; }
														$query = "insert into micro_fabriccompositionlayer (\n";
														$query .= implode(",\n", $vars);
														$query .= ") values (\n";
														$query .= implode(",\n", $vals);
														$query .= ")\n";

														$this->db->query($query);

													}

												}

											}

											if($thisfabric->fabricGrainSizeInfo != ""){

												$thisfabricgrainsize = $thisfabric->fabricGrainSizeInfo;

												$fabricgrainsize_id = $this->db->get_var("select nextval('micro_fabricgrainsize_id_seq')");
												$query = "";
												$vars = ['id','fabric_id'];
												$vals = [$fabricgrainsize_id, $fabric_id];
												if($thisfabricgrainsize->grainSizeNotes!="") {$vars[]='grainsizenotes'; $vals[]= "'".pg_escape_string($thisfabricgrainsize->grainSizeNotes)."'"; }
												$query = "insert into micro_fabricgrainsize (\n";
												$query .= implode(",\n", $vars);
												$query .= ") values (\n";
												$query .= implode(",\n", $vals);
												$query .= ")\n";

												$this->db->query($query);

												if($thisfabricgrainsize->layers != ""){

													foreach($thisfabricgrainsize->layers as $thisfabricgrainsizelayer){

														$fabricgrainsizelayer_id = $this->db->get_var("select nextval('micro_fabricgrainsizelayer_id_seq')");
														$query = "";
														$vars = ['id','grain_size_id'];
														$vals = [$fabricgrainsizelayer_id, $fabricgrainsize_id];
														if($thisfabricgrainsizelayer->grainSize!="") {$vars[]='grainsize'; $vals[]= "'".pg_escape_string($thisfabricgrainsizelayer->grainSize)."'"; }
														if($thisfabricgrainsizelayer->thickness!=""){ $vars[]='thickness'; $vals[]= $thisfabricgrainsizelayer->thickness; }
														if($thisfabricgrainsizelayer->thicknessUnits!="") {$vars[]='thicknessunits'; $vals[]= "'".pg_escape_string($thisfabricgrainsizelayer->thicknessUnits)."'"; }
														$query = "insert into micro_fabricgrainsizelayer (\n";
														$query .= implode(",\n", $vars);
														$query .= ") values (\n";
														$query .= implode(",\n", $vals);
														$query .= ")\n";

														$this->db->query($query);

													}

												}

											}

											if($thisfabric->fabricGrainShapeInfo != ""){

												$thisfabricgrainshape = $thisfabric->fabricGrainShapeInfo;

												$fabricgrainshape_id = $this->db->get_var("select nextval('micro_fabricgrainshape_id_seq')");
												$query = "";
												$vars = ['id','fabric_id'];
												$vals = [$fabricgrainshape_id, $fabric_id];
												if($thisfabricgrainshape->alignment!="") {$vars[]='alignment'; $vals[]= "'".pg_escape_string($thisfabricgrainshape->alignment)."'"; }
												if($thisfabricgrainshape->shape!="") {$vars[]='shape'; $vals[]= "'".pg_escape_string($thisfabricgrainshape->shape)."'"; }
												if($thisfabricgrainshape->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisfabricgrainshape->notes)."'"; }
												if($thisfabricgrainshape->phases!="") {$vars[]='phases'; $vals[]= "'".pg_escape_string(implode(", ", $thisfabricgrainshape->phases))."'"; }
												$query = "insert into micro_fabricgrainshape (\n";
												$query .= implode(",\n", $vars);
												$query .= ") values (\n";
												$query .= implode(",\n", $vals);
												$query .= ")\n";

												$this->db->query($query);

											}

											if($thisfabric->fabricCleavageInfo != ""){

												$thisfabriccleavage = $thisfabric->fabricCleavageInfo;

												$fabriccleavage_id = $this->db->get_var("select nextval('micro_fabriccleavage_id_seq')");
												$query = "";
												$vars = ['id','fabric_id'];
												$vals = [$fabriccleavage_id, $fabric_id];
												if($thisfabriccleavage->spacing!=""){ $vars[]='spacing'; $vals[]= $thisfabriccleavage->spacing; }
												if($thisfabriccleavage->spacingUnit!="") {$vars[]='spacingunit'; $vals[]= "'".pg_escape_string($thisfabriccleavage->spacingUnit)."'"; }
												if($thisfabriccleavage->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisfabriccleavage->notes)."'"; }
												if($thisfabriccleavage->geometryOfSeams!="") {$vars[]='geometryofseams'; $vals[]= "'".pg_escape_string(implode(", ", $thisfabriccleavage->geometryOfSeams))."'"; }
												if($thisfabriccleavage->styloliticCleavage!=""){ $vars[]='styloliticcleavage'; $vals[]= ($thisfabriccleavage->styloliticCleavage == 1 ? 'true' : 'false'); }
												$query = "insert into micro_fabriccleavage (\n";
												$query .= implode(",\n", $vars);
												$query .= ") values (\n";
												$query .= implode(",\n", $vals);
												$query .= ")\n";

												$this->db->query($query);

											}

										}

									}

								}

								if($thismicrographmetadata->associatedFiles != ""){

									foreach($thismicrographmetadata->associatedFiles as $thisassociatedfile){

										$associatedfile_id = $this->db->get_var("select nextval('micro_associatedfile_id_seq')");
										$query = "";
										$vars = ['id','micrograph_id'];
										$vals = [$associatedfile_id, $micrographmetadata_id];
										if($thisassociatedfile->fileName!="") {$vars[]='filename'; $vals[]= "'".pg_escape_string($thisassociatedfile->fileName)."'"; }
										if($thisassociatedfile->originalPath!="") {$vars[]='originalpath'; $vals[]= "'".pg_escape_string($thisassociatedfile->originalPath)."'"; }
										if($thisassociatedfile->fileType!="") {$vars[]='filetype'; $vals[]= "'".pg_escape_string($thisassociatedfile->fileType)."'"; }
										if($thisassociatedfile->otherType!="") {$vars[]='othertype'; $vals[]= "'".pg_escape_string($thisassociatedfile->otherType)."'"; }
										if($thisassociatedfile->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisassociatedfile->notes)."'"; }
										$query = "insert into micro_associatedfile (\n";
										$query .= implode(",\n", $vars);
										$query .= ") values (\n";
										$query .= implode(",\n", $vals);
										$query .= ")\n";

										$this->db->query($query);

									}
								}

								if($thismicrographmetadata->links != ""){

									foreach($thismicrographmetadata->links as $thislink){

										$link_id = $this->db->get_var("select nextval('micro_link_id_seq')");
										$query = "";
										$vars = ['id','micrograph_id'];
										$vals = [$link_id, $micrographmetadata_id];
										if($thislink->label!="") {$vars[]='label'; $vals[]= "'".pg_escape_string($thislink->label)."'"; }
										if($thislink->url!="") {$vars[]='url'; $vals[]= "'".pg_escape_string($thislink->url)."'"; }
										$query = "insert into micro_link (\n";
										$query .= implode(",\n", $vars);
										$query .= ") values (\n";
										$query .= implode(",\n", $vals);
										$query .= ")\n";

										$this->db->query($query);

									}
								}

								if($thismicrographmetadata->instrument != ""){

									$thisinstrument = $thismicrographmetadata->instrument;

									$instrument_id = $this->db->get_var("select nextval('micro_instrument_id_seq')");
									$query = "";
									$vars = ['id','micrograph_id'];
									$vals = [$instrument_id, $micrographmetadata_id];
									if($thisinstrument->instrumentType!="") {$vars[]='instrumenttype'; $vals[]= "'".pg_escape_string($thisinstrument->instrumentType)."'"; }
									if($thisinstrument->dataType!="") {$vars[]='datatype'; $vals[]= "'".pg_escape_string($thisinstrument->dataType)."'"; }
									if($thisinstrument->instrumentBrand!="") {$vars[]='instrumentbrand'; $vals[]= "'".pg_escape_string($thisinstrument->instrumentBrand)."'"; }
									if($thisinstrument->instrumentModel!="") {$vars[]='instrumentmodel'; $vals[]= "'".pg_escape_string($thisinstrument->instrumentModel)."'"; }
									if($thisinstrument->university!="") {$vars[]='university'; $vals[]= "'".pg_escape_string($thisinstrument->university)."'"; }
									if($thisinstrument->laboratory!="") {$vars[]='laboratory'; $vals[]= "'".pg_escape_string($thisinstrument->laboratory)."'"; }
									if($thisinstrument->dataCollectionSoftware!="") {$vars[]='datacollectionsoftware'; $vals[]= "'".pg_escape_string($thisinstrument->dataCollectionSoftware)."'"; }
									if($thisinstrument->dataCollectionSoftwareVersion!="") {$vars[]='datacollectionsoftwareversion'; $vals[]= "'".pg_escape_string($thisinstrument->dataCollectionSoftwareVersion)."'"; }
									if($thisinstrument->postProcessingSoftware!="") {$vars[]='postprocessingsoftware'; $vals[]= "'".pg_escape_string($thisinstrument->postProcessingSoftware)."'"; }
									if($thisinstrument->postProcessingSoftwareVersion!="") {$vars[]='postprocessingsoftwareversion'; $vals[]= "'".pg_escape_string($thisinstrument->postProcessingSoftwareVersion)."'"; }
									if($thisinstrument->filamentType!="") {$vars[]='filamenttype'; $vals[]= "'".pg_escape_string($thisinstrument->filamentType)."'"; }
									if($thisinstrument->instrumentNotes!="") {$vars[]='instrumentnotes'; $vals[]= "'".pg_escape_string($thisinstrument->instrumentNotes)."'"; }
									if($thisinstrument->accelerationVoltage!=""){ $vars[]='accelerationvoltage'; $vals[]= $thisinstrument->accelerationVoltage; }
									if($thisinstrument->beamCurrent!=""){ $vars[]='beamcurrent'; $vals[]= $thisinstrument->beamCurrent; }
									if($thisinstrument->spotSize!=""){ $vars[]='spotsize'; $vals[]= $thisinstrument->spotSize; }
									if($thisinstrument->aperture!=""){ $vars[]='aperture'; $vals[]= $thisinstrument->aperture; }
									if($thisinstrument->cameraLength!=""){ $vars[]='cameralength'; $vals[]= $thisinstrument->cameraLength; }
									if($thisinstrument->cameraBinning!="") {$vars[]='camerabinning'; $vals[]= "'".pg_escape_string($thisinstrument->cameraBinning)."'"; }
									if($thisinstrument->analysisDwellTime!=""){ $vars[]='analysisdwelltime'; $vals[]= $thisinstrument->analysisDwellTime; }
									if($thisinstrument->dwellTime!=""){ $vars[]='dwelltime'; $vals[]= $thisinstrument->dwellTime; }
									if($thisinstrument->workingDistance!=""){ $vars[]='workingdistance'; $vals[]= $thisinstrument->workingDistance; }
									if($thisinstrument->instrumentPurged!=""){ $vars[]='instrumentpurged'; $vals[]= $thisinstrument->instrumentPurged; }
									if($thisinstrument->instrumentPurgedGasType!="") {$vars[]='instrumentpurgedgastype'; $vals[]= "'".pg_escape_string($thisinstrument->instrumentPurgedGasType)."'"; }
									if($thisinstrument->environmentPurged!=""){ $vars[]='environmentpurged'; $vals[]= $thisinstrument->environmentPurged; }
									if($thisinstrument->environmentPurgedGasType!="") {$vars[]='environmentpurgedgastype'; $vals[]= "'".pg_escape_string($thisinstrument->environmentPurgedGasType)."'"; }
									if($thisinstrument->scanTime!=""){ $vars[]='scantime'; $vals[]= $thisinstrument->scanTime; }
									if($thisinstrument->resolution!=""){ $vars[]='resolution'; $vals[]= $thisinstrument->resolution; }
									if($thisinstrument->spectralResolution!=""){ $vars[]='spectralresolution'; $vals[]= $thisinstrument->spectralResolution; }
									if($thisinstrument->wavenumberRange!="") {$vars[]='wavenumberrange'; $vals[]= "'".pg_escape_string($thisinstrument->wavenumberRange)."'"; }
									if($thisinstrument->averaging!="") {$vars[]='averaging'; $vals[]= "'".pg_escape_string($thisinstrument->averaging)."'"; }
									if($thisinstrument->backgroundComposition!="") {$vars[]='backgroundcomposition'; $vals[]= "'".pg_escape_string($thisinstrument->backgroundComposition)."'"; }
									if($thisinstrument->backgroundCorrectionFrequencyAndNotes!="") {$vars[]='backgroundcorrectionfrequencyandnotes'; $vals[]= "'".pg_escape_string($thisinstrument->backgroundCorrectionFrequencyAndNotes)."'"; }
									if($thisinstrument->excitationWavelength!=""){ $vars[]='excitationwavelength'; $vals[]= $thisinstrument->excitationWavelength; }
									if($thisinstrument->laserPower!=""){ $vars[]='laserpower'; $vals[]= $thisinstrument->laserPower; }
									if($thisinstrument->diffractionGrating!=""){ $vars[]='diffractiongrating'; $vals[]= $thisinstrument->diffractionGrating; }
									if($thisinstrument->integrationTime!=""){ $vars[]='integrationtime'; $vals[]= $thisinstrument->integrationTime; }
									if($thisinstrument->objective!=""){ $vars[]='objective'; $vals[]= $thisinstrument->objective; }
									if($thisinstrument->calibration!="") {$vars[]='calibration'; $vals[]= "'".pg_escape_string($thisinstrument->calibration)."'"; }
									if($thisinstrument->notesOnPostProcessing!="") {$vars[]='notesonpostprocessing'; $vals[]= "'".pg_escape_string($thisinstrument->notesOnPostProcessing)."'"; }
									if($thisinstrument->atomicMode!="") {$vars[]='atomicmode'; $vals[]= "'".pg_escape_string($thisinstrument->atomicMode)."'"; }
									if($thisinstrument->cantileverStiffness!=""){ $vars[]='cantileverstiffness'; $vals[]= $thisinstrument->cantileverStiffness; }
									if($thisinstrument->tipDiameter!=""){ $vars[]='tipdiameter'; $vals[]= $thisinstrument->tipDiameter; }
									if($thisinstrument->operatingFrequency!=""){ $vars[]='operatingfrequency'; $vals[]= $thisinstrument->operatingFrequency; }
									if($thisinstrument->scanDimensions!="") {$vars[]='scandimensions'; $vals[]= "'".pg_escape_string($thisinstrument->scanDimensions)."'"; }
									if($thisinstrument->scanArea!="") {$vars[]='scanarea'; $vals[]= "'".pg_escape_string($thisinstrument->scanArea)."'"; }
									if($thisinstrument->spatialResolution!=""){ $vars[]='spatialresolution'; $vals[]= $thisinstrument->spatialResolution; }
									if($thisinstrument->temperatureOfRoom!=""){ $vars[]='temperatureofroom'; $vals[]= $thisinstrument->temperatureOfRoom; }
									if($thisinstrument->relativeHumidity!=""){ $vars[]='relativehumidity'; $vals[]= $thisinstrument->relativeHumidity; }
									if($thisinstrument->sampleTemperature!=""){ $vars[]='sampletemperature'; $vals[]= $thisinstrument->sampleTemperature; }
									if($thisinstrument->stepSize!=""){ $vars[]='stepsize'; $vals[]= $thisinstrument->stepSize; }
									if($thisinstrument->backgroundDwellTime!=""){ $vars[]='backgrounddwelltime'; $vals[]= $thisinstrument->backgroundDwellTime; }
									if($thisinstrument->backgroundCorrectionTechnique!="") {$vars[]='backgroundcorrectiontechnique'; $vals[]= "'".pg_escape_string($thisinstrument->backgroundCorrectionTechnique)."'"; }
									if($thisinstrument->deadTime!=""){ $vars[]='deadtime'; $vals[]= $thisinstrument->deadTime; }
									if($thisinstrument->calibrationStandardNotes!="") {$vars[]='calibrationstandardnotes'; $vals[]= "'".pg_escape_string($thisinstrument->calibrationStandardNotes)."'"; }
									if($thisinstrument->notesOnCrystalStructuresUsed!="") {$vars[]='notesoncrystalstructuresused'; $vals[]= "'".pg_escape_string($thisinstrument->notesOnCrystalStructuresUsed)."'"; }
									if($thisinstrument->color!="") {$vars[]='color'; $vals[]= "'".pg_escape_string($thisinstrument->color)."'"; }
									if($thisinstrument->rgbCheck!="") {$vars[]='rgbcheck'; $vals[]= "'".pg_escape_string($thisinstrument->rgbCheck)."'"; }
									if($thisinstrument->energyLoss!="") {$vars[]='energyloss'; $vals[]= "'".pg_escape_string($thisinstrument->energyLoss)."'"; }
									$query = "insert into micro_instrument (\n";
									$query .= implode(",\n", $vars);
									$query .= ") values (\n";
									$query .= implode(",\n", $vals);
									$query .= ")\n";

									$this->db->query($query);

									if($thisinstrument->instrumentDetectors != ""){

										foreach($thisinstrument->instrumentDetectors as $thisinstrumentdetector){

											$instrumentdetector_id = $this->db->get_var("select nextval('micro_instrumentdetector_id_seq')");
											$query = "";
											$vars = ['id','instrument_id'];
											$vals = [$instrumentdetector_id, $instrument_id];
											if($thisinstrumentdetector->detectorType!="") {$vars[]='detectortype'; $vals[]= "'".pg_escape_string($thisinstrumentdetector->detectorType)."'"; }
											if($thisinstrumentdetector->detectorMake!="") {$vars[]='detectormake'; $vals[]= "'".pg_escape_string($thisinstrumentdetector->detectorMake)."'"; }
											if($thisinstrumentdetector->detectorModel!="") {$vars[]='detectormodel'; $vals[]= "'".pg_escape_string($thisinstrumentdetector->detectorModel)."'"; }
											$query = "insert into micro_instrumentdetector (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

										}

									}

								}

								if($thismicrographmetadata->clasticDeformationBandInfo != ""){

									$thisclasticdeformationbandinfo = $thismicrographmetadata->clasticDeformationBandInfo;

									$clasticdeformationbandinfo_id = $this->db->get_var("select nextval('micro_clasticdeformationbandinfo_id_seq')");
									$query = "";
									$vars = ['id','micrograph_id'];
									$vals = [$clasticdeformationbandinfo_id, $micrographmetadata_id];
									if($thisclasticdeformationbandinfo->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisclasticdeformationbandinfo->notes)."'"; }
									$query = "insert into micro_clasticdeformationbandinfo (\n";
									$query .= implode(",\n", $vars);
									$query .= ") values (\n";
									$query .= implode(",\n", $vals);
									$query .= ")\n";

									$this->db->query($query);

									if($thisclasticdeformationbandinfo->bands != ""){

										foreach($thisclasticdeformationbandinfo->bands as $thisclasticdeformationband){

											$clasticdeformationband_id = $this->db->get_var("select nextval('micro_clasticdeformationband_id_seq')");
											$query = "";
											$vars = ['id','clastic_info_id'];
											$vals = [$clasticdeformationband_id, $clasticdeformationbandinfo_id];
											if($thisclasticdeformationband->thickness!=""){ $vars[]='thickness'; $vals[]= $thisclasticdeformationband->thickness; }
											if($thisclasticdeformationband->thicknessUnit!="") {$vars[]='thicknessunit'; $vals[]= "'".pg_escape_string($thisclasticdeformationband->thicknessUnit)."'"; }
											if($thisclasticdeformationband->cements!="") {$vars[]='cements'; $vals[]= "'".pg_escape_string($thisclasticdeformationband->cements)."'"; }
											$query = "insert into micro_clasticdeformationband (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

											if($thisclasticdeformationband->types != ""){

												foreach($thisclasticdeformationband->types as $thisclasticdeformationbandsubtype){

													$clasticdeformationbandsubtype_id = $this->db->get_var("select nextval('micro_clasticdeformationbandsubtype_id_seq')");
													$query = "";
													$vars = ['id','clastic_band_id'];
													$vals = [$clasticdeformationbandsubtype_id, $clasticdeformationband_id];
													if($thisclasticdeformationbandsubtype->type!="") {$vars[]='type'; $vals[]= "'".pg_escape_string($thisclasticdeformationbandsubtype->type)."'"; }
													if($thisclasticdeformationbandsubtype->aperture!=""){ $vars[]='aperture'; $vals[]= $thisclasticdeformationbandsubtype->aperture; }
													if($thisclasticdeformationbandsubtype->apertureUnit!="") {$vars[]='apertureunit'; $vals[]= "'".pg_escape_string($thisclasticdeformationbandsubtype->apertureUnit)."'"; }
													if($thisclasticdeformationbandsubtype->offset!=""){ $vars[]='clasticoffset'; $vals[]= $thisclasticdeformationbandsubtype->offset; }
													if($thisclasticdeformationbandsubtype->offsetUnit!="") {$vars[]='clasticoffsetunit'; $vals[]= "'".pg_escape_string($thisclasticdeformationbandsubtype->offsetUnit)."'"; }
													$query = "insert into micro_clasticdeformationbandsubtype (\n";
													$query .= implode(",\n", $vars);
													$query .= ") values (\n";
													$query .= implode(",\n", $vals);
													$query .= ")\n";

													$this->db->query($query);

												}

											}
										}
									}

								}

								if($thismicrographmetadata->grainBoundaryInfo != ""){

									$thisgrainboundaryinfo = $thismicrographmetadata->grainBoundaryInfo;

									$grainboundaryinfo_id = $this->db->get_var("select nextval('micro_grainboundaryinfo_id_seq')");
									$query = "";
									$vars = ['id','micrograph_id'];
									$vals = [$grainboundaryinfo_id, $micrographmetadata_id];
									if($thisgrainboundaryinfo->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisgrainboundaryinfo->notes)."'"; }
									$query = "insert into micro_grainboundaryinfo (\n";
									$query .= implode(",\n", $vars);
									$query .= ") values (\n";
									$query .= implode(",\n", $vals);
									$query .= ")\n";

									$this->db->query($query);

									if($thisgrainboundaryinfo->boundaries != ""){

										foreach($thisgrainboundaryinfo->boundaries as $thisgrainboundary){

											$grainboundary_id = $this->db->get_var("select nextval('micro_grainboundary_id_seq')");
											$query = "";
											$vars = ['id','grain_boundary_info_id'];
											$vals = [$grainboundary_id, $grainboundaryinfo_id];
											if($thisgrainboundary->phase1!="") {$vars[]='phase1'; $vals[]= "'".pg_escape_string($thisgrainboundary->phase1)."'"; }
											if($thisgrainboundary->phase2!="") {$vars[]='phase2'; $vals[]= "'".pg_escape_string($thisgrainboundary->phase2)."'"; }
											if($thisgrainboundary->typeOfBoundary!="") {$vars[]='typeofboundary'; $vals[]= "'".pg_escape_string($thisgrainboundary->typeOfBoundary)."'"; }
											$query = "insert into micro_grainboundary (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

											if($thisgrainboundary->morphologies != ""){

												foreach($thisgrainboundary->morphologies as $thisgrainboundarymorphology){

													$grainboundarymorphology_id = $this->db->get_var("select nextval('micro_grainboundarymorphology_id_seq')");
													$query = "";
													$vars = ['id','grain_boundary_id'];
													$vals = [$grainboundarymorphology_id, $grainboundary_id];
													if($thisgrainboundarymorphology->type!="") {$vars[]='type'; $vals[]= "'".pg_escape_string($thisgrainboundarymorphology->type)."'"; }
													$query = "insert into micro_grainboundarymorphology (\n";
													$query .= implode(",\n", $vars);
													$query .= ") values (\n";
													$query .= implode(",\n", $vals);
													$query .= ")\n";

													$this->db->query($query);

												}

											}

											if($thisgrainboundary->descriptors != ""){

												foreach($thisgrainboundary->descriptors as $thisgrainboundarydescriptor){

													$grainboundarydescriptor_id = $this->db->get_var("select nextval('micro_grainboundarydescriptor_id_seq')");
													$query = "";
													$vars = ['id','grain_boundary_id'];
													$vals = [$grainboundarydescriptor_id, $grainboundary_id];
													if($thisgrainboundarydescriptor->type!="") {$vars[]='type'; $vals[]= "'".pg_escape_string($thisgrainboundarydescriptor->type)."'"; }
													$query = "insert into micro_grainboundarydescriptor (\n";
													$query .= implode(",\n", $vars);
													$query .= ") values (\n";
													$query .= implode(",\n", $vals);
													$query .= ")\n";

													$this->db->query($query);

													if($thisgrainboundarydescriptor->subTypes != ""){

														foreach($thisgrainboundarydescriptor->subTypes as $thisgrainboundarydescriptorsub){

															$grainboundarydescriptorsub_id = $this->db->get_var("select nextval('micro_grainboundarydescriptorsub_id_seq')");
															$query = "";
															$vars = ['id','grain_boundary_descriptor_id'];
															$vals = [$grainboundarydescriptorsub_id, $grainboundarydescriptor_id];
															if($thisgrainboundarydescriptorsub->type!="") {$vars[]='type'; $vals[]= "'".pg_escape_string($thisgrainboundarydescriptorsub->type)."'"; }
															if($thisgrainboundarydescriptorsub->otherType!="") {$vars[]='othertype'; $vals[]= "'".pg_escape_string($thisgrainboundarydescriptorsub->otherType)."'"; }
															$query = "insert into micro_grainboundarydescriptorsub (\n";
															$query .= implode(",\n", $vars);
															$query .= ") values (\n";
															$query .= implode(",\n", $vals);
															$query .= ")\n";

															$this->db->query($query);

														}

													}

												}

											}

										}

									}

								}

								if($thismicrographmetadata->intraGrainInfo != ""){

									$thisintragraininfo = $thismicrographmetadata->intraGrainInfo;

									$intragraininfo_id = $this->db->get_var("select nextval('micro_intragraininfo_id_seq')");
									$query = "";
									$vars = ['id','micrograph_id'];
									$vals = [$intragraininfo_id, $micrographmetadata_id];
									if($thisintragraininfo->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisintragraininfo->notes)."'"; }
									$query = "insert into micro_intragraininfo (\n";
									$query .= implode(",\n", $vars);
									$query .= ") values (\n";
									$query .= implode(",\n", $vals);
									$query .= ")\n";

									$this->db->query($query);

									if($thisintragraininfo->grains != ""){

										foreach($thisintragraininfo->grains as $thisintragrain){

											$intragrain_id = $this->db->get_var("select nextval('micro_intragrain_id_seq')");
											$query = "";
											$vars = ['id','intragraininfo_id'];
											$vals = [$intragrain_id, $intragraininfo_id];
											if($thisintragrain->mineral!="") {$vars[]='mineral'; $vals[]= "'".pg_escape_string($thisintragrain->mineral)."'"; }
											$query = "insert into micro_intragrain (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

											if($thisintragrain->grainTextures != null){

												foreach($thisintragrain->grainTextures as $thisintragraintexturalfeature){

													$intragraintexturalfeature_id = $this->db->get_var("select nextval('micro_intragraintexturalfeature_id_seq')");
													$query = "";
													$vars = ['id', 'intragrain_id'];
													$vals = [$intragraintexturalfeature_id, $intragrain_id];
													if($thisintragraintexturalfeature->type!="") {$vars[]='type'; $vals[]= "'".pg_escape_string($thisintragraintexturalfeature->type)."'"; }
													if($thisintragraintexturalfeature->otherType!="") {$vars[]='othertype'; $vals[]= "'".pg_escape_string($thisintragraintexturalfeature->otherType)."'"; }
													$query = "insert into micro_intragraintexturalfeature (\n";
													$query .= implode(",\n", $vars);
													$query .= ") values (\n";
													$query .= implode(",\n", $vals);
													$query .= ")\n";

													$this->db->query($query);

												}

											}

										}

									}

								}

								if($thismicrographmetadata->faultsShearZonesInfo != ""){

									$thisfaultsShearZonesInfo = $thismicrographmetadata->faultsShearZonesInfo;

									$faultsshearzonesinfo_id = $this->db->get_var("select nextval('micro_faultsshearzonesinfo_id_seq')");
									$query = "";
									$vars = ['id','micrograph_id'];
									$vals = [$faultsshearzonesinfo_id, $micrographmetadata_id];
									if($thisfaultsShearZonesInfo->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisfaultsShearZonesInfo->notes)."'"; }
									$query = "insert into micro_faultsshearzonesinfo (\n";
									$query .= implode(",\n", $vars);
									$query .= ") values (\n";
									$query .= implode(",\n", $vals);
									$query .= ")\n";

									$this->db->query($query);

									if($thisfaultsShearZonesInfo->faultsShearZones != null){

										foreach($thisfaultsShearZonesInfo->faultsShearZones as $thisfaultshearzones){

											$faultshearzones_id = $this->db->get_var("select nextval('micro_faultsshearzones_id_seq')");
											$query = "";
											$vars = ['id','faults_shear_zones_info_id'];
											$vals = [$faultshearzones_id, $faultsshearzonesinfo_id];

											if($thisfaultshearzones->offset!="") {$vars[]='offsetval'; $vals[]= "$thisfaultshearzones->offset"; }
											if($thisfaultshearzones->offsetUnit!="") {$vars[]='offset_unit'; $vals[]= "'".pg_escape_string($thisfaultshearzones->offsetUnit)."'"; }
											if($thisfaultshearzones->width!="") {$vars[]='width'; $vals[]= "$thisfaultshearzones->width"; }
											if($thisfaultshearzones->widthUnit!="") {$vars[]='width_unit'; $vals[]= "'".pg_escape_string($thisfaultshearzones->widthUnit)."'"; }

											$query = "insert into micro_faultsshearzones (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

											if($thisfaultshearzones->shearSenses != null){
												foreach($thisfaultshearzones->shearSenses as $sense){
													$sensequery = "
														insert into micro_faultsshearzonesshearsense (faults_shear_zones_id, sense_type)
														values
														($faultshearzones_id, '$sense->type');
													";

													$this->db->query($sensequery);
												}
											}

											if($thisfaultshearzones->indicators != null){
												foreach($thisfaultshearzones->indicators as $indicators){

													$indicatorquery = "
														insert into micro_faultsshearzonesindicators (faults_shear_zones_id, indicator_type)
														values
														($faultshearzones_id, '$indicators->type');
													";

													$this->db->query($indicatorquery);
												}
											}

										}

									}

								}

								if($thismicrographmetadata->veinInfo != ""){

									$thisveininfo = $thismicrographmetadata->veinInfo;

									$veininfo_id = $this->db->get_var("select nextval('micro_veininfo_id_seq')");
									$query = "";
									$vars = ['id','micrograph_id'];
									$vals = [$veininfo_id, $micrographmetadata_id];
									if($thisveininfo->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisveininfo->notes)."'"; }
									$query = "insert into micro_veininfo (\n";
									$query .= implode(",\n", $vars);
									$query .= ") values (\n";
									$query .= implode(",\n", $vals);
									$query .= ")\n";

									$this->db->query($query);

									if($thisveininfo->veins != ""){

										foreach($thisveininfo->veins as $thisvein){

											$vein_id = $this->db->get_var("select nextval('micro_vein_id_seq')");
											$query = "";
											$vars = ['id','veininfo_id'];
											$vals = [$vein_id, $veininfo_id];
											if($thisvein->mineralogy!="") {$vars[]='mineralogy'; $vals[]= "'".pg_escape_string($thisvein->mineralogy)."'"; }
											$query = "insert into micro_vein (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

											if($thisvein->crystalShapes != ""){

												foreach($thisvein->crystalShapes as $thisveinsub){

													$veinsub_id = $this->db->get_var("select nextval('micro_veinsub_id_seq')");
													$query = "";
													$vars = ['id','vein_id'];
													$vals = [$veinsub_id, $vein_id];
													if($thisveinsub->type!="") {$vars[]='type'; $vals[]= "'".pg_escape_string($thisveinsub->type)."'"; }
													if($thisveinsub->subType!="") {$vars[]='subtype'; $vals[]= "'".pg_escape_string($thisveinsub->subType)."'"; }
													if($thisveinsub->numericValue!=""){ $vars[]='numericvalue'; $vals[]= $thisveinsub->numericValue; }
													if($thisveinsub->unit!="") {$vars[]='unit'; $vals[]= "'".pg_escape_string($thisveinsub->unit)."'"; }
													$query = "insert into micro_veinsub (\n";
													$query .= implode(",\n", $vars);
													$query .= ") values (\n";
													$query .= implode(",\n", $vals);
													$query .= ")\n";

													$this->db->query($query);

													$this->db->query("insert into micro_vein_crystalshapes (vein_id, veinsub_id) values ($vein_id, $veinsub_id)");

												}

											}

											if($thisvein->growthMorphologies != ""){

												foreach($thisvein->growthMorphologies as $thisveinsub){

													$veinsub_id = $this->db->get_var("select nextval('micro_veinsub_id_seq')");
													$query = "";
													$vars = ['id','vein_id'];
													$vals = [$veinsub_id, $vein_id];
													if($thisveinsub->type!="") {$vars[]='type'; $vals[]= "'".pg_escape_string($thisveinsub->type)."'"; }
													if($thisveinsub->subType!="") {$vars[]='subtype'; $vals[]= "'".pg_escape_string($thisveinsub->subType)."'"; }
													if($thisveinsub->numericValue!=""){ $vars[]='numericvalue'; $vals[]= $thisveinsub->numericValue; }
													if($thisveinsub->unit!="") {$vars[]='unit'; $vals[]= "'".pg_escape_string($thisveinsub->unit)."'"; }
													$query = "insert into micro_veinsub (\n";
													$query .= implode(",\n", $vars);
													$query .= ") values (\n";
													$query .= implode(",\n", $vals);
													$query .= ")\n";

													$this->db->query($query);

													$this->db->query("insert into micro_vein_growthmorphologies (vein_id, veinsub_id) values ($vein_id, $veinsub_id)");

												}

											}

											if($thisvein->inclusionTrails != ""){

												foreach($thisvein->inclusionTrails as $thisveinsub){

													$veinsub_id = $this->db->get_var("select nextval('micro_veinsub_id_seq')");
													$query = "";
													$vars = ['id','vein_id'];
													$vals = [$veinsub_id, $vein_id];
													if($thisveinsub->type!="") {$vars[]='type'; $vals[]= "'".pg_escape_string($thisveinsub->type)."'"; }
													if($thisveinsub->subType!="") {$vars[]='subtype'; $vals[]= "'".pg_escape_string($thisveinsub->subType)."'"; }
													if($thisveinsub->numericValue!=""){ $vars[]='numericvalue'; $vals[]= $thisveinsub->numericValue; }
													if($thisveinsub->unit!="") {$vars[]='unit'; $vals[]= "'".pg_escape_string($thisveinsub->unit)."'"; }
													$query = "insert into micro_veinsub (\n";
													$query .= implode(",\n", $vars);
													$query .= ") values (\n";
													$query .= implode(",\n", $vals);
													$query .= ")\n";

													$this->db->query($query);

													$this->db->query("insert into micro_vein_inclusiontrails (vein_id, veinsub_id) values ($vein_id, $veinsub_id)");

												}

											}

											if($thisvein->kinematics != ""){

												foreach($thisvein->kinematics as $thisveinsub){

													$veinsub_id = $this->db->get_var("select nextval('micro_veinsub_id_seq')");
													$query = "";
													$vars = ['id','vein_id'];
													$vals = [$veinsub_id, $vein_id];
													if($thisveinsub->type!="") {$vars[]='type'; $vals[]= "'".pg_escape_string($thisveinsub->type)."'"; }
													if($thisveinsub->subType!="") {$vars[]='subtype'; $vals[]= "'".pg_escape_string($thisveinsub->subType)."'"; }
													if($thisveinsub->numericValue!=""){ $vars[]='numericvalue'; $vals[]= $thisveinsub->numericValue; }
													if($thisveinsub->unit!="") {$vars[]='unit'; $vals[]= "'".pg_escape_string($thisveinsub->unit)."'"; }
													$query = "insert into micro_veinsub (\n";
													$query .= implode(",\n", $vars);
													$query .= ") values (\n";
													$query .= implode(",\n", $vals);
													$query .= ")\n";

													$this->db->query($query);

													$this->db->query("insert into micro_vein_kinematics (vein_id, veinsub_id) values ($vein_id, $veinsub_id)");

												}

											}

										}

									}

								}

								if($thismicrographmetadata->pseudotachylyteInfo != ""){

									$thispseudotachylyteinfo = $thismicrographmetadata->pseudotachylyteInfo;

									$pseudotachylyteinfo_id = $this->db->get_var("select nextval('micro_pseudotachylyteinfo_id_seq')");
									$query = "";
									$vars = ['id','micrograph_id'];
									$vals = [$pseudotachylyteinfo_id, $micrographmetadata_id];
									if($thispseudotachylyteinfo->reasoning!="") {$vars[]='reasoning'; $vals[]= "'".pg_escape_string($thispseudotachylyteinfo->reasoning)."'"; }
									if($thispseudotachylyteinfo->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thispseudotachylyteinfo->notes)."'"; }
									$query = "insert into micro_pseudotachylyteinfo (\n";
									$query .= implode(",\n", $vars);
									$query .= ") values (\n";
									$query .= implode(",\n", $vals);
									$query .= ")\n";

									$this->db->query($query);

									if($thispseudotachylyteinfo->pseudotachylytes != ""){

										foreach($thispseudotachylyteinfo->pseudotachylytes as $thispseudotachylyte){

											$pseudotachylyte_id = $this->db->get_var("select nextval('micro_pseudotachylyte_id_seq')");
											$query = "";
											$vars = ['id','pseudotachylyteinfo_id'];
											$vals = [$pseudotachylyte_id, $pseudotachylyteinfo_id];
											if($thispseudotachylyte->label!="") {$vars[]='label'; $vals[]= "'".pg_escape_string($thispseudotachylyte->label)."'"; }
											if($thispseudotachylyte->hasMatrixGroundmass!=""){ $vars[]='hasmatrixgroundmass'; $vals[]= ($thispseudotachylyte->hasMatrixGroundmass == 1 ? 'true' : 'false'); }
											if($thispseudotachylyte->matrixGroundmassColor!="") {$vars[]='matrixgroundmasscolor'; $vals[]= "'".pg_escape_string($thispseudotachylyte->matrixGroundmassColor)."'"; }
											if($thispseudotachylyte->matrixGroundmassConstraintsOnComposition!=""){ $vars[]='matrixgroundmassconstraintsoncomposition'; $vals[]= ($thispseudotachylyte->matrixGroundmassConstraintsOnComposition == 1 ? 'true' : 'false'); }
											if($thispseudotachylyte->matrixGroundmassConstraintsOnCompositionDetails!="") {$vars[]='matrixgroundmassconstraintsoncompositiondetails'; $vals[]= "'".pg_escape_string($thispseudotachylyte->matrixGroundmassConstraintsOnCompositionDetails)."'"; }
											if($thispseudotachylyte->hasCrystallites!=""){ $vars[]='hascrystallites'; $vals[]= ($thispseudotachylyte->hasCrystallites == 1 ? 'true' : 'false'); }
											if($thispseudotachylyte->crystallitesMineralogy!="") {$vars[]='crystallitesmineralogy'; $vals[]= "'".pg_escape_string($thispseudotachylyte->crystallitesMineralogy)."'"; }
											if($thispseudotachylyte->otherShape!="") {$vars[]='othershape'; $vals[]= "'".pg_escape_string($thispseudotachylyte->otherShape)."'"; }
											if($thispseudotachylyte->crystallitesLowerSize!=""){ $vars[]='crystalliteslowersize'; $vals[]= $thispseudotachylyte->crystallitesLowerSize; }
											if($thispseudotachylyte->crystallitesLowerSizeUnit!="") {$vars[]='crystalliteslowersizeunit'; $vals[]= "'".pg_escape_string($thispseudotachylyte->crystallitesLowerSizeUnit)."'"; }
											if($thispseudotachylyte->crystallitesUpperSize!=""){ $vars[]='crystallitesuppersize'; $vals[]= $thispseudotachylyte->crystallitesUpperSize; }
											if($thispseudotachylyte->crystallitesUpperSizeUnit!="") {$vars[]='crystallitesuppersizeunit'; $vals[]= "'".pg_escape_string($thispseudotachylyte->crystallitesUpperSizeUnit)."'"; }
											if($thispseudotachylyte->crystallitesZoning!=""){ $vars[]='crystalliteszoning'; $vals[]= ($thispseudotachylyte->crystallitesZoning == 1 ? 'true' : 'false'); }
											if($thispseudotachylyte->crystallitesZoningDetails!="") {$vars[]='crystalliteszoningdetails'; $vals[]= "'".pg_escape_string($thispseudotachylyte->crystallitesZoningDetails)."'"; }
											if($thispseudotachylyte->crystallitesDistribution!="") {$vars[]='crystallitesdistribution'; $vals[]= "'".pg_escape_string($thispseudotachylyte->crystallitesDistribution)."'"; }
											if($thispseudotachylyte->hasSurvivorClasts!=""){ $vars[]='hassurvivorclasts'; $vals[]= ($thispseudotachylyte->hasSurvivorClasts == 1 ? 'true' : 'false'); }
											if($thispseudotachylyte->survivorClastsMineralogy!="") {$vars[]='survivorclastsmineralogy'; $vals[]= "'".pg_escape_string($thispseudotachylyte->survivorClastsMineralogy)."'"; }
											if($thispseudotachylyte->survivorClastsMarginDescription!="") {$vars[]='survivorclastsmargindescription'; $vals[]= "'".pg_escape_string($thispseudotachylyte->survivorClastsMarginDescription)."'"; }
											if($thispseudotachylyte->survivorClastsDistribution!="") {$vars[]='survivorclastsdistribution'; $vals[]= "'".pg_escape_string($thispseudotachylyte->survivorClastsDistribution)."'"; }
											if($thispseudotachylyte->hasSulphideOxide!=""){ $vars[]='hassulphideoxide'; $vals[]= ($thispseudotachylyte->hasSulphideOxide == 1 ? 'true' : 'false'); }
											if($thispseudotachylyte->sulphideOxideMineralogy!="") {$vars[]='sulphideoxidemineralogy'; $vals[]= "'".pg_escape_string($thispseudotachylyte->sulphideOxideMineralogy)."'"; }
											if($thispseudotachylyte->sulphideOxideLowerSize!=""){ $vars[]='sulphideoxidelowersize'; $vals[]= $thispseudotachylyte->sulphideOxideLowerSize; }
											if($thispseudotachylyte->sulphideOxideLowerSizeUnit!="") {$vars[]='sulphideoxidelowersizeunit'; $vals[]= "'".pg_escape_string($thispseudotachylyte->sulphideOxideLowerSizeUnit)."'"; }
											if($thispseudotachylyte->sulphideOxideUpperSize!=""){ $vars[]='sulphideoxideuppersize'; $vals[]= $thispseudotachylyte->sulphideOxideUpperSize; }
											if($thispseudotachylyte->sulphideOxideUpperSizeUnit!="") {$vars[]='sulphideoxideuppersizeunit'; $vals[]= "'".pg_escape_string($thispseudotachylyte->sulphideOxideUpperSizeUnit)."'"; }
											if($thispseudotachylyte->sulphideOxideDistribution!="") {$vars[]='sulphideoxidedistribution'; $vals[]= "'".pg_escape_string($thispseudotachylyte->sulphideOxideDistribution)."'"; }
											if($thispseudotachylyte->hasFabric!=""){ $vars[]='hasfabric'; $vals[]= ($thispseudotachylyte->hasFabric == 1 ? 'true' : 'false'); }
											if($thispseudotachylyte->fabricDescription!="") {$vars[]='fabricdescription'; $vals[]= "'".pg_escape_string($thispseudotachylyte->fabricDescription)."'"; }
											if($thispseudotachylyte->hasInjectionFeatures!=""){ $vars[]='hasinjectionfeatures'; $vals[]= ($thispseudotachylyte->hasInjectionFeatures == 1 ? 'true' : 'false'); }
											if($thispseudotachylyte->injectionFeaturesAperture!=""){ $vars[]='injectionfeaturesaperture'; $vals[]= $thispseudotachylyte->injectionFeaturesAperture; }
											if($thispseudotachylyte->injectionFeaturesApertureUnit!="") {$vars[]='injectionfeaturesapertureunit'; $vals[]= "'".pg_escape_string($thispseudotachylyte->injectionFeaturesApertureUnit)."'"; }
											if($thispseudotachylyte->injectionFeaturesLength!=""){ $vars[]='injectionfeatureslength'; $vals[]= $thispseudotachylyte->injectionFeaturesLength; }
											if($thispseudotachylyte->injectionFeaturesLengthUnit!="") {$vars[]='injectionfeatureslengthunit'; $vals[]= "'".pg_escape_string($thispseudotachylyte->injectionFeaturesLengthUnit)."'"; }
											if($thispseudotachylyte->hasChilledMargins!=""){ $vars[]='haschilledmargins'; $vals[]= ($thispseudotachylyte->hasChilledMargins == 1 ? 'true' : 'false'); }
											if($thispseudotachylyte->chilledMarginsDescription!="") {$vars[]='chilledmarginsdescription'; $vals[]= "'".pg_escape_string($thispseudotachylyte->chilledMarginsDescription)."'"; }
											if($thispseudotachylyte->hasVesciclesAmygdules!=""){ $vars[]='hasvesciclesamygdules'; $vals[]= ($thispseudotachylyte->hasVesciclesAmygdules == 1 ? 'true' : 'false'); }
											if($thispseudotachylyte->vesciclesAmygdulesMineralogy!="") {$vars[]='vesciclesamygdulesmineralogy'; $vals[]= "'".pg_escape_string($thispseudotachylyte->vesciclesAmygdulesMineralogy)."'"; }
											if($thispseudotachylyte->vesciclesAmygdulesLowerSize!=""){ $vars[]='vesciclesamygduleslowersize'; $vals[]= $thispseudotachylyte->vesciclesAmygdulesLowerSize; }
											if($thispseudotachylyte->vesciclesAmygdulesLowerSizeUnit!="") {$vars[]='vesciclesamygduleslowersizeunit'; $vals[]= "'".pg_escape_string($thispseudotachylyte->vesciclesAmygdulesLowerSizeUnit)."'"; }
											if($thispseudotachylyte->vesciclesAmygdulesUpperSize!=""){ $vars[]='vesciclesamygdulesuppersize'; $vals[]= $thispseudotachylyte->vesciclesAmygdulesUpperSize; }
											if($thispseudotachylyte->vesciclesAmygdulesUpperSizeUnit!="") {$vars[]='vesciclesamygdulesuppersizeunit'; $vals[]= "'".pg_escape_string($thispseudotachylyte->vesciclesAmygdulesUpperSizeUnit)."'"; }
											if($thispseudotachylyte->vesciclesAmygdulesDistribution!="") {$vars[]='vesciclesamygdulesdistribution'; $vals[]= "'".pg_escape_string($thispseudotachylyte->vesciclesAmygdulesDistribution)."'"; }
											$query = "insert into micro_pseudotachylyte (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

										}

									}

								}

								if($thismicrographmetadata->extinctionMicrostructureInfo != ""){

									$thisextinctionmicrographinfo = $thismicrographmetadata->extinctionMicrostructureInfo;

									$extinctionmicrographinfo_id = $this->db->get_var("select nextval('micro_extinctionmicrostructureinfo_id_seq')");
									$query = "";
									$vars = ['id','micrograph_id'];
									$vals = [$extinctionmicrographinfo_id, $micrographmetadata_id];
									if($thisextinctionmicrographinfo->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisextinctionmicrographinfo->notes)."'"; }
									$query = "insert into micro_extinctionmicrostructureinfo (\n";
									$query .= implode(",\n", $vars);
									$query .= ") values (\n";
									$query .= implode(",\n", $vals);
									$query .= ")\n";

									$this->db->query($query);

									if($thisextinctionmicrographinfo->extinctionMicrostructures != ""){

										foreach($thisextinctionmicrographinfo->extinctionMicrostructures as $thismicrostructure){

											$extinctionmicrostructure_id = $this->db->get_var("select nextval('micro_extinctionmicrostructure_id_seq')");
											$query = "";
											$vars = ['id','extinction_microstructure_id'];
											$vals = [$extinctionmicrostructure_id, $extinctionmicrographinfo_id];

											if($thismicrostructure->phase!="") {$vars[]='phase'; $vals[]= "'".pg_escape_string($thismicrostructure->phase)."'"; }

											$query = "insert into micro_extinctionmicrostructure (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

											if($thismicrostructure->dislocations!="") {
												foreach($thismicrostructure->dislocations as $f){
													$this->db->query("insert into micro_extinctiondislocation (extinction_id, type) values ($extinctionmicrostructure_id,'$f->type')");
												}
											}

											if($thismicrostructure->subDislocations!="") {
												foreach($thismicrostructure->subDislocations as $f){
													$this->db->query("insert into micro_extinctiondislocationsub (extinction_id, type) values ($extinctionmicrostructure_id,'$f->type')");
												}
											}

											if($thismicrostructure->heterogeneousExtinctions!="") {
												foreach($thismicrostructure->heterogeneousExtinctions as $f){
													$this->db->query("insert into micro_extinctionhetero (extinction_id, type) values ($extinctionmicrostructure_id,'$f->type')");
												}
											}

											if($thismicrostructure->subGrainStructures!="") {
												foreach($thismicrostructure->subGrainStructures as $f){
													$this->db->query("insert into micro_extinctionsubgrain (extinction_id, type) values ($extinctionmicrostructure_id,'$f->type')");
												}
											}

											if($thismicrostructure->extinctionBands!="") {
												foreach($thismicrostructure->extinctionBands as $f){
													$this->db->query("insert into micro_extinctionbands (extinction_id, type) values ($extinctionmicrostructure_id,'$f->type')");
												}
											}

											if($thismicrostructure->subWideExtinctionBands!="") {
												foreach($thismicrostructure->subWideExtinctionBands as $f){
													$this->db->query("insert into micro_extinctionwidebands (extinction_id, type) values ($extinctionmicrostructure_id,'$f->type')");
												}
											}

											if($thismicrostructure->subFineExtinctionBands!="") {
												foreach($thismicrostructure->subFineExtinctionBands as $f){
													$this->db->query("insert into micro_extinctionfinebands (extinction_id, type) values ($extinctionmicrostructure_id,'$f->type')");
												}
											}

										}

									}

								}

								if($thismicrographmetadata->foldInfo != ""){

									$thisfoldinfo = $thismicrographmetadata->foldInfo;

									$foldinfo_id = $this->db->get_var("select nextval('micro_foldinfo_id_seq')");
									$query = "";
									$vars = ['id','micrograph_id'];
									$vals = [$foldinfo_id, $micrographmetadata_id];
									if($thisfoldinfo->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisfoldinfo->notes)."'"; }
									$query = "insert into micro_foldinfo (\n";
									$query .= implode(",\n", $vars);
									$query .= ") values (\n";
									$query .= implode(",\n", $vals);
									$query .= ")\n";

									$this->db->query($query);

									if($thisfoldinfo->folds != ""){

										foreach($thisfoldinfo->folds as $thisfold){

											$fold_id = $this->db->get_var("select nextval('micro_fold_id_seq')");
											$query = "";
											$vars = ['id','fold_info_id'];
											$vals = [$fold_id, $foldinfo_id];
											if($thisfold->label!="") {$vars[]='label'; $vals[]= "'".pg_escape_string($thisfold->label)."'"; }
											if($thisfold->interLimbAngle != ""){ $vars[]='interlimbangle'; $vals[]= implode(",", $thisfold->interLimbAngle); }
											if($thisfold->interLimbAngleOther!="") {$vars[]='interlimbangleother'; $vals[]= "'".pg_escape_string($thisfold->interLimbAngleOther)."'"; }
											if($thisfold->closure!="") {$vars[]='closure'; $vals[]= "'".pg_escape_string($thisfold->closure)."'"; }
											if($thisfold->closureOther!="") {$vars[]='closureother'; $vals[]= "'".pg_escape_string($thisfold->closureOther)."'"; }
											if($thisfold->orientationAxialTrace!="") {$vars[]='orientationaxialtrace'; $vals[]= "'".pg_escape_string($thisfold->orientationAxialTrace)."'"; }
											if($thisfold->symmetry!="") {$vars[]='symmetry'; $vals[]= "'".pg_escape_string($thisfold->symmetry)."'"; }
											if($thisfold->vergence!="") {$vars[]='vergence'; $vals[]= "'".pg_escape_string($thisfold->vergence)."'"; }
											if($thisfold->wavelength!=""){ $vars[]='wavelength'; $vals[]= $thisfold->wavelength; }
											if($thisfold->wavelengthUnit!="") {$vars[]='wavelengthunit'; $vals[]= "'".pg_escape_string($thisfold->wavelengthUnit)."'"; }
											if($thisfold->amplitude!=""){ $vars[]='amplitude'; $vals[]= $thisfold->amplitude; }
											if($thisfold->amplitudeUnit!="") {$vars[]='amplitudeunit'; $vals[]= "'".pg_escape_string($thisfold->amplitudeUnit)."'"; }
											if($thisfold->foldStyle!="") {$vars[]='foldstyle'; $vals[]= "'".pg_escape_string($thisfold->foldStyle)."'"; }
											if($thisfold->foldStyleOther!="") {$vars[]='foldstyleother'; $vals[]= "'".pg_escape_string($thisfold->foldStyleOther)."'"; }
											if($thisfold->foldContinuity!="") {$vars[]='foldcontinuity'; $vals[]= "'".pg_escape_string($thisfold->foldContinuity)."'"; }
											if($thisfold->foldContinuityOther!="") {$vars[]='foldcontinuityother'; $vals[]= "'".pg_escape_string($thisfold->foldContinuityOther)."'"; }
											if($thisfold->facing!="") {$vars[]='facing'; $vals[]= "'".pg_escape_string($thisfold->facing)."'"; }
											if($thisfold->facingOther!="") {$vars[]='facingother'; $vals[]= "'".pg_escape_string($thisfold->facingOther)."'"; }
											$query = "insert into micro_fold (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

										}

									}

								}

								if($thismicrographmetadata->fractureInfo != ""){

									$thisfractureinfo = $thismicrographmetadata->fractureInfo;

									$fractureinfo_id = $this->db->get_var("select nextval('micro_fractureinfo_id_seq')");
									$query = "";
									$vars = ['id','micrograph_id'];
									$vals = [$fractureinfo_id, $micrographmetadata_id];
									if($thisfractureinfo->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisfractureinfo->notes)."'"; }
									$query = "insert into micro_fractureinfo (\n";
									$query .= implode(",\n", $vars);
									$query .= ") values (\n";
									$query .= implode(",\n", $vals);
									$query .= ")\n";

									$this->db->query($query);

									if($thisfractureinfo->fractures != ""){

										foreach($thisfractureinfo->fractures as $thisfracture){

											$fracture_id = $this->db->get_var("select nextval('micro_fracture_id_seq')");
											$query = "";
											$vars = ['id','fracture_info_id'];
											$vals = [$fracture_id, $fractureinfo_id];

											if($thisfracture->granularity!="") {$vars[]='granularity'; $vals[]= "'".pg_escape_string($thisfracture->granularity)."'"; }
											if($thisfracture->mineralogy!="") {$vars[]='mineralogy'; $vals[]= "'".pg_escape_string($thisfracture->mineralogy)."'"; }
											if($thisfracture->kinematicType!="") {$vars[]='kinematictype'; $vals[]= "'".pg_escape_string($thisfracture->kinematicType)."'"; }
											if($thisfracture->openingAperture!="") {$vars[]='openingaperture'; $vals[]= "$thisfracture->openingAperture"; }
											if($thisfracture->openingApertureUnit!="") {$vars[]='openingapertureunit'; $vals[]= "'".pg_escape_string($thisfracture->openingApertureUnit)."'"; }
											if($thisfracture->shearOffset!="") {$vars[]='shearoffset'; $vals[]= "$thisfracture->shearOffset"; }
											if($thisfracture->shearOffsetUnit!="") {$vars[]='shearoffsetunit'; $vals[]= "'".pg_escape_string($thisfracture->shearOffsetUnit)."'"; }
											if($thisfracture->hybridAperture!="") {$vars[]='hybridaperture'; $vals[]= "$thisfracture->hybridAperture"; }
											if($thisfracture->hybridApertureUnit!="") {$vars[]='hybridapertureunit'; $vals[]= "'".pg_escape_string($thisfracture->hybridApertureUnit)."'"; }
											if($thisfracture->hybridOffset!="") {$vars[]='hybridoffset'; $vals[]= "$thisfracture->hybridOffset"; }
											if($thisfracture->hybridOffsetUnit!="") {$vars[]='hybridoffsetunit'; $vals[]= "'".pg_escape_string($thisfracture->hybridOffsetUnit)."'"; }
											if($thisfracture->sealedHealed==1) {
												$vars[]='sealedhealed'; $vals[]= "true";
											}else{
												$vars[]='sealedhealed'; $vals[]= "false";
											}

											$query = "insert into micro_fracture (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

										}

									}

								}

								if($thismicrographmetadata->orientationInfo != ""){

									$thismicrographorientation = $thismicrographmetadata->orientationInfo;

									$micrographorientation_id = $this->db->get_var("select nextval('micro_micrographorientation_id_seq')");
									$query = "";
									$vars = ['id','micrographmetadata_id'];
									$vals = [$micrographorientation_id, $micrographmetadata_id];
									if($thismicrographorientation->orientationMethod!="") {$vars[]='orientationmethod'; $vals[]= "'".pg_escape_string($thismicrographorientation->orientationMethod)."'"; }
									if($thismicrographorientation->topTrend!=""){ $vars[]='toptrend'; $vals[]= $thismicrographorientation->topTrend; }
									if($thismicrographorientation->topPlunge!=""){ $vars[]='topplunge'; $vals[]= $thismicrographorientation->topPlunge; }
									if($thismicrographorientation->topReferenceCorner!="") {$vars[]='topreferencecorner'; $vals[]= "'".pg_escape_string($thismicrographorientation->topReferenceCorner)."'"; }
									if($thismicrographorientation->sideTrend!=""){ $vars[]='sidetrend'; $vals[]= $thismicrographorientation->sideTrend; }
									if($thismicrographorientation->sidePlunge!=""){ $vars[]='sideplunge'; $vals[]= $thismicrographorientation->sidePlunge; }
									if($thismicrographorientation->sideReferenceCorner!="") {$vars[]='sidereferencecorner'; $vals[]= "'".pg_escape_string($thismicrographorientation->sideReferenceCorner)."'"; }
									if($thismicrographorientation->trendPlungeStrike!=""){ $vars[]='trendplungestrike'; $vals[]= $thismicrographorientation->trendPlungeStrike; }
									if($thismicrographorientation->trendPlungeDip!=""){ $vars[]='trendplungedip'; $vals[]= $thismicrographorientation->trendPlungeDip; }
									if($thismicrographorientation->fabricStrike!=""){ $vars[]='fabricstrike'; $vals[]= $thismicrographorientation->fabricStrike; }
									if($thismicrographorientation->fabricDip!=""){ $vars[]='fabricdip'; $vals[]= $thismicrographorientation->fabricDip; }
									if($thismicrographorientation->fabricTrend!=""){ $vars[]='fabrictrend'; $vals[]= $thismicrographorientation->fabricTrend; }
									if($thismicrographorientation->fabricPlunge!=""){ $vars[]='fabricplunge'; $vals[]= $thismicrographorientation->fabricPlunge; }
									if($thismicrographorientation->fabricRake!=""){ $vars[]='fabricrake'; $vals[]= $thismicrographorientation->fabricRake; }
									if($thismicrographorientation->fabricReference!="") {$vars[]='fabricreference'; $vals[]= "'".pg_escape_string($thismicrographorientation->fabricReference)."'"; }
									if($thismicrographorientation->lookDirection!="") {$vars[]='lookdirection'; $vals[]= "'".pg_escape_string($thismicrographorientation->lookDirection)."'"; }
									if($thismicrographorientation->topCorner!="") {$vars[]='topcorner'; $vals[]= "'".pg_escape_string($thismicrographorientation->topCorner)."'"; }
									$query = "insert into micro_micrographorientation (\n";
									$query .= implode(",\n", $vars);
									$query .= ") values (\n";
									$query .= implode(",\n", $vals);
									$query .= ")\n";

									$this->db->query($query);

								}

								if($thismicrographmetadata->spots != ""){

									foreach($thismicrographmetadata->spots as $thisspotmetadata){

										$spotmetadata_id = $this->db->get_var("select nextval('micro_spotmetadata_id_seq')");
										$query = "";
										$vars = ['id','micrograph_id'];
										$vals = [$spotmetadata_id, $micrographmetadata_id];
										if($thisspotmetadata->id!="") {$vars[]='strabo_id'; $vals[]= "'".pg_escape_string($thisspotmetadata->id)."'"; }
										if($thisspotmetadata->name!="") {$vars[]='name'; $vals[]= "'".pg_escape_string($thisspotmetadata->name)."'"; }
										if($thisspotmetadata->labelColor!="") {$vars[]='labelcolor'; $vals[]= "'".pg_escape_string($thisspotmetadata->labelColor)."'"; }
										if($thisspotmetadata->showLabel!=""){ $vars[]='showlabel'; $vals[]= ($thisspotmetadata->showLabel == 1 ? 'true' : 'false'); }
										if($thisspotmetadata->color!="") {$vars[]='color'; $vals[]= "'".pg_escape_string($thisspotmetadata->color)."'"; }
										if($thisspotmetadata->date!="") {$vars[]='date'; $vals[]= "'".pg_escape_string($thisspotmetadata->date)."'"; }
										if($thisspotmetadata->time!="") {$vars[]='time'; $vals[]= "'".pg_escape_string($thisspotmetadata->time)."'"; }
										if($thisspotmetadata->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisspotmetadata->notes)."'"; }
										if($thisspotmetadata->modifiedTimestamp!=""){ $vars[]='modifiedtimestamp'; $vals[]= $thisspotmetadata->modifiedTimestamp; }
										if($thisspotmetadata->geometryType!="") {$vars[]='geometrytype'; $vals[]= "'".pg_escape_string($thisspotmetadata->geometryType)."'"; }
										if($thisspotmetadata->points!="") {$vars[]='points'; $vals[]= "'".json_encode($thisspotmetadata->points)."'"; }

										if($thisspotmetadata->tags!=""){

											$spot_tags = [];

											foreach($thisspotmetadata->tags as $strabo_tag_id){
												foreach($collectedTags as $collectedTag){
													if($collectedTag->strabo_id == $strabo_tag_id){
														$query = "insert into micro_spot_tag (spot_id, tag_id, project_id) values ($spotmetadata_id, $collectedTag->pg_id, $project_metadata_id)";
														$this->logToFile($query, "micro_spot_tag query");
														$this->db->query($query);
														$spot_tags[] = json_decode($collectedTag->json);
													}
												}
											}

											$this->logToFile($spot_tags, "spot pg tags");

											$vars[]='tags_json'; $vals[]= "'".json_encode($spot_tags)."'";
										}

										$query = "insert into micro_spotmetadata (\n";
										$query .= implode(",\n", $vars);
										$query .= ") values (\n";
										$query .= implode(",\n", $vals);
										$query .= ")\n";

										$this->db->query($query);

										//all spot data here
										if($thisspotmetadata->mineralogy != ""){

											$thismineralogy = $thisspotmetadata->mineralogy;

											$mineralogy_id = $this->db->get_var("select nextval('micro_mineralogy_id_seq')");
											$query = "";
											$vars = ['id','spot_id'];
											$vals = [$mineralogy_id, $spotmetadata_id];
											if($thismineralogy->percentageCalculationMethod!="") {$vars[]='percentagecalculationmethod'; $vals[]= "'".pg_escape_string($thismineralogy->percentageCalculationMethod)."'"; }
											if($thismineralogy->mineralogyMethod!="") {$vars[]='mineralogymethod'; $vals[]= "'".pg_escape_string($thismineralogy->mineralogyMethod)."'"; }
											if($thismineralogy->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thismineralogy->notes)."'"; }
											$query = "insert into micro_mineralogy (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

											if($thismineralogy->minerals != ""){

												foreach($thismineralogy->minerals as $thismineral){

													$mineral_id = $this->db->get_var("select nextval('micro_mineral_id_seq')");
													$query = "";
													$vars = ['id','mineralogy_id'];
													$vals = [$mineral_id,$mineralogy_id];
													if($thismineral->name!="") {$vars[]='name'; $vals[]= "'".pg_escape_string($thismineral->name)."'"; }
													if($thismineral->operator!="") {$vars[]='operator'; $vals[]= "'".pg_escape_string($thismineral->operator)."'"; }
													if($thismineral->percentage!=""){ $vars[]='percentage'; $vals[]= $thismineral->percentage; }
													$query = "insert into micro_mineral (\n";
													$query .= implode(",\n", $vars);
													$query .= ") values (\n";
													$query .= implode(",\n", $vals);
													$query .= ")\n";

													$this->db->query($query);

												}

											}

										}

										if($thisspotmetadata->lithologyInfo != ""){

											$thislithology = $thisspotmetadata->lithologyInfo;

											$lithologyinfo_id = $this->db->get_var("select nextval('micro_lithologyinfo_id_seq')");
											$query = "";
											$vars = ['id','spot_id'];
											$vals = [$lithologyinfo_id, $spotmetadata_id];
											if($thislithology->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thislithology->notes)."'"; }
											$query = "insert into micro_lithologyinfo (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

											if($thislithology->lithologies != ""){

												foreach($thislithology->lithologies as $lithology){

													$lithology_id = $this->db->get_var("select nextval('micro_lithology_id_seq')");
													$query = "";
													$vars = ['id','lithology_id'];
													$vals = [$lithology_id,$lithologyinfo_id];

													if($lithology->level1!="") {$vars[]='level1'; $vals[]= "'".pg_escape_string($lithology->level1)."'"; }
													if($lithology->level1Other!="") {$vars[]='level1other'; $vals[]= "'".pg_escape_string($lithology->level1Other)."'"; }
													if($lithology->level2!="") {$vars[]='level2'; $vals[]= "'".pg_escape_string($lithology->level2)."'"; }
													if($lithology->level2Other!="") {$vars[]='level2other'; $vals[]= "'".pg_escape_string($lithology->level2Other)."'"; }
													if($lithology->level3!="") {$vars[]='level3'; $vals[]= "'".pg_escape_string($lithology->level3)."'"; }
													if($lithology->level3Other!="") {$vars[]='level3other'; $vals[]= "'".pg_escape_string($lithology->level3Other)."'"; }

													$query = "insert into micro_lithology (\n";
													$query .= implode(",\n", $vars);
													$query .= ") values (\n";
													$query .= implode(",\n", $vals);
													$query .= ")\n";

													$this->db->query($query);

												}

											}

										}

										if($thisspotmetadata->grainInfo != ""){

											$thisgraininfo = $thisspotmetadata->grainInfo;

											$graininfo_id = $this->db->get_var("select nextval('micro_graininfo_id_seq')");
											$query = "";
											$vars = ['id','spot_id'];
											$vals = [$graininfo_id, $spotmetadata_id];
											if($thisgraininfo->grainSizeNotes!="") {$vars[]='grainsizenotes'; $vals[]= "'".pg_escape_string($thisgraininfo->grainSizeNotes)."'"; }
											if($thisgraininfo->grainShapeNotes!="") {$vars[]='grainshapenotes'; $vals[]= "'".pg_escape_string($thisgraininfo->grainShapeNotes)."'"; }
											if($thisgraininfo->grainOrientationNotes!="") {$vars[]='grainorientationnotes'; $vals[]= "'".pg_escape_string($thisgraininfo->grainOrientationNotes)."'"; }
											$query = "insert into micro_graininfo (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

											if($thisgraininfo->grainSizeInfo != ""){

												foreach($thisgraininfo->grainSizeInfo as $thisgrainsize){

													$grainsize_id = $this->db->get_var("select nextval('micro_grainsize_id_seq')");
													$query = "";
													$vars = ['id','graininfo_id'];
													$vals = [$grainsize_id, $graininfo_id];
													if($thisgrainsize->phases!=""){ $vars[]='phases'; $vals[]= implode(", ", $thisgrainsize->phases); }
													if($thisgrainsize->mean!=""){ $vars[]='mean'; $vals[]= $thisgrainsize->mean; }
													if($thisgrainsize->median!=""){ $vars[]='median'; $vals[]= $thisgrainsize->median; }
													if($thisgrainsize->mode!=""){ $vars[]='mode'; $vals[]= $thisgrainsize->mode; }
													if($thisgrainsize->standardDeviation!=""){ $vars[]='standarddeviation'; $vals[]= $thisgrainsize->standardDeviation; }
													if($thisgrainsize->sizeUnit!="") {$vars[]='sizeunit'; $vals[]= "'".pg_escape_string($thisgrainsize->sizeUnit)."'"; }
													$query = "insert into micro_grainsize (\n";
													$query .= implode(",\n", $vars);
													$query .= ") values (\n";
													$query .= implode(",\n", $vals);
													$query .= ")\n";

													$this->db->query($query);

												}

											}

											if($thisgraininfo->grainShapeInfo != ""){

												foreach($thisgraininfo->grainShapeInfo as $thisgrainshape){

													$grainshape_id = $this->db->get_var("select nextval('micro_grainshape_id_seq')");
													$query = "";
													$vars = ['id','graininfo_id'];
													$vals = [$grainshape_id, $graininfo_id];
													if($thisgrainshape->phases!=""){ $vars[]='phases'; $vals[]= implode(", ", $thisgrainshape->phases); }
													if($thisgrainshape->shape!="") {$vars[]='shape'; $vals[]= "'".pg_escape_string($thisgrainshape->shape)."'"; }
													$query = "insert into micro_grainshape (\n";
													$query .= implode(",\n", $vars);
													$query .= ") values (\n";
													$query .= implode(",\n", $vals);
													$query .= ")\n";

													$this->db->query($query);

												}

											}

											if($thisgraininfo->grainOrientationInfo != ""){

												foreach($thisgraininfo->grainOrientationInfo as $thisgrainorientation){

													$grainorientation_id = $this->db->get_var("select nextval('micro_grainorientation_id_seq')");
													$query = "";
													$vars = ['id','graininfo_id'];
													$vals = [$grainorientation_id, $graininfo_id];
													if($thisgrainorientation->phases!=""){ $vars[]='phases'; $vals[]= implode(", ", $thisgrainorientation->phases); }
													if($thisgrainorientation->meanOrientation!=""){ $vars[]='meanorientation'; $vals[]= $thisgrainorientation->meanOrientation; }
													if($thisgrainorientation->relativeTo!="") {$vars[]='relativeto'; $vals[]= "'".pg_escape_string($thisgrainorientation->relativeTo)."'"; }
													if($thisgrainorientation->software!="") {$vars[]='software'; $vals[]= "'".pg_escape_string($thisgrainorientation->software)."'"; }
													if($thisgrainorientation->spoTechnique!="") {$vars[]='spotechnique'; $vals[]= "'".pg_escape_string($thisgrainorientation->spoTechnique)."'"; }
													if($thisgrainorientation->spoOther!="") {$vars[]='spoother'; $vals[]= "'".pg_escape_string($thisgrainorientation->spoOther)."'"; }
													$query = "insert into micro_grainorientation (\n";
													$query .= implode(",\n", $vars);
													$query .= ") values (\n";
													$query .= implode(",\n", $vals);
													$query .= ")\n";

													$this->db->query($query);

												}

											}

										}

										if($thisspotmetadata->fabricInfo != ""){

											$thisfabricinfo = $thisspotmetadata->fabricInfo;

											$fabricinfo_id = $this->db->get_var("select nextval('micro_fabricinfo_id_seq')");
											$query = "";
											$vars = ['id','spot_id'];
											$vals = [$fabricinfo_id, $spotmetadata_id];
											if($thisfabricinfo->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisfabricinfo->notes)."'"; }
											$query = "insert into micro_fabricinfo (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

											if($thisfabricinfo->fabrics != ""){

												foreach($thisfabricinfo->fabrics as $thisfabric){

													$fabric_id = $this->db->get_var("select nextval('micro_fabric_id_seq')");
													$query = "";
													$vars = ['id','fabric_info_id'];
													$vals = [$fabric_id, $fabricinfo_id];
													if($thisfabric->fabricLabel!="") {$vars[]='fabriclabel'; $vals[]= "'".pg_escape_string($thisfabric->fabricLabel)."'"; }
													if($thisfabric->fabricElement!="") {$vars[]='fabricelement'; $vals[]= "'".pg_escape_string($thisfabric->fabricElement)."'"; }
													if($thisfabric->fabricCategory!="") {$vars[]='fabriccategory'; $vals[]= "'".pg_escape_string($thisfabric->fabricCategory)."'"; }
													if($thisfabric->fabricSpacing!="") {$vars[]='fabricspacing'; $vals[]= "'".pg_escape_string($thisfabric->fabricSpacing)."'"; }
													if($thisfabric->fabricDefinedBy!="") {$vars[]='fabricdefinedby'; $vals[]= implode(", ", $thisfabric->fabricDefinedBy); }
													$query = "insert into micro_fabric (\n";
													$query .= implode(",\n", $vars);
													$query .= ") values (\n";
													$query .= implode(",\n", $vals);
													$query .= ")\n";

													$this->db->query($query);

													if($thisfabric->fabricCompositionInfo != ""){

														$thisfabriccomposition = $thisfabric->fabricCompositionInfo;
														$fabriccomposition_id = $this->db->get_var("select nextval('micro_fabriccomposition_id_seq')");
														$query = "";
														$vars = ['id','fabric_id'];
														$vals = [$fabriccomposition_id, $fabric_id];
														if($thisfabriccomposition->compositionNotes!="") {$vars[]='compositionnotes'; $vals[]= "'".pg_escape_string($thisfabriccomposition->compositionNotes)."'"; }
														$query = "insert into micro_fabriccomposition (\n";
														$query .= implode(",\n", $vars);
														$query .= ") values (\n";
														$query .= implode(",\n", $vals);
														$query .= ")\n";

														$this->db->query($query);

														if($thisfabriccomposition->layers != ""){

															foreach($thisfabriccomposition->layers as $thisfabriccompositionlayer){

																$fabriccompositionlayer_id = $this->db->get_var("select nextval('micro_fabriccompositionlayer_id_seq')");
																$query = "";
																$vars = ['id','fabric_composition_id'];
																$vals = [$fabriccompositionlayer_id, $fabriccomposition_id];
																if($thisfabriccompositionlayer->composition!="") {$vars[]='composition'; $vals[]= "'".pg_escape_string($thisfabriccompositionlayer->composition)."'"; }
																if($thisfabriccompositionlayer->thickness!=""){ $vars[]='thickness'; $vals[]= $thisfabriccompositionlayer->thickness; }
																if($thisfabriccompositionlayer->thicknessUnits!="") {$vars[]='thicknessunits'; $vals[]= "'".pg_escape_string($thisfabriccompositionlayer->thicknessUnits)."'"; }
																$query = "insert into micro_fabriccompositionlayer (\n";
																$query .= implode(",\n", $vars);
																$query .= ") values (\n";
																$query .= implode(",\n", $vals);
																$query .= ")\n";

																$this->db->query($query);

															}

														}

													}

													if($thisfabric->fabricGrainSizeInfo != ""){

														$thisfabricgrainsize = $thisfabric->fabricGrainSizeInfo;

														$fabricgrainsize_id = $this->db->get_var("select nextval('micro_fabricgrainsize_id_seq')");
														$query = "";
														$vars = ['id','fabric_id'];
														$vals = [$fabricgrainsize_id, $fabric_id];
														if($thisfabricgrainsize->grainSizeNotes!="") {$vars[]='grainsizenotes'; $vals[]= "'".pg_escape_string($thisfabricgrainsize->grainSizeNotes)."'"; }
														$query = "insert into micro_fabricgrainsize (\n";
														$query .= implode(",\n", $vars);
														$query .= ") values (\n";
														$query .= implode(",\n", $vals);
														$query .= ")\n";

														$this->db->query($query);

														if($thisfabricgrainsize->layers != ""){

															foreach($thisfabricgrainsize->layers as $thisfabricgrainsizelayer){

																$fabricgrainsizelayer_id = $this->db->get_var("select nextval('micro_fabricgrainsizelayer_id_seq')");
																$query = "";
																$vars = ['id','grain_size_id'];
																$vals = [$fabricgrainsizelayer_id, $fabricgrainsize_id];
																if($thisfabricgrainsizelayer->grainSize!="") {$vars[]='grainsize'; $vals[]= "'".pg_escape_string($thisfabricgrainsizelayer->grainSize)."'"; }
																if($thisfabricgrainsizelayer->thickness!=""){ $vars[]='thickness'; $vals[]= $thisfabricgrainsizelayer->thickness; }
																if($thisfabricgrainsizelayer->thicknessUnits!="") {$vars[]='thicknessunits'; $vals[]= "'".pg_escape_string($thisfabricgrainsizelayer->thicknessUnits)."'"; }
																$query = "insert into micro_fabricgrainsizelayer (\n";
																$query .= implode(",\n", $vars);
																$query .= ") values (\n";
																$query .= implode(",\n", $vals);
																$query .= ")\n";

																$this->db->query($query);

															}

														}

													}

													if($thisfabric->fabricGrainShapeInfo != ""){

														$thisfabricgrainshape = $thisfabric->fabricGrainShapeInfo;

														$fabricgrainshape_id = $this->db->get_var("select nextval('micro_fabricgrainshape_id_seq')");
														$query = "";
														$vars = ['id','fabric_id'];
														$vals = [$fabricgrainshape_id, $fabric_id];
														if($thisfabricgrainshape->alignment!="") {$vars[]='alignment'; $vals[]= "'".pg_escape_string($thisfabricgrainshape->alignment)."'"; }
														if($thisfabricgrainshape->shape!="") {$vars[]='shape'; $vals[]= "'".pg_escape_string($thisfabricgrainshape->shape)."'"; }
														if($thisfabricgrainshape->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisfabricgrainshape->notes)."'"; }
														if($thisfabricgrainshape->phases!="") {$vars[]='phases'; $vals[]= implode(", ", $thisfabricgrainshape->phases); }
														$query = "insert into micro_fabricgrainshape (\n";
														$query .= implode(",\n", $vars);
														$query .= ") values (\n";
														$query .= implode(",\n", $vals);
														$query .= ")\n";

														$this->db->query($query);

													}

													if($thisfabric->fabricCleavageInfo != ""){

														$thisfabriccleavage = $thisfabric->fabricCleavageInfo;

														$fabriccleavage_id = $this->db->get_var("select nextval('micro_fabriccleavage_id_seq')");
														$query = "";
														$vars = ['id','fabric_id'];
														$vals = [$fabriccleavage_id, $fabric_id];
														if($thisfabriccleavage->spacing!=""){ $vars[]='spacing'; $vals[]= $thisfabriccleavage->spacing; }
														if($thisfabriccleavage->spacingUnit!="") {$vars[]='spacingunit'; $vals[]= "'".pg_escape_string($thisfabriccleavage->spacingUnit)."'"; }
														if($thisfabriccleavage->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisfabriccleavage->notes)."'"; }
														if($thisfabriccleavage->geometryOfSeams!="") {$vars[]='geometryofseams'; $vals[]= implode(", ", $thisfabriccleavage->geometryOfSeams); }
														if($thisfabriccleavage->styloliticCleavage!=""){ $vars[]='styloliticcleavage'; $vals[]= ($thisfabriccleavage->styloliticCleavage == 1 ? 'true' : 'false'); }
														$query = "insert into micro_fabriccleavage (\n";
														$query .= implode(",\n", $vars);
														$query .= ") values (\n";
														$query .= implode(",\n", $vals);
														$query .= ")\n";

														$this->db->query($query);

													}

												}

											}

										}

										if($thisspotmetadata->associatedFiles != ""){

											foreach($thisspotmetadata->associatedFiles as $thisassociatedfile){

												$associatedfile_id = $this->db->get_var("select nextval('micro_associatedfile_id_seq')");
												$query = "";
												$vars = ['id','spot_id'];
												$vals = [$associatedfile_id, $spotmetadata_id];
												if($thisassociatedfile->fileName!="") {$vars[]='filename'; $vals[]= "'".pg_escape_string($thisassociatedfile->fileName)."'"; }
												if($thisassociatedfile->originalPath!="") {$vars[]='originalpath'; $vals[]= "'".pg_escape_string($thisassociatedfile->originalPath)."'"; }
												if($thisassociatedfile->fileType!="") {$vars[]='filetype'; $vals[]= "'".pg_escape_string($thisassociatedfile->fileType)."'"; }
												if($thisassociatedfile->otherType!="") {$vars[]='othertype'; $vals[]= "'".pg_escape_string($thisassociatedfile->otherType)."'"; }
												if($thisassociatedfile->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisassociatedfile->notes)."'"; }
												$query = "insert into micro_associatedfile (\n";
												$query .= implode(",\n", $vars);
												$query .= ") values (\n";
												$query .= implode(",\n", $vals);
												$query .= ")\n";

												$this->db->query($query);

											}
										}

										if($thisspotmetadata->links != ""){

											foreach($thisspotmetadata->links as $thislink){

												$link_id = $this->db->get_var("select nextval('micro_link_id_seq')");
												$query = "";
												$vars = ['id','spot_id'];
												$vals = [$link_id, $spotmetadata_id];
												if($thislink->label!="") {$vars[]='label'; $vals[]= "'".pg_escape_string($thislink->label)."'"; }
												if($thislink->url!="") {$vars[]='url'; $vals[]= "'".pg_escape_string($thislink->url)."'"; }
												$query = "insert into micro_link (\n";
												$query .= implode(",\n", $vars);
												$query .= ") values (\n";
												$query .= implode(",\n", $vals);
												$query .= ")\n";

												$this->db->query($query);

											}
										}

										if($thisspotmetadata->instrument != ""){

											$thisinstrument = $thisspotmetadata->instrument;

											$instrument_id = $this->db->get_var("select nextval('micro_instrument_id_seq')");
											$query = "";
											$vars = ['id','spot_id'];
											$vals = [$instrument_id, $spotmetadata_id];
											if($thisinstrument->instrumentType!="") {$vars[]='instrumenttype'; $vals[]= "'".pg_escape_string($thisinstrument->instrumentType)."'"; }
											if($thisinstrument->dataType!="") {$vars[]='datatype'; $vals[]= "'".pg_escape_string($thisinstrument->dataType)."'"; }
											if($thisinstrument->instrumentBrand!="") {$vars[]='instrumentbrand'; $vals[]= "'".pg_escape_string($thisinstrument->instrumentBrand)."'"; }
											if($thisinstrument->instrumentModel!="") {$vars[]='instrumentmodel'; $vals[]= "'".pg_escape_string($thisinstrument->instrumentModel)."'"; }
											if($thisinstrument->university!="") {$vars[]='university'; $vals[]= "'".pg_escape_string($thisinstrument->university)."'"; }
											if($thisinstrument->laboratory!="") {$vars[]='laboratory'; $vals[]= "'".pg_escape_string($thisinstrument->laboratory)."'"; }
											if($thisinstrument->dataCollectionSoftware!="") {$vars[]='datacollectionsoftware'; $vals[]= "'".pg_escape_string($thisinstrument->dataCollectionSoftware)."'"; }
											if($thisinstrument->dataCollectionSoftwareVersion!="") {$vars[]='datacollectionsoftwareversion'; $vals[]= "'".pg_escape_string($thisinstrument->dataCollectionSoftwareVersion)."'"; }
											if($thisinstrument->postProcessingSoftware!="") {$vars[]='postprocessingsoftware'; $vals[]= "'".pg_escape_string($thisinstrument->postProcessingSoftware)."'"; }
											if($thisinstrument->postProcessingSoftwareVersion!="") {$vars[]='postprocessingsoftwareversion'; $vals[]= "'".pg_escape_string($thisinstrument->postProcessingSoftwareVersion)."'"; }
											if($thisinstrument->filamentType!="") {$vars[]='filamenttype'; $vals[]= "'".pg_escape_string($thisinstrument->filamentType)."'"; }
											if($thisinstrument->instrumentNotes!="") {$vars[]='instrumentnotes'; $vals[]= "'".pg_escape_string($thisinstrument->instrumentNotes)."'"; }
											if($thisinstrument->accelerationVoltage!=""){ $vars[]='accelerationvoltage'; $vals[]= $thisinstrument->accelerationVoltage; }
											if($thisinstrument->beamCurrent!=""){ $vars[]='beamcurrent'; $vals[]= $thisinstrument->beamCurrent; }
											if($thisinstrument->spotSize!=""){ $vars[]='spotsize'; $vals[]= $thisinstrument->spotSize; }
											if($thisinstrument->aperture!=""){ $vars[]='aperture'; $vals[]= $thisinstrument->aperture; }
											if($thisinstrument->cameraLength!=""){ $vars[]='cameralength'; $vals[]= $thisinstrument->cameraLength; }
											if($thisinstrument->cameraBinning!="") {$vars[]='camerabinning'; $vals[]= "'".pg_escape_string($thisinstrument->cameraBinning)."'"; }
											if($thisinstrument->analysisDwellTime!=""){ $vars[]='analysisdwelltime'; $vals[]= $thisinstrument->analysisDwellTime; }
											if($thisinstrument->dwellTime!=""){ $vars[]='dwelltime'; $vals[]= $thisinstrument->dwellTime; }
											if($thisinstrument->workingDistance!=""){ $vars[]='workingdistance'; $vals[]= $thisinstrument->workingDistance; }
											if($thisinstrument->instrumentPurged!=""){ $vars[]='instrumentpurged'; $vals[]= $thisinstrument->instrumentPurged; }
											if($thisinstrument->instrumentPurgedGasType!="") {$vars[]='instrumentpurgedgastype'; $vals[]= "'".pg_escape_string($thisinstrument->instrumentPurgedGasType)."'"; }
											if($thisinstrument->environmentPurged!=""){ $vars[]='environmentpurged'; $vals[]= $thisinstrument->environmentPurged; }
											if($thisinstrument->environmentPurgedGasType!="") {$vars[]='environmentpurgedgastype'; $vals[]= "'".pg_escape_string($thisinstrument->environmentPurgedGasType)."'"; }
											if($thisinstrument->scanTime!=""){ $vars[]='scantime'; $vals[]= $thisinstrument->scanTime; }
											if($thisinstrument->resolution!=""){ $vars[]='resolution'; $vals[]= $thisinstrument->resolution; }
											if($thisinstrument->spectralResolution!=""){ $vars[]='spectralresolution'; $vals[]= $thisinstrument->spectralResolution; }
											if($thisinstrument->wavenumberRange!="") {$vars[]='wavenumberrange'; $vals[]= "'".pg_escape_string($thisinstrument->wavenumberRange)."'"; }
											if($thisinstrument->averaging!="") {$vars[]='averaging'; $vals[]= "'".pg_escape_string($thisinstrument->averaging)."'"; }
											if($thisinstrument->backgroundComposition!="") {$vars[]='backgroundcomposition'; $vals[]= "'".pg_escape_string($thisinstrument->backgroundComposition)."'"; }
											if($thisinstrument->backgroundCorrectionFrequencyAndNotes!="") {$vars[]='backgroundcorrectionfrequencyandnotes'; $vals[]= "'".pg_escape_string($thisinstrument->backgroundCorrectionFrequencyAndNotes)."'"; }
											if($thisinstrument->excitationWavelength!=""){ $vars[]='excitationwavelength'; $vals[]= $thisinstrument->excitationWavelength; }
											if($thisinstrument->laserPower!=""){ $vars[]='laserpower'; $vals[]= $thisinstrument->laserPower; }
											if($thisinstrument->diffractionGrating!=""){ $vars[]='diffractiongrating'; $vals[]= $thisinstrument->diffractionGrating; }
											if($thisinstrument->integrationTime!=""){ $vars[]='integrationtime'; $vals[]= $thisinstrument->integrationTime; }
											if($thisinstrument->objective!=""){ $vars[]='objective'; $vals[]= $thisinstrument->objective; }
											if($thisinstrument->calibration!="") {$vars[]='calibration'; $vals[]= "'".pg_escape_string($thisinstrument->calibration)."'"; }
											if($thisinstrument->notesOnPostProcessing!="") {$vars[]='notesonpostprocessing'; $vals[]= "'".pg_escape_string($thisinstrument->notesOnPostProcessing)."'"; }
											if($thisinstrument->atomicMode!="") {$vars[]='atomicmode'; $vals[]= "'".pg_escape_string($thisinstrument->atomicMode)."'"; }
											if($thisinstrument->cantileverStiffness!=""){ $vars[]='cantileverstiffness'; $vals[]= $thisinstrument->cantileverStiffness; }
											if($thisinstrument->tipDiameter!=""){ $vars[]='tipdiameter'; $vals[]= $thisinstrument->tipDiameter; }
											if($thisinstrument->operatingFrequency!=""){ $vars[]='operatingfrequency'; $vals[]= $thisinstrument->operatingFrequency; }
											if($thisinstrument->scanDimensions!="") {$vars[]='scandimensions'; $vals[]= "'".pg_escape_string($thisinstrument->scanDimensions)."'"; }
											if($thisinstrument->scanArea!="") {$vars[]='scanarea'; $vals[]= "'".pg_escape_string($thisinstrument->scanArea)."'"; }
											if($thisinstrument->spatialResolution!=""){ $vars[]='spatialresolution'; $vals[]= $thisinstrument->spatialResolution; }
											if($thisinstrument->temperatureOfRoom!=""){ $vars[]='temperatureofroom'; $vals[]= $thisinstrument->temperatureOfRoom; }
											if($thisinstrument->relativeHumidity!=""){ $vars[]='relativehumidity'; $vals[]= $thisinstrument->relativeHumidity; }
											if($thisinstrument->sampleTemperature!=""){ $vars[]='sampletemperature'; $vals[]= $thisinstrument->sampleTemperature; }
											if($thisinstrument->stepSize!=""){ $vars[]='stepsize'; $vals[]= $thisinstrument->stepSize; }
											if($thisinstrument->backgroundDwellTime!=""){ $vars[]='backgrounddwelltime'; $vals[]= $thisinstrument->backgroundDwellTime; }
											if($thisinstrument->backgroundCorrectionTechnique!="") {$vars[]='backgroundcorrectiontechnique'; $vals[]= "'".pg_escape_string($thisinstrument->backgroundCorrectionTechnique)."'"; }
											if($thisinstrument->deadTime!=""){ $vars[]='deadtime'; $vals[]= $thisinstrument->deadTime; }
											if($thisinstrument->calibrationStandardNotes!="") {$vars[]='calibrationstandardnotes'; $vals[]= "'".pg_escape_string($thisinstrument->calibrationStandardNotes)."'"; }
											if($thisinstrument->notesOnCrystalStructuresUsed!="") {$vars[]='notesoncrystalstructuresused'; $vals[]= "'".pg_escape_string($thisinstrument->notesOnCrystalStructuresUsed)."'"; }
											if($thisinstrument->color!="") {$vars[]='color'; $vals[]= "'".pg_escape_string($thisinstrument->color)."'"; }
											if($thisinstrument->rgbCheck!="") {$vars[]='rgbcheck'; $vals[]= "'".pg_escape_string($thisinstrument->rgbCheck)."'"; }
											if($thisinstrument->energyLoss!="") {$vars[]='energyloss'; $vals[]= "'".pg_escape_string($thisinstrument->energyLoss)."'"; }
											$query = "insert into micro_instrument (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

											if($thisinstrument->instrumentDetectors != ""){

												foreach($thisinstrument->instrumentDetectors as $thisinstrumentdetector){

													$instrumentdetector_id = $this->db->get_var("select nextval('micro_instrumentdetector_id_seq')");
													$query = "";
													$vars = ['id','instrument_id'];
													$vals = [$instrumentdetector_id, $instrument_id];
													if($thisinstrumentdetector->detectorType!="") {$vars[]='detectortype'; $vals[]= "'".pg_escape_string($thisinstrumentdetector->detectorType)."'"; }
													if($thisinstrumentdetector->detectorMake!="") {$vars[]='detectormake'; $vals[]= "'".pg_escape_string($thisinstrumentdetector->detectorMake)."'"; }
													if($thisinstrumentdetector->detectorModel!="") {$vars[]='detectormodel'; $vals[]= "'".pg_escape_string($thisinstrumentdetector->detectorModel)."'"; }
													$query = "insert into micro_instrumentdetector (\n";
													$query .= implode(",\n", $vars);
													$query .= ") values (\n";
													$query .= implode(",\n", $vals);
													$query .= ")\n";

													$this->db->query($query);

												}

											}

										}

										if($thisspotmetadata->clasticDeformationBandInfo != ""){

											$thisclasticdeformationbandinfo = $thisspotmetadata->clasticDeformationBandInfo;

											$clasticdeformationbandinfo_id = $this->db->get_var("select nextval('micro_clasticdeformationbandinfo_id_seq')");
											$query = "";
											$vars = ['id','spot_id'];
											$vals = [$clasticdeformationbandinfo_id, $spotmetadata_id];
											if($thisclasticdeformationbandinfo->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisclasticdeformationbandinfo->notes)."'"; }
											$query = "insert into micro_clasticdeformationbandinfo (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

											if($thisclasticdeformationbandinfo->bands != ""){

												foreach($thisclasticdeformationbandinfo->bands as $thisclasticdeformationband){

													$clasticdeformationband_id = $this->db->get_var("select nextval('micro_clasticdeformationband_id_seq')");
													$query = "";
													$vars = ['id','clastic_info_id'];
													$vals = [$clasticdeformationband_id, $clasticdeformationbandinfo_id];
													if($thisclasticdeformationband->thickness!=""){ $vars[]='thickness'; $vals[]= $thisclasticdeformationband->thickness; }
													if($thisclasticdeformationband->thicknessUnit!="") {$vars[]='thicknessunit'; $vals[]= "'".pg_escape_string($thisclasticdeformationband->thicknessUnit)."'"; }
													if($thisclasticdeformationband->cements!="") {$vars[]='cements'; $vals[]= "'".pg_escape_string($thisclasticdeformationband->cements)."'"; }
													$query = "insert into micro_clasticdeformationband (\n";
													$query .= implode(",\n", $vars);
													$query .= ") values (\n";
													$query .= implode(",\n", $vals);
													$query .= ")\n";

													$this->db->query($query);

													if($thisclasticdeformationband->types != ""){

														foreach($thisclasticdeformationband->types as $thisclasticdeformationbandsubtype){

															$clasticdeformationbandsubtype_id = $this->db->get_var("select nextval('micro_clasticdeformationbandsubtype_id_seq')");
															$query = "";
															$vars = ['id','clastic_band_id'];
															$vals = [$clasticdeformationbandsubtype_id, $clasticdeformationband_id];
															if($thisclasticdeformationbandsubtype->type!="") {$vars[]='type'; $vals[]= "'".pg_escape_string($thisclasticdeformationbandsubtype->type)."'"; }
															if($thisclasticdeformationbandsubtype->aperture!=""){ $vars[]='aperture'; $vals[]= $thisclasticdeformationbandsubtype->aperture; }
															if($thisclasticdeformationbandsubtype->apertureUnit!="") {$vars[]='apertureunit'; $vals[]= "'".pg_escape_string($thisclasticdeformationbandsubtype->apertureUnit)."'"; }
															if($thisclasticdeformationbandsubtype->offset!=""){ $vars[]='clasticoffset'; $vals[]= $thisclasticdeformationbandsubtype->offset; }
															if($thisclasticdeformationbandsubtype->offsetUnit!="") {$vars[]='clasticoffsetunit'; $vals[]= "'".pg_escape_string($thisclasticdeformationbandsubtype->offsetUnit)."'"; }
															$query = "insert into micro_clasticdeformationbandsubtype (\n";
															$query .= implode(",\n", $vars);
															$query .= ") values (\n";
															$query .= implode(",\n", $vals);
															$query .= ")\n";

															$this->db->query($query);

														}

													}
												}
											}

										}

										if($thisspotmetadata->grainBoundaryInfo != ""){

											$thisgrainboundaryinfo = $thisspotmetadata->grainBoundaryInfo;

											$grainboundaryinfo_id = $this->db->get_var("select nextval('micro_grainboundaryinfo_id_seq')");
											$query = "";
											$vars = ['id','spot_id'];
											$vals = [$grainboundaryinfo_id, $spotmetadata_id];
											if($thisgrainboundaryinfo->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisgrainboundaryinfo->notes)."'"; }
											$query = "insert into micro_grainboundaryinfo (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

											if($thisgrainboundaryinfo->boundaries != ""){

												foreach($thisgrainboundaryinfo->boundaries as $thisgrainboundary){

													$grainboundary_id = $this->db->get_var("select nextval('micro_grainboundary_id_seq')");
													$query = "";
													$vars = ['id','grain_boundary_info_id'];
													$vals = [$grainboundary_id, $grainboundaryinfo_id];
													if($thisgrainboundary->phase1!="") {$vars[]='phase1'; $vals[]= "'".pg_escape_string($thisgrainboundary->phase1)."'"; }
													if($thisgrainboundary->phase2!="") {$vars[]='phase2'; $vals[]= "'".pg_escape_string($thisgrainboundary->phase2)."'"; }
													if($thisgrainboundary->typeOfBoundary!="") {$vars[]='typeofboundary'; $vals[]= "'".pg_escape_string($thisgrainboundary->typeOfBoundary)."'"; }
													$query = "insert into micro_grainboundary (\n";
													$query .= implode(",\n", $vars);
													$query .= ") values (\n";
													$query .= implode(",\n", $vals);
													$query .= ")\n";

													$this->db->query($query);

													if($thisgrainboundary->morphologies != ""){

														foreach($thisgrainboundary->morphologies as $thisgrainboundarymorphology){

															$grainboundarymorphology_id = $this->db->get_var("select nextval('micro_grainboundarymorphology_id_seq')");
															$query = "";
															$vars = ['id','grain_boundary_id'];
															$vals = [$grainboundarymorphology_id, $grainboundary_id];
															if($thisgrainboundarymorphology->type!="") {$vars[]='type'; $vals[]= "'".pg_escape_string($thisgrainboundarymorphology->type)."'"; }
															$query = "insert into micro_grainboundarymorphology (\n";
															$query .= implode(",\n", $vars);
															$query .= ") values (\n";
															$query .= implode(",\n", $vals);
															$query .= ")\n";

															$this->db->query($query);

														}

													}

													if($thisgrainboundary->descriptors != ""){

														foreach($thisgrainboundary->descriptors as $thisgrainboundarydescriptor){

															$grainboundarydescriptor_id = $this->db->get_var("select nextval('micro_grainboundarydescriptor_id_seq')");
															$query = "";
															$vars = ['id','grain_boundary_id'];
															$vals = [$grainboundarydescriptor_id, $grainboundary_id];
															if($thisgrainboundarydescriptor->type!="") {$vars[]='type'; $vals[]= "'".pg_escape_string($thisgrainboundarydescriptor->type)."'"; }
															$query = "insert into micro_grainboundarydescriptor (\n";
															$query .= implode(",\n", $vars);
															$query .= ") values (\n";
															$query .= implode(",\n", $vals);
															$query .= ")\n";

															$this->db->query($query);

															if($thisgrainboundarydescriptor->subTypes != ""){

																foreach($thisgrainboundarydescriptor->subTypes as $thisgrainboundarydescriptorsub){

																	$grainboundarydescriptorsub_id = $this->db->get_var("select nextval('micro_grainboundarydescriptorsub_id_seq')");
																	$query = "";
																	$vars = ['id','grain_boundary_descriptor_id'];
																	$vals = [$grainboundarydescriptorsub_id, $grainboundarydescriptor_id];
																	if($thisgrainboundarydescriptorsub->type!="") {$vars[]='type'; $vals[]= "'".pg_escape_string($thisgrainboundarydescriptorsub->type)."'"; }
																	if($thisgrainboundarydescriptorsub->otherType!="") {$vars[]='othertype'; $vals[]= "'".pg_escape_string($thisgrainboundarydescriptorsub->otherType)."'"; }
																	$query = "insert into micro_grainboundarydescriptorsub (\n";
																	$query .= implode(",\n", $vars);
																	$query .= ") values (\n";
																	$query .= implode(",\n", $vals);
																	$query .= ")\n";

																	$this->db->query($query);

																}

															}

														}

													}

												}

											}

										}

										if($thisspotmetadata->intraGrainInfo != ""){

											$thisintragraininfo = $thisspotmetadata->intraGrainInfo;

											$intragraininfo_id = $this->db->get_var("select nextval('micro_intragraininfo_id_seq')");
											$query = "";
											$vars = ['id','spot_id'];
											$vals = [$intragraininfo_id, $spotmetadata_id];
											if($thisintragraininfo->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisintragraininfo->notes)."'"; }
											$query = "insert into micro_intragraininfo (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

											if($thisintragraininfo->grains != ""){

												foreach($thisintragraininfo->grains as $thisintragrain){

													$intragrain_id = $this->db->get_var("select nextval('micro_intragrain_id_seq')");
													$query = "";
													$vars = ['id','intragraininfo_id'];
													$vals = [$intragrain_id, $intragraininfo_id];
													if($thisintragrain->mineral!="") {$vars[]='mineral'; $vals[]= "'".pg_escape_string($thisintragrain->mineral)."'"; }
													$query = "insert into micro_intragrain (\n";
													$query .= implode(",\n", $vars);
													$query .= ") values (\n";
													$query .= implode(",\n", $vals);
													$query .= ")\n";

													$this->db->query($query);

													if($thisintragrain->grainTextures != null){

														foreach($thisintragrain->grainTextures as $thisintragraintexturalfeature){

															$intragraintexturalfeature_id = $this->db->get_var("select nextval('micro_intragraintexturalfeature_id_seq')");
															$query = "";
															$vars = ['id', 'intragrain_id'];
															$vals = [$intragraintexturalfeature_id, $intragrain_id];
															if($thisintragraintexturalfeature->type!="") {$vars[]='type'; $vals[]= "'".pg_escape_string($thisintragraintexturalfeature->type)."'"; }
															if($thisintragraintexturalfeature->otherType!="") {$vars[]='othertype'; $vals[]= "'".pg_escape_string($thisintragraintexturalfeature->otherType)."'"; }
															$query = "insert into micro_intragraintexturalfeature (\n";
															$query .= implode(",\n", $vars);
															$query .= ") values (\n";
															$query .= implode(",\n", $vals);
															$query .= ")\n";

															$this->db->query($query);

														}

													}

												}

											}

										}

										if($thisspotmetadata->extinctionMicrostructureInfo != ""){

											$thisextinctionmicrographinfo = $thisspotmetadata->extinctionMicrostructureInfo;

											$extinctionmicrographinfo_id = $this->db->get_var("select nextval('micro_extinctionmicrostructureinfo_id_seq')");
											$query = "";
											$vars = ['id','spot_id'];
											$vals = [$extinctionmicrographinfo_id, $spotmetadata_id];
											if($thisextinctionmicrographinfo->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisextinctionmicrographinfo->notes)."'"; }
											$query = "insert into micro_extinctionmicrostructureinfo (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

											if($thisextinctionmicrographinfo->extinctionMicrostructures != ""){

												foreach($thisextinctionmicrographinfo->extinctionMicrostructures as $thismicrostructure){

													$extinctionmicrostructure_id = $this->db->get_var("select nextval('micro_extinctionmicrostructure_id_seq')");
													$query = "";
													$vars = ['id','extinction_microstructure_id'];
													$vals = [$extinctionmicrostructure_id, $extinctionmicrographinfo_id];

													if($thismicrostructure->phase!="") {$vars[]='phase'; $vals[]= "'".pg_escape_string($thismicrostructure->phase)."'"; }

													$query = "insert into micro_extinctionmicrostructure (\n";
													$query .= implode(",\n", $vars);
													$query .= ") values (\n";
													$query .= implode(",\n", $vals);
													$query .= ")\n";

													$this->db->query($query);

													if($thismicrostructure->dislocations!="") {
														foreach($thismicrostructure->dislocations as $f){
															$this->db->query("insert into micro_extinctiondislocation (extinction_id, type) values ($extinctionmicrostructure_id,'$f->type')");
														}
													}

													if($thismicrostructure->subDislocations!="") {
														foreach($thismicrostructure->subDislocations as $f){
															$this->db->query("insert into micro_extinctiondislocationsub (extinction_id, type) values ($extinctionmicrostructure_id,'$f->type')");
														}
													}

													if($thismicrostructure->heterogeneousExtinctions!="") {
														foreach($thismicrostructure->heterogeneousExtinctions as $f){
															$this->db->query("insert into micro_extinctionhetero (extinction_id, type) values ($extinctionmicrostructure_id,'$f->type')");
														}
													}

													if($thismicrostructure->subGrainStructures!="") {
														foreach($thismicrostructure->subGrainStructures as $f){
															$this->db->query("insert into micro_extinctionsubgrain (extinction_id, type) values ($extinctionmicrostructure_id,'$f->type')");
														}
													}

													if($thismicrostructure->extinctionBands!="") {
														foreach($thismicrostructure->extinctionBands as $f){
															$this->db->query("insert into micro_extinctionbands (extinction_id, type) values ($extinctionmicrostructure_id,'$f->type')");
														}
													}

													if($thismicrostructure->subWideExtinctionBands!="") {
														foreach($thismicrostructure->subWideExtinctionBands as $f){
															$this->db->query("insert into micro_extinctionwidebands (extinction_id, type) values ($extinctionmicrostructure_id,'$f->type')");
														}
													}

													if($thismicrostructure->subFineExtinctionBands!="") {
														foreach($thismicrostructure->subFineExtinctionBands as $f){
															$this->db->query("insert into micro_extinctionfinebands (extinction_id, type) values ($extinctionmicrostructure_id,'$f->type')");
														}
													}

												}

											}

										}

										if($thisspotmetadata->faultsShearZonesInfo != ""){

											$thisfaultsShearZonesInfo = $thisspotmetadata->faultsShearZonesInfo;

											$faultsshearzonesinfo_id = $this->db->get_var("select nextval('micro_faultsshearzonesinfo_id_seq')");
											$query = "";
											$vars = ['id','spot_id'];
											$vals = [$faultsshearzonesinfo_id, $spotmetadata_id];
											if($thisfaultsShearZonesInfo->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisfaultsShearZonesInfo->notes)."'"; }
											$query = "insert into micro_faultsshearzonesinfo (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

											if($thisfaultsShearZonesInfo->faultsShearZones != null){

												foreach($thisfaultsShearZonesInfo->faultsShearZones as $thisfaultshearzones){

													$faultshearzones_id = $this->db->get_var("select nextval('micro_faultsshearzones_id_seq')");
													$query = "";
													$vars = ['id','faults_shear_zones_info_id'];
													$vals = [$faultshearzones_id, $faultsshearzonesinfo_id];

													if($thisfaultshearzones->offset!="") {$vars[]='offsetval'; $vals[]= "$thisfaultshearzones->offset"; }
													if($thisfaultshearzones->offsetUnit!="") {$vars[]='offset_unit'; $vals[]= "'".pg_escape_string($thisfaultshearzones->offsetUnit)."'"; }
													if($thisfaultshearzones->width!="") {$vars[]='width'; $vals[]= "$thisfaultshearzones->width"; }
													if($thisfaultshearzones->widthUnit!="") {$vars[]='width_unit'; $vals[]= "'".pg_escape_string($thisfaultshearzones->widthUnit)."'"; }

													$query = "insert into micro_faultsshearzones (\n";
													$query .= implode(",\n", $vars);
													$query .= ") values (\n";
													$query .= implode(",\n", $vals);
													$query .= ")\n";

													$this->db->query($query);

													if($thisfaultshearzones->shearSenses != null){
														foreach($thisfaultshearzones->shearSenses as $sense){
															$this->db->query("
																insert into micro_faultsshearzonesshearsense (faults_shear_zones_id, sense_type)
																values
																($faultshearzones_id, '$sense->type');
															");
														}
													}

													if($thisfaultshearzones->indicators != null){
														foreach($thisfaultshearzones->indicators as $indicators){
															$this->db->query("
																insert into micro_faultsshearzonesindicators (faults_shear_zones_id, indicator_type)
																values
																($faultshearzones_id, '$indicators->type');
															");
														}
													}

												}

											}

										}

										if($thisspotmetadata->veinInfo != ""){

											$thisveininfo = $thisspotmetadata->veinInfo;

											$veininfo_id = $this->db->get_var("select nextval('micro_veininfo_id_seq')");
											$query = "";
											$vars = ['id','spot_id'];
											$vals = [$veininfo_id, $spotmetadata_id];
											if($thisveininfo->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisveininfo->notes)."'"; }
											$query = "insert into micro_veininfo (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

											if($thisveininfo->veins != ""){

												foreach($thisveininfo->veins as $thisvein){

													$vein_id = $this->db->get_var("select nextval('micro_vein_id_seq')");
													$query = "";
													$vars = ['id','veininfo_id'];
													$vals = [$vein_id, $veininfo_id];
													if($thisvein->mineralogy!="") {$vars[]='mineralogy'; $vals[]= "'".pg_escape_string($thisvein->mineralogy)."'"; }
													$query = "insert into micro_vein (\n";
													$query .= implode(",\n", $vars);
													$query .= ") values (\n";
													$query .= implode(",\n", $vals);
													$query .= ")\n";

													$this->db->query($query);

													if($thisvein->crystalShapes != ""){

														foreach($thisvein->crystalShapes as $thisveinsub){

															$veinsub_id = $this->db->get_var("select nextval('micro_veinsub_id_seq')");
															$query = "";
															$vars = ['id','vein_id'];
															$vals = [$veinsub_id, $vein_id];
															if($thisveinsub->type!="") {$vars[]='type'; $vals[]= "'".pg_escape_string($thisveinsub->type)."'"; }
															if($thisveinsub->subType!="") {$vars[]='subtype'; $vals[]= "'".pg_escape_string($thisveinsub->subType)."'"; }
															if($thisveinsub->numericValue!=""){ $vars[]='numericvalue'; $vals[]= $thisveinsub->numericValue; }
															if($thisveinsub->unit!="") {$vars[]='unit'; $vals[]= "'".pg_escape_string($thisveinsub->unit)."'"; }
															$query = "insert into micro_veinsub (\n";
															$query .= implode(",\n", $vars);
															$query .= ") values (\n";
															$query .= implode(",\n", $vals);
															$query .= ")\n";

															$this->db->query($query);

															$this->db->query("insert into micro_vein_crystalshapes (vein_id, veinsub_id) values ($vein_id, $veinsub_id)");

														}

													}

													if($thisvein->growthMorphologies != ""){

														foreach($thisvein->growthMorphologies as $thisveinsub){

															$veinsub_id = $this->db->get_var("select nextval('micro_veinsub_id_seq')");
															$query = "";
															$vars = ['id','vein_id'];
															$vals = [$veinsub_id, $vein_id];
															if($thisveinsub->type!="") {$vars[]='type'; $vals[]= "'".pg_escape_string($thisveinsub->type)."'"; }
															if($thisveinsub->subType!="") {$vars[]='subtype'; $vals[]= "'".pg_escape_string($thisveinsub->subType)."'"; }
															if($thisveinsub->numericValue!=""){ $vars[]='numericvalue'; $vals[]= $thisveinsub->numericValue; }
															if($thisveinsub->unit!="") {$vars[]='unit'; $vals[]= "'".pg_escape_string($thisveinsub->unit)."'"; }
															$query = "insert into micro_veinsub (\n";
															$query .= implode(",\n", $vars);
															$query .= ") values (\n";
															$query .= implode(",\n", $vals);
															$query .= ")\n";

															$this->db->query($query);

															$this->db->query("insert into micro_vein_growthmorphologies (vein_id, veinsub_id) values ($vein_id, $veinsub_id)");

														}

													}

													if($thisvein->inclusionTrails != ""){

														foreach($thisvein->inclusionTrails as $thisveinsub){

															$veinsub_id = $this->db->get_var("select nextval('micro_veinsub_id_seq')");
															$query = "";
															$vars = ['id','vein_id'];
															$vals = [$veinsub_id, $vein_id];
															if($thisveinsub->type!="") {$vars[]='type'; $vals[]= "'".pg_escape_string($thisveinsub->type)."'"; }
															if($thisveinsub->subType!="") {$vars[]='subtype'; $vals[]= "'".pg_escape_string($thisveinsub->subType)."'"; }
															if($thisveinsub->numericValue!=""){ $vars[]='numericvalue'; $vals[]= $thisveinsub->numericValue; }
															if($thisveinsub->unit!="") {$vars[]='unit'; $vals[]= "'".pg_escape_string($thisveinsub->unit)."'"; }
															$query = "insert into micro_veinsub (\n";
															$query .= implode(",\n", $vars);
															$query .= ") values (\n";
															$query .= implode(",\n", $vals);
															$query .= ")\n";

															$this->db->query($query);

															$this->db->query("insert into micro_vein_inclusiontrails (vein_id, veinsub_id) values ($vein_id, $veinsub_id)");

														}

													}

													if($thisvein->kinematics != ""){

														foreach($thisvein->kinematics as $thisveinsub){

															$veinsub_id = $this->db->get_var("select nextval('micro_veinsub_id_seq')");
															$query = "";
															$vars = ['id','vein_id'];
															$vals = [$veinsub_id, $vein_id];
															if($thisveinsub->type!="") {$vars[]='type'; $vals[]= "'".pg_escape_string($thisveinsub->type)."'"; }
															if($thisveinsub->subType!="") {$vars[]='subtype'; $vals[]= "'".pg_escape_string($thisveinsub->subType)."'"; }
															if($thisveinsub->numericValue!=""){ $vars[]='numericvalue'; $vals[]= $thisveinsub->numericValue; }
															if($thisveinsub->unit!="") {$vars[]='unit'; $vals[]= "'".pg_escape_string($thisveinsub->unit)."'"; }
															$query = "insert into micro_veinsub (\n";
															$query .= implode(",\n", $vars);
															$query .= ") values (\n";
															$query .= implode(",\n", $vals);
															$query .= ")\n";

															$this->db->query($query);

															$this->db->query("insert into micro_vein_kinematics (vein_id, veinsub_id) values ($vein_id, $veinsub_id)");

														}

													}

												}

											}

										}

										if($thisspotmetadata->pseudotachylyteInfo != ""){

											$thispseudotachylyteinfo = $thisspotmetadata->pseudotachylyteInfo;

											$pseudotachylyteinfo_id = $this->db->get_var("select nextval('micro_pseudotachylyteinfo_id_seq')");
											$query = "";
											$vars = ['id','spot_id'];
											$vals = [$pseudotachylyteinfo_id, $spotmetadata_id];
											if($thispseudotachylyteinfo->reasoning!="") {$vars[]='reasoning'; $vals[]= "'".pg_escape_string($thispseudotachylyteinfo->reasoning)."'"; }
											if($thispseudotachylyteinfo->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thispseudotachylyteinfo->notes)."'"; }
											$query = "insert into micro_pseudotachylyteinfo (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

											if($thispseudotachylyteinfo->pseudotachylytes != ""){

												foreach($thispseudotachylyteinfo->pseudotachylytes as $thispseudotachylyte){

													$pseudotachylyte_id = $this->db->get_var("select nextval('micro_pseudotachylyte_id_seq')");
													$query = "";
													$vars = ['id','pseudotachylyteinfo_id'];
													$vals = [$pseudotachylyte_id, $pseudotachylyteinfo_id];
													if($thispseudotachylyte->label!="") {$vars[]='label'; $vals[]= "'".pg_escape_string($thispseudotachylyte->label)."'"; }
													if($thispseudotachylyte->hasMatrixGroundmass!=""){ $vars[]='hasmatrixgroundmass'; $vals[]= ($thispseudotachylyte->hasMatrixGroundmass == 1 ? 'true' : 'false'); }
													if($thispseudotachylyte->matrixGroundmassColor!="") {$vars[]='matrixgroundmasscolor'; $vals[]= "'".pg_escape_string($thispseudotachylyte->matrixGroundmassColor)."'"; }
													if($thispseudotachylyte->matrixGroundmassConstraintsOnComposition!=""){ $vars[]='matrixgroundmassconstraintsoncomposition'; $vals[]= ($thispseudotachylyte->matrixGroundmassConstraintsOnComposition == 1 ? 'true' : 'false'); }
													if($thispseudotachylyte->matrixGroundmassConstraintsOnCompositionDetails!="") {$vars[]='matrixgroundmassconstraintsoncompositiondetails'; $vals[]= "'".pg_escape_string($thispseudotachylyte->matrixGroundmassConstraintsOnCompositionDetails)."'"; }
													if($thispseudotachylyte->hasCrystallites!=""){ $vars[]='hascrystallites'; $vals[]= ($thispseudotachylyte->hasCrystallites == 1 ? 'true' : 'false'); }
													if($thispseudotachylyte->crystallitesMineralogy!="") {$vars[]='crystallitesmineralogy'; $vals[]= "'".pg_escape_string($thispseudotachylyte->crystallitesMineralogy)."'"; }
													if($thispseudotachylyte->otherShape!="") {$vars[]='othershape'; $vals[]= "'".pg_escape_string($thispseudotachylyte->otherShape)."'"; }
													if($thispseudotachylyte->crystallitesLowerSize!=""){ $vars[]='crystalliteslowersize'; $vals[]= $thispseudotachylyte->crystallitesLowerSize; }
													if($thispseudotachylyte->crystallitesLowerSizeUnit!="") {$vars[]='crystalliteslowersizeunit'; $vals[]= "'".pg_escape_string($thispseudotachylyte->crystallitesLowerSizeUnit)."'"; }
													if($thispseudotachylyte->crystallitesUpperSize!=""){ $vars[]='crystallitesuppersize'; $vals[]= $thispseudotachylyte->crystallitesUpperSize; }
													if($thispseudotachylyte->crystallitesUpperSizeUnit!="") {$vars[]='crystallitesuppersizeunit'; $vals[]= "'".pg_escape_string($thispseudotachylyte->crystallitesUpperSizeUnit)."'"; }
													if($thispseudotachylyte->crystallitesZoning!=""){ $vars[]='crystalliteszoning'; $vals[]= ($thispseudotachylyte->crystallitesZoning == 1 ? 'true' : 'false'); }
													if($thispseudotachylyte->crystallitesZoningDetails!="") {$vars[]='crystalliteszoningdetails'; $vals[]= "'".pg_escape_string($thispseudotachylyte->crystallitesZoningDetails)."'"; }
													if($thispseudotachylyte->crystallitesDistribution!="") {$vars[]='crystallitesdistribution'; $vals[]= "'".pg_escape_string($thispseudotachylyte->crystallitesDistribution)."'"; }
													if($thispseudotachylyte->hasSurvivorClasts!=""){ $vars[]='hassurvivorclasts'; $vals[]= ($thispseudotachylyte->hasSurvivorClasts == 1 ? 'true' : 'false'); }
													if($thispseudotachylyte->survivorClastsMineralogy!="") {$vars[]='survivorclastsmineralogy'; $vals[]= "'".pg_escape_string($thispseudotachylyte->survivorClastsMineralogy)."'"; }
													if($thispseudotachylyte->survivorClastsMarginDescription!="") {$vars[]='survivorclastsmargindescription'; $vals[]= "'".pg_escape_string($thispseudotachylyte->survivorClastsMarginDescription)."'"; }
													if($thispseudotachylyte->survivorClastsDistribution!="") {$vars[]='survivorclastsdistribution'; $vals[]= "'".pg_escape_string($thispseudotachylyte->survivorClastsDistribution)."'"; }
													if($thispseudotachylyte->hasSulphideOxide!=""){ $vars[]='hassulphideoxide'; $vals[]= ($thispseudotachylyte->hasSulphideOxide == 1 ? 'true' : 'false'); }
													if($thispseudotachylyte->sulphideOxideMineralogy!="") {$vars[]='sulphideoxidemineralogy'; $vals[]= "'".pg_escape_string($thispseudotachylyte->sulphideOxideMineralogy)."'"; }
													if($thispseudotachylyte->sulphideOxideLowerSize!=""){ $vars[]='sulphideoxidelowersize'; $vals[]= $thispseudotachylyte->sulphideOxideLowerSize; }
													if($thispseudotachylyte->sulphideOxideLowerSizeUnit!="") {$vars[]='sulphideoxidelowersizeunit'; $vals[]= "'".pg_escape_string($thispseudotachylyte->sulphideOxideLowerSizeUnit)."'"; }
													if($thispseudotachylyte->sulphideOxideUpperSize!=""){ $vars[]='sulphideoxideuppersize'; $vals[]= $thispseudotachylyte->sulphideOxideUpperSize; }
													if($thispseudotachylyte->sulphideOxideUpperSizeUnit!="") {$vars[]='sulphideoxideuppersizeunit'; $vals[]= "'".pg_escape_string($thispseudotachylyte->sulphideOxideUpperSizeUnit)."'"; }
													if($thispseudotachylyte->sulphideOxideDistribution!="") {$vars[]='sulphideoxidedistribution'; $vals[]= "'".pg_escape_string($thispseudotachylyte->sulphideOxideDistribution)."'"; }
													if($thispseudotachylyte->hasFabric!=""){ $vars[]='hasfabric'; $vals[]= ($thispseudotachylyte->hasFabric == 1 ? 'true' : 'false'); }
													if($thispseudotachylyte->fabricDescription!="") {$vars[]='fabricdescription'; $vals[]= "'".pg_escape_string($thispseudotachylyte->fabricDescription)."'"; }
													if($thispseudotachylyte->hasInjectionFeatures!=""){ $vars[]='hasinjectionfeatures'; $vals[]= ($thispseudotachylyte->hasInjectionFeatures == 1 ? 'true' : 'false'); }
													if($thispseudotachylyte->injectionFeaturesAperture!=""){ $vars[]='injectionfeaturesaperture'; $vals[]= $thispseudotachylyte->injectionFeaturesAperture; }
													if($thispseudotachylyte->injectionFeaturesApertureUnit!="") {$vars[]='injectionfeaturesapertureunit'; $vals[]= "'".pg_escape_string($thispseudotachylyte->injectionFeaturesApertureUnit)."'"; }
													if($thispseudotachylyte->injectionFeaturesLength!=""){ $vars[]='injectionfeatureslength'; $vals[]= $thispseudotachylyte->injectionFeaturesLength; }
													if($thispseudotachylyte->injectionFeaturesLengthUnit!="") {$vars[]='injectionfeatureslengthunit'; $vals[]= "'".pg_escape_string($thispseudotachylyte->injectionFeaturesLengthUnit)."'"; }
													if($thispseudotachylyte->hasChilledMargins!=""){ $vars[]='haschilledmargins'; $vals[]= ($thispseudotachylyte->hasChilledMargins == 1 ? 'true' : 'false'); }
													if($thispseudotachylyte->chilledMarginsDescription!="") {$vars[]='chilledmarginsdescription'; $vals[]= "'".pg_escape_string($thispseudotachylyte->chilledMarginsDescription)."'"; }
													if($thispseudotachylyte->hasVesciclesAmygdules!=""){ $vars[]='hasvesciclesamygdules'; $vals[]= ($thispseudotachylyte->hasVesciclesAmygdules == 1 ? 'true' : 'false'); }
													if($thispseudotachylyte->vesciclesAmygdulesMineralogy!="") {$vars[]='vesciclesamygdulesmineralogy'; $vals[]= "'".pg_escape_string($thispseudotachylyte->vesciclesAmygdulesMineralogy)."'"; }
													if($thispseudotachylyte->vesciclesAmygdulesLowerSize!=""){ $vars[]='vesciclesamygduleslowersize'; $vals[]= $thispseudotachylyte->vesciclesAmygdulesLowerSize; }
													if($thispseudotachylyte->vesciclesAmygdulesLowerSizeUnit!="") {$vars[]='vesciclesamygduleslowersizeunit'; $vals[]= "'".pg_escape_string($thispseudotachylyte->vesciclesAmygdulesLowerSizeUnit)."'"; }
													if($thispseudotachylyte->vesciclesAmygdulesUpperSize!=""){ $vars[]='vesciclesamygdulesuppersize'; $vals[]= $thispseudotachylyte->vesciclesAmygdulesUpperSize; }
													if($thispseudotachylyte->vesciclesAmygdulesUpperSizeUnit!="") {$vars[]='vesciclesamygdulesuppersizeunit'; $vals[]= "'".pg_escape_string($thispseudotachylyte->vesciclesAmygdulesUpperSizeUnit)."'"; }
													if($thispseudotachylyte->vesciclesAmygdulesDistribution!="") {$vars[]='vesciclesamygdulesdistribution'; $vals[]= "'".pg_escape_string($thispseudotachylyte->vesciclesAmygdulesDistribution)."'"; }
													$query = "insert into micro_pseudotachylyte (\n";
													$query .= implode(",\n", $vars);
													$query .= ") values (\n";
													$query .= implode(",\n", $vals);
													$query .= ")\n";

													$this->db->query($query);

												}

											}

										}

										if($thisspotmetadata->foldInfo != ""){

											$thisfoldinfo = $thisspotmetadata->foldInfo;

											$foldinfo_id = $this->db->get_var("select nextval('micro_foldinfo_id_seq')");
											$query = "";
											$vars = ['id','spot_id'];
											$vals = [$foldinfo_id, $spotmetadata_id];
											if($thisfoldinfo->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisfoldinfo->notes)."'"; }
											$query = "insert into micro_foldinfo (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

											if($thisfoldinfo->folds != ""){

												foreach($thisfoldinfo->folds as $thisfold){

													$fold_id = $this->db->get_var("select nextval('micro_fold_id_seq')");
													$query = "";
													$vars = ['id','fold_info_id'];
													$vals = [$fold_id, $foldinfo_id];
													if($thisfold->label!="") {$vars[]='label'; $vals[]= "'".pg_escape_string($thisfold->label)."'"; }
													if($thisfold->interLimbAngle != ""){ $vars[]='interlimbangle'; $vals[]= implode(",", $thisfold->interLimbAngle); }
													if($thisfold->interLimbAngleOther!="") {$vars[]='interlimbangleother'; $vals[]= "'".pg_escape_string($thisfold->interLimbAngleOther)."'"; }
													if($thisfold->closure!="") {$vars[]='closure'; $vals[]= "'".pg_escape_string($thisfold->closure)."'"; }
													if($thisfold->closureOther!="") {$vars[]='closureother'; $vals[]= "'".pg_escape_string($thisfold->closureOther)."'"; }
													if($thisfold->orientationAxialTrace!="") {$vars[]='orientationaxialtrace'; $vals[]= "'".pg_escape_string($thisfold->orientationAxialTrace)."'"; }
													if($thisfold->symmetry!="") {$vars[]='symmetry'; $vals[]= "'".pg_escape_string($thisfold->symmetry)."'"; }
													if($thisfold->vergence!="") {$vars[]='vergence'; $vals[]= "'".pg_escape_string($thisfold->vergence)."'"; }
													if($thisfold->wavelength!=""){ $vars[]='wavelength'; $vals[]= $thisfold->wavelength; }
													if($thisfold->wavelengthUnit!="") {$vars[]='wavelengthunit'; $vals[]= "'".pg_escape_string($thisfold->wavelengthUnit)."'"; }
													if($thisfold->amplitude!=""){ $vars[]='amplitude'; $vals[]= $thisfold->amplitude; }
													if($thisfold->amplitudeUnit!="") {$vars[]='amplitudeunit'; $vals[]= "'".pg_escape_string($thisfold->amplitudeUnit)."'"; }
													if($thisfold->foldStyle!="") {$vars[]='foldstyle'; $vals[]= "'".pg_escape_string($thisfold->foldStyle)."'"; }
													if($thisfold->foldStyleOther!="") {$vars[]='foldstyleother'; $vals[]= "'".pg_escape_string($thisfold->foldStyleOther)."'"; }
													if($thisfold->foldContinuity!="") {$vars[]='foldcontinuity'; $vals[]= "'".pg_escape_string($thisfold->foldContinuity)."'"; }
													if($thisfold->foldContinuityOther!="") {$vars[]='foldcontinuityother'; $vals[]= "'".pg_escape_string($thisfold->foldContinuityOther)."'"; }
													if($thisfold->facing!="") {$vars[]='facing'; $vals[]= "'".pg_escape_string($thisfold->facing)."'"; }
													if($thisfold->facingOther!="") {$vars[]='facingother'; $vals[]= "'".pg_escape_string($thisfold->facingOther)."'"; }
													$query = "insert into micro_fold (\n";
													$query .= implode(",\n", $vars);
													$query .= ") values (\n";
													$query .= implode(",\n", $vals);
													$query .= ")\n";

													$this->db->query($query);

												}

											}

										}

										if($thisspotmetadata->fractureInfo != ""){

											$thisfractureinfo = $thisspotmetadata->fractureInfo;

											$fractureinfo_id = $this->db->get_var("select nextval('micro_fractureinfo_id_seq')");
											$query = "";
											$vars = ['id','spot_id'];
											$vals = [$fractureinfo_id, $spotmetadata_id];
											if($thisfractureinfo->notes!="") {$vars[]='notes'; $vals[]= "'".pg_escape_string($thisfractureinfo->notes)."'"; }
											$query = "insert into micro_fractureinfo (\n";
											$query .= implode(",\n", $vars);
											$query .= ") values (\n";
											$query .= implode(",\n", $vals);
											$query .= ")\n";

											$this->db->query($query);

											if($thisfractureinfo->fractures != ""){

												foreach($thisfractureinfo->fractures as $thisfracture){

													$fracture_id = $this->db->get_var("select nextval('micro_fracture_id_seq')");
													$query = "";
													$vars = ['id','fracture_info_id'];
													$vals = [$fracture_id, $fractureinfo_id];

													if($thisfracture->granularity!="") {$vars[]='granularity'; $vals[]= "'".pg_escape_string($thisfracture->granularity)."'"; }
													if($thisfracture->mineralogy!="") {$vars[]='mineralogy'; $vals[]= "'".pg_escape_string($thisfracture->mineralogy)."'"; }
													if($thisfracture->kinematicType!="") {$vars[]='kinematictype'; $vals[]= "'".pg_escape_string($thisfracture->kinematicType)."'"; }
													if($thisfracture->openingAperture!="") {$vars[]='openingaperture'; $vals[]= "$thisfracture->openingAperture"; }
													if($thisfracture->openingApertureUnit!="") {$vars[]='openingapertureunit'; $vals[]= "'".pg_escape_string($thisfracture->openingApertureUnit)."'"; }
													if($thisfracture->shearOffset!="") {$vars[]='shearoffset'; $vals[]= "$thisfracture->shearOffset"; }
													if($thisfracture->shearOffsetUnit!="") {$vars[]='shearoffsetunit'; $vals[]= "'".pg_escape_string($thisfracture->shearOffsetUnit)."'"; }
													if($thisfracture->hybridAperture!="") {$vars[]='hybridaperture'; $vals[]= "$thisfracture->hybridAperture"; }
													if($thisfracture->hybridApertureUnit!="") {$vars[]='hybridapertureunit'; $vals[]= "'".pg_escape_string($thisfracture->hybridApertureUnit)."'"; }
													if($thisfracture->hybridOffset!="") {$vars[]='hybridoffset'; $vals[]= "$thisfracture->hybridOffset"; }
													if($thisfracture->hybridOffsetUnit!="") {$vars[]='hybridoffsetunit'; $vals[]= "'".pg_escape_string($thisfracture->hybridOffsetUnit)."'"; }
													if($thisfracture->sealedHealed==1) {
														$vars[]='sealedhealed'; $vals[]= "true";
													}else{
														$vars[]='sealedhealed'; $vals[]= "false";
													}

													$query = "insert into micro_fracture (\n";
													$query .= implode(",\n", $vars);
													$query .= ") values (\n";
													$query .= implode(",\n", $vals);
													$query .= ")\n";

													$this->db->query($query);

												}

											}

										}

									}

								}

							}

						}

					}

				}

			}

		}

		//Log upload here
		$this->db->query("
			insert into up_down_stats
				(
					userpkey,
					upload_download,
					data_type,
					count_type,
					count
				) values (
					$userpkey,
					'upload',
					'strabomicro',
					'micrograph',
					$micrographcount
				)
		");

	}

	public function getProjectCount($projectid){
		$projectcount = $this->db->get_var("select count(*) from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid';");
		return $projectcount;
	}

	public function deleteProject($projectid) {

		$pkey = $this->db->get_var("select id from micro_projectmetadata where strabo_id='$projectid' and userpkey=$this->userpkey");

		if($pkey != ""){
			exec("rm -rf ".$_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/".$pkey);
			exec("rm -rf ".$_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/".$pkey.".zip");

			$this->db->query("delete from micro_tag where project_id = $pkey");
			$this->db->query("delete from micro_micrograph_tag where project_id = $pkey");
			$this->db->query("delete from micro_spot_tag where project_id = $pkey");

			$this->db->query("
							delete from micro_fold where fold_info_id in (
								select id from micro_foldinfo where spot_id in (
									select id from micro_spotmetadata where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_foldinfo where spot_id in (
								select id from micro_spotmetadata where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_fold where fold_info_id in (
								select id from micro_foldinfo where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_foldinfo where micrograph_id in (
								select id from micro_micrographmetadata where sample_id in (
									select id from micro_samplemetadata where dataset_id in (
										select id from micro_datasetmetadata where project_id in (
											select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
										)
									)
								)
							);

							delete from micro_fracture where fracture_info_id in (
								select id from micro_fractureinfo where spot_id in (
									select id from micro_spotmetadata where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_fractureinfo where spot_id in (
								select id from micro_spotmetadata where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_fracture where fracture_info_id in (
								select id from micro_fractureinfo where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_fractureinfo where micrograph_id in (
								select id from micro_micrographmetadata where sample_id in (
									select id from micro_samplemetadata where dataset_id in (
										select id from micro_datasetmetadata where project_id in (
											select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
										)
									)
								)
							);

							delete from micro_vein_crystalshapes where vein_id in (
								select id from micro_vein where veininfo_id in (
									select id from micro_veininfo where spot_id in (
										select id from micro_spotmetadata where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_vein_growthmorphologies where vein_id in (
								select id from micro_vein where veininfo_id in (
									select id from micro_veininfo where spot_id in (
										select id from micro_spotmetadata where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_vein_inclusiontrails where vein_id in (
								select id from micro_vein where veininfo_id in (
									select id from micro_veininfo where spot_id in (
										select id from micro_spotmetadata where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_vein_kinematics where vein_id in (
								select id from micro_vein where veininfo_id in (
									select id from micro_veininfo where spot_id in (
										select id from micro_spotmetadata where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_vein_crystalshapes where vein_id in (
								select id from micro_vein where veininfo_id in (
									select id from micro_veininfo where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_vein_growthmorphologies where vein_id in (
								select id from micro_vein where veininfo_id in (
									select id from micro_veininfo where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_vein_inclusiontrails where vein_id in (
								select id from micro_vein where veininfo_id in (
									select id from micro_veininfo where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_vein_kinematics where vein_id in (
								select id from micro_vein where veininfo_id in (
									select id from micro_veininfo where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_faultsshearzonesshearsense where faults_shear_zones_id in (
								select id from micro_faultsshearzones where faults_shear_zones_info_id in (
									select id from micro_faultsshearzonesinfo where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_faultsshearzonesshearsense where faults_shear_zones_id in (
								select id from micro_faultsshearzones where faults_shear_zones_info_id in (
									select id from micro_faultsshearzonesinfo where spot_id in (
										select id from micro_spotmetadata where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_faultsshearzonesindicators where faults_shear_zones_id in (
								select id from micro_faultsshearzones where faults_shear_zones_info_id in (
									select id from micro_faultsshearzonesinfo where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_faultsshearzonesindicators where faults_shear_zones_id in (
								select id from micro_faultsshearzones where faults_shear_zones_info_id in (
									select id from micro_faultsshearzonesinfo where spot_id in (
										select id from micro_spotmetadata where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_faultsshearzones where faults_shear_zones_info_id in (
								select id from micro_faultsshearzonesinfo where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_faultsshearzones where faults_shear_zones_info_id in (
								select id from micro_faultsshearzonesinfo where spot_id in (
									select id from micro_spotmetadata where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_faultsshearzonesinfo where micrograph_id in (
								select id from micro_micrographmetadata where sample_id in (
									select id from micro_samplemetadata where dataset_id in (
										select id from micro_datasetmetadata where project_id in (
											select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
										)
									)
								)
							);

							delete from micro_faultsshearzonesinfo where spot_id in (
								select id from micro_spotmetadata where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_veinsub where vein_id in (
								select id from micro_vein where veininfo_id in (
									select id from micro_veininfo where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_veinsub where vein_id in (
								select id from micro_vein where veininfo_id in (
									select id from micro_veininfo where spot_id in (
										select id from micro_spotmetadata where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_vein where veininfo_id in (
								select id from micro_veininfo where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_vein where veininfo_id in (
								select id from micro_veininfo where spot_id in (
									select id from micro_spotmetadata where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_veininfo where micrograph_id in (
								select id from micro_micrographmetadata where sample_id in (
									select id from micro_samplemetadata where dataset_id in (
										select id from micro_datasetmetadata where project_id in (
											select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
										)
									)
								)
							);

							delete from micro_veininfo where spot_id in (
								select id from micro_spotmetadata where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_extinctiondislocation where extinction_id in (
								select id from micro_extinctionmicrostructure where extinction_microstructure_id in (
									select id from micro_extinctionmicrostructureinfo where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_extinctiondislocation where extinction_id in (
								select id from micro_extinctionmicrostructure where extinction_microstructure_id in (
									select id from micro_extinctionmicrostructureinfo where spot_id in (
										select id from micro_spotmetadata where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_extinctiondislocationsub where extinction_id in (
								select id from micro_extinctionmicrostructure where extinction_microstructure_id in (
									select id from micro_extinctionmicrostructureinfo where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_extinctiondislocationsub where extinction_id in (
								select id from micro_extinctionmicrostructure where extinction_microstructure_id in (
									select id from micro_extinctionmicrostructureinfo where spot_id in (
										select id from micro_spotmetadata where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_extinctionhetero where extinction_id in (
								select id from micro_extinctionmicrostructure where extinction_microstructure_id in (
									select id from micro_extinctionmicrostructureinfo where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_extinctionhetero where extinction_id in (
								select id from micro_extinctionmicrostructure where extinction_microstructure_id in (
									select id from micro_extinctionmicrostructureinfo where spot_id in (
										select id from micro_spotmetadata where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_extinctionsubgrain where extinction_id in (
								select id from micro_extinctionmicrostructure where extinction_microstructure_id in (
									select id from micro_extinctionmicrostructureinfo where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_extinctionsubgrain where extinction_id in (
								select id from micro_extinctionmicrostructure where extinction_microstructure_id in (
									select id from micro_extinctionmicrostructureinfo where spot_id in (
										select id from micro_spotmetadata where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_extinctionbands where extinction_id in (
								select id from micro_extinctionmicrostructure where extinction_microstructure_id in (
									select id from micro_extinctionmicrostructureinfo where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_extinctionbands where extinction_id in (
								select id from micro_extinctionmicrostructure where extinction_microstructure_id in (
									select id from micro_extinctionmicrostructureinfo where spot_id in (
										select id from micro_spotmetadata where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_extinctionwidebands where extinction_id in (
								select id from micro_extinctionmicrostructure where extinction_microstructure_id in (
									select id from micro_extinctionmicrostructureinfo where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_extinctionwidebands where extinction_id in (
								select id from micro_extinctionmicrostructure where extinction_microstructure_id in (
									select id from micro_extinctionmicrostructureinfo where spot_id in (
										select id from micro_spotmetadata where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_extinctionfinebands where extinction_id in (
								select id from micro_extinctionmicrostructure where extinction_microstructure_id in (
									select id from micro_extinctionmicrostructureinfo where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_extinctionfinebands where extinction_id in (
								select id from micro_extinctionmicrostructure where extinction_microstructure_id in (
									select id from micro_extinctionmicrostructureinfo where spot_id in (
										select id from micro_spotmetadata where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_extinctionmicrostructure where extinction_microstructure_id in (
								select id from micro_extinctionmicrostructureinfo where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_extinctionmicrostructure where extinction_microstructure_id in (
								select id from micro_extinctionmicrostructureinfo where spot_id in (
									select id from micro_spotmetadata where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_extinctionmicrostructureinfo where micrograph_id in (
								select id from micro_micrographmetadata where sample_id in (
									select id from micro_samplemetadata where dataset_id in (
										select id from micro_datasetmetadata where project_id in (
											select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
										)
									)
								)
							);

							delete from micro_extinctionmicrostructureinfo where spot_id in (
								select id from micro_spotmetadata where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_grainorientation where graininfo_id in (
								select id from micro_graininfo where spot_id in (
									select id from micro_spotmetadata where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_grainshape where graininfo_id in (
								select id from micro_graininfo where spot_id in (
									select id from micro_spotmetadata where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_grainsize where graininfo_id in (
								select id from micro_graininfo where spot_id in (
									select id from micro_spotmetadata where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_grainorientation where graininfo_id in (
								select id from micro_graininfo where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_grainshape where graininfo_id in (
								select id from micro_graininfo where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_grainsize where graininfo_id in (
								select id from micro_graininfo where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_graininfo where spot_id in (
								select id from micro_spotmetadata where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_graininfo where micrograph_id in (
								select id from micro_micrographmetadata where sample_id in (
									select id from micro_samplemetadata where dataset_id in (
										select id from micro_datasetmetadata where project_id in (
											select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
										)
									)
								)
							);

							delete from micro_projectgroups where micrograph_id in (
								select id from micro_micrographmetadata where sample_id in (
									select id from micro_samplemetadata where dataset_id in (
										select id from micro_datasetmetadata where project_id in (
											select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
										)
									)
								)
							);

							delete from micro_associatedfile where spot_id in (
								select id from micro_spotmetadata where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_associatedfile where micrograph_id in (
								select id from micro_micrographmetadata where sample_id in (
									select id from micro_samplemetadata where dataset_id in (
										select id from micro_datasetmetadata where project_id in (
											select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
										)
									)
								)
							);

							delete from micro_clasticdeformationbandsubtype where clastic_band_id in (
								select id from micro_clasticdeformationband where clastic_info_id in (
									select id from micro_clasticdeformationbandinfo where spot_id in (
										select id from micro_spotmetadata where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_clasticdeformationbandsubtype where clastic_band_id in (
								select id from micro_clasticdeformationband where clastic_info_id in (
									select id from micro_clasticdeformationbandinfo where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_clasticdeformationband where clastic_info_id in (
								select id from micro_clasticdeformationbandinfo where spot_id in (
									select id from micro_spotmetadata where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_clasticdeformationband where clastic_info_id in (
								select id from micro_clasticdeformationbandinfo where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_clasticdeformationbandinfo where spot_id in (
								select id from micro_spotmetadata where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_clasticdeformationbandinfo where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
							);

							delete from micro_mineral where mineralogy_id in (
								select id from micro_mineralogy where spot_id in (
									select id from micro_spotmetadata where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_mineral where mineralogy_id in (
								select id from micro_mineralogy where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_mineralogy where spot_id in (
								select id from micro_spotmetadata where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_mineralogy where micrograph_id in (
								select id from micro_micrographmetadata where sample_id in (
									select id from micro_samplemetadata where dataset_id in (
										select id from micro_datasetmetadata where project_id in (
											select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
										)
									)
								)
							);

							delete from micro_lithology where lithology_id in (
								select id from micro_lithologyinfo where spot_id in (
									select id from micro_spotmetadata where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_lithology where lithology_id in (
								select id from micro_lithologyinfo where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_lithologyinfo where spot_id in (
								select id from micro_spotmetadata where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_lithologyinfo where micrograph_id in (
								select id from micro_micrographmetadata where sample_id in (
									select id from micro_samplemetadata where dataset_id in (
										select id from micro_datasetmetadata where project_id in (
											select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
										)
									)
								)
							);

							delete from micro_grainboundarydescriptorsub where grain_boundary_descriptor_id in (
								select id from micro_grainboundarydescriptor where grain_boundary_id in (
									select id from micro_grainboundary where grain_boundary_info_id in (
										select id from micro_grainboundaryinfo where spot_id in (
											select id from micro_spotmetadata where micrograph_id in (
												select id from micro_micrographmetadata where sample_id in (
													select id from micro_samplemetadata where dataset_id in (
														select id from micro_datasetmetadata where project_id in (
															select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
														)
													)
												)
											)
										)
									)
								)
							);

							delete from micro_grainboundarydescriptorsub where grain_boundary_descriptor_id in (
								select id from micro_grainboundarydescriptor where grain_boundary_id in (
									select id from micro_grainboundary where grain_boundary_info_id in (
										select id from micro_grainboundaryinfo where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_grainboundarydescriptor where grain_boundary_id in (
								select id from micro_grainboundary where grain_boundary_info_id in (
									select id from micro_grainboundaryinfo where spot_id in (
										select id from micro_spotmetadata where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_grainboundarydescriptor where grain_boundary_id in (
								select id from micro_grainboundary where grain_boundary_info_id in (
									select id from micro_grainboundaryinfo where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_grainboundarymorphology where grain_boundary_id in (
								select id from micro_grainboundary where grain_boundary_info_id in (
									select id from micro_grainboundaryinfo where spot_id in (
										select id from micro_spotmetadata where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_grainboundarymorphology where grain_boundary_id in (
								select id from micro_grainboundary where grain_boundary_info_id in (
									select id from micro_grainboundaryinfo where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_grainboundary where grain_boundary_info_id in (
								select id from micro_grainboundaryinfo where spot_id in (
									select id from micro_spotmetadata where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_grainboundary where grain_boundary_info_id in (
								select id from micro_grainboundaryinfo where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_grainboundaryinfo where spot_id in (
								select id from micro_spotmetadata where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_grainboundaryinfo where micrograph_id in (
								select id from micro_micrographmetadata where sample_id in (
									select id from micro_samplemetadata where dataset_id in (
										select id from micro_datasetmetadata where project_id in (
											select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
										)
									)
								)
							);

							delete from micro_intragraintexturalfeature where intragrain_id in (
								select id from micro_intragrain where intragraininfo_id in (
									select id from micro_intragraininfo where spot_id in (
										select id from micro_spotmetadata where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_intragraintexturalfeature where intragrain_id in (
								select id from micro_intragrain where intragraininfo_id in (
									select id from micro_intragraininfo where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_intragrain where intragraininfo_id in (
								select id from micro_intragraininfo where spot_id in (
									select id from micro_spotmetadata where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_intragrain where intragraininfo_id in (
								select id from micro_intragraininfo where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_intragraininfo where spot_id in (
								select id from micro_spotmetadata where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_intragraininfo where micrograph_id in (
								select id from micro_micrographmetadata where sample_id in (
									select id from micro_samplemetadata where dataset_id in (
										select id from micro_datasetmetadata where project_id in (
											select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
										)
									)
								)
							);

							delete from micro_fabriccompositionlayer where fabric_composition_id in (
								select id from micro_fabriccomposition where fabric_id in (
									select id from micro_fabric where fabric_info_id in (
										select id from micro_fabricinfo where spot_id in (
											select id from micro_spotmetadata where micrograph_id in (
												select id from micro_micrographmetadata where sample_id in (
													select id from micro_samplemetadata where dataset_id in (
														select id from micro_datasetmetadata where project_id in (
															select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
														)
													)
												)
											)
										)
									)
								)
							);

							delete from micro_fabriccompositionlayer where fabric_composition_id in (
								select id from micro_fabriccomposition where fabric_id in (
									select id from micro_fabric where fabric_info_id in (
										select id from micro_fabricinfo where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_fabriccomposition where fabric_id in (
								select id from micro_fabric where fabric_info_id in (
									select id from micro_fabricinfo where spot_id in (
										select id from micro_spotmetadata where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_fabriccomposition where fabric_id in (
								select id from micro_fabric where fabric_info_id in (
									select id from micro_fabricinfo where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_fabriccleavage where fabric_id in (
								select id from micro_fabric where fabric_info_id in (
									select id from micro_fabricinfo where spot_id in (
										select id from micro_spotmetadata where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_fabriccleavage where fabric_id in (
								select id from micro_fabric where fabric_info_id in (
									select id from micro_fabricinfo where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_fabricgrainsizelayer where grain_size_id in (
								select id from micro_fabricgrainsize where fabric_id in (
									select id from micro_fabric where fabric_info_id in (
										select id from micro_fabricinfo where spot_id in (
											select id from micro_spotmetadata where micrograph_id in (
												select id from micro_micrographmetadata where sample_id in (
													select id from micro_samplemetadata where dataset_id in (
														select id from micro_datasetmetadata where project_id in (
															select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
														)
													)
												)
											)
										)
									)
								)
							);

							delete from micro_fabricgrainsizelayer where grain_size_id in (
								select id from micro_fabricgrainsize where fabric_id in (
									select id from micro_fabric where fabric_info_id in (
										select id from micro_fabricinfo where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_fabricgrainsize where fabric_id in (
								select id from micro_fabric where fabric_info_id in (
									select id from micro_fabricinfo where spot_id in (
										select id from micro_spotmetadata where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_fabricgrainsize where fabric_id in (
								select id from micro_fabric where fabric_info_id in (
									select id from micro_fabricinfo where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_fabricgrainshape where fabric_id in (
								select id from micro_fabric where fabric_info_id in (
									select id from micro_fabricinfo where spot_id in (
										select id from micro_spotmetadata where micrograph_id in (
											select id from micro_micrographmetadata where sample_id in (
												select id from micro_samplemetadata where dataset_id in (
													select id from micro_datasetmetadata where project_id in (
														select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
													)
												)
											)
										)
									)
								)
							);

							delete from micro_fabricgrainshape where fabric_id in (
								select id from micro_fabric where fabric_info_id in (
									select id from micro_fabricinfo where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_fabric where fabric_info_id in (
								select id from micro_fabricinfo where spot_id in (
									select id from micro_spotmetadata where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_fabric where fabric_info_id in (
								select id from micro_fabricinfo where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_fabricinfo where spot_id in (
								select id from micro_spotmetadata where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_fabricinfo where micrograph_id in (
								select id from micro_micrographmetadata where sample_id in (
									select id from micro_samplemetadata where dataset_id in (
										select id from micro_datasetmetadata where project_id in (
											select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
										)
									)
								)
							);

							delete from micro_link where spot_id in (
								select id from micro_spotmetadata where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_link where micrograph_id in (
								select id from micro_micrographmetadata where sample_id in (
									select id from micro_samplemetadata where dataset_id in (
										select id from micro_datasetmetadata where project_id in (
											select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
										)
									)
								)
							);

							delete from micro_pseudotachylyte where pseudotachylyteinfo_id in (
								select id from micro_pseudotachylyteinfo where spot_id in (
									select id from micro_spotmetadata where micrograph_id in (
										select id from micro_micrographmetadata where sample_id in (
											select id from micro_samplemetadata where dataset_id in (
												select id from micro_datasetmetadata where project_id in (
													select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
												)
											)
										)
									)
								)
							);

							delete from micro_pseudotachylyte where pseudotachylyteinfo_id in (
								select id from micro_pseudotachylyteinfo where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_pseudotachylyteinfo where spot_id in (
								select id from micro_spotmetadata where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_pseudotachylyteinfo where micrograph_id in (
								select id from micro_micrographmetadata where sample_id in (
									select id from micro_samplemetadata where dataset_id in (
										select id from micro_datasetmetadata where project_id in (
											select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
										)
									)
								)
							);

							delete from micro_instrumentdetector where instrument_id in (
								select id from micro_instrument where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_instrumentwdsstandards where instrument_id in (
								select id from micro_instrument where micrograph_id in (
									select id from micro_micrographmetadata where sample_id in (
										select id from micro_samplemetadata where dataset_id in (
											select id from micro_datasetmetadata where project_id in (
												select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
											)
										)
									)
								)
							);

							delete from micro_instrument where micrograph_id in (
								select id from micro_micrographmetadata where sample_id in (
									select id from micro_samplemetadata where dataset_id in (
										select id from micro_datasetmetadata where project_id in (
											select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
										)
									)
								)
							);

							delete from micro_micrographorientation where micrographmetadata_id in (
								select id from micro_micrographmetadata where sample_id in (
									select id from micro_samplemetadata where dataset_id in (
										select id from micro_datasetmetadata where project_id in (
											select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
										)
									)
								)
							);

							delete from micro_spotmetadata where micrograph_id in (
								select id from micro_micrographmetadata where sample_id in (
									select id from micro_samplemetadata where dataset_id in (
										select id from micro_datasetmetadata where project_id in (
											select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
										)
									)
								)
							);

							delete from micro_micrographmetadata where sample_id in (
								select id from micro_samplemetadata where dataset_id in (
									select id from micro_datasetmetadata where project_id in (
										select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
									)
								)
							);

							delete from micro_samplemetadata where dataset_id in (
								select id from micro_datasetmetadata where project_id in (
									select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
								)
							);

							delete from micro_datasetmetadata where project_id in (
								select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
							);

							delete from micro_groupmetadata where project_id in (
								select id from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid'
							);

							delete from micro_projectmetadata where userpkey=$this->userpkey and strabo_id = '$projectid';
			");
		}

	}

}