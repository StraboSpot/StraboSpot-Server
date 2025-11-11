<?php
/**
 * File: api.php
 * Description: REST API documentation and endpoint information
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
							<h2>StraboSpot REST API</h2>
						</header>

<div class="apidoc" style="padding-top:10px;">

<div style="width:100%;border: 1px solid #333333;padding:5px;margin-bottom:10px;">
The StraboSpot REST API uses HTTP Basic Auth for authentication. An account can be obtained
by clicking on the "<a href="register">ACCOUNT->REGISTER</a>" link above. Once an account is created, it will need
to be validated by email. When using the StraboSpot REST API, you will use your email address as the username and your
password as entered during registration. Alternatively, the StraboSpot system also supports JWT (JSON Web Tokens) for
access all API endpoints. The path for JWT access is "/jwtdb/" instead of "/db/" for all requests. JSON web tokens can
be acquired by clicking <a href="/my_jwts">here</a>.
</div>

<!--
<div style="width:100%;border: 1px solid #333333;padding:5px;margin-bottom:10px;">
Please note that the REST API currently uses a somewhat simplified GeoJSON representation of each feature. The features
will eventually require more attributes determined by a set of contrained vocabularies. These will be incorporated soon.
</div>
-->

<div class="row">
	<div class="col-3 col-12-xsmall">

<fieldset style="border: 1px solid #CDCDCD; padding: 8px; padding-bottom:0px; margin: 8px 0">
	<legend><strong>Table of Contents</strong></legend>
<!--
<a href="#createfeature">Create/Update Feature</a><br>
-->

	<div style="font-weight:bold;">StraboField:</div>
	<div style="padding-left:5px;">
	<a href="#createmultiplefeatures">Upload Spot(s)</a><br>

	<a href="#getfeature">Get Spot</a><br>
	<a href="#deletefeature">Delete Spot</a><br>
	<!--
	<a href="#search">Search</a><br>
	-->
	<a href="#getmyfeatures">Get My Spots</a><br>
	<a href="#deleteallofmyfeatures">Delete All of My Spots</a><br>
	<a href="#authenticateuser">Authenticate User</a><br>
	<a href="#uploadimage">Upload Image</a><br>
	<a href="#getimage">Get Image</a><br>
	<a href="#deleteimage">Delete Image</a><br>
	<a href="#verifyimageexistence">Verify Image Existence</a><br>
	<a href="#verifyimagesexistence">Verify Multiple Images Existence</a><br>
	<!---
	<a href="#getallimagesforgivenfeature">Get All Images for Given Feature</a><br>
	--->
	<a href="#createdataset">Create Dataset</a><br>
	<a href="#updatedataset">Update Dataset</a><br>
	<a href="#getdataset">Get Dataset</a><br>
	<a href="#deletedataset">Delete Dataset</a><br>
	<a href="#getmydatasets">Get My Datasets</a><br>
	<a href="#addspottodataset">Add Spot to Dataset</a><br>
	<a href="#movespottodataset">Move Spot to Dataset</a><br>
	<a href="#getallspotsforgivendataset">Get All Spots for Given Dataset</a><br>
	<a href="#getallfieldsforgivendataset">Get All Fields for Given Dataset</a><br>

	<a href="#deleteallspotsforgivendataset">Delete All Spots for Given Dataset</a><br>
	<a href="#createproject">Create Project</a><br>
	<a href="#updateproject">Update Project</a><br>
	<a href="#getproject">Get Project</a><br>
	<a href="#deleteproject">Delete Project</a><br>
	<a href="#getmyprojects">Get My Projects</a><br>
	<a href="#getallimagesforgivenproject">Get All Images for a Given Project</a><br>
	<a href="#adddatasettoproject">Add Dataset to Project</a><br>
	<a href="#getalldatasetsforgivenproject">Get All Datasets for Given Project</a><br>
	<a href="#deletealldatasetsforgivenproject">Delete All Datasets for Given Project</a><br>
	<a href="#createprofile">Create Profile</a><br>
	<a href="#updateprofile">Update Profile</a><br>
	<a href="#getprofile">Get Profile</a><br>
	<a href="#deleteprofile">Delete Profile</a><br>
	<a href="#deleteaccount">Delete Account</a><br>
	<a href="#uploadprofileimage">Upload Profile Image</a><br>
	<a href="#getprofileimage">Get Profile Image</a><br>
	<a href="#deleteprofileimage">Delete Profile Image</a><br>
	<a href="#uploadmacrojwt">Upload Macrostrat JWT</a><br>
	<a href="#getmacrojwt">Get Macrostrat JWT</a><br>
	<a href="#deletemacrojwt">Delete Macrostrat JWT</a><br>
	</div>
	<div style="font-weight:bold;padding-top:5px;">StraboMicro:</div>
	<div style="padding-left:5px;">
	<!--<a href="#getmicroprojectcount">Get Project Count</a><br>-->
	<a href="#getmymicroprojects">Get My Projects</a><br>
	<a href="#getmicroproject">Get Project</a><br>
	<a href="#getmicroimage">Get Image</a><br>
	</div>
</fieldset>

	</div>
	<div class="col-9 col-12-xsmall">

<!-- ******************************************************************************** -->
<!--
	<A name="createfeature"></A>
	<h2 class="wsite-content-title">Create/Update Feature</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>POST:</strong> https://strabospot.org/db/feature</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
   "type": "Feature",
   "geometry": {
	 "type": "Polygon",
	 "coordinates": [[
		 [100.0, 0.0], [101.0, 0.0], [101.0, 1.0], [100.0, 1.0], [100.0, 0.0]
		]]
   },
   "properties": {
	 "id": 14309503161234
	 "date": "2014-11-20",
	 "name": "My Test Feature Polygon Name",
	 "spotType": "c",
	 "modified_timestamp": "14561720041234"
   }
}
</pre>
</div>
		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>201:</strong> Created</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "geometry" : {
	"type" : "Polygon",
	"coordinates" : [[
	  [100,0], [101,0], [101,1], [100,1],[100,0]
	]]
  },
  "properties" : {
	"id": 14309503161234,
	"spotType" : "c",
	"date" : "2014-11-20",
	"name" : "My Test Feature Polygon Name",
	"modified_timestamp": "14561720041234"
	"self" : "https://strabospot.org/db/feature/246"
  },
  "type" : "Feature"

}
</pre>
</div>
	</div>

-->

<!-- ******************************************************************************** -->
	<A name="createmultiplefeatures"></A>
	<A name="updateFeature"></A>
	<h2 class="wsite-content-title">Upload Spots(s)</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>POST:</strong>https://strabospot.org/db/datasetspots/12345</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Where:</strong> 12345 is the dataset id.</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Note:</strong> This function accepts a GeoJSON FeatureCollection and populates it as individual spot(s) for the given dataset. An existing dataset is required.</code></span>
				</li>
			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "type": "FeatureCollection",
  "features": [
	{
	  "type": "Feature",
	  "properties": {
		"images": [
		  {
			"height": 1999,
			"id": 1122334455667788991,
			"annotated": true,
			"title": "new image here",
			"width": 720,
			"caption": "caption here"
		  },
		  {
			"height": 999,
			"id": 1122334455667788992,
			"annotated": true,
			"title": "title of image here",
			"width": 720,
			"caption": "caption here"
		  },
		  {
			"height": 669,
			"id": 1122334455667788993,
			"annotated": false,
			"title": "",
			"width": 500,
			"caption": "caption here"
		  }
		],
		"time": "2016-02-18T21:10:35.000Z",
		"id": 12345678901,
		"orientation_data": [
		  {
			"dip_direction": 65,
			"strike": 55,
			"dip": 45,
			"facing": "upright",
			"orientation_type": "planar_orientation"
		  }
		],
		"modified_timestamp": 1455830604745,
		"date": "2016-02-18T21:10:35.000Z",
		"samples": [
		  {
			"sample_id_name": "id2",
			"oriented_sample": "yes",
			"sample_description": "Hhh",
			"sample_orientation_notes": "Fffhhh"
		  }
		],
		"name": "My test point1"
	  },
	  "geometry": {
		"type": "Point",
		"coordinates": [
		  -97.678707920763003,
		  38.576879262485001
		]
	  }
	},
	{
	  "type": "Feature",
	  "properties": {
		"images": [
		  {
			"height": 1999,
			"id": 1122334455667788994,
			"annotated": true,
			"title": "new image here",
			"width": 720,
			"caption": "caption here"
		  },
		  {
			"height": 999,
			"id": 1122334455667788995,
			"annotated": true,
			"title": "title of image here",
			"width": 720,
			"caption": "caption here"
		  },
		  {
			"height": 669,
			"id": 1122334455667788996,
			"annotated": false,
			"title": "",
			"width": 500,
			"caption": "caption here"
		  }
		],
		"time": "2016-02-18T21:10:35.000Z",
		"id": 12345678902,
		"orientation_data": [
		  {
			"dip_direction": 65,
			"strike": 55,
			"dip": 45,
			"facing": "upright",
			"orientation_type": "planar_orientation"
		  }
		],
		"modified_timestamp": 1455830604745,
		"date": "2016-02-18T21:10:35.000Z",
		"samples": [
		  {
			"sample_id_name": "id3",
			"oriented_sample": "yes",
			"sample_description": "Hhh",
			"sample_orientation_notes": "Fffhhh"
		  }
		],
		"name": "My test point2"
	  },
	  "geometry": {
		"type": "Point",
		"coordinates": [
		  -97.678707920763003,
		  38.576879262485001
		]
	  }
	},
	{
	  "type": "Feature",
	  "properties": {
		"images": [
		  {
			"height": 1999,
			"id": 1122334455667788997,
			"annotated": true,
			"title": "new image here",
			"width": 720,
			"caption": "caption here"
		  },
		  {
			"height": 999,
			"id": 1122334455667788998,
			"annotated": true,
			"title": "title of image here",
			"width": 720,
			"caption": "caption here"
		  },
		  {
			"height": 669,
			"id": 1122334455667788999,
			"annotated": false,
			"title": "",
			"width": 500,
			"caption": "caption here"
		  }
		],
		"time": "2016-02-18T21:10:35.000Z",
		"id": 12345678903,
		"orientation_data": [
		  {
			"dip_direction": 65,
			"strike": 55,
			"dip": 45,
			"facing": "upright",
			"orientation_type": "planar_orientation"
		  }
		],
		"modified_timestamp": 1455830604745,
		"date": "2016-02-18T21:10:35.000Z",
		"samples": [
		  {
			"sample_id_name": "id3",
			"oriented_sample": "yes",
			"sample_description": "Hhh",
			"sample_orientation_notes": "Fffhhh"
		  }
		],
		"name": "My test point3"
	  },
	  "geometry": {
		"type": "Point",
		"coordinates": [
		  -97.678707920763003,
		  38.576879262485001
		]
	  }
	}
  ]
}
</pre>
</div>
		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>201:</strong> Created</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "type": "FeatureCollection",
  "features": [
	{
	  "type": "Feature",
	  "properties": {
		"images": [
		  {
			"height": 1999,
			"id": 1122334455667788991,
			"annotated": true,
			"title": "new image here",
			"width": 720,
			"caption": "caption here",
			"self": "https://strabospot.org/image/1122334455667788991"
		  },
		  {
			"height": 999,
			"id": 1122334455667788992,
			"annotated": true,
			"title": "title of image here",
			"width": 720,
			"caption": "caption here",
			"self": "https://strabospot.org/image/1122334455667788992"
		  },
		  {
			"height": 669,
			"id": 1122334455667788993,
			"annotated": false,
			"title": "",
			"width": 500,
			"caption": "caption here",
			"self": "https://strabospot.org/image/1122334455667788993"
		  }
		],
		"time": "2016-02-18T21:10:35.000Z",
		"id": 12345678901,
		"self": "https://strabospot.org/feature/12345678901",
		"orientation_data": [
		  {
			"dip_direction": 65,
			"strike": 55,
			"dip": 45,
			"facing": "upright",
			"orientation_type": "planar_orientation"
		  }
		],
		"modified_timestamp": 1455830604745,
		"date": "2016-02-18T21:10:35.000Z",
		"samples": [
		  {
			"sample_id_name": "id2",
			"oriented_sample": "yes",
			"sample_description": "Hhh",
			"sample_orientation_notes": "Fffhhh"
		  }
		],
		"name": "My test point1"
	  },
	  "geometry": {
		"type": "Point",
		"coordinates": [
		  -97.678707920763003,
		  38.576879262485001
		]
	  }
	},
	{
	  "type": "Feature",
	  "properties": {
		"images": [
		  {
			"height": 1999,
			"id": 1122334455667788994,
			"annotated": true,
			"title": "new image here",
			"width": 720,
			"caption": "caption here",
			"self": "https://strabospot.org/image/1122334455667788994"
		  },
		  {
			"height": 999,
			"id": 1122334455667788995,
			"annotated": true,
			"title": "title of image here",
			"width": 720,
			"caption": "caption here",
			"self": "https://strabospot.org/image/1122334455667788995"
		  },
		  {
			"height": 669,
			"id": 1122334455667788996,
			"annotated": false,
			"title": "",
			"width": 500,
			"caption": "caption here",
			"self": "https://strabospot.org/image/1122334455667788996"
		  }
		],
		"time": "2016-02-18T21:10:35.000Z",
		"id": 12345678902,
		"self": "https://strabospot.org/feature/12345678902",
		"orientation_data": [
		  {
			"dip_direction": 65,
			"strike": 55,
			"dip": 45,
			"facing": "upright",
			"orientation_type": "planar_orientation"
		  }
		],
		"modified_timestamp": 1455830604745,
		"date": "2016-02-18T21:10:35.000Z",
		"samples": [
		  {
			"sample_id_name": "id3",
			"oriented_sample": "yes",
			"sample_description": "Hhh",
			"sample_orientation_notes": "Fffhhh"
		  }
		],
		"name": "My test point2"
	  },
	  "geometry": {
		"type": "Point",
		"coordinates": [
		  -97.678707920763003,
		  38.576879262485001
		]
	  }
	},
	{
	  "type": "Feature",
	  "properties": {
		"images": [
		  {
			"height": 1999,
			"id": 1122334455667788997,
			"annotated": true,
			"title": "new image here",
			"width": 720,
			"caption": "caption here",
			"self": "https://strabospot.org/image/1122334455667788997"
		  },
		  {
			"height": 999,
			"id": 1122334455667788998,
			"annotated": true,
			"title": "title of image here",
			"width": 720,
			"caption": "caption here",
			"self": "https://strabospot.org/image/1122334455667788998"
		  },
		  {
			"height": 669,
			"id": 1122334455667788999,
			"annotated": false,
			"title": "",
			"width": 500,
			"caption": "caption here",
			"self": "https://strabospot.org/image/1122334455667788999"
		  }
		],
		"time": "2016-02-18T21:10:35.000Z",
		"id": 12345678903,
		"self": "https://strabospot.org/feature/12345678903",
		"orientation_data": [
		  {
			"dip_direction": 65,
			"strike": 55,
			"dip": 45,
			"facing": "upright",
			"orientation_type": "planar_orientation"
		  }
		],
		"modified_timestamp": 1455830604745,
		"date": "2016-02-18T21:10:35.000Z",
		"samples": [
		  {
			"sample_id_name": "id3",
			"oriented_sample": "yes",
			"sample_description": "Hhh",
			"sample_orientation_notes": "Fffhhh"
		  }
		],
		"name": "My test point3"
	  },
	  "geometry": {
		"type": "Point",
		"coordinates": [
		  -97.678707920763003,
		  38.576879262485001
		]
	  }
	}
  ]
}
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="getfeature"></A>
	<h2 class="wsite-content-title">Get Spot</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>GET:</strong> https://strabospot.org/db/feature/14309503161234,</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>200:</strong> OK</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "geometry" : {
	"type" : "Polygon",
	"coordinates" : [[
	  [100,0], [101,0], [101,1], [100,1],[100,0]
	]]
  },
  "properties" : {
	"id": 14309503161234,
	"date" : "2014-11-20",
	"name" : "My Test Feature Polygon Name",
	"self" : "https://strabospot.org/db/feature/1234"
  },
  "type" : "Feature"

}
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="deletefeature"></A>
	<h2 class="wsite-content-title">Delete Spot</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>DELETE:</strong> https://strabospot.org/db/feature/14309503161234,</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>204:</strong> No Content</code></span>
				</li>

			</ul>
		</div>
	</div>

<!-- ********************************************************************************

	<A name="search"></A>
	<h2 class="wsite-content-title">Search (BBOX only for now)</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>POST:</strong> https://strabospot.org/db/search</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{

	"BBOX" : "0,0,200,200"

}
</pre>
</div>
		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>200:</strong> OK</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "type" : "FeatureCollection",
  "features" : [
	{
	  "geometry" : {
		"type" : "Polygon",
		"coordinates" : [[
	  [100,0], [101,0], [101,1], [100,1],[100,0]
		]]
	  },
	  "properties" : {
		"id": 14309503161234,
		"date" : "2014-11-20",
		"name" : "Test 3",
		"self" : "https://strabospot.org/db/feature/1242"
	  },
	  "type" : "Feature"
	},
	{
	  "geometry" : {
		"type" : "Polygon",
		"coordinates" : [[
	  [100,0], [101,0], [101,1], [100,1],[100,0]
		]]
	  },
	  "properties" : {
		"id": 14309503161235,
		"date" : "2014-11-20",
		"name" : "Test 2",
		"self" : "https://strabospot.org/db/feature/1241"
	  },
	  "type" : "Feature"
	},
	{
	  "geometry" : {
		"type" : "Polygon",
		"coordinates" : [[
	  [100,0], [101,0], [101,1], [100,1],[100,0]
		]]
	  },
	  "properties" : {
		"id" : 14309503161236,
		"date" : "2014-11-20",
		"name" : "Test 1",
		"self" : "https://strabospot.org/db/feature/1240"
	  },
	  "type" : "Feature"
	},
	{
	  "geometry" : {
		"type" : "Polygon",
		"coordinates" : [[
	  [100,0], [101,0], [101,1], [100,1],[100,0]
		]]
	  },
	  "properties" : {
		"id" : 14309503161237,
		"date" : "2014-11-20",
		"name" : "Test Poly",
		"self" : "https://strabospot.org/db/feature/1239"
	  },
	  "type" : "Feature"
	}

  ]
}
</pre>
</div>
	</div>

-->

<!-- ******************************************************************************** -->

	<A name="getmyfeatures"></A>
	<h2 class="wsite-content-title">Get My Spots (Return all spots of authorized user)</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>GET:</strong> https://strabospot.org/db/myFeatures</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>200:</strong> OK</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "type" : "FeatureCollection",
  "features" : [
	{
	  "geometry" : {
		"type" : "Polygon",
		"coordinates" : [[
	  [100,0], [101,0], [101,1], [100,1],[100,0]
		]]
	  },
	  "properties" : {
		"id" : 14309503161234,
		"date" : "2014-11-20",
		"name" : "Test 3",
		"self" : "https://strabospot.org/db/feature/1242"
	  },
	  "type" : "Feature"
	},
	{
	  "geometry" : {
		"type" : "Polygon",
		"coordinates" : [[
	  [100,0], [101,0], [101,1], [100,1],[100,0]
		]]
	  },
	  "properties" : {
		"id": 14309503161235,
		"date" : "2014-11-20",
		"name" : "Test 2",
		"self" : "https://strabospot.org/db/feature/1241"
	  },
	  "type" : "Feature"
	},
	{
	  "geometry" : {
		"type" : "Polygon",
		"coordinates" : [[
	  [100,0], [101,0], [101,1], [100,1],[100,0]
		]]
	  },
	  "properties" : {
		"id" : 14309503161236,
		"date" : "2014-11-20",
		"name" : "Test 1",
		"self" : "https://strabospot.org/db/feature/1240"
	  },
	  "type" : "Feature"
	},
	{
	  "geometry" : {
		"type" : "Polygon",
		"coordinates" : [[
	  [100,0], [101,0], [101,1], [100,1],[100,0]
		]]
	  },
	  "properties" : {
		"id" : 14309503161237,
		"date" : "2014-11-20",
		"name" : "Test Poly",
		"self" : "https://strabospot.org/db/feature/1239"
	  },
	  "type" : "Feature"
	}

  ]
}
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->
	<A name="deleteallofmyfeatures"></A>
	<A name="deleteMyFeatures"></A>
	<h2 class="wsite-content-title">Delete All of My Spots (Delete all spots of authorized user)</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>DELETE:</strong> https://strabospot.org/db/myFeatures</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>204:</strong> No Content</code></span>
				</li>

			</ul>
		</div>
	</div>

<!-- ******************************************************************************** -->
	<A name="authenticateuser"></A>
	<A name="authenticateUser"></A>
	<h2 class="wsite-content-title">Authenticate User</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>POST:</strong> https://strabospot.org/userAuthenticate</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{

	"email" : "foo@foo.com",
	"password" : "bar"

}
</pre>
</div>
		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>200:</strong> OK</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "valid" : "true"
}
</pre>

or

<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "valid" : "false"
}
</pre>

</div>
	</div>

<!-- ******************************************************************************** -->

<!-- ********************************************************************************

	<h2 class="wsite-content-title">Create Feature</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>POST:</strong> https://strabospot.org/db/feature</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
   "type": "Feature",
   "geometry": {
	 "type": "Polygon",
	 "coordinates": [[
		 [100.0, 0.0], [101.0, 0.0], [101.0, 1.0], [100.0, 1.0], [100.0, 0.0]
					]]
   },
   "properties": {
	 "date": "2014-11-20",
	 "id": "foobar123",
	 "name": "My Test Feature Polygon Name",
	 "spotType": "c"
   }
}
</pre>
</div>
		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>201:</strong> Created</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Location:</strong> https://localhost:7474/db/data/node/291</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "geometry" : {
	"type" : "Polygon",
	"coordinates" : [[
	  [100,0], [101,0], [101,1], [100,1],[100,0]
	]]
  },
  "properties" : {
	"id": "foobar123",
	"spotType" : "c",
	"date" : "2014-11-20",
	"name" : "My Test Feature Polygon Name",
	"self" : "https://strabospot.org/db/feature/246"
  },
  "type" : "Feature"
}
</pre>
</div>
	</div>

 ******************************************************************************** -->

<A name="imageFunctions"></A>
	<A name="uploadimage"></A>
	<h2 class="wsite-content-title">Upload Image</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>POST:</strong> https://strabospot.org/db/image</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
Due to a file being uploaded, this service accepts multiple POST variables:

Variable Name: "id" - ID of image being uploaded. (required)
Variable Name: "modified_timestamp" - Unix timestamp when image last modified. (optional)
Variable Name: "image_file" - File containing image to be uploaded. (required)

Also, please note the the encapsulation type MUST be set to "multipart/form-data".
</pre>

<!---
Variable Name: "id" - ID of image being uploaded. (required)
Variable Name: "feature_id" - ID of feature associated with image. (required)
Variable Name: "image_file" - File containing image to be uploaded. (required)
Variable Name: "caption" - Optional caption for image.
Variable Name: "title" - Optional title for image.
Variable Name: "width" - Optional width of image.
Variable Name: "height" - Optional height of image.
--->

</div>
		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>201:</strong> Created</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{"self":"https://strabospot.org/db/image/531","id":"531"}
</pre>
	Note: Due to the complexity of using multipart/form-data, a test page has been created to test this service. <br>This
	page can be found at: <a href="https://strabospot.org/imageupload">https://strabospot.org/imageupload</a>.
</div>

	</div>

<!-- ******************************************************************************** -->

	<A name="getimage"></A>
	<h2 class="wsite-content-title">Get Image</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>GET:</strong> https://strabospot.org/db/image/527</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>200:</strong> OK</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> image/jpg</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
File content of image is returned.
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="deleteimage"></A>
	<h2 class="wsite-content-title">Delete Image</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>DELETE:</strong> https://strabospot.org/db/image/1234</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>204:</strong> No Content</code></span>
				</li>

			</ul>
		</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="verifyimageexistence"></A>
	<h2 class="wsite-content-title">Verify Image Existence</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>GET:</strong> https://strabospot.org/db/verifyimage/1234</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>200:</strong> Success</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>404:</strong> Not Found</code></span>
				</li>

			</ul>
		</div>
	</div>

<!-- ******************************************************************************** -->
<!-- ******************************************************************************** -->

<!--
********************************************************************************
-->

<A name="verifyimagesexistence"></A>

	<a name="createdataset"></a>
	<h2 class="wsite-content-title">Verify Multiple Images Existence</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>POST:</strong> https://strabospot.org/db/verifyImages</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Note:</strong> This function accepts an array of image IDs and returns an array of those NOT found on the server.</code></span>
				</li>
			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
[
"14933043023319",
"15638104924629",
"15638106581721",
"15638107960818",
"15638108757466",
"15638112859644",
"15638120791554",
"15638121473783",
"15638133655910",
"15638138515003",
"15638215868385",
"15639009840188"
]
</pre>
</div>
		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>200:</strong> Valid Request</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
[
"15638121473783",
"15638133655910",
"15638138515003"
]
</pre>
</div>
<div style="padding-left:30px;padding-bottom:10px;">

	Note: The returned array of image IDs represents those NOT found on the server (of those provided).
</div>
	</div>

<!-- ******************************************************************************** -->

<!-- ******************************************************************************** -->
<!--

	<A name="getallimagesforgivenfeature"></A>
	<h2 class="wsite-content-title">Get All Images for Given Feature</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>GET:</strong> https://strabospot.org/db/featureImages/12345</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>200:</strong> OK</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "images": [
	{
	  "caption": "caption here",
	  "self": "https://strabospot.org/db/image/57162"
	},
	{
	  "caption": "another caption here",
	  "self": "https://strabospot.org/db/image/57163"
	},
	{
	  "caption": "caption three",
	  "self": "https://strabospot.org/db/image/57164"
	}
  ]
}
</pre>
</div>
	</div>
-->

<!--
********************************************************************************
-->

<A name="datasetFunctions"></A>

	<A name="createdataset"></A>
	<h2 class="wsite-content-title">Create Dataset</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>POST:</strong> https://strabospot.org/db/dataset</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
	"id": 14573623121234,
	"name": "MyDatasetName",
	"modified_timestamp": 1457377455,
	"date": "03/08/2016"
}
</pre>
</div>
		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>201:</strong> Created</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
	"datasettype" : "app",
	"name": "MyDatasetName",
	"id": 14573623121234,
	"self" : "https://strabospot.org/db/dataset/123456",
	"modified_timestamp": 1457377455,
	"date": "03/08/2016"
}
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->
	<A name="updatedataset"></A>
	<h2 class="wsite-content-title">Update Dataset</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>POST:</strong> https://strabospot.org/db/dataset/14573623121234</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Warning:</strong> All attributes will be overwritten with provided JSON values.</code></span>
				</li>
			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
   "name": "MyNewDatasetName"
}
</pre>
</div>
		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>201:</strong> Created</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
	"datasettype" : "app",
	"name": "MyNewDatasetName",
	"id": 14573623121234,
	"self" : "https://strabospot.org/db/dataset/14573623121234",
	"modified_timestamp": 1457377455,
	"date": "03/08/2016"
}
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="getdataset"></A>
	<h2 class="wsite-content-title">Get Dataset</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>GET:</strong> https://strabospot.org/db/dataset/"id": 14573623121234,</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>200:</strong> OK</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
	"datasettype" : "app",
	"name": "MyNewDatasetName",
	"id": 14573623121234,
	"self" : "https://strabospot.org/db/dataset/123456",
	"modified_timestamp": 1457377455,
	"date": "03/08/2016"
}
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="deletedataset"></A>
	<h2 class="wsite-content-title">Delete Dataset (also deletes spots)</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>DELETE:</strong> https://strabospot.org/db/dataset/123456</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>204:</strong> No Content</code></span>
				</li>

			</ul>
		</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="getmydatasets"></A>
	<h2 class="wsite-content-title">Get My Datasets (Return all datasets of authorized user)</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>GET:</strong> https://strabospot.org/db/myDatasets</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>200:</strong> OK</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "datasets" : [
	{
		"datasettype" : "app",
		"self" : "https://strabospot.org/db/dataset/14573623121234",
		"id" : 14573623121234,
		"name" : "MyNewDatasetName",
		"modified_timestamp": 1457362312,
		"date": "03/08/2016"
	},
	{
		"datasettype" : "app",
		"self" : "https://strabospot.org/db/dataset/14573623121235",
		"id" : 14573623121235,
		"name" : "Second Dataset",
		"modified_timestamp": 1457362312,
		"date": "03/08/2016"
	},
	{
		"datasettype" : "app",
		"self" : "https://strabospot.org/db/dataset/14573623121236",
		"id" : 14573623121236,
		"name" : "ThirdDataset",
		"modified_timestamp": 1457362312,
		"date": "03/08/2016"
	},
	{
		"datasettype" : "app",
		"self" : "https://strabospot.org/db/dataset/14573623121237",
		"id" : 14573623121237,
		"name" : "FourthDataset",
		"modified_timestamp": 1457362312,
		"date": "03/08/2016"
	}
  ]
}
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="addspottodataset"></A>
	<h2 class="wsite-content-title">Add Spot to Dataset</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>POST:</strong> https://strabospot.org/db/datasetSpots/123456</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Where:</strong> 123456 is the dataset id.</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
	"id":14309503161234
}
</pre>
</div>
		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>201:</strong> Created</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "message" : "Spot 14309503161234 added to dataset 123456."
}
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="movespottodataset"></A>
	<h2 class="wsite-content-title">Move Spot to Dataset</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>POST:</strong> https://strabospot.org/db/moveSpotToDataset</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
	"spot_id": 17049235492898,
	"dataset_id": 17049235120641,
	"modified_timestamp": 1704923569635
}
</pre>
</div>

<div style="padding-left:30px;padding-bottom:10px;">

	Note: This endpoint moves the spot provided to the dataset provided and updates the modified timestamps<br>of the project(s), original dataset, new dataset, and spot using the modified_timestamp provided.
</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>201:</strong> Success</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "message": "Spot 17049235492898 moved to dataset 17049235120641."
}
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="getallspotsforgivendataset"></A>
	<h2 class="wsite-content-title">Get all Spots for Given Dataset</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>GET:</strong> https://strabospot.org/db/datasetSpots/123456</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>200:</strong> OK</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "type" : "FeatureCollection",
  "features" : [
	{
	  "geometry" : {
		"type" : "Polygon",
		"coordinates" : [[
	  [100,0], [101,0], [101,1], [100,1],[100,0]
		]]
	  },
	  "properties" : {
		"id" : 14309503161234,
		"date" : "2014-11-20",
		"name" : "Test 3",
		"self" : "https://strabospot.org/db/feature/1242"
	  },
	  "type" : "Feature"
	},
	{
	  "geometry" : {
		"type" : "Polygon",
		"coordinates" : [[
	  [100,0], [101,0], [101,1], [100,1],[100,0]
		]]
	  },
	  "properties" : {
		"id": 14309503161235,
		"date" : "2014-11-20",
		"name" : "Test 2",
		"self" : "https://strabospot.org/db/feature/1241"
	  },
	  "type" : "Feature"
	},
	{
	  "geometry" : {
		"type" : "Polygon",
		"coordinates" : [[
	  [100,0], [101,0], [101,1], [100,1],[100,0]
		]]
	  },
	  "properties" : {
		"id" : 14309503161236,
		"date" : "2014-11-20",
		"name" : "Test 1",
		"self" : "https://strabospot.org/db/feature/1240"
	  },
	  "type" : "Feature"
	},
	{
	  "geometry" : {
		"type" : "Polygon",
		"coordinates" : [[
	  [100,0], [101,0], [101,1], [100,1],[100,0]
		]]
	  },
	  "properties" : {
		"id" : 14309503161237,
		"date" : "2014-11-20",
		"name" : "Test Poly",
		"self" : "https://strabospot.org/db/feature/1239"
	  },
	  "type" : "Feature"
	}

  ]
}
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="getallfieldsforgivendataset"></A>
	<h2 class="wsite-content-title">Get all Fields that are populated for Given Dataset</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>GET:</strong> https://strabospot.org/db/datasetFields/123456</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>200:</strong> OK</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
[
  "id",
  "self",
  "name",
  "date",
  "time",
  "modified_timestamp",
  "strike",
  "dip_direction",
  "dip",
  "facing",
  "label",
  "trend",
  "plunge",
  "rake",
  "rake_calculated"
]
</pre>

		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Optional:</strong> An optional point/line/polygon can be added to this request
														to limit the output to a specific feature type, e.g., <br>https://strabospot.org/db/datasetFields/123456/point
														<br>or<br>https://strabospot.org/db/datasetFields/123456/polygon</code></span>
				</li>

			</ul>
		</div>

</div>

	</div>

<!-- ******************************************************************************** -->

	<A name="deleteallspotsforgivendataset"></A>
	<h2 class="wsite-content-title">Delete All Spots for Given Dataset (removes linking, but spots remain intact)</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>DELETE:</strong> https://strabospot.org/db/datasetSpots/123456</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>204:</strong> No Content</code></span>
				</li>

			</ul>
		</div>
	</div>

<!-- ******************************************************************************** -->

<!-- ******************************************************************************** -->

<A name="projectFunctions"></A>

	<A name="createproject"></A>
	<h2 class="wsite-content-title">Create Project</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>POST:</strong> https://strabospot.org/db/project</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "id": 14573774551234,
  "date": "03/08/2016",
  "modified_timestamp": 1457377455,
  "description": {
	"project_name": "My Project Name",
	"start_date": "startdate",
	"end_date": "enddate",
	"purpose_of_study": "purposeofstudy",
	"other_team_members": "otherteammembers",
	"area_of_interest": "areaofinterest",
	"spot_prefix": "spotprefixlabel",
	"starting_number_for_spot": "startingnumberforspots",
	"sample_prefix": "sampleprefixlabel",
	"instruments": "instrumentsused",
	"gps_datum": "gpsdatum",
	"magnetic_declination": "magneticdeclination",
	"Notes": "notes"
  },
  "daily_setup": {
	"foo": "foo"
  },
  "rock_units": [
	{
	  "unit_label_abbreviation": "unitlabel",
	  "map_unit_name": "unitorformationname",
	  "member_name": "membername",
	  "submember_name": "submembername",
	  "absolute_age_of_geologic_unit": "absoluteageofgeologicunit",
	  "age_uncertainty": "ageuncertainty",
	  "group_unit_type": "groupunittype",
	  "rock_type": "rocktype",
	  "sediment_type": "sedimenttype",
	  "other_sediment_type": "othersedimenttype",
	  "sedimentary_rock_type": "sedimentaryrocktype",
	  "igneous_rock_class": "igneousrockclass",
	  "volcanic_rock_type": "volcanicrocktype",
	  "other_volcanic_rock_type": "othervolcanicrocktype",
	  "plutonic_rock_types": "plutonicrocktypes",
	  "other_plutonic_rock_type": "otherplutonicrocktypes",
	  "metamorphic_rock_types": "metamorphicrocktypes",
	  "other_metamorphic_rock_type": "othermetamorphicrocktype",
	  "group_geologic_age": "groupgeologicage",
	  "epoch": "epoch",
	  "other_epoch": "otherepoch",
	  "period": "period",
	  "era": "era",
	  "eon": "eon",
	  "age_modifier": "agemodifier",
	  "description": "description",
	  "Notes": "notes"
	},
	{
	  "unit_label_abbreviation": "unitlabel",
	  "map_unit_name": "unitorformationname",
	  "member_name": "membername",
	  "submember_name": "submembername",
	  "absolute_age_of_geologic_unit": "absoluteageofgeologicunit",
	  "age_uncertainty": "ageuncertainty",
	  "group_unit_type": "groupunittype",
	  "rock_type": "rocktype",
	  "sediment_type": "sedimenttype",
	  "other_sediment_type": "othersedimenttype",
	  "sedimentary_rock_type": "sedimentaryrocktype",
	  "igneous_rock_class": "igneousrockclass",
	  "volcanic_rock_type": "volcanicrocktype",
	  "other_volcanic_rock_type": "othervolcanicrocktype",
	  "plutonic_rock_types": "plutonicrocktypes",
	  "other_plutonic_rock_type": "otherplutonicrocktypes",
	  "metamorphic_rock_types": "metamorphicrocktypes",
	  "other_metamorphic_rock_type": "othermetamorphicrocktype",
	  "group_geologic_age": "groupgeologicage",
	  "epoch": "epoch",
	  "other_epoch": "otherepoch",
	  "period": "period",
	  "era": "era",
	  "eon": "eon",
	  "age_modifier": "agemodifier",
	  "description": "description",
	  "Notes": "notes"
	}
  ],
  "preferences": {
	"orientation": false,
	"_3dstructures": false,
	"images": false,
	"sample": false,
	"inferences": false,
	"nesting": false,
	"right_hand_rule": false,
	"drop_down_to_finish": false,
	"student_mode": false
  }
}
</pre>
</div>
		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>201:</strong> Created</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "description": {
	"project_name": "My Project Name",
	"start_date": "startdate",
	"end_date": "enddate",
	"purpose_of_study": "purposeofstudy",
	"other_team_members": "otherteammembers",
	"area_of_interest": "areaofinterest",
	"spot_prefix": "spotprefixlabel",
	"starting_number_for_spot": "startingnumberforspots",
	"sample_prefix": "sampleprefixlabel",
	"instruments": "instrumentsused",
	"gps_datum": "gpsdatum",
	"magnetic_declination": "magneticdeclination",
	"Notes": "notes"
  },
  "daily_setup": {
	"foo": "foo"
  },
  "rock_units": [
	{
	  "unit_label_abbreviation": "unitlabel",
	  "map_unit_name": "unitorformationname",
	  "member_name": "membername",
	  "submember_name": "submembername",
	  "absolute_age_of_geologic_unit": "absoluteageofgeologicunit",
	  "age_uncertainty": "ageuncertainty",
	  "group_unit_type": "groupunittype",
	  "rock_type": "rocktype",
	  "sediment_type": "sedimenttype",
	  "other_sediment_type": "othersedimenttype",
	  "sedimentary_rock_type": "sedimentaryrocktype",
	  "igneous_rock_class": "igneousrockclass",
	  "volcanic_rock_type": "volcanicrocktype",
	  "other_volcanic_rock_type": "othervolcanicrocktype",
	  "plutonic_rock_types": "plutonicrocktypes",
	  "other_plutonic_rock_type": "otherplutonicrocktypes",
	  "metamorphic_rock_types": "metamorphicrocktypes",
	  "other_metamorphic_rock_type": "othermetamorphicrocktype",
	  "group_geologic_age": "groupgeologicage",
	  "epoch": "epoch",
	  "other_epoch": "otherepoch",
	  "period": "period",
	  "era": "era",
	  "eon": "eon",
	  "age_modifier": "agemodifier",
	  "description": "description",
	  "Notes": "notes"
	},
	{
	  "unit_label_abbreviation": "unitlabel",
	  "map_unit_name": "unitorformationname",
	  "member_name": "membername",
	  "submember_name": "submembername",
	  "absolute_age_of_geologic_unit": "absoluteageofgeologicunit",
	  "age_uncertainty": "ageuncertainty",
	  "group_unit_type": "groupunittype",
	  "rock_type": "rocktype",
	  "sediment_type": "sedimenttype",
	  "other_sediment_type": "othersedimenttype",
	  "sedimentary_rock_type": "sedimentaryrocktype",
	  "igneous_rock_class": "igneousrockclass",
	  "volcanic_rock_type": "volcanicrocktype",
	  "other_volcanic_rock_type": "othervolcanicrocktype",
	  "plutonic_rock_types": "plutonicrocktypes",
	  "other_plutonic_rock_type": "otherplutonicrocktypes",
	  "metamorphic_rock_types": "metamorphicrocktypes",
	  "other_metamorphic_rock_type": "othermetamorphicrocktype",
	  "group_geologic_age": "groupgeologicage",
	  "epoch": "epoch",
	  "other_epoch": "otherepoch",
	  "period": "period",
	  "era": "era",
	  "eon": "eon",
	  "age_modifier": "agemodifier",
	  "description": "description",
	  "Notes": "notes"
	}
  ],
  "preferences": {
	"orientation": false,
	"_3dstructures": false,
	"images": false,
	"sample": false,
	"inferences": false,
	"nesting": false,
	"right_hand_rule": false,
	"drop_down_to_finish": false,
	"student_mode": false
  },
  "projecttype": "app",
  "self": "https://strabospot.org/db/project/14573774551234",
  "id": 14573774551234,
  "date": "03/08/2016",
  "modified_timestamp": 1457377455
}

</pre>
</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="updateproject"></A>
	<h2 class="wsite-content-title">Update Project</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>POST:</strong> https://strabospot.org/db/project/14573774551234</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Warning:</strong> All attributes will be overwritten with provided JSON values.</code></span>
				</li>
			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "description": {
	"project_name": "My New Project Name",
	"start_date": "startdate",
	"end_date": "enddate",
	"purpose_of_study": "purposeofstudy",
	"other_team_members": "otherteammembers",
	"area_of_interest": "areaofinterest",
	"spot_prefix": "spotprefixlabel",
	"starting_number_for_spot": "startingnumberforspots",
	"sample_prefix": "sampleprefixlabel",
	"instruments": "instrumentsused",
	"gps_datum": "gpsdatum",
	"magnetic_declination": "magneticdeclination",
	"Notes": "notes"
  },
  "daily_setup": {
	"foo": "foo"
  },
  "rock_units": [
	{
	  "unit_label_abbreviation": "unitlabel",
	  "map_unit_name": "unitorformationname",
	  "member_name": "membername",
	  "submember_name": "submembername",
	  "absolute_age_of_geologic_unit": "absoluteageofgeologicunit",
	  "age_uncertainty": "ageuncertainty",
	  "group_unit_type": "groupunittype",
	  "rock_type": "rocktype",
	  "sediment_type": "sedimenttype",
	  "other_sediment_type": "othersedimenttype",
	  "sedimentary_rock_type": "sedimentaryrocktype",
	  "igneous_rock_class": "igneousrockclass",
	  "volcanic_rock_type": "volcanicrocktype",
	  "other_volcanic_rock_type": "othervolcanicrocktype",
	  "plutonic_rock_types": "plutonicrocktypes",
	  "other_plutonic_rock_type": "otherplutonicrocktypes",
	  "metamorphic_rock_types": "metamorphicrocktypes",
	  "other_metamorphic_rock_type": "othermetamorphicrocktype",
	  "group_geologic_age": "groupgeologicage",
	  "epoch": "epoch",
	  "other_epoch": "otherepoch",
	  "period": "period",
	  "era": "era",
	  "eon": "eon",
	  "age_modifier": "agemodifier",
	  "description": "description",
	  "Notes": "notes"
	},
	{
	  "unit_label_abbreviation": "unitlabel",
	  "map_unit_name": "unitorformationname",
	  "member_name": "membername",
	  "submember_name": "submembername",
	  "absolute_age_of_geologic_unit": "absoluteageofgeologicunit",
	  "age_uncertainty": "ageuncertainty",
	  "group_unit_type": "groupunittype",
	  "rock_type": "rocktype",
	  "sediment_type": "sedimenttype",
	  "other_sediment_type": "othersedimenttype",
	  "sedimentary_rock_type": "sedimentaryrocktype",
	  "igneous_rock_class": "igneousrockclass",
	  "volcanic_rock_type": "volcanicrocktype",
	  "other_volcanic_rock_type": "othervolcanicrocktype",
	  "plutonic_rock_types": "plutonicrocktypes",
	  "other_plutonic_rock_type": "otherplutonicrocktypes",
	  "metamorphic_rock_types": "metamorphicrocktypes",
	  "other_metamorphic_rock_type": "othermetamorphicrocktype",
	  "group_geologic_age": "groupgeologicage",
	  "epoch": "epoch",
	  "other_epoch": "otherepoch",
	  "period": "period",
	  "era": "era",
	  "eon": "eon",
	  "age_modifier": "agemodifier",
	  "description": "description",
	  "Notes": "notes"
	}
  ],
  "preferences": {
	"orientation": false,
	"_3dstructures": false,
	"images": false,
	"sample": false,
	"inferences": false,
	"nesting": false,
	"right_hand_rule": false,
	"drop_down_to_finish": false,
	"student_mode": false
  },
  "modified_timestamp": 1457377455
}
</pre>
</div>
		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>201:</strong> Created</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "description": {
	"project_name": "My New Project Name",
	"start_date": "startdate",
	"end_date": "enddate",
	"purpose_of_study": "purposeofstudy",
	"other_team_members": "otherteammembers",
	"area_of_interest": "areaofinterest",
	"spot_prefix": "spotprefixlabel",
	"starting_number_for_spot": "startingnumberforspots",
	"sample_prefix": "sampleprefixlabel",
	"instruments": "instrumentsused",
	"gps_datum": "gpsdatum",
	"magnetic_declination": "magneticdeclination",
	"Notes": "notes"
  },
  "daily_setup": {
	"foo": "foo"
  },
  "rock_units": [
	{
	  "unit_label_abbreviation": "unitlabel",
	  "map_unit_name": "unitorformationname",
	  "member_name": "membername",
	  "submember_name": "submembername",
	  "absolute_age_of_geologic_unit": "absoluteageofgeologicunit",
	  "age_uncertainty": "ageuncertainty",
	  "group_unit_type": "groupunittype",
	  "rock_type": "rocktype",
	  "sediment_type": "sedimenttype",
	  "other_sediment_type": "othersedimenttype",
	  "sedimentary_rock_type": "sedimentaryrocktype",
	  "igneous_rock_class": "igneousrockclass",
	  "volcanic_rock_type": "volcanicrocktype",
	  "other_volcanic_rock_type": "othervolcanicrocktype",
	  "plutonic_rock_types": "plutonicrocktypes",
	  "other_plutonic_rock_type": "otherplutonicrocktypes",
	  "metamorphic_rock_types": "metamorphicrocktypes",
	  "other_metamorphic_rock_type": "othermetamorphicrocktype",
	  "group_geologic_age": "groupgeologicage",
	  "epoch": "epoch",
	  "other_epoch": "otherepoch",
	  "period": "period",
	  "era": "era",
	  "eon": "eon",
	  "age_modifier": "agemodifier",
	  "description": "description",
	  "Notes": "notes"
	},
	{
	  "unit_label_abbreviation": "unitlabel",
	  "map_unit_name": "unitorformationname",
	  "member_name": "membername",
	  "submember_name": "submembername",
	  "absolute_age_of_geologic_unit": "absoluteageofgeologicunit",
	  "age_uncertainty": "ageuncertainty",
	  "group_unit_type": "groupunittype",
	  "rock_type": "rocktype",
	  "sediment_type": "sedimenttype",
	  "other_sediment_type": "othersedimenttype",
	  "sedimentary_rock_type": "sedimentaryrocktype",
	  "igneous_rock_class": "igneousrockclass",
	  "volcanic_rock_type": "volcanicrocktype",
	  "other_volcanic_rock_type": "othervolcanicrocktype",
	  "plutonic_rock_types": "plutonicrocktypes",
	  "other_plutonic_rock_type": "otherplutonicrocktypes",
	  "metamorphic_rock_types": "metamorphicrocktypes",
	  "other_metamorphic_rock_type": "othermetamorphicrocktype",
	  "group_geologic_age": "groupgeologicage",
	  "epoch": "epoch",
	  "other_epoch": "otherepoch",
	  "period": "period",
	  "era": "era",
	  "eon": "eon",
	  "age_modifier": "agemodifier",
	  "description": "description",
	  "Notes": "notes"
	}
  ],
  "preferences": {
	"orientation": false,
	"_3dstructures": false,
	"images": false,
	"sample": false,
	"inferences": false,
	"nesting": false,
	"right_hand_rule": false,
	"drop_down_to_finish": false,
	"student_mode": false
  },
  "projecttype": "app",
  "self": "https://strabospot.org/db/project/204006",
  "id": 14573774551234,
  "date": "03/08/2016",
  "modified_timestamp": 1457377455
}
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="getproject"></A>
	<h2 class="wsite-content-title">Get Project</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>GET:</strong> https://strabospot.org/db/project/14573774551234</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>200:</strong> OK</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "description": {
	"project_name": "My Project Name",
	"start_date": "startdate",
	"end_date": "enddate",
	"purpose_of_study": "purposeofstudy",
	"other_team_members": "otherteammembers",
	"area_of_interest": "areaofinterest",
	"spot_prefix": "spotprefixlabel",
	"starting_number_for_spot": "startingnumberforspots",
	"sample_prefix": "sampleprefixlabel",
	"instruments": "instrumentsused",
	"gps_datum": "gpsdatum",
	"magnetic_declination": "magneticdeclination",
	"Notes": "notes"
  },
  "daily_setup": {
	"foo": "foo"
  },
  "rock_units": [
	{
	  "unit_label_abbreviation": "unitlabel",
	  "map_unit_name": "unitorformationname",
	  "member_name": "membername",
	  "submember_name": "submembername",
	  "absolute_age_of_geologic_unit": "absoluteageofgeologicunit",
	  "age_uncertainty": "ageuncertainty",
	  "group_unit_type": "groupunittype",
	  "rock_type": "rocktype",
	  "sediment_type": "sedimenttype",
	  "other_sediment_type": "othersedimenttype",
	  "sedimentary_rock_type": "sedimentaryrocktype",
	  "igneous_rock_class": "igneousrockclass",
	  "volcanic_rock_type": "volcanicrocktype",
	  "other_volcanic_rock_type": "othervolcanicrocktype",
	  "plutonic_rock_types": "plutonicrocktypes",
	  "other_plutonic_rock_type": "otherplutonicrocktypes",
	  "metamorphic_rock_types": "metamorphicrocktypes",
	  "other_metamorphic_rock_type": "othermetamorphicrocktype",
	  "group_geologic_age": "groupgeologicage",
	  "epoch": "epoch",
	  "other_epoch": "otherepoch",
	  "period": "period",
	  "era": "era",
	  "eon": "eon",
	  "age_modifier": "agemodifier",
	  "description": "description",
	  "Notes": "notes"
	},
	{
	  "unit_label_abbreviation": "unitlabel",
	  "map_unit_name": "unitorformationname",
	  "member_name": "membername",
	  "submember_name": "submembername",
	  "absolute_age_of_geologic_unit": "absoluteageofgeologicunit",
	  "age_uncertainty": "ageuncertainty",
	  "group_unit_type": "groupunittype",
	  "rock_type": "rocktype",
	  "sediment_type": "sedimenttype",
	  "other_sediment_type": "othersedimenttype",
	  "sedimentary_rock_type": "sedimentaryrocktype",
	  "igneous_rock_class": "igneousrockclass",
	  "volcanic_rock_type": "volcanicrocktype",
	  "other_volcanic_rock_type": "othervolcanicrocktype",
	  "plutonic_rock_types": "plutonicrocktypes",
	  "other_plutonic_rock_type": "otherplutonicrocktypes",
	  "metamorphic_rock_types": "metamorphicrocktypes",
	  "other_metamorphic_rock_type": "othermetamorphicrocktype",
	  "group_geologic_age": "groupgeologicage",
	  "epoch": "epoch",
	  "other_epoch": "otherepoch",
	  "period": "period",
	  "era": "era",
	  "eon": "eon",
	  "age_modifier": "agemodifier",
	  "description": "description",
	  "Notes": "notes"
	}
  ],
  "preferences": {
	"orientation": false,
	"_3dstructures": false,
	"images": false,
	"sample": false,
	"inferences": false,
	"nesting": false,
	"right_hand_rule": false,
	"drop_down_to_finish": false,
	"student_mode": false
  },
  "projecttype": "app",
  "self": "https://strabospot.org/db/project/14573774551234",
  "id": 14573774551234,
  "date": "03/08/2016",
  "modified_timestamp": 1457377455
}
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="deleteproject"></A>
	<h2 class="wsite-content-title">Delete Project (also deletes datasets)</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>DELETE:</strong> https://strabospot.org/db/project/123456</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>204:</strong> No Content</code></span>
				</li>

			</ul>
		</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="getmyprojects"></A>
	<h2 class="wsite-content-title">Get My Projects (Return all projects of authorized user)</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>GET:</strong> https://strabospot.org/db/myProjects</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>200:</strong> OK</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "projects": [
	{
	  "projecttype": "app",
	  "name": "My Project Name",
	  "self": "https://strabospot.org/db/project/14573774551234",
	  "id": 14573774551234,
	  "date": "03/08/2016",
	  "modified_timestamp": 1457377455
	},
	{
	  "projecttype": "app",
	  "name": "new project name",
	  "self": "https://strabospot.org/db/project/14573774551235",
	  "id": 14573774551235,
	  "date": "03/08/2016",
	  "modified_timestamp": 1457377455
	}
  ]
}
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="adddatasettoproject"></A>
	<h2 class="wsite-content-title">Add Dataset to Project</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>POST:</strong> https://strabospot.org/db/projectDatasets/123456</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Where:</strong> 123456 is the project id.</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
	"id":654321
}
</pre>
</div>
		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>201:</strong> Created</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "message" : "Dataset 654321 added to Project 123456."
}
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="getalldatasetsforgivenproject"></A>
	<h2 class="wsite-content-title">Get all Datasets for Given Project</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>GET:</strong> https://strabospot.org/db/projectDatasets/123456</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>200:</strong> OK</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "datasets": [
	{
	  "datasettype": "app",
	  "name": "My Dataset Name",
	  "self": "https://strabospot.org/db/dataset/14573774551234",
	  "id": 14573774551234,
	  "date": "03/08/2016",
	  "modified_timestamp": 1457377455
	},
	{
	  "datasettype": "app",
	  "name": "My New Dataset Name",
	  "self": "https://strabospot.org/db/dataset/14573774551235",
	  "id": 14573774551235,
	  "date": "03/08/2016",
	  "modified_timestamp": 1457377455
	},
	{
	  "datasettype": "app",
	  "name": "Another Dataset Name",
	  "self": "https://strabospot.org/db/dataset/14573774551236",
	  "id": 14573774551236,
	  "date": "03/08/2016",
	  "modified_timestamp": 1457377455
	}
  ]
}
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="deletealldatasetsforgivenproject"></A>
	<h2 class="wsite-content-title">Delete All Datasets for Given Project (removes linking, but datasets remain intact)</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>DELETE:</strong> https://strabospot.org/db/projectDatasets/123456</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>204:</strong> No Content</code></span>
				</li>

			</ul>
		</div>
	</div>

<!-- ******************************************************************************** -->

<!-- ******************************************************************************** -->

	<A name="getallimagesforgivenproject"></A>
	<h2 class="wsite-content-title">Get all Images for Given Project</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>GET:</strong> https://strabospot.org/db/projectImages/123456</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>200:</strong> OK</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "images": [
	{
	  "id": 14761235708763,
	  "modified_timestamp": 1476123123
	},
	{
	  "id": 14761235603555,
	  "modified_timestamp": 1476123134
	},
	{
	  "id": 14759541269574,
	  "modified_timestamp": 1476123234
	},
	{
	  "id": 14759526677778,
	  "modified_timestamp": 1476123345
	},
	{
	  "id": 14759532056864,
	  "modified_timestamp": 1476123456
	}
  ]
}
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->

<!-- ******************************************************************************** -->

	<A name="deleteaccount"></A>
	<h2 class="wsite-content-title">Delete Account</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>DELETE:</strong> https://strabospot.org/db/account,</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>204:</strong> No Content</code></span>
				</li>

			</ul>
		</div>
	</div>

<!-- ******************************************************************************** -->

<A name="profileFunctions"></A>

	<A name="createprofile"></A>
	<h2 class="wsite-content-title">Create Profile</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>POST:</strong> https://strabospot.org/db/profile</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Note:</strong> "name" parameter is not accepted, as this information is gathered from the credentials table</code></span>
				</li>
			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "mapbox_access_token": "lkajsdflkj"
}
</pre>
</div>
		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>201:</strong> Created</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "name": "John Doe",
  "mapbox_access_token": "lkajsdflkj"
}
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="updateprofile"></A>
	<h2 class="wsite-content-title">Update Profile</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>POST:</strong> https://strabospot.org/db/profile</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Note:</strong> "name" parameter is not accepted, as this information is gathered from the credentials table</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Warning:</strong> All attributes will be overwritten with provided JSON values.</code></span>
				</li>
			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "mapbox_access_token": "lkajsdflkj"
}
</pre>
</div>
		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>201:</strong> Created</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "name": "John Doe",
  "mapbox_access_token": "lkajsdflkj"
}
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="getprofile"></A>
	<h2 class="wsite-content-title">Get Profile</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>GET:</strong> https://strabospot.org/db/profile</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>200:</strong> OK</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "name": "John Doe",
  "mapbox_access_token": "lkajsdflkj"
}
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="deleteprofile"></A>
	<h2 class="wsite-content-title">Delete Profile (doesn't delete name)</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>DELETE:</strong> https://strabospot.org/db/profile</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>204:</strong> No Content</code></span>
				</li>

			</ul>
		</div>
	</div>

<!-- ******************************************************************************** -->

<!-- ******************************************************************************** -->

<A name="profileImageFunctions"></A>
	<A name="uploadprofileimage"></A>
	<h2 class="wsite-content-title">Upload Profile Image</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>POST:</strong> https://strabospot.org/db/profileimage</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
This service accepts the following POST variable:

Variable Name: "image_file" - File containing image to be uploaded. (required)

Also, please note the the encapsulation type MUST be set to "multipart/form-data".
</pre>
</div>
		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>201:</strong> Created</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">

	Note: Due to the complexity of using multipart/form-data, a test page has been created to test this service. <br>This
	page can be found at: <a href="https://strabospot.org/profileimageupload">https://strabospot.org/profileimageupload</a>.
</div>

	</div>

<!-- ******************************************************************************** -->

	<A name="getprofileimage"></A>
	<h2 class="wsite-content-title">Get Profile Image</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>GET:</strong> https://strabospot.org/db/profileimage</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>200:</strong> OK</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> image/jpg</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
File content of image is returned.
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="deleteprofileimage"></A>
	<h2 class="wsite-content-title">Delete Profile Image</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>DELETE:</strong> https://strabospot.org/db/profileimage</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>204:</strong> No Content</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
		<div style="padding-left:30px;">
		<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "message": "Profile image deleted."
}
		</pre>
		</div>
	</div>

<A name="macroJWTFunctions"></A>

	<A name="uploadmacrojwt"></A>
	<h2 class="wsite-content-title">Upload Macrostrat JWT</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>POST:</strong> https://strabospot.org/db/macroJWT</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c"
}
</pre>
</div>
		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>201:</strong> Macrostrat JWT created</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c"
}
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->

<!-- ******************************************************************************** -->

	<A name="getmacrojwt"></A>
	<h2 class="wsite-content-title">Get Macrostrat JWT</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>GET:</strong> https://strabospot.org/db/macroJWT</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>200:</strong> OK</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c"
}
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="deletemacrojwt"></A>
	<h2 class="wsite-content-title">Delete Macrostrat JWT</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>DELETE:</strong> https://strabospot.org/db/macroJWT</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>204:</strong> No Content</code></span>
				</li>

			</ul>
		</div>
	</div>

<!-- ******************************************************************************** -->

<!-- ******************************************************************************** -->

<!-- Micro -->

<!-- ******************************************************************************** -->

	<A name="getmymicroprojects"></A>
	<h2 class="wsite-content-title">Get My Projects (Return all micro projects of authorized user)</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>GET:</strong> https://strabospot.org/microdb/myProjects</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>200:</strong> OK</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "projects": [
	{
	  "id": "16461495923123",
	  "name": "My Micro Project",
	  "self": "https://strabospot.org/microdb/project/16461495923123"
	},
	{
	  "id": "16470197040567",
	  "name": "Another Micro Project",
	  "self": "https://strabospot.org/microdb/project/16470197040567"
	}
  ]
}
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="getmicroproject"></A>
	<h2 class="wsite-content-title">Get Project (Return Micro project with given id)</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>GET:</strong> https://strabospot.org/microdb/project/16470197040123</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>200:</strong> OK</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> application/json; charset=UTF-8</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
{
  "count": 1,
  "projectDetails": {
	"id": "16470197040405",
	"name": "TestProject",
	"purposeOfStudy": "",
	"otherTeamMembers": "",
	"areaOfInterest": "",
	"gpsDatum": "",
	"magneticDeclination": "",
	"notes": "",
	"projectLocation": "local",
	"datasets": [
	  {
		"id": "16470197055239",
		"name": "Default",
		"samples": [
		  {
			"id": "16470197135869",
			"label": "Samp1",
			"sampleID": "Samp1",
			"mainSamplingPurpose": "",
			"sampleDescription": "",
			"materialType": "",
			"inplacenessOfSample": "",
			"orientedSample": "",
			"sampleSize": "",
			"degreeOfWeathering": "",
			"sampleNotes": "",
			"sampleType": "",
			"color": "",
			"lithology": "",
			"sampleUnit": "",
			"otherMaterialType": "",
			"sampleOrientationNotes": "",
			"micrographs": [
			  {
				"id": "16470197593827",
				"name": "MG1",
				"imageType": "Hs",
				"width": 2500,
				"height": 1855,
				"polish": false,
				"polishDescription": "",
				"notes": "",
				"scalePixelsPerCentimeter": 250.51012595621313,
				"mineralogy": {
				  "percentageCalculationMethod": "Qualitative Estimate",
				  "mineralogyMethod": "Optical",
				  "minerals": [
					{
					  "name": "Jadeite",
					  "operator": "eq",
					  "percentage": 44
					},
					{
					  "name": "Zircon",
					  "operator": "eq",
					  "percentage": 4
					}
				  ],
				  "notes": "Mineralogy Notes Here..."
				},
				"spots": [
				  {
					"id": "16470265571146",
					"name": "spot1",
					"labelColor": "0xffffffff",
					"showLabel": false,
					"color": "0xff8080ff",
					"date": "2022-03-11T19:22:32.562Z",
					"time": "2022-03-11T19:22:32.562Z",
					"notes": "",
					"modifiedTimestamp": 1647026552563,
					"geometryType": "polygon",
					"points": [
					  {
						"X": 460.0484261501211,
						"Y": 436.84422921711047
					  },
					  {
						"X": 639.6287328490719,
						"Y": 348.0629539951573
					  },
					  {
						"X": 827.2800645682001,
						"Y": 557.9096045197739
					  },
					  {
						"X": 589.1848264729621,
						"Y": 598.2647296206617
					  }
					],
					"extinctionMicrostructureInfo": {
					  "extinctionMicrostructures": [
						{
						  "phase": "Jadeite",
						  "dislocations": [
							{
							  "type": "Dislocation"
							}
						  ],
						  "subDislocations": [
							{
							  "type": "Edge"
							}
						  ],
						  "heterogeneousExtinctions": [
							{
							  "type": "Chessboard"
							}
						  ]
						},
						{
						  "phase": "Zircon",
						  "dislocations": [
							{
							  "type": "Dislocation"
							},
							{
							  "type": "Tangle or pile-up"
							}
						  ],
						  "subDislocations": [
							{
							  "type": "Edge"
							},
							{
							  "type": "Screw"
							},
							{
							  "type": "Unknown"
							}
						  ],
						  "heterogeneousExtinctions": [
							{
							  "type": "Patchy"
							},
							{
							  "type": "Undulose"
							},
							{
							  "type": "Chessboard"
							},
							{
							  "type": "Sweeping undulose"
							}
						  ],
						  "subGrainStructures": [
							{
							  "type": "Low-angle grain boundary"
							}
						  ],
						  "extinctionBands": [
							{
							  "type": "Wide extinction bands"
							},
							{
							  "type": "Fine extinction bands"
							},
							{
							  "type": "Localized extinction bands"
							}
						  ],
						  "subWideExtinctionBands": [
							{
							  "type": "Kink bands"
							},
							{
							  "type": "Deformation bands"
							}
						  ],
						  "subFineExtinctionBands": [
							{
							  "type": "Deformation lamellae"
							},
							{
							  "type": "Small wavelength undulatory extinction"
							}
						  ]
						}
					  ],
					  "notes": "spot ext notes"
					}
				  }
				],
				"orientationInfo": {
				  "orientationMethod": "unoriented"
				},
				"instrument": {
				  "instrumentType": "Scanning Electron Microscopy (SEM)",
				  "dataType": "Energy Dispersive X-ray Spectroscopy (EDS)",
				  "instrumentBrand": "Tescan",
				  "instrumentModel": "Lyra-3 Model GMH",
				  "university": "Texas A&M",
				  "laboratory": "Materials Characterization Facility",
				  "dataCollectionSoftware": "Aztec",
				  "dataCollectionSoftwareVersion": "",
				  "postProcessingSoftware": "Aztec",
				  "postProcessingSoftwareVersion": "",
				  "filamentType": "Fil Type 3",
				  "instrumentDetectors": [
					{
					  "detectorType": "EDS",
					  "detectorMake": "Oxford",
					  "detectorModel": "X- MaxN 50"
					},
					{
					  "detectorType": "Secondary Electron",
					  "detectorMake": "",
					  "detectorModel": ""
					},
					{
					  "detectorType": "Backscattered Electron",
					  "detectorMake": "",
					  "detectorModel": ""
					}
				  ],
				  "instrumentNotes": ""
				},
				"grainBoundaryInfo": {
				  "boundaries": [
					{
					  "typeOfBoundary": "grain",
					  "phase1": "Jadeite",
					  "phase2": "Zircon",
					  "morphologies": [
						{
						  "type": "Serrated"
						},
						{
						  "type": "Lobate"
						}
					  ],
					  "descriptors": [
						{
						  "type": "Filling/Decoration",
						  "subTypes": [
							{
							  "type": "Minor Phase"
							}
						  ]
						}
					  ]
					}
				  ],
				  "notes": "Some notes here..."
				},
				"extinctionMicrostructureInfo": {
				  "extinctionMicrostructures": [
					{
					  "phase": "Jadeite",
					  "dislocations": [
						{
						  "type": "Dislocation"
						},
						{
						  "type": "Tangle or pile-up"
						}
					  ],
					  "subDislocations": [
						{
						  "type": "Edge"
						},
						{
						  "type": "Screw"
						},
						{
						  "type": "Unknown"
						}
					  ],
					  "heterogeneousExtinctions": [
						{
						  "type": "Patchy"
						},
						{
						  "type": "Undulose"
						},
						{
						  "type": "Chessboard"
						},
						{
						  "type": "Sweeping undulose"
						}
					  ],
					  "subGrainStructures": [
						{
						  "type": "Low-angle grain boundary"
						}
					  ],
					  "extinctionBands": [
						{
						  "type": "Wide extinction bands"
						},
						{
						  "type": "Fine extinction bands"
						},
						{
						  "type": "Localized extinction bands"
						}
					  ],
					  "subWideExtinctionBands": [
						{
						  "type": "Kink bands"
						},
						{
						  "type": "Deformation bands"
						}
					  ],
					  "subFineExtinctionBands": [
						{
						  "type": "Deformation lamellae"
						},
						{
						  "type": "Small wavelength undulatory extinction"
						}
					  ]
					},
					{
					  "phase": "Jadeite",
					  "dislocations": [
						{
						  "type": "Dislocation"
						}
					  ]
					},
					{
					  "phase": "Zircon",
					  "dislocations": [
						{
						  "type": "Dislocation"
						},
						{
						  "type": "Tangle or pile-up"
						}
					  ],
					  "subDislocations": [
						{
						  "type": "Edge"
						},
						{
						  "type": "Screw"
						},
						{
						  "type": "Unknown"
						}
					  ]
					}
				  ],
				  "notes": "Some Ext Min Notes Here..."
				},
				"isMicroVisible": true,
				"imageURL": "https://strabospot.org/microdb/image/16470197593827"
			  }
			]
		  }
		]
	  }
	]
  }
}
</pre>
</div>
	</div>

<!-- ******************************************************************************** -->

	<A name="getmicroimage"></A>
	<h2 class="wsite-content-title">Get Micrograph Image</h2>
	<div style="padding-left:20px;padding-bottom:30px;">
		<span class="emphasis"><em>Example request</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>GET:</strong> https://strabospot.org/microdb/image/16470197593123</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Accept:</strong> application/json; charset=UTF-8</code></span>
				</li>
			</ul>
		</div>

		<span class="emphasis"><em>Example response</em></span>
		<div class="itemizedlist">
			<ul class="itemizedlist" style="list-style-type: disc; ">

				<li class="listitem">
					<span class="strong"><code class="literal"><strong>200:</strong> OK</code></span>
				</li>
				<li class="listitem">
					<span class="strong"><code class="literal"><strong>Content-Type:</strong> image/jpg</code></span>
				</li>

			</ul>
		</div>
<div style="padding-left:30px;">
<pre class="programlisting cm-s-default" data-lang="javascript">
File content of micrograph image is returned.
</pre>
</div>
	</div>

<!--
	<a href="#getmicroprojectcount">Get Project Count</a><br>
	<a href="#getmymicroprojects">Get My Projects</a><br>
	<a href="#getmicroproject">Get Project</a><br>
	<a href="#getmicroimage">Get Image</a><br>
-->

	</div>
</div>

</div>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include("includes/mfooter.php");

?>
