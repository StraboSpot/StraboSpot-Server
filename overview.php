<?

include("includes/header.php");
/*
?>


<iframe frameborder="0" height="700" id="iframe-0" name="iframe-0" scrolling="auto" src="https://help.strabospot.org/overview" width="100%">Your browser does not support iframes. But You can use the following Link.</iframe>





<?
include("includes/footer.php");



?>



<?
exit();
include("includes/header.php");
*/
?>


<h2 class="wsite-content-title">Motivation for Strabo</B></h2>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in">Tectonics
  describes the architecture of the Earth, a significant part of which
  is the deformation features — faults, folds, fractures, fabrics,
  and microstructures — that are integral to forming the lithosphere.
  Structural Geology and Tectonics (SG&amp;T) together are a four
  dimensional science that include geometry, kinematics, dynamics,
  rheology, and both relative and absolute timing data, all of which
  together allow us to piece together the history of our planet. The
  data that reveal that history span many orders of magnitude of both
  temporal and spatial scales.  At present, those data are available to
  the community only in the published literature, which is necessarily
  a highly abstracted, and commonly subjective, subset of all the
  information collected.  This proposal aims to design an online Data
  System that will provide access to the basic data of our science,
which will supplement and enhance the current literature.</P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in; widows: 0; orphans: 0">
There is no uniform mechanism to post or search digitally for SG&amp;T
data.  Such data, however, form perhaps the most basic dataset about
the solid Earth, insofar as it captures that part of the Earth
exposed to direct observation and is the fundamental ground truth
against which all models of Earth development must be compared. Until
around 2000, there were few means of collecting these data
efficiently and digitally, and there is still a complete inability to
share these data in a common format.  This is a serious impediment to
structural geologists and a barrier to other fields.  This barrier
was highlighted prominently at the most recent EarthScope meeting.</P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in">In
response to this problem, Basil Tikoff and Doug Walker organized and
ran the EarthCube End User Domain Workshop for SG&amp;T, the first in
a series of such EarthCube meetings.  The workshop was advertised
broadly and open to any participant.  The full report is available at
<FONT COLOR="#0000ff"><A HREF="http://earthcube.ning.com/group/structural-geology-tectonics">http://earthcube.ning.com/group/structural-geology-tectonics</A></FONT>,
but the critical summary points are:</P>
<UL>
	<LI><P CLASS="western" STYLE="margin-bottom: 0in">There is no
	digital Data System (acquisition and database) for SG&amp;T data.
	Further, there is no widely accessible way to archive structure data
	digitally, with the result that structural data cannot be discovered
	or easily reused.</P>
	<LI><P CLASS="western" STYLE="margin-bottom: 0in">A community effort
	to standardize data collection would result in a tremendous saving
	of time and provide a focus for the community to improve data
	collection and quality. 
	</P>
	<LI><P CLASS="western" STYLE="margin-bottom: 0in">The ability to
	make data available for download to all other interested researchers
	across disciplines would facilitate an improvement in the quality of
	science.</P>
</UL>
<P CLASS="western" STYLE="margin-bottom: 0in"><B>The workshop
participants considered the lack of a dedicated database for SG&amp;T
data to be the most pressing problem related to needed
cyberinfrastructure and Geoinformatics.</B></P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in">For
  that reason the Strabo Project was started by Julie Newman, Basil
  Tikoff and Doug Walker.  We had significant help in brainstorming the
  database approach from Ryan Clark (MapBox).  It has been funded under
  and NSF Geoinformatics grant.  We refer to this as the Strabo Data
System.</P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in">&nbsp;</P>
<h2 class="wsite-content-title">What is a Spot?</h2>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in; widows: 0; orphans: 0">
  In an attempt to encapsulate the large spatial variation and complex
  geometries of naturally deformed rocks during a workshop on shear
  zone, Spot Mode was devised to track hierarchical and spatial
  relations between structures at all scales, and to link map scale,
  mesoscale and laboratory scale data. This is the basis of the Strabo
  Data System.  A Spot can be a single measurement, an aggregation of
  individual measurements, or even establish relationships between
  numerous other Spots. A Spot defines the area over which a
  measurement or quantity is applicable (e.g., a <I><B>domain</B></I>
  in the structural geology literature): To borrow a phrase from the
  numerical modeling community, it a &quot;representative volume [area]
  element&quot;.  A Spot, in this sense, is analogous to the beam size
  of analytical equipment used for <I>in situ</I> analysis.  The
  concept of, and the necessity for, a Spot mode may not be apparent
  for non-structural geologist, so we present a field example below
  that is well known to the PIs and that will hopefully elucidate the
utility of this approach.</P>
<P CLASS="western" STYLE="margin-top: 0.04in; margin-bottom: 0in"><I><B>Case
example – Twin Sisters ultramafic complex, Washington State</B></I></P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in; widows: 0; orphans: 0">
Tikoff et al. (2010) report on the deformation and rheological
behavior in the Twin Sisters ultramafic complex (Fig. 1).  These
peridotites are characterized by alternating, sub-parallel bands of
dunite and harzburgite, which host orthopyroxenite bands/dikes that
are generally folded or boudinaged.  Tikoff et al. (2010) mapped a
100 x 140 m area in detail, measured fabrics (lineations,
foliations), mapped normal faults, and documented the
shortening/elongation of the orthopyroxenite dikes (Fig. 1a).  In the
lab, they conducted wavelength/thickness analyses of the
orthopyroxenite dikes as well as microstructural analyses of the
orthopyroxenites and host peridotites.  
</P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in">The
study is an example of a modern field study, where data are used for
multiple purposes; one purpose was to determine finite strain.  In
the Tikoff et al. (2010) study, the aggregate data from all of the
folded dikes in the study area yielded a finite strain that applies
to the entire 100 x 140 m area; this is the appropriate Spot size for
this analysis (~50 m radius circle) and the area over which the
analysis is representative (Fig. 1a).  However, the finite strain
analysis is based on a series of measurements from folded dikes
within the field area (smaller Spots within Fig. 1a).  A plunge and
an azimuth were measured at every fold hinge and a fold axial plane
was estimated where possible from each folded dike.  Each set of
measurements at each location may define a new Spot (Fig. 1b).  Note
that Spot sizes vary:  The wavelength of each folded dike is related
to the thickness of each dike. Thus, for a thin, folded dike with a
short wavelength, an orientation and thickness reading was taken
every dm to m (Fig. 1b), while measurements were taken on the m-scale
for the thickest dikes. Thus, the Spot size for these fold
measurements would depend on the wavelength of the folding; dm-scale
Spots are appropriate for the thinnest dikes while m-scale Spots are
appropriate for the thickest dikes. 
</P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in"><IMG SRC="includes/images/Motivation%20for%20Strabo%202_html_60c1858.png" NAME="graphics1" ALIGN=BOTTOM WIDTH=708 HEIGHT=531 BORDER=0></P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in"><BR>
</P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in">Further,
the Spots that record measurements for the smaller-scale structures
(i.e., fold hinges) are recorded in a hierarchical relationship with
the larger scale 50 m radius circle that defines the area for which
these measurements are relevant (Fig. 1b). <I>These linked Spots thus
distinguish the areas over which the measurements are representative,
and the spatial and hierarchical relationships between them</I>.  The
aggregate of these spots is relevant to understanding the finite
strain of the area represented by the larger, 50 m radius Spot. 
Moreover, the Spot would be mapped with “purpose” tag: <I>Finite
strain</I>.  Thus, anyone looking for finite strain estimates from
naturally deformed rocks could search via a purpose.  
</P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in">The
data recorded from the Twin Sisters field area was used for a second
purpose:  Tikoff et al. (2010) used the same data from the folded
dikes to estimate rheology.  They estimated that orthopyroxene dikes
have 25 times the effective viscosity as the dunite domains. Thus,
for this second analysis, a Spot would be centered at the same point
as the finite strain “Spot” on Figure 1a , but this Spot would be
labeled with “purpose” tag; <I>rheology</I>.”  We note that the
finite strain analysis and rheological information are
interpretative, derived quantitatively from the primary data, and
need to be identified as such. Moreover, the purpose of an individual
Spot (such as fault movement) may be different from the purpose of
the aggregate Spot (such as stress orientation).  Also note, that the
tag “purpose” is not required to be assigned, or could be
assigned at a later point in the research process.</P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in"><BR>
</P>
<P CLASS="western" STYLE="margin-bottom: 0in"><I><B>Below resolution
of portable GPS</B></I></P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in">Structural
geologists rarely take a single measurement on a feature within a
complex outcrop, and, we anticipate that many Spots will occur below
the resolution of hand-held GPS (2-7 m) and thus unable to have a
distinct GPS location, as in the example of the small-scale folded
dike, above.  In these cases, the Spot will be linked to either a
photograph, sketch, or detailed map (Fig. 1b, c) made by the
scientist.  Thus, every Spot will have a spatial position tied to
other Spots through hierarchical relationships.   For example,
stations across a strain gradient would be recorded by multiple,
closely packed Spots that are linked explicitly to each other.  An
added benefit of the Spot mode workflow is that the Data System will
explicitly request practitioners to report a scale over which the
measurement is appropriate. 
</P>
<P CLASS="western" STYLE="margin-top: 0.04in; margin-bottom: 0in"><I><B>Laboratory
Measurements </B></I>
</P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in; widows: 0; orphans: 0">
Laboratory acquired, microstructural data has become increasingly
critical to interpretations of deformation histories, tectonic
settings and deformation processes at all scales.  This assessment is
relevant regardless of whether one is focused on a small area to
address a specific problem (using Spot mode) or reconnaissance
mapping (using Mapping mode).  To contribute to such interpretations,
microstructural data must be analyzed within the context of the
macro- and meso-scale structures in which the microscale structures
were formed (e.g., bedding, foliations, folds, fault zones).  The
Spot mode is the key concept that will allow researchers to tie
microstructures to location and macro- and mesoscale structures, and
maintain important spatial relations between all structures.  The
Spot mode, as a recorder of hierarchical relations, can be used at
all scales, so that even within the scale of a thin section (e.g.,
optical microscope to transmission electron microscope (TEM)-scale),
spatial relations may be maintained.</P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in">The
workflow for collection of laboratory data, however, will often
require a different strategy than for field-based data.  First,
microstructural data are often acquired on computers associated with
analytic instrumentation (e.g., electron microscopes), so that data
will be in the form of data files and/or graphs.  Further, as at
larger scales, microstructural data is increasingly linked to data
from other subdisciplines in the geosciences.  For example, while
geochemical data (e.g., electron microprobe analyses; X-ray
diffraction data; isotopic analyses) are not typically considered to
be structural data, deformation processes are closely linked to
chemistry, and the spatial relations of compositional variations are
often critical to a geochemical/structural data set and tectonic
interpretations.  Therefore, the database must be able to interact
with datasets from other subdisciplines, which we anticipate will be
done via the EarthCube initiative. 
</P>
<P CLASS="western" STYLE="margin-top: 0.04in; margin-bottom: 0in"><B>Spot
attributes</B></P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in; widows: 0; orphans: 0">
While our main goal is to record observational (primary) data, it
became clear through discussion of the needs of scientists both
within and outside the SG&amp;T Community that interpretive/derived
data must be incorporated in the Data System.  Oftentimes, it is the
derived data that would direct a search of available records.  For
example, one might be interested in finding locations that
experienced deformation within a specified <I>tectonic setting</I>,
at a certain <I>metamorphic grade</I>, or that exhibits evidence for
a certain <I>deformation mechanism</I>.  These interpretations often
depend on a collection of primary data.   
</P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in; widows: 0; orphans: 0">
Therefore, the attributes of Spots that we anticipate including are
both primary (measured and observed) and derived (interpreted). 
<B>Primary</B> data will include, for example:  Spatial location;
linear, planar and tensor quantities (note that multiple measurements
at any one location are possible); small-scale structures; and
spatial and hierarchical relations between structures.  <B>Derived</B>
data might include attributes such as: Tectonic setting; metamorphic
grade (P,T); strain rate; shear sense; displacement; deformation
mechanism; and, a link to the publication that resulted from these
analyses.  
</P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in; widows: 0; orphans: 0">
We emphasize, however, that we are interested first and foremost in
recording primary data in the Data System.  We view peer-reviewed
publications as the appropriate venue to disseminate information
about synthesis that has been undertaken by scientist(s).  However,
we will support uploading of outcrop sketches, images,
cross-sections, and other information.  These will be geospatially
referenced and take the form primarily of images.  Text may be tied
to these images.  
</P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in; widows: 0; orphans: 0">
Overall, we are very excited about the Spot mode of data analysis. 
It allows tracking of hierarchical and spatial relations between
structures at all scales, and will link Map-scale, mesoscale and
laboratory-scale data.  The concept was generated by a group of
structural geologists and cyberinfrastructure specialists through
discussion about how to organize information about shear zones and
areas of ductile deformation, and was immediately accepted by that
group.  This example demonstrates the utility of facilitating
interactions between practicing structural geologists and
cyberinfrastructure specialists.</P>
<P CLASS="western" STYLE="margin-left: 0.25in; text-indent: -0.25in">Tikoff,
  B., Larson, C.E., Newman, J., and Little, T. (2010) Field-based
  constraints on rheology of the lithospheric mantle, Twin Sisters,
Washington. Lithosphere, v. 2, p. 418-422, DOI: 10.1130/L97.1.</P>
<P CLASS="western" STYLE="margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in; page-break-before: always">&nbsp;</P>
<P CLASS="western" STYLE="margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in; page-break-before: always">&nbsp;</P>
<h2 class="wsite-content-title">What Strabo Terms Mean</h2>
<P CLASS="western" STYLE="margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in">
These are terms you may encounter in the Strabo Data System.</P>
<P CLASS="western" STYLE="margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in">
  Attribute – some quality of the observation.  For example, dip and
strike, fold shape, or shear zone thickness.</P>
<P CLASS="western" STYLE="margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in">
Downward Nest –also downward nesting.  Fitting spots within a
larger spot spatially, with subsequent observation having the same or
smaller Spot size, contained within the starting Spot.  For example,
establish a structure and then automatically related further
observations or measurements (Spots) to it.  Can also group Spots
together that share a characteristic, e.g., same bed or layer.</P>
<P CLASS="western" STYLE="margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in">
Edge – anything connecting two nodes.  This is typically a
relationship with some logic (e.g., same bed).  Edges can also have
attributes – e.g., same bed at distance of 1m.</P>
<P CLASS="western" STYLE="margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in">
Field – same as attribute.</P>
<P CLASS="western" STYLE="margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in">
  Group – also grouping.  Logical, conceptual, or classification
  relationship between Spots.  
</P>
<P CLASS="western" STYLE="margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in">
  Image – any raster data.  This includes photos and sketches. 
  Sketches and pictures can originate with device camera or storage. 
  Image is almost any layer that cannot be altered by Strabo.  Data can
be overlain on images.</P>
<P CLASS="western" STYLE="margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in">
  Nest – also nesting.  The collection of Spots that share some
  spatial relationship.  For example, part of same structure in the
  same location.  Can also just mean data from one location, e.g., a
  dike cutting layering. Can also mean a hierarchy of spatial Spots,
meaning that Spots can fit spatially within or around other Spots.</P>
<P CLASS="western" STYLE="margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in">
  Node – entry in the database.  Same as Vertex. This is an
  observation, photo, sample, anything of this sort.  It can also be
  the start of a relationship or Nest.  For example, I am going to set
  a point on the map and collect a lot of spots connected with this
point/Spot (see Randy Style).</P>
<P CLASS="western" STYLE="margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in">
  Purpose – the reason something is done.  Why is a sample collected,
why am I doing the project, etc.  Can be very verbose if needed.</P>
<P CLASS="western" STYLE="margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in">
Relationship – see Edge.</P>
<P CLASS="western" STYLE="margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in">
  Randy Style – identify a feature/Spot, put the SPOT into Strabo,
  and collect many observations around it.  The observations share
  location with the Spot, but do not have unique locations.  AKA
Randolph Style.</P>
<P CLASS="western" STYLE="margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in">
  Sheet – a mockup of the user interface for a particular task or
  data entry item.  Can be used for both mobile and online forms of
Strabo.</P>
<P CLASS="western" STYLE="margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in">
  Spreadsheet – the data or content model for a particular sheet or
part of a sheet.</P>
<P CLASS="western" STYLE="margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in">
  Spot – observations, measurements, photo, or relationship
  associated with a spatial region.  Everything is a Spot (note capitol
  “S”).  For example, a single strike and dip of bedding, a set of
  measurements taken Randy Style, or documented cross cutting
  relationships.  Also, groups can share a relationship (see above) or
even be classified as a new spot.</P>
<P CLASS="western" STYLE="margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in">
  Tab – the main navigation bar on the application.  Appears for the
appropriate data types as described in the Notes document.</P>
<P CLASS="western" STYLE="margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in">
  Upward Nest – also upward nesting.  Fitting Spots into a larger
  Spot in spatial extent. For example, a set of bed measurements are
  used to define a fold orientation.  This is generally done after
  collecting data.  
</P>
<P CLASS="western" STYLE="margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in">
Vertex – see Node.</P>
<P CLASS="western" STYLE="margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in">
<BR>
</P>
<h2 class="wsite-content-title">Spot Data Attributes/Fields</h2>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in; widows: 0; orphans: 0">
  <FONT FACE="Times"><FONT SIZE=3 STYLE="font-size: 13pt">This list
    shows all possible Spot fields. Note not all fields will be
    populated. If not a top-level field, required fields are only
    required when field is displayed (based on skip logic using KOBO -
  </FONT></FONT><FONT COLOR="#0000ff"><A HREF="http://www.kobotoolbox.org/"><FONT FACE="Times"><FONT SIZE=3 STYLE="font-size: 13pt">http://www.kobotoolbox.org</FONT></FONT></A></FONT><FONT FACE="Times"><FONT SIZE=3 STYLE="font-size: 13pt">).
    This may make most sense to programmers looking at this, but names
    in </FONT></FONT><FONT FACE="Times"><FONT SIZE=3 STYLE="font-size: 13pt"><SPAN STYLE="background: #ff0000" color:"#FFFFFF">red</SPAN></FONT></FONT><FONT FACE="Times"><FONT SIZE=3 STYLE="font-size: 13pt">
are fields/attributes.</FONT></FONT></P>
<P CLASS="western" STYLE="margin-bottom: 0in; widows: 0; orphans: 0"><FONT FACE="Courier"><FONT SIZE=2>{</FONT></FONT></P>
<P CLASS="western" STYLE="margin-bottom: 0in; widows: 0; orphans: 0">
 <FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>&quot;geometry&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
{</FONT></FONT></P>
<blockquote>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>&quot;coordinates&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
    {},</FONT></FONT></p>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>&quot;type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
    &quot;one of [Point, MultiPoint, LineString, MultiLineString,
      Polygon, MultiPolygon]&quot;</FONT></FONT></p>
</blockquote>
<P CLASS="western" STYLE="margin-bottom: 0in; widows: 0; orphans: 0">
 <FONT FACE="Courier"><FONT SIZE=2>},</FONT></FONT></P>
<P CLASS="western" STYLE="margin-bottom: 0in; widows: 0; orphans: 0">
 <FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>&quot;properties&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
{</FONT></FONT></P>
<blockquote>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>&quot;_3d_structures&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
    [</FONT></FONT></p>
  <blockquote>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
      <FONT FACE="Courier"><FONT SIZE=2>	{</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>approximate_scale_m_lobate&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Approximate Scale (m)&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>approximate_scale_of_boudinage&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Approximate Scale of Boudinage (m)&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>approximate_scale_of_mullions&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Approximate Scale of Mullions (m)&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>average_width_of_boudin_neck&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Average Width of Boudin Neck (m)&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>boudin_linear_measure_quality&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [5, 4, 3, 2, 1]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Linear Measurement Quality</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
              </FONT></FONT><FONT COLOR="#fb00ff"><FONT FACE="Courier"><FONT SIZE=2>Hint:
                for boudin necklines&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>boudinage&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        acknowledge</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            BOUDINAGE&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>boudinage_2nd_plunge&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Plunge&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>boudinage_2nd_trend&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Trend&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>boudinage_2nd_trend_uncertaint&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>boudinage_competent&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Competent Material&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>boudinage_dip&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Dip&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>boudinage_dip_direction&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Dip Direction&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>boudinage_geometry&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [unidirectional, bidirectional]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Boudinage Geometry&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>boudinage_incompetent&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Incompetent Material&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>boudinage_plunge&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Plunge&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>boudinage_shape&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [symmetrical, asymmetrical]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Boudinage Shape&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>boudinage_shear_sense&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [north, ne, east, se, south, sw, west, nw]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Shear Sense&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>boudinage_strike&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Strike&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>boudinage_trend&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Trend&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>boudinage_trend_uncertainty&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Measured Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>boudinage_wavelength_m&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Wavelength (m)&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>boudinaged_layer_thickness_m&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Boudinaged Layer Thickness (m)&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>competent_material_fold&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Competent Material&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipse_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [finite_strain, preferred_shap, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Ellipse Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoid_int_orient_uncertain&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Orientation Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoid_int_plunge&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Plunge&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoid_int_plunge_uncertain&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoid_int_trend&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Trend&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoid_int_trend_uncertaint&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoid_int_value&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Value&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoid_int_value_uncertaint&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoid_max_orient_uncertain&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Orientation Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoid_max_plunge&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Plunge&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoid_max_plunge_uncertain&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoid_max_trend&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Trend&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoid_max_trend_uncertaint&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoid_max_uncertainty&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoid_max_value&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Value&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoid_min_orient_uncertain&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Orientation Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoid_min_plunge&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Plunge&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoid_min_plunge_uncertain&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoid_min_trend&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Trend&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoid_min_trend_uncertaint&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoid_min_value&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Value&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoid_min_value_uncertaint&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoid_quality&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [5___best, 4, 3, 2, 1___worst]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Quality of Measurement&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoid_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [finite_strain, shape_preferred_orientation,
        anisotropy_of_magnetic_suspect, stress, infinitesimal_strain, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Ellipsoid Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoidal_data&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        acknowledge</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            ELLIPSOIDAL DATA&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>ellipsoidal_tensor_notes&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Ellipsoidal Tensor Notes&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>elliptical_data&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        acknowledge</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            ELLIPTICAL DATA&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>elliptical_dip&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Dip&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>elliptical_dip_direction&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Dip Direction&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>elliptical_notes&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Elliptical Data Notes&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>elliptical_orientation_uncerta&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Orientation Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>elliptical_quality&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [accurate, approximate, irregular]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Quality&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>elliptical_quality_of_measurem&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [5___best, 4, 3, 2, 1___worst]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Quality of Measurement&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>elliptical_rake&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Rake&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>elliptical_ratio&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Elliptical Ratio&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>elliptical_strike&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Strike&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>elliptical_value_uncertainty&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Value Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>fold_attitude&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [upright, overturned, vertical, horizontal, recumbent,
        inclined]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Dominant Fold Attitude&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>fold_fol_description&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Description of Foliation&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>fold_fol_dip&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Dip&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>fold_fol_dip_direction&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Dip Direction&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>fold_fol_quality&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [accurate, approximate, irregular]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Orientation Quality</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
              </FONT></FONT><FONT COLOR="#fb00ff"><FONT FACE="Courier"><FONT SIZE=2>Hint:
                How accurate is the measurement? Irregular means the feature is
                curviplanar.&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>fold_fol_strike&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Strike&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>fold_foliation_Type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [axial_planar, fanning_conver, fanning_diverg,
        transected]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Foliation Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>fold_geometry&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [anticline, syncline, monocline, antiform, synform,
        s_fold, z_fold, m_fold, sheath, single_layer_b, ptygmatic,
        crenulation]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Dominant Fold Geometry</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
              </FONT></FONT><FONT COLOR="#fb00ff"><FONT FACE="Courier"><FONT SIZE=2>Hint:
                What is the shape of the fold when looking down-plunge?&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>fold_shape&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [chevron, cuspate, circular, elliptical]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Dominant Fold Shape&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>fold_shortening_dir_plunge&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Plunge&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>fold_shortening_dir_trend&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Trend&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>fold_shortening_dir_uncertaint&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>folded_layer_thickness_m&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Folded Layer Thickness (m)&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>folds&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        acknowledge</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            FOLDS&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>igneous_migmatite&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        acknowledge</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            IGNEOUS/MIGMATITE&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>igneous_migmatite_feat_descrip&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Igneous/Migmatite Features Description&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>incompetent_material_fold&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Incompetent Material&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>lobate_competent_material&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Competent Material&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>lobate_cuspate&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        acknowledge</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            LOBATE-CUSPATE&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>lobate_incompetent_material&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Incompetent Material&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>mullion_competent_material&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Competent Material&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>mullion_dip&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Dip&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>mullion_dip_direction&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Dip Direction&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>mullion_geometry&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [unidirectional, bidirectional]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Mullion Geometry&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>mullion_incompetent_material&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Incompetent Material&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>mullion_layer_thickness_m&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Mullion Layer Thickness (m)&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>mullion_linear_measure_quality&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [5, 4, 3, 2, 1]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Linear Measurement Quality&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>mullion_plunge&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Plunge&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>mullion_strike&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Strike&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>mullion_symmetry&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [symmetrical, asymmetrical]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Mullion Symmetry&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>mullion_trend&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Trend&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>mullion_uncertainty&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>mullion_wavelength_m&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Wavelength (m)&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>mullions&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        acknowledge</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            MULLIONS&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>non_ellipsoidal_data&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        acknowledge</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            NON-ELLIPSOIDAL DATA&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>non_ellipsoidal_tensor_notes&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Non-ellipsoidal Tensor Notes&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>non_ellipsoidal_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [flow_apophyses, displacements, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Non-ellipsoidal Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>nonellipsoidal_int_orient_unce&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Orientation Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>nonellipsoidal_int_plunge&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Plunge&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>nonellipsoidal_int_plunge_unce&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>nonellipsoidal_int_trend&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Trend&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>nonellipsoidal_int_uncertainty&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>nonellipsoidal_int_value&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Value&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>nonellipsoidal_int_value_uncer&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>nonellipsoidal_max_orient_Unce&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Orientation Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>nonellipsoidal_max_plunge&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Plunge&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>nonellipsoidal_max_plunge_unce&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>nonellipsoidal_max_trend&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Trend&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>nonellipsoidal_max_trend_uncer&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>nonellipsoidal_max_value&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Value&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>nonellipsoidal_max_value_uncer&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>nonellipsoidal_min_orient_Unce&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Orientation Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>nonellipsoidal_min_plunge&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Plunge&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>nonellipsoidal_min_plunge_unce&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>nonellipsoidal_min_trend&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Trend&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>nonellipsoidal_min_trend_uncer&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>nonellipsoidal_min_value&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Value&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>nonellipsoidal_min_value_uncer&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Uncertainty&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>nonellipsoidal_quality_of_meas&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [5___best, 4, 3, 2, 1___worst]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Quality of Measurement&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>number_of_necks_measured&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Number of Necks Measured&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_3D_structure&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        acknowledge</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            OTHER 3-D STRUCTURE&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_ellipse_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Ellipse type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_ellipsoid_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Ellipsoid Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_non_ellipsoidal_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Non-ellipsoidal Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_structure_description&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Structure Description&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>soft_sediment_def_description&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Soft Sediment Deformation Description&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>soft_sediment_deformation&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        acknowledge</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            SOFT SEDIMENT DEFORMATION&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>struct_notes&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        note</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Notes&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>tectonite_character&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [s_only, s___l, s___l_1, s___l_2, l___s, l___s_1, l_only]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Tectonite Character&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>tectonite_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [s_tectonite, sl_tectonite, ls_tectonite, l_tectonite]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Tectonite Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>tectonites&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        acknowledge</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            TECTONITES&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>tightness&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [gentle, open, close, tight, isoclinal]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Tightness / Interlimb Angle&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>vergence&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [north, ne, east, se, south, sw, west, nw]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Vergence</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
              </FONT></FONT><FONT COLOR="#fb00ff"><FONT FACE="Courier"><FONT SIZE=2>Hint:
                Approximate vergence fold asymmetry or other...irection from f&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>wavelength_m&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Wavelength (m)&quot;</FONT></FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
      <FONT FACE="Courier"><FONT SIZE=2>}</FONT></FONT></p>
  </blockquote>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT FACE="Courier"><FONT SIZE=2>],</FONT></FONT></p>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>&quot;date&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
    &quot;datetime&quot;,</FONT></FONT></p>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>&quot;id&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
    &quot;number; timestamp (in milliseconds) with a random 1 digit
      number appended (= 14 digit id)&quot;,</FONT></FONT></p>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>&quot;modified_timestamp&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
    &quot;timestamp&quot;,</FONT></FONT></p>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>&quot;name&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
    </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
        </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>&quot;orientation_data&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
    [</FONT></FONT></p>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT FACE="Courier"><FONT SIZE=2>{</FONT></FONT></p>
  <blockquote>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>associated_orientation&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      [],</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>defined_by&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Lineation Defined by&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>feature_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [groove_marks, parting_lineat, magmatic_miner_1,
        xenolith_encla, intersection, pencil_cleav, mineral_align,
        deformed_marke, rodding, boudin, mullions, fold_hinge, striations,
        slickenlines, slickenfibers, mineral_streak, vorticity_axis,
        flow_transport, vergence, vector, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Linear Feature Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>geo_age&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Geologic Age of Structure&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>max_age&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Maximum Age of Structure (Ma)&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>max_age_just&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Justification of Maximum Age&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>min_age&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Minimum Age of Structure (Ma)&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>min_age_just&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Justification of Minimum Age&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>notes&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        note</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Line Notes&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>orientation_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      &quot;linear_orientation; </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_feature&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Linear Feature&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>plunge&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Plunge&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>quality&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [5, 4, 3, 2, 1]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Linear Measurement Quality&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>rake&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Rake</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#fb00ff"><FONT FACE="Courier"><FONT SIZE=2>Hint:
              angle from strike on plane (0-180)?&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>rake_calculated&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [yes, no]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Rake Calculated?&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>trend&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Trend</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#fb00ff"><FONT FACE="Courier"><FONT SIZE=2>Hint:
              Azimuth in degrees&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>vorticity&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [clockwise, counterclockwi]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Vorticity Type&quot;</FONT></FONT></FONT></p>
  </blockquote>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT FACE="Courier"><FONT SIZE=2>},</FONT></FONT></p>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT FACE="Courier"><FONT SIZE=2>{</FONT></FONT></p>
  <blockquote>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>associated_orientation&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      [],</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>bedding_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [lithologic_cha, sedimentary_fe]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Bedding Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>contact_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [stratigraphic, alluvial, unconformity, angular_unconf,
        nonconformity, disconformity, volcanic, other_depositional_contact,
        dike, sill, pluton, migmatite, injectite, other_igneous,
        boundary_of_metamorphic_rocks, isograd, other_metamorp, other,
        unknown]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Contact Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>dip&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Dip&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>dip_direction&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Dip Direction&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>directional_indicators&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select_multiple tj67x48</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Directional Indicators&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>facing&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [upright, overturned, vertical, not_applicable]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Plane Facing&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>facing_defined_by&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [stratigraphy, facing_indicat, assumed, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Plane Facing Defined By&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>fault_or_sz_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [dextral, sinistral, oblique, reverse, thrust,
        low_angle_normal, dextral_reverse, dextral_normal, sinistral_reverse,
        sinistral_normal, high_angle, low_angle, scissor, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Type of Fault or Shear Zone Boundary&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>feature_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [bedding, contact, foliation, fracture, vein, fault,
        shear_zone, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Planar Feature Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>foliation_defined_by&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Foliation Defined by&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>foliation_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [cleavage, slatey_cleavage, phyllitic_cleavage,
        crenulation_cl, phacoidal, schistosity, planar_schistosity,
        anast_schistos, gneissic_folia, mineral_alignm, comp_banding,
        deformed_marker, mylonitic_foliation, s_plane, c_plane,
        c__or_extensional_plane, protomylonite, mylonite, ultramylonite,
        gouge, cataclasite, solid_state_mineral_alignment,
        magmatic_mineral_alignment, migmatitic, migmatitic_mineral_alignment,
        mineral_elongation, compaction, soft_sediment, stylolites,
        slickolites, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Foliation Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>fracture_defined_by&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Fracture Defined by&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>fracture_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [joint, opening_mode, shear_fracture, filled_fractur,
        other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Fracture Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>geo_age&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Geologic Age of Structure&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>length&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Planar Feature Length (m)&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>max_age&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Maximum Age of Structure (Ma)&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>max_age_just&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Justification of Maximum Age&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>min_age&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Minimum Age of Structure (Ma)&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>min_age_just&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Justification of Minimum Age&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>movement&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [n_side_up, ne_side_up, e_side_up, se_side_up, s_side_up,
        sw_side_up, w_side_up, nw_side_up, top_to_n, top_to_ne, top_to_e,
        top_to_se, top_to_s, top_to_sw, top_to_w, top_to_nw, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Movement&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>movement_justification&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select_multiple gq4wq38</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Movement Justification&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>notes&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        note</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Planar Feature Notes&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>orientation_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      &quot;planar_orientation; </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_contact_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Contact Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_dep_contact_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Depositional Contact Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_directional_indic&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Directional Indicator&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_facing_defined_by&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Plane Facing Defined By&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_fault_or_sz_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Fault or Shear Zone Boundary Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_foliation_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Foliation Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_fracture_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Fracture Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_ig_contact_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Igneous Contact Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_met_contact_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Metamorphic Contact Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_movement&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Movement&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_movement_justification&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Movement Justification&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_structures&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [fold_axial_sur, plane_boudinag, plane_mullions]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Geological Structures&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_vein_fill&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Vein Mineral&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>quality&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [5, 4, 3, 2, 1]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Planar Measurement Quality</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
              </FONT></FONT><FONT COLOR="#fb00ff"><FONT FACE="Courier"><FONT SIZE=2>Hint:
                How well was this plane measured?&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>strike&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Strike</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#fb00ff"><FONT FACE="Courier"><FONT SIZE=2>Hint:
              Azimuth in degrees&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>thickness&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Planar Feature Thickness (m)&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>vein_fill&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [quartz, calcite, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Vein Mineral Fill&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>vein_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [normal_opening, oblique_openin, antitaxial, syntaxial,
        unknown]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Vein Type&quot;</FONT></FONT></FONT></p>
  </blockquote>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT FACE="Courier"><FONT SIZE=2>},</FONT></FONT></p>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT FACE="Courier"><FONT SIZE=2>{</FONT></FONT></p>
  <blockquote>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>alteration_zone&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Alteration Zone Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>associated_orientation&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      [],</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>damage_zone&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [vein_formation, fractures, faulting, def_bands,
        defined_by_other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Damage Zone Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>damage_zone_defined_by&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Damage Zone defined by&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>defined_by&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Tabular Feature Defined By&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>dip&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Dip&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>dip_direction&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Dip Direction&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>dir_indicators&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select_multiple tj67x48</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Directional Indicators&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>enveloping_surface&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [fractures, veins, faults, shear_zones, folds]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Enveloping Surface Features&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>enveloping_surface_geometry&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [planar, en_echelon, random, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Enveloping Surface Features Geometry&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>facing&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [upright, overturned, vertical, uncertain,
        not_applicable]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Feature Facing&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>facing_defined_by&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [stratigraphy, facing_indicat, assumed, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Facing Direction Defined By&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>fault_or_sz&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [dextral, sinistral, oblique, reverse, thrust,
        low_angle_normal, dextral_reverse, dextral_normal, sinistral_reverse,
        sinistral_normal, high_angle, low_angle, scissor, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Fault Zone or Shear Zone Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>feature_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [stratigraphic, intrusive, injection, vein, vein_array,
        zone_fracturin, zone_faulting, shear_zone, damage_zone,
        alteration_zone, enveloping_surface, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Tabular Feature Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>fracture_zone&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [joints, opening_mode, shear_fracture, filled_fractur,
        other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Fracture Zone Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>fracture_zone_def_by&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Fracture Zone Boundary Define By&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>geo_age&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Geologic Age of Structure&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>injection_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [clastic_dike, injectite, pseudotachylit]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Injection Structure Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>intrusive_body_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [dike, sill, migmatite, injectite, schlieren, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Intrusive Body Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>length&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Tabular Feature Length (m)&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>max_age&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Maximum Age of Structure (Ma)&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>max_age_just&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Justification of Maximum Age&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>min_age&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Minimum Age of Structure (Ma)&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>min_age_just&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Justification of Minimum Age&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>movement&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [n_side_up, ne_side_up, e_side_up, se_side_up, s_side_up,
        sw_side_up, w_side_up, nw_side_up, top_to_n, top_to_ne, top_to_e,
        top_to_se, top_to_s, top_to_sw, top_to_w, top_to_nw, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Movement&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>movement_justification&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select_multiple gq4wq38</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Movement Justification&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>notes&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        note</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Tabular Feature Notes&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>orientation_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      &quot;tabular_orientation; </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_dir_indicators&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Directional Indicator&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_facing_defined_by&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Facing Defined By&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_fault_or_sz&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Fault Zone or Shear Zone Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_feature&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Tabular Feature&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_intrusive_body&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Intrusive Body&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_movement&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Movement&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_movement_justification&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Movement Justification&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_surface_geometry&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Other Surface Geometry Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_vein_array&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Vein Array&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_vein_fill&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Vein Mineral&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>quality&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [5, 4, 3, 2, 1]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Tabular Feature Orientation Quality</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
              </FONT></FONT><FONT COLOR="#fb00ff"><FONT FACE="Courier"><FONT SIZE=2>Hint:
                How well was this plane measured?&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>strat_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [bedded_rock, volcanic_flows]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Stratigraphic Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>strike&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Strike</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#fb00ff"><FONT FACE="Courier"><FONT SIZE=2>Hint:
              Azimuth in degrees&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>tabularity&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [constant, semi_constant, variable, highly_variabl]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Tabular Thickness Type/Tabularity&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>thickness&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Tabular Feature Thickness (m)&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>vein_array&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [en_echelon, general, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Vein Array&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>vein_fill&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [quartz, calcite, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Vein Mineral Fill&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>vein_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [normal_opening, oblique_openin, antitaxial, syntaxial,
        unknown]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Vein Type&quot;</FONT></FONT></FONT></p>
  </blockquote>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT FACE="Courier"><FONT SIZE=2>}</FONT></FONT></p>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT FACE="Courier"><FONT SIZE=2>],</FONT></FONT></p>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>&quot;rock_unit&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
    {</FONT></FONT></p>
  <blockquote>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>Notes&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        note</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Notes&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>absolute_age_of_geologic_unit&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Absolute Age of Geologic Unit (Ma)&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>age_modifier&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [late, middle, early]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Age Modifier&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>age_uncertainty&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        decimal</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Age Uncertainty (Ma)&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>description&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Description&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>eon&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [phanerozoic, proterozoic, archean, hadean]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Eon&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>epoch&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [holocene, pleistocene, pliocene, miocene, oligocene,
        eocene, paleocene, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Epoch&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>era&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [cenozoic, mesozoic, paleozoic, neoproterozoic,
        mesoproterozoi, paleoproterozo, neoarchean, mesoarchean,
        paleoarchean, eoarchean]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Era&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>igneous_rock_class&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [volcanic, plutonic, hypabyssal]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Igneous Rock Class&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>map_unit_name&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Unit or Formation Name&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>member_name&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Member Name&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>metamorphic_rock_types&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [low_grade, medium_grade, high_grade, slate, phyllite,
        schist, gneiss, marble, quartzite, amphibolite, serpentinite,
        hornfels, metavolcanic, calc_silicate, mylonite, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Metamorphic Rock Types&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_epoch&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Epoch&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_metamorphic_rock_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Metamorphic Rock Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_plutonic_rock_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Plutonic Rock Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_sediment_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Sediment Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_sedimentary_rock_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Sedimentary Rock Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_volcanic_rock_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Volcanic Rock Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>period&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [quaternary, neogene, paleogene, cretaceous, jurassic,
        triassic, permian, carboniferous, pennsylvanian, mississippian,
        devonian, silurian, ordovician, cambrian, ediacaran, cryogenian,
        tonian, stenian, ectasian, calymmian, statherian, orosirian,
        rhyacian, siderian]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Period&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>plutonic_rock_types&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [granite, alkali_granite, quartz_monz, syenite,
        granodiorite, monzonite, tonalite, diorite, gabbro, anorthosite,
        other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Plutonic Rock Types&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>rock_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [igneous, metamorphic, sedimentary, sediment]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Rock Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>sediment_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [alluvium, older_alluvium, colluvium, lake_deposit,
        eolian, talus, breccia, gravel, sand, silt, clay, moraine, till,
        loess, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Sediment Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>sedimentary_rock_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [limestone, dolostone, travertine, evaporite, chert,
        conglomerate, breccia, sandstone, siltstone, mudstone, shale,
        claystone, coal, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Sedimentary Rock Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>submember_name&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Submember Name&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>unit_label_abbreviation&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Unit Label (abbreviation)&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>volcanic_rock_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [basalt, basaltic_andes, andesite, latite, dacite,
        rhyolite, tuff, ash_fall_tuff, ash_flow_tuff, vitrophyre, trachyte,
        trachyandesite, tuff_breccia, lapilli_tuff, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Volcanic Rock Type&quot;</FONT></FONT></FONT></p>
  </blockquote>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT FACE="Courier"><FONT SIZE=2>},</FONT></FONT></p>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>&quot;samples&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
    [</FONT></FONT></p>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT FACE="Courier"><FONT SIZE=2>{</FONT></FONT></p>
  <blockquote>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>intactness_of_sample&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [5___definitely, 4, 3, 2, 1___float]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Intactness of Sample&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>main_sampling_purpose&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [fabric___micro, petrology, geochronology, geochemistry,
        other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Main Sampling Purpose&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>material_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [intact_rock, fragmented_roc, sediment, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Material Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>oriented_sample&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [yes, no]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Oriented Sample&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_material_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Material Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_sampling_purpose&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Sampling Purpose&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>sample_description&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Sample Description&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>sample_id_name&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Sample specific ID / Name&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>sample_notes&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        note</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Sample Notes&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>sample_orientation_notes&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Sample Orientation Notes&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>sample_size&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Sample Size&quot;</FONT></FONT></FONT></p>
  </blockquote>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT FACE="Courier"><FONT SIZE=2>}</FONT></FONT></p>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT FACE="Courier"><FONT SIZE=2>],</FONT></FONT></p>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>&quot;time&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
    &quot;datetime&quot;,</FONT></FONT></p>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>&quot;trace&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
    {</FONT></FONT></p>
  <blockquote>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>antropogenic_feature&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [fence_line, property_line, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Antropogenic Feature&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>contact_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [depositional, intrusive, metamorphic, marker_layer,
        unknown]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Contact Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>depositional_contact_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [stratigraphic, alluvial, unconformity, option_7,
        option_8, nonconformity, volcanic, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Depositional Contact Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>fold_attitude&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [plunging, upright, overturned, vertical, unknown,
        horizontal, recumbent, inclined, reclined, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Fold Attitude&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>fold_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [syncline, anticline, monocline, option_11, option_12,
        synform, antiform, s_fold, z_fold, m_fold, sheath, ptygmatic,
        unknown]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Fold Type</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
              </FONT></FONT><FONT COLOR="#fb00ff"><FONT FACE="Courier"><FONT SIZE=2>Hint:
                What is the shape of the fold when looking down-plunge?&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>geologic_structure_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [fault, shear_zone, deformation_zo, fold_axial_tra,
        other_structur]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Geologic Structure Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>geomorphic_feature&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [gulley, ridge, striation, rill, stream_bed,
        shoreline_stra, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Geomorphic Feature&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>intrusive_contact_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [dike, sill, pluton, migmatite, injectite, unknown]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Intrusive Contact Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>marker_layer_details&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        note</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Marker Layer Details&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>metamorphic_contact_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [btwn_2_dif_mm, isograd, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Metamorphic Contact Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_anthropogenic_feature&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Anthropogenic Feature&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_contact_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Contact Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_depositional_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Depositional Contact&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_feature&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [extent_of_mapp, extent_of_biol, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Feature&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_fold_attitude&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Fold Attitude&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_fold_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Fold Type&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_geomorphic_feature&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Geomorphic Feature&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_intrusive_contact&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Intrusive Contact&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_metamorphic_contact&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Metamorphic Contact&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_other_feature&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Other Features&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_other_structural_zone&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Other Structural Zone&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_shear_sense&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Shear Sense&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_structural_zones&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [deformation_ba, fracture_zone, damage_zone,
        alteration_zon, structural_dom, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Structural Zones&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_trace_character&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Trace Character&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>other_trace_quality&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Other Trace Quality&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>shear_sense&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [dextral, sinistral, normal, low_angle_norm, reverse,
        thrust, dextral_reverse, dextral_normal, sinistral_reverse,
        sinistral_normal, high_angle, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Shear Sense&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>trace_character&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [sharp, gradational, chilled, brecciated, unknown, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Trace Character&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>trace_feature&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        acknowledge</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Trace Feature&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>trace_notes&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        note</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Trace Notes&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>trace_quality&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [known, approximate, approximate(?), inferred,
        inferred(?), concealed, other]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            Trace Quality&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>trace_type&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        select one [contact, geologic_struc, geomorphic_fea, anthropenic_fe,
        other_features]</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#fc7808"><FONT FACE="Courier"><FONT SIZE=2>REQUIRED</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
              Trace Type&quot;</FONT></FONT></FONT></p>
  </blockquote>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT FACE="Courier"><FONT SIZE=2>}</FONT></FONT></p>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT FACE="Courier"><FONT SIZE=2>},</FONT></FONT></p>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>&quot;images&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
    [</FONT></FONT></p>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT FACE="Courier"><FONT SIZE=2>{</FONT></FONT></p>
  <blockquote>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>annotated&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      &quot;true/false for whether or not the image is used as an Image
      Basemap&quot;,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>caption&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Image Description&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>orientation_of_view_subject&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Orientation of View Subject</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
            </FONT></FONT><FONT COLOR="#fb00ff"><FONT FACE="Courier"><FONT SIZE=2>Hint:
              e.g., facing direction of cliff face&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>scale_of_image&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Scale of Overall Image&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>scale_of_object&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Scale and type of object in image (m)&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>title&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        text</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>; </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
          Image Name&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>view_angle_plunge&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            View angle (plunge)&quot;</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>,</FONT></FONT></p>
    <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0"><FONT COLOR="#fb0007"><FONT FACE="Courier"><FONT SIZE=2>view_azimuth_trend&quot;:</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>
      </FONT></FONT><FONT COLOR="#0f7001"><FONT FACE="Courier"><FONT SIZE=2>&quot;Type:
        integer</FONT></FONT></FONT><FONT FACE="Courier"><FONT SIZE=2>;
          </FONT></FONT><FONT COLOR="#0000ff"><FONT FACE="Courier"><FONT SIZE=2>Label:
            View azimuth (trend)&quot;</FONT></FONT></FONT></p>
  </blockquote>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT FACE="Courier"><FONT SIZE=2>}</FONT></FONT></p>
  <p class="western" style="margin-bottom: 0in; widows: 0; orphans: 0">
    <FONT FACE="Courier"><FONT SIZE=2>]</FONT></FONT></p>
</blockquote>
<P CLASS="western" STYLE="margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in">
<FONT FACE="Courier"><FONT SIZE=2>}</FONT></FONT></P>
<P CLASS="western" STYLE="margin-bottom: 0in"><BR>
</P>
<P CLASS="western" STYLE="margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in; page-break-before: always">
<FONT SIZE=4 STYLE="font-size: 16pt"><B>Who was Strabo?</B></FONT></P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in"><IMG SRC="includes/images/Motivation%20for%20Strabo%202_html_m64d44131.jpg" NAME="graphics2" ALIGN=BOTTOM WIDTH=303 HEIGHT=404 BORDER=0></P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in"><FONT COLOR="#0000ff"><A HREF="https://commons.wikimedia.org/wiki/File:Statue_of_Strabo_in_Amasia.jpg">https://commons.wikimedia.org/wiki/File:Statue_of_Strabo_in_Amasia.jpg</A></FONT></P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in">Strabo
  was a Greek geographer who lived from about 63BC to 24AD.  A detailed
  account of Strabo can be found on Wikipedia -
  <FONT COLOR="#0000ff"><A HREF="https://en.wikipedia.org/wiki/Strabo"><B>https://en.wikipedia.org/wiki/Strabo</B></A></FONT>.
  We consider Strabo to be the first structural geologist and
  tectonocist.   His writings include the following (taken from
  Wikipedia article, quote from Lyell’s “Principles of Geology” 
  12<SUP>th</SUP> edition, 1875, somewhat paraphrased in Lyell and
Casaubon):</P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in"><FONT COLOR="#1c1c1c"><FONT FACE="Helvetica, sans-serif">'It
  is not, because the lands covered by seas were originally at
  different altitudes, that the waters have risen, or subsided, or
  receded from some parts and inundated others. But the reason is, that
  the same land is sometimes raised up and sometimes depressed, and the
  sea also is simultaneously raised and depressed, so that it either
  overflows or returns into its own place again. We must therefore
  ascribe the cause to the ground, either to that ground which is under
  the sea, or to that which becomes flooded by it, but rather to that
  which lies beneath the sea, for this is more moveable, and, on
  account of its humidity, can be altered with great celerity. It is
  proper, </FONT></FONT><FONT COLOR="#1c1c1c"><FONT FACE="Helvetica, sans-serif">to
    derive our explanations from things which are obvious, and in some
    measure of daily occurrence, such as deluges, earthquakes, volcanic
    eruptions, and sudden swellings of the land beneath the sea;</FONT></FONT><FONT COLOR="#1c1c1c"><FONT FACE="Helvetica, sans-serif">
    for the last raise up the sea also, and when the same lands subside
    again, they occasion the sea to be let down. And it is not merely the
    small, but the large islands also, and not merely the islands, but
    the continents, which can be lifted up together with the sea; and
    both large and small tracts may subside, for habitations and cities,
    like Bure, Bizona, and many others, have been engulfed by
earthquakes.'</FONT></FONT></P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in"><FONT COLOR="#151515">Reference
from Casaubon (1587) - Strabonis, Geographia. Casaubon p. 1707.</FONT></P>
<P CLASS="western" STYLE="margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in">
<FONT COLOR="#151515">A more accurate translation is:</FONT></P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in">“<FONT FACE="Helvetica, sans-serif">Some,
  however, may be disinclined to admit this explanation, and would
  rather have proof from things more manifest to the senses, and which
  seem to meet us at every turn. Now deluges, earthquakes, eruptions of
  wind, and risings in the bed of the sea, these things cause the
  rising of the ocean, as sinking of the bottom causes it to become
  lower. It is not the case that small volcanic or other islands can be
  raised up from the sea, and not large ones, nor that all islands can,
  but not continents, since extensive sinkings of the land no less than
  small ones have been known; witness the yawning of those chasms which
  have ingulfed whole districts no less than their cities, as is said
  to have happened to Bura,</FONT><FONT COLOR="#0000e9"><SUP><FONT FACE="Helvetica, sans-serif">1</FONT></SUP></FONT><FONT FACE="Helvetica, sans-serif">
  Bizone,</FONT><FONT COLOR="#0000e9"><SUP><FONT FACE="Helvetica, sans-serif">2</FONT></SUP></FONT><FONT FACE="Helvetica, sans-serif">
  and many other towns at the time of earthquakes: and there is no more
  reason why one should rather think Sicily to have been disjoined from
  the main-land of Italy than cast up from the bottom of the sea by the
  fires of Ætna, as the Lipari and Pithecussan</FONT><FONT COLOR="#0000e9"><SUP><FONT FACE="Helvetica, sans-serif">3</FONT></SUP></FONT><FONT FACE="Helvetica, sans-serif">
Isles have been.</FONT><FONT COLOR="#151515"><FONT FACE="Helvetica, sans-serif">”</FONT></FONT><BR>
</P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in">Strabo
  Book 3 (Strabo 1.3.10<FONT COLOR="#151515">)
</FONT><FONT COLOR="#0000ff"><A HREF="http://www.perseus.tufts.edu/hopper/text?doc=Perseus%3Atext%3A1999.01.0239%3Abook%3D1%3Achapter%3D3%3Asection%3D10">http://www.perseus.tufts.edu/hopper/text?doc=Perseus%3Atext%3A1999.01.0239%3Abook%3D1%3Achapter%3D3%3Asection%3D10</A></FONT></P>

<P CLASS="western" STYLE="padding-top:20px;text-indent: 0in; margin-bottom: 0in; page-break-before: always">
<FONT SIZE=4 STYLE="font-size: 16pt"><B>Presentations on Strabo
Database</B></FONT></P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in"><a href ="includes/files/GSA2015.pdf" target="_blank">GSA Annual Meeting, 2015</a></P>
<P CLASS="western" STYLE="text-indent: 0.25in; margin-bottom: 0in"><a href ="includes/files/AGU2015.pdf" target="_blank">AGU Meeting, 2015</a></P>


<P CLASS="western" STYLE="padding-top:20px;margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in; page-break-before: always">
<FONT SIZE=4 STYLE="font-size: 16pt"><B>Who is Involved in Strabo
Development?</B></FONT><BR>
</P>

<div style="padding-left:20px;padding-top:10px;">
<P CLASS="western" STYLE="margin-bottom: 0in"><BR>
</P>
<P CLASS="western" STYLE="margin-bottom: 0in"><strong>Texas A&amp;M</strong></P>
<P CLASS="western" STYLE="margin-bottom: 0in">	Julie Newman</P>
<P CLASS="western" STYLE="margin-bottom: 0in"><BR>
</P>
<P CLASS="western" STYLE="margin-bottom: 0in"><strong>University of Kansas</strong></P>
<P CLASS="western" STYLE="margin-bottom: 0in">Doug Walker</P>
<P CLASS="western" STYLE="margin-bottom: 0in">	Jessica Good</P>
<P CLASS="western" STYLE="margin-bottom: 0in">	Jason Ash</P>
<P CLASS="western" STYLE="margin-bottom: 0in">	Joe Andrew</P>
<P CLASS="western" STYLE="margin-bottom: 0in">	Andreas Moeller</P>
<P CLASS="western" STYLE="margin-bottom: 0in">	Emily Bunse</P>
<P CLASS="western" STYLE="margin-bottom: 0in">	Carson Rufledt</P>

<P CLASS="western" STYLE="margin-bottom: 0in"><BR>
</P>
<P CLASS="western" STYLE="margin-bottom: 0in"><strong>University of Wisconsin</strong></P>
<P CLASS="western" STYLE="margin-bottom: 0in">	Basil Tikoff</P>
<P CLASS="western" STYLE="margin-bottom: 0in">	Maureen Kahn</P>
<P CLASS="western" STYLE="margin-bottom: 0in">	Zach Michaels</P>
<P CLASS="western" STYLE="margin-bottom: 0in">	Randy Williams</P>
<P CLASS="western" STYLE="margin-bottom: 0in">	</P>
</div>



<P CLASS="western" STYLE="padding-top:20px;margin-left: 0.5in; text-indent: -0.5in; margin-bottom: 0in; page-break-before: always">
<FONT SIZE=4 STYLE="font-size: 16pt"><B>Bugs, Feature Requests, and Other Issues</B></FONT><BR>
</P>

<ul>
	<li>
		The preferred method of reporting is to post an issue at <a href="https://github.com/StraboSpot/strabo-mobile/issues">https://github.com/StraboSpot/strabo-mobile/issues</a>
	</li>
	<li>
		Alternatively, you can send an email to <a href="mailto:strabospot@gmail.com">strabospot@gmail.com</a>.
	</li>
</ul>
















<?
include("includes/footer.php");
?>