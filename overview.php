<?php
/**
 * File: overview.php
 * Description: View details interface
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("includes/mheader.php");
?>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>About StraboSpot</h2>
						</header>

						<!-- Content -->
							<section id="content">

								<h3>What is StraboSpot?</h3>
								<p>The StraboSpot ecosystem is a network of interconnected applications and software designed to facilitate the collection, management, integration, and sharing of field and laboratory data in the Geologic Sciences. The goal of StraboSpot is to make these data consistent with FAIR (Findable, Accessible, Interoperable, and Reusable) principles and to integrate multidisciplinary field and laboratory geologic data types into one shared data system. Through community input and development, StraboSpot now incorporates structural geology, petrology, sedimentology, and tephra volcanology workflows. The use of controlled vocabularies developed by these communities promotes the standardization of data collection and increases the findability of data. </p>
								<p>StraboSpot not only provides a shared data repository, but also the tools to collect and manage data and images. The data system uses the concept of spots, observations that apply over a specified spatial dimension, to nests observations from the regional to microscopic scale and to group data and images as chosen by the user (see below). This approach allows users to connect geologically complex relationships throughout their workflow. </p>

								<h3>The System Includes:</h3>
								<ul>
									<li><strong>StraboField:</strong> A mobile application designed to collect measurements, notes, images, samples, and other data while in the field. StraboField is available for all devices on the Google Play and Apple App stores.</li>
									<li><strong>StraboWeb:</strong> A web-interface where users can edit, view, and share datasets from their browser.</li>
									<li><strong>StraboMicro:</strong> A desktop application to manage, store and share data, observations and analysis that occur at the microstructural level (e.g., optical, SEM, EBSD data)</li>
									<li><strong>StraboExperimental:</strong> A digital database for experimental geophysical data, particularly for rock deformation tests.</li>
									<li><strong>Interoperability with other applications:</strong> Interoperability with other applications including Stereonet (by Rick Allmendinger) and StraboTools (by Allen Glazner). During field work, or back in the lab, users can simply lasso spots and paste into Stereonet for quick visualization about bedding, core orientations, and more.</li>
								</ul>

								<h3>Motivation for Strabo</h3>
								<p>Tectonics describes the architecture of the Earth, a significant part of which is the deformation features — faults, folds, fractures, fabrics, and microstructures — that are integral to forming the lithosphere. Structural Geology and Tectonics (SG&T) together are a four dimensional science that include geometry, kinematics, dynamics, rheology, and both relative and absolute timing data, all of which together allow us to piece together the history of our planet. The data that reveal that history span many orders of magnitude of both temporal and spatial scales. At present, those data are available to the community only in the published literature, which is necessarily a highly abstracted, and commonly subjective, subset of all the information collected. This proposal aims to design an online Data System that will provide access to the basic data of our science, which will supplement and enhance the current literature.</p>
								<p>There is no uniform mechanism to post or search digitally for SG&T data. Such data, however, form perhaps the most basic dataset about the solid Earth, insofar as it captures that part of the Earth exposed to direct observation and is the fundamental ground truth against which all models of Earth development must be compared. Until around 2000, there were few means of collecting these data efficiently and digitally, and there is still a complete inability to share these data in a common format. This is a serious impediment to structural geologists and a barrier to other fields. This barrier was highlighted prominently at the most recent EarthScope meeting.</p>
								<p>In response to this problem, Basil Tikoff and Doug Walker organized and ran the EarthCube End User Domain Workshop for SG&T, the first in a series of such EarthCube meetings. The workshop was advertised broadly and open to any participant. The full report included the following critical summary points:</p>
								<ul>
									<li>There is no digital Data System (acquisition and database) for SG&T data. Further, there is no widely accessible way to archive structure data digitally, with the result that structural data cannot be discovered or easily reused.</li>
									<li>A community effort to standardize data collection would result in a tremendous saving of time and provide a focus for the community to improve data collection and quality. </li>
									<li>The ability to make data available for download to all other interested researchers across disciplines would facilitate an improvement in the quality of science.</li>
								</ul>

								<p>The workshop participants considered the lack of a dedicated database for SG&T data to be the most pressing problem related to needed cyberinfrastructure and Geoinformatics. For that reason the Strabo Project was started by Julie Newman, Basil Tikoff and Doug Walker. We had significant help in brainstorming the database approach from Ryan Clark (MapBox). It has been funded under and NSF Geoinformatics grant. We refer to this as the Strabo Data System.</p>

								<h3>What is a Spot?</h3>
								<p>In an attempt to encapsulate the large spatial variation and complex geometries of naturally deformed rocks during a workshop on shear zone, Spot Mode was devised to track hierarchical and spatial relations between structures at all scales, and to link map scale, mesoscale and laboratory scale data. This is the basis of the Strabo Data System. A Spot can be a single measurement, an aggregation of individual measurements, or even establish relationships between numerous other Spots. A Spot defines the area over which a measurement or quantity is applicable (e.g., a domain in the structural geology literature): To borrow a phrase from the numerical modeling community, it a "representative volume [area] element". A Spot, in this sense, is analogous to the beam size of analytical equipment used for in situ analysis. The concept of, and the necessity for, a Spot mode may not be apparent for non-structural geologist, so we present a field example below that is well known to the PIs and that will hopefully elucidate the utility of this approach.</p>

								<h4>Case example – Twin Sisters ultramafic complex, Washington State:</h4>
								<p>Tikoff et al. (2010) report on the deformation and rheological behavior in the Twin Sisters ultramafic complex (Fig. 1). These peridotites are characterized by alternating, sub-parallel bands of dunite and harzburgite, which host orthopyroxenite bands/dikes that are generally folded or boudinaged. Tikoff et al. (2010) mapped a 100 x 140 m area in detail, measured fabrics (lineations, foliations), mapped normal faults, and documented the shortening/elongation of the orthopyroxenite dikes (Fig. 1a). In the lab, they conducted wavelength/thickness analyses of the orthopyroxenite dikes as well as microstructural analyses of the orthopyroxenites and host peridotites.</p>
								<p>The study is an example of a modern field study, where data are used for multiple purposes; one purpose was to determine finite strain. In the Tikoff et al. (2010) study, the aggregate data from all of the folded dikes in the study area yielded a finite strain that applies to the entire 100 x 140 m area; this is the appropriate Spot size for this analysis (~50 m radius circle) and the area over which the analysis is representative (Fig. 1a). However, the finite strain analysis is based on a series of measurements from folded dikes within the field area (smaller Spots within Fig. 1a). A plunge and an azimuth were measured at every fold hinge and a fold axial plane was estimated where possible from each folded dike. Each set of measurements at each location may define a new Spot (Fig. 1b). Note that Spot sizes vary: The wavelength of each folded dike is related to the thickness of each dike. Thus, for a thin, folded dike with a short wavelength, an orientation and thickness reading was taken every dm to m (Fig. 1b), while measurements were taken on the m-scale for the thickest dikes. Thus, the Spot size for these fold measurements would depend on the wavelength of the folding; dm-scale Spots are appropriate for the thinnest dikes while m-scale Spots are appropriate for the thickest dikes.</p>

								<div class="row gtr-50 gtr-uniform">
									<div class="col-12"><span class="image fit"><img src="/includes/mimages/straboDiagram.png" alt=""></span></div>
								</div>

								<p class="padTop">Further, the Spots that record measurements for the smaller-scale structures (i.e., fold hinges) are recorded in a hierarchical relationship with the larger scale 50 m radius circle that defines the area for which these measurements are relevant (Fig. 1b). These linked Spots thus distinguish the areas over which the measurements are representative, and the spatial and hierarchical relationships between them. The aggregate of these spots is relevant to understanding the finite strain of the area represented by the larger, 50 m radius Spot. Moreover, the Spot would be mapped with “purpose” tag: Finite strain. Thus, anyone looking for finite strain estimates from naturally deformed rocks could search via a purpose.</p>
								<p>The data recorded from the Twin Sisters field area was used for a second purpose: Tikoff et al. (2010) used the same data from the folded dikes to estimate rheology. They estimated that orthopyroxene dikes have 25 times the effective viscosity as the dunite domains. Thus, for this second analysis, a Spot would be centered at the same point as the finite strain “Spot” on Figure 1a , but this Spot would be labeled with “purpose” tag; rheology.” We note that the finite strain analysis and rheological information are interpretative, derived quantitatively from the primary data, and need to be identified as such. Moreover, the purpose of an individual Spot (such as fault movement) may be different from the purpose of the aggregate Spot (such as stress orientation). Also note, that the tag “purpose” is not required to be assigned, or could be assigned at a later point in the research process.</p>

								<h4>Below Resolution of Portable GPS</h4>
								<p>Structural geologists rarely take a single measurement on a feature within a complex outcrop, and, we anticipate that many Spots will occur below the resolution of hand-held GPS (2-7 m) and thus unable to have a distinct GPS location, as in the example of the small-scale folded dike, above. In these cases, the Spot will be linked to either a photograph, sketch, or detailed map (Fig. 1b, c) made by the scientist. Thus, every Spot will have a spatial position tied to other Spots through hierarchical relationships. For example, stations across a strain gradient would be recorded by multiple, closely packed Spots that are linked explicitly to each other. An added benefit of the Spot mode workflow is that the Data System will explicitly request practitioners to report a scale over which the measurement is appropriate.</p>

								<h4>Laboratory Measurements</h4>
								<p>Laboratory acquired, microstructural data has become increasingly critical to interpretations of deformation histories, tectonic settings and deformation processes at all scales. This assessment is relevant regardless of whether one is focused on a small area to address a specific problem (using Spot mode) or reconnaissance mapping (using Mapping mode). To contribute to such interpretations, microstructural data must be analyzed within the context of the macro- and meso-scale structures in which the microscale structures were formed (e.g., bedding, foliations, folds, fault zones). The Spot mode is the key concept that will allow researchers to tie microstructures to location and macro- and mesoscale structures, and maintain important spatial relations between all structures. The Spot mode, as a recorder of hierarchical relations, can be used at all scales, so that even within the scale of a thin section (e.g., optical microscope to transmission electron microscope (TEM)-scale), spatial relations may be maintained.</p>
								<p>The workflow for collection of laboratory data, however, will often require a different strategy than for field-based data. First, microstructural data are often acquired on computers associated with analytic instrumentation (e.g., electron microscopes), so that data will be in the form of data files and/or graphs. Further, as at larger scales, microstructural data is increasingly linked to data from other subdisciplines in the geosciences. For example, while geochemical data (e.g., electron microprobe analyses; X-ray diffraction data; isotopic analyses) are not typically considered to be structural data, deformation processes are closely linked to chemistry, and the spatial relations of compositional variations are often critical to a geochemical/structural data set and tectonic interpretations. Therefore, the database must be able to interact with datasets from other subdisciplines, which we anticipate will be done via the EarthCube initiative.</p>

								<h4>Spot Attributes</h4>
								<p>While our main goal is to record observational (primary) data, it became clear through discussion of the needs of scientists both within and outside the SG&T Community that interpretive/derived data must be incorporated in the Data System. Oftentimes, it is the derived data that would direct a search of available records. For example, one might be interested in finding locations that experienced deformation within a specified tectonic setting, at a certain metamorphic grade, or that exhibits evidence for a certain deformation mechanism. These interpretations often depend on a collection of primary data.</p>
								<p>Therefore, the attributes of Spots that we anticipate including are both primary (measured and observed) and derived (interpreted). Primary data will include, for example: Spatial location; linear, planar and tensor quantities (note that multiple measurements at any one location are possible); small-scale structures; and spatial and hierarchical relations between structures. Derived data might include attributes such as: Tectonic setting; metamorphic grade (P,T); strain rate; shear sense; displacement; deformation mechanism; and, a link to the publication that resulted from these analyses.</p>
								<p>We emphasize, however, that we are interested first and foremost in recording primary data in the Data System. We view peer-reviewed publications as the appropriate venue to disseminate information about synthesis that has been undertaken by scientist(s). However, we will support uploading of outcrop sketches, images, cross-sections, and other information. These will be geospatially referenced and take the form primarily of images. Text may be tied to these images.</p>
								<p>Overall, we are very excited about the Spot mode of data analysis. It allows tracking of hierarchical and spatial relations between structures at all scales, and will link Map-scale, mesoscale and laboratory-scale data. The concept was generated by a group of structural geologists and cyberinfrastructure specialists through discussion about how to organize information about shear zones and areas of ductile deformation, and was immediately accepted by that group. This example demonstrates the utility of facilitating interactions between practicing structural geologists and cyberinfrastructure specialists.</p>
								<p>Tikoff, B., Larson, C.E., Newman, J., and Little, T. (2010) Field-based constraints on rheology of the lithospheric mantle, Twin Sisters, Washington. Lithosphere, v. 2, p. 418-422, DOI: 10.1130/L97.1.</p>

								<h3>What Strabo Terms Mean</h3>
								<h4>These are terms you may encounter in the Strabo Data System:</h4>
								<ul>
									<li><strong>Attribute</strong>: some quality of the observation. For example, dip and strike, fold shape, or shear zone thickness.</li>
									<li><strong>Downward Nest (also downward nesting)</strong>: Fitting spots within a larger spot spatially, with subsequent observation having the same or smaller Spot size, contained within the starting Spot. For example, establish a structure and then automatically related further observations or measurements (Spots) to it. Can also group Spots together that share a characteristic, e.g., same bed or layer.</li>
									<li><strong>Edge</strong>: anything connecting two nodes. This is typically a relationship with some logic (e.g., same bed). Edges can also have attributes: e.g., same bed at distance of 1m.</li>
									<li><strong>Field</strong>: same as attribute.</li>
									<li><strong>Group</strong>: also grouping. Logical, conceptual, or classification relationship between Spots.</li>
									<li><strong>Image</strong>: any raster data. This includes photos and sketches. Sketches and pictures can originate with device camera or storage. Image is almost any layer that cannot be altered by Strabo. Data can be overlain on images.</li>
									<li><strong>Nest</strong>: also nesting. The collection of Spots that share some spatial relationship. For example, part of same structure in the same location. Can also just mean data from one location, e.g., a dike cutting layering. Can also mean a hierarchy of spatial Spots, meaning that Spots can fit spatially within or around other Spots.</li>
									<li><strong>Node</strong>: entry in the database. Same as Vertex. This is an observation, photo, sample, anything of this sort. It can also be the start of a relationship or Nest. For example, I am going to set a point on the map and collect a lot of spots connected with this point/Spot (see Randy Style).</li>
									<li><strong>Purpose</strong>: the reason something is done. Why is a sample collected, why am I doing the project, etc. Can be very verbose if needed.</li>
									<li><strong>Relationship</strong>: see Edge.</li>
									<li><strong>Randy Style</strong>: identify a feature/Spot, put the SPOT into Strabo, and collect many observations around it. The observations share location with the Spot, but do not have unique locations. AKA Randolph Style.</li>
									<li><strong>Sheet</strong>: a mockup of the user interface for a particular task or data entry item. Can be used for both mobile and online forms of Strabo.</li>
									<li><strong>Spreadsheet</strong>: the data or content model for a particular sheet or part of a sheet.</li>
									<li><strong>Spot</strong>: observations, measurements, photo, or relationship associated with a spatial region. Everything is a Spot (note capitol “S”). For example, a single strike and dip of bedding, a set of measurements taken Randy Style, or documented cross cutting relationships. Also, groups can share a relationship (see above) or even be classified as a new spot.</li>
									<li><strong>Tab</strong>: the main navigation bar on the application. Appears for the appropriate data types as described in the Notes document.</li>
									<li><strong>Upward Nest</strong>: also upward nesting. Fitting Spots into a larger Spot in spatial extent. For example, a set of bed measurements are used to define a fold orientation. This is generally done after collecting data.</li>
									<li><strong>Vertex</strong>: see Node.</li>
								</ul>

								<h3>Who was Strabo?</h3>

								<div class="row gtr-50 gtr-uniform">
									<div class="col-4 col-6-xsmall">
										<span class="image fit">
											<img src="/includes/mimages/strabostatue.jpg" alt="">
										</span>
									</div>
									<div class="col-4 col-6-xsmall">
										<span class="fit">
											<a href="https://commons.wikimedia.org/wiki/File:Statue_of_Strabo_in_Amasia.jpg" target="_blank">Source: Wikimedia Commons</a>
										</span>
									</div>
								</div>

								<p class="padTop">Strabo was a Greek geographer who lived from about 63BC to 24AD. A detailed account of Strabo can be found on <a href="https://en.wikipedia.org/wiki/Strabo" target="_blank">Wikipedia</a>. We consider Strabo to be the first structural geologist and tectonocist. His writings include the following (taken from Wikipedia article, quote from Lyell’s “Principles of Geology” 12th edition, 1875, somewhat paraphrased in Lyell and Casaubon):</p>

								<blockquote>It is not, because the lands covered by seas were originally at different altitudes, that the waters have risen, or subsided, or receded from some parts and inundated others. But the reason is, that the same land is sometimes raised up and sometimes depressed, and the sea also is simultaneously raised and depressed, so that it either overflows or returns into its own place again. We must therefore ascribe the cause to the ground, either to that ground which is under the sea, or to that which becomes flooded by it, but rather to that which lies beneath the sea, for this is more moveable, and, on account of its humidity, can be altered with great celerity. It is proper, to derive our explanations from things which are obvious, and in some measure of daily occurrence, such as deluges, earthquakes, volcanic eruptions, and sudden swellings of the land beneath the sea; for the last raise up the sea also, and when the same lands subside again, they occasion the sea to be let down. And it is not merely the small, but the large islands also, and not merely the islands, but the continents, which can be lifted up together with the sea; and both large and small tracts may subside, for habitations and cities, like Bure, Bizona, and many others, have been engulfed by earthquakes.</blockquote>
								<p>- Reference from Casaubon (1587) - Strabonis, Geographia. Casaubon p. 1707.</p>

								<p>A more accurate translation is:</p>
								<blockquote>Some, however, may be disinclined to admit this explanation, and would rather have proof from things more manifest to the senses, and which seem to meet us at every turn. Now deluges, earthquakes, eruptions of wind, and risings in the bed of the sea, these things cause the rising of the ocean, as sinking of the bottom causes it to become lower. It is not the case that small volcanic or other islands can be raised up from the sea, and not large ones, nor that all islands can, but not continents, since extensive sinkings of the land no less than small ones have been known; witness the yawning of those chasms which have ingulfed whole districts no less than their cities, as is said to have happened to Bura,1 Bizone,2 and many other towns at the time of earthquakes: and there is no more reason why one should rather think Sicily to have been disjoined from the main-land of Italy than cast up from the bottom of the sea by the fires of Ætna, as the Lipari and Pithecussan3 Isles have been.</blockquote>
								<p><a href="http://www.perseus.tufts.edu/hopper/text?doc=Perseus%3Atext%3A1999.01.0239%3Abook%3D1%3Achapter%3D3%3Asection%3D10" target="_blank">- Strabo Book 3 (Strabo 1.3.10)</a></p>

								<h3>Who is Involved in Strabo Development?</h3>

								<h4>Texas A&M</h4>
								<ul>
									<li>Julie Newman</li>
									<li>Andreas Kronenberg</li>
								</ul>

								<h4>University of Kansas</h4>
								<ul>
									<li>Doug Walker</li>
									<li>Drew Davidson</li>
									<li>Jessica Good</li>
									<li>Nathan Novak</li>
									<li>Joe Andrew</li>
									<li>Andreas Moeller</li>
									<li>Jason Ash</li>
									<li>Emily Bunse (former)</li>
									<li>Carson Rufledt (former)</li>
								</ul>

								<h4>University of Wisconsin</h4>
								<ul>
									<li>Basil Tikoff</li>
									<li>Ellen Nelson</li>
									<li>Nick Roberts (former)</li>
									<li>Alex Lusk (former)</li>
									<li>Maureen Kahn (former)</li>
									<li>Zach Michaels (former)</li>
									<li>Randy Williams (former)</li>
								</ul>

								<h4>Lakehead University</h4>
								<ul>
									<li>Noah Philips</li>
								</ul>

								<h4>Massachusetts Institute of Technology</h4>
								<ul>
									<li>Ulrich Mok</li>
								</ul>

								<h3>Bugs, Feature Requests, and Other Issues</h3>
								<ul>
									<li>The preferred method of reporting is to post an issue at <a href="https://github.com/StraboSpot/StraboSpot2/issues" target="_blank">https://github.com/StraboSpot/StraboSpot2/issues</a>.</li>
									<li>Alternatively, you can send an email to <a href="mailto://strabospot@gmail.com">strabospot@gmail.com</a>.</li>

								</ul>

							</section>
					</div>
				</div>

<?php
include("includes/mfooter.php");
?>