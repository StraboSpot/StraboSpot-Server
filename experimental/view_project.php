<?php
/**
 * File: view_project.php
 * Description: Displays project details, datasets, and associated information
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

//include("../logincheck.php");
session_start();
include("../prepare_connections.php");


$uuid = $_GET['u'] ?? '';
$uuid = preg_replace('/[^a-zA-Z0-9\-]/', '', $uuid);
if($uuid == "") die("Project not found.");
$row = $db->get_row_prepared("SELECT * FROM straboexp.project WHERE uuid = $1 AND (ispublic OR userpkey=$2)", array($uuid, $userpkey));
if($row->pkey == "") die("Project not found.");



/*
stdClass Object
(
	[pkey] => 189
	[userpkey] => 3
	[uuid] => f09df4f3-51e0-4d32-a9d2-e76dc4612a17
	[created_timestamp] => 2025-02-28 10:15:39.104921-05
	[modified_timestamp] => 2025-04-24 15:47:17.804487-04
	[name] => exp test feb
	[notes] => asdfgasdfg
	[ispublic] => t
	[keywords] => '-02':587 '-024e-4':504 '-0635':308 '-1':294,712 '-11':586 '-1985':571 '-324':19 '-4':572,811 '-419e-8':309 '-4884':759 '-5':841 '-50':426,434 '-715':54 '-7279':20 '-752':575 '-7e23':758 '-8145':324 '-9118':574 '-9480':810 '/i/0bd44f43-2004-45c9-9e98-76300b35b241/paterson_rig-drawings.pdf':339 '/i/24730478-7e23-4884-bbd1-3b757ab063b9/sample_assembly.jpg':756 '/i/39be9c55-da00-4d80-939f-cd569ae75df4/img_1880.jpeg':828 '/i/39dee459-ddbc-4a34-8730-5dd3e1203488/carr_057-um.txt':845 '/i/41166980-024e-4b95-a3b3-e99798f8f26e/dscf0001a.jpg':502 '/i/4e57d896-9480-4d38-a1e1-70d3a16e9488/img_1878.jpeg':818 '/i/68c2e820-cf42-482a-b5c9-413786acc180/split_cylinder.jpg':770 '/i/8f55a626-89ef-48c9-84af-40243b545dc2/paterson_rig-manual.docx':354 '/i/bd7dfcfb-466c-4a4d-9754-b96394aeef09/dscf0001a.jpg':319 '/i/c58c365c-1985-4ab1-9118-752b4bca478e/il_570xn.1056584090_ci3d.jpg':569 '/i/e1ee8211-0fcc-48e9-8d8a-0c85d9fb5fd9/2018-10-09_15.31.07.jpg':306 '/peclab/':23 '0':407 '0.036':713 '0.1':697,723 '0.1.140959':452 '057':515 '0f440c39':779 '0f440c39-d290-4f09-94eb-e646b9ed5e1f':778 '1':157 '10':387,802 '100kn':410 '105':428 '121f':322 '13':92 '1300':71 '15':118 '16xe':386 '19':517 '1khz':398,420 '2':523,528,581 '2023':585 '24730478':757 '24dcdt':433 '2mm':748 '3':667 '30':123 '300oc':223 '34':524 '35':625 '39be9c55':821 '39be9c55-da00-4d80-939f-cd569ae75df4':820 '39dee459':837 '39dee459-ddbc-4a34':836 '3b757ab063b9':762 '3degc':628 '40':164 '400c/50mpa':629 '400mpa':269 '40243b545dc2':360 '4084':323 '41166980':503 '413786acc180':776 '482a':774 '48c9':358 '4a34':839 '4c06fe70e313':312 '4cf9':343 '4d80':823 '4e57':808 '4ed3c371':307 '4f09':781 '5':50,333,348,513 '5/sec':854 '500':65 '5hz':496 '5mpa':666 '617':18 '68c2e820':772 '68c2e820-cf42-482a-b5c9-413786acc180':771 '70d3a16e9488':815 '77':7 '7dcdt':425 '84af':359 '8730':840 '89ef':357 '8f55a626':356 '8f55a626-89ef-48c9-84af-40243b545dc2':355 '939f':824 '94eb':782 'a1e1':814 'a3b3':507 'a948':344 'ab1':573 'absolut':919 'acoust':208,211,373 'activ':404 'actuat':380 'ad70e81884a7':345 'ae':213,225 'aeb15b2eccc5':325 'allow':178,207 'alp':545 'alumina':601,652 'analog':392,472 'anoth':588 'appar':552 'apparatus':52,56,94,102,146,203 'applic':862,886,907 'apuan':544 'argon':74 'asdfgasdfg':4 'ash':5 'assembl':286,316,595,765 'assembly-cu':764 'avenu':9 'avoid':750 'axial':389,396,467,680 'b34':530 'b4bca478e':576 'b5c9':775 'b6f':311 'b6f-4c06fe70e313':310 'b95':506 'b95-a3b3-e99798f8f26e':505 'backup':747 'bar':918 'barometr':917 'bbd1':761 'bbd1-3b757ab063b9':760 'bed':550 'bianco':579 'bldg.54-715':10 'block':522,527,580,650 'bore':654 'brian':44,326 'bridg':442 'brittle/plastic':592 'built':376 'c':72,458 'c/mm':158 'c58c365c':570 'c729c4c3':341 'c729c4c3-ef82-4cf9-a948-ad70e81884a7':340 'calcit':538 'calibr':296,622 'cambridg':11 'capabl':58 'capacit':401,882 'carr':514,521 'carrara':518,525,532,535,540,546,577,582,589 'cc':561 'cd569ae75df4':825 'cell':132,403,451,884,890,902 'cf42':773 'clock':495,497,865,868,873 'compens':134 'compress':896 'configur':371 'confin':61,79,265,453,663,669,699,740 'connect':565,608 'constant':705 'continu':895 'control':246,694 'cool':630,730,743 'copper':645 'cord.mit.edu':642 'creep':241 'csv':499 'cu':598,766 'current':215 'custom':405 'cycl':665 'cylind':520,651,659 'd290':780 'd38':813 'd38-a1e1-70d3a16e9488':812 'd78d654a':321 'd78d654a-121f':320 'd896':809 'da00':822 'daq':370,494 'data':491,498,893,905 'dd3e1203488':842 'ddbc':838 'deform':40,110,183,193,583,591,703,850 'deg':457 'degc':271 'densiti':560 'depend':273,283 'depressur':753 'depressuris':738 'deriv':869 'design':83,136 'diagram':335 'diamet':121,299,653,655 'differenti':394,635,695 'digit':479 'displac':281,412,716,745 'docx':351 'downstream':446 'drained/undrained':254 'draw':346 'dri':564 'e54':53 'e646b9ed5e1f':783 'e99798f8f26e':508 'eap':32 'eapsweb.mit.edu':38 'earth':28 'ef82':342 'elimin':138 'emiss':212 'end':449 'equip':171 'erron':904 'evan':45,327 'except':880 'exist':93 'exp':1 'experi':111,222,593 'experiment':788,793,831,911 'extern':413,418,715,888 'failur':238 'fault':557 'feb':3 'final':795 'find':676 'flow':243 'fluid':190,245,256,562 'foliat':555 'forc':649 'form':859,870 'former':42 'friction':141 'furnac':152,272,624,734 'furthermor':168 'g':559 'gain':415,417,430,432 'gas':75,276,483 'give':891,903 'good':785,819,892 'gradient':155,618 'haskel':482 'heat':229,689 'hettangian':542 'high':108,196,230,414,416,631 'high-resolut':107 'hip':232 'homogen':534 'hot':162 'hot-zon':161 'hour':856 'imag':807,832 'in-situ':724,804 'initi':662,674 'input':393,406,487 'input/unit':408 'institut':25 'instrument':382 'intensifi':438 'intern':130,314,390,395,493,610,872,881,900 'introduct':187 'itali':541 'italia':548 'jacket':599,643,767 'jason':6 'jpg':303 'k':464 'kn':290,292,391 'lab':37,41,47,639 'laboratori':35 'larg':114 'length':126,167,301,646,791 'limit':218 'line':481 'linear':474 'lineat':553 'load':131,143,227,289,388,397,402,450,469,685,696,883,889,901 'loadcel':315 'locat':529 'low':220,429,431,887 'ls':427 'lvdt':419,424 'lvdt/rvdt':422 'ma':12 'manag':640 'manual':350,364 'marbl':519,526,533,536,578,590 'massachusett':8,24 'matej':14 'max':411 'maximum':799 'measur':144,181,214,226,374,789,798,848 'medium':80 'mervyn':86 'milistrain':720 'miner':234 'mio':385 'mm':119,124,165,282,293 'mm/min':471,714 'mok':638 'motor':475,477 'move':679 'mpa':66,267 'mpec.scripts.mit.edu':22 'mpec.scripts.mit.edu/peclab/':21 'mpec@mit.edu':17 'nation':381 'nomin':268,803 'none':399 'omega':462 'omega-typ':461 'one':90 'output':473,480 'p':258 'p-wave':257 'parallel':492 'paramet':777 'part':349 'passiv':460 'paterson':48,51,87,202,313,332,347,512 'pc':671,878 'pci':384,864,871 'pci-clock':863 'pci-mio-16xe':383 'pdf':336 'pec':15 'permeabl':180,247,251,253,921 'physic':34 'pictur':302 'piston':135,681 'planetari':30 'pleas':908 'point':678 'pore':174,244,255,274,436,441,606 'poros':566 'post':787,792,830 'post-experiment':829 'postexperiment':790 'precis':876 'pressur':62,175,197,266,275,437,440,444,607,632,664,670,675,700,741 'pressure/leak':660 'prof':43,85 'professor':16 'protocol':912 'pump':478,484 'quarri':543 'ram':468 'rate':707,709,717,853 'reach':60 'reaction':235 'reactiv':242 'recent':200 'refer':909 'referenc':447 'relat':113,855 'releas':739 'reload':751 'resolut':109 'rig':49,334 'rock':33,39,194 'run':691,914 's-wave':261 'sampl':115,285,298,300,455,594,616,620,683,731,735,763,852 'satur':563 'scienc':31 'seal':140 'sec':711,866 'sensor':378,423,443,511 'sergio':328 'seri':834,847 'serv':76 'short':361 'simultan':693 'singl':448 'situ':726,806 'spacer':604,657 'split':658 'spring':295 'standard':365,369,531,597 'start':702,860 'state':250 'steadi':249 'steady-st':248 'step':476 'stiff':291,297 'stop':721 'strabospot.org':305,318,338,353,501,568,755,769,817,827,844 'strabospot.org/i/0bd44f43-2004-45c9-9e98-76300b35b241/paterson_rig-drawings.pdf':337 'strabospot.org/i/24730478-7e23-4884-bbd1-3b757ab063b9/sample_assembly.jpg':754 'strabospot.org/i/39be9c55-da00-4d80-939f-cd569ae75df4/img_1880.jpeg':826 'strabospot.org/i/39dee459-ddbc-4a34-8730-5dd3e1203488/carr_057-um.txt':843 'strabospot.org/i/41166980-024e-4b95-a3b3-e99798f8f26e/dscf0001a.jpg':500 'strabospot.org/i/4e57d896-9480-4d38-a1e1-70d3a16e9488/img_1878.jpeg':816 'strabospot.org/i/68c2e820-cf42-482a-b5c9-413786acc180/split_cylinder.jpg':768 'strabospot.org/i/8f55a626-89ef-48c9-84af-40243b545dc2/paterson_rig-manual.docx':352 'strabospot.org/i/bd7dfcfb-466c-4a4d-9754-b96394aeef09/dscf0001a.jpg':317 'strabospot.org/i/c58c365c-1985-4ab1-9118-752b4bca478e/il_570xn.1056584090_ci3d.jpg':567 'strabospot.org/i/e1ee8211-0fcc-48e9-8d8a-0c85d9fb5fd9/2018-10-09_15.31.07.jpg':304 'strain':706,708,718,727,796 'strength':237,239,240 'stress':636,686,800 'stress/strain':634 'suit':105 'switch':732 'synthesi':233 'system':176,490,857,867 'tabl':509 'technolog':27 'temperatur':68,154,199,221,231,270,617,672,692,915 'test':2,584,661,704,851 'text':835 'thank':127 'thermocoupl':459,611 'thick':648 'three':150 'three-zon':149 'time':489,668,833,846,858,879 'top':456,613 'toscana':547 'total':920 'touch':677 'touchpoint':688,701 'transduc':445 'transient':252 'triaxial':633 'trigger':485,486 'tube':644 'type':97,463 'u_mok@mit.edu':641 'ulrich':637 'um':516 'undefin':916 'uniqu':104 'unit':488 'univers':36 'unload':228,728,737 'upgrad':205 'upstream':439 'usa':13 'use':375,897 'various':189,786 'veloc':209,260,264,466,470 'version':362 'vessel':454 'vinciguerra':329 'vol':539 'volt':409 'volumetr':435 'wall':647 'water':278,922 'wave':259,263 'weight':558 'well':185 'white':537 'wide':100 'width':794 'without':372 'wlhsyvubki':923 'world':99 'world-wid':98 'x1':400 'x20':421 'yield':236 'zirconia':603,656 'zone':151,163 'δs1':746 'ε':797 'ε1':719 'ε3/dt':710 'σ':801 'σ1':280,288,687 'σ1-displacement':279 'σ1-load':287
)
*/



$projectuserpkey = $row->userpkey;
$userrow = $db->get_row_prepared("SELECT * FROM users WHERE pkey = $1", array($projectuserpkey));
$owner = $userrow->firstname." ".$userrow->lastname;



$json = file_get_contents("/srv/app/www/doi/doiFiles/$uuid/project.json");
$json = json_decode($json);

$projectname = $row->name;
$projectpkey = $row->pkey;
$created = $row->created_timestamp;
$modified = $row->modified_timestamp;


$experiments = $db->get_results_prepared("SELECT * FROM straboexp.experiment WHERE project_pkey = $1", array($projectpkey));


include("../includes/header.php");
?>

<h2 style="text-align:center;">StraboExperimental Project:</h2>
<h2 style="text-align:center;"><?php echo $projectname?></h2>
<div style="text-align:center;">Owner: <?php echo $owner?></div>

<?php
if($doi != ""){
?>
<div style="text-align:center;">DOI: <?php echo $doi?></div>
<?php
}
?>

<div style="text-align:center;">Created: <?php echo $created?></div>
<div style="text-align:center;">Modified: <?php echo $modified?></div>

<?php
if(count($experiments) > 0){
?>

	<h3>Experiments:</h3>
	<div class="strabotable" style="margin-left:0px;">
		<table>
			<tbody>
				<tr>
					<td style="width:140px;"></td>
					<td>Name</td>
					<td style="width:110px;">Created</td>
					<td style="width:110px;">Modified</td>
				</tr>
<?php
foreach($experiments as $row){

	/*
	[pkey] => 578
	[project_pkey] => 189
	[userpkey] => 3
	[id] => Example Experiment
	[created_timestamp] => 2025-02-28 10:15:52.076032-05
	[modified_timestamp] => 2025-02-28 10:15:52.076032-05
	[json] => {
	*/

	$name = $row->id;
	$created_timestamp = $row->created_timestamp;
	$modified_timestamp = $row->modified_timestamp;
	$exuuid = $row->uuid;
	$exppkey = $row->pkey;

?>
				<tr>
					<td>
						<div style="text-align:center;">
							<a href="view_experiment?e=<?php echo $exppkey?>" target="_blank">View</a>&nbsp;&nbsp;&nbsp;&nbsp;
							<!--<a href="download_doi_experiment?p=<?php echo $uuid?>&e=<?php echo $exuuid?>" target="_blank">Download</a>-->
						</div>
					</td>
					<td nowrap=""><?php echo $name?></div></td>
					<td nowrap=""><?php echo $created_timestamp?></div></td>
					<td nowrap=""><?php echo $modified_timestamp?></div></td>
				</tr>
<?php
}
?>
			</tbody>
		</table>
	</div>

<?php
}else{
?>
	No saved DOI projects found.
<?php
}
?>




<?php
include("../includes/footer.php");
?>