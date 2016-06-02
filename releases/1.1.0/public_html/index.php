<?php
date_timezone_set('Australia/Victoria');

error_reporting(E_ERROR);
ini_set('display_errors', true);

include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'functions.php';

$passed = false;
$error = '';
session_start();
if (!isset($_SESSION['nosubmit']))
	$_SESSION['nosubmit'] = false;
$pu = parse_url($_SERVER['REQUEST_URI']);
$source = (isset($_SERVER['HTTPS'])?'https://':'http://').strtolower($_SERVER['HTTP_HOST']).$pu['path'];
if ($_SERVER['REQUEST_METHOD']=='POST') {
	foreach($_POST as $key=>$value)
		$_POST[$key] = trim($value);
	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$error = "email address isn't valid, use a valid email address!";
	} elseif (empty($_POST['email']) || empty($_POST['subject']) || empty($_POST['reason']) || empty($_POST['name'])) {
		$error = "all fields are required and must be filled in!";
	}
	if (empty($error))
		$passed=true;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="content-language" content="en" />
<meta name="robots" content="no index, no follow" />
<meta name="rating" content="general" />
<meta name="author" content="Robin Spratt (President)" />
<meta name="copyright" content="© <?php echo date('Y'); ?> - Chronolabs Cooperative" />
<meta name="generator" content="PHP" />
<link rel="shortcut icon" type="image/ico" href="<?php echo $source; ?>favicon.ico" />
<link rel="icon" type="image/png" href="<?php echo $source; ?>icon.png" />
<link rel="apple-touch-icon" href="<?php echo $source; ?>apple-touch-icon-56x56.png">
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo $source; ?>apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo $source; ?>apple-touch-icon-114x114.png">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Why?</title>
<link type="text/css" rel="stylesheet" href="<?php echo $source; ?>css/style.css" />
<!-- AddThis Smart Layers BEGIN -->
<!-- Go to http://www.addthis.com/get/smart-layers to customize -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-50f9a1c208996c1d"></script>
<script type="text/javascript">
  addthis.layers({
	'theme' : 'transparent',
	'share' : {
	  'position' : 'right',
	  'numPreferredServices' : 6
	}, 
	'follow' : {
	  'services' : [
		{'service': 'facebook', 'id': 'chronolabs'},
		{'service': 'google_follow', 'id': '111267413375420332318'}
	  ]
	},  
	'whatsnext' : {},  
	'recommended' : {
	  'title': 'Recommended for you:'
	} 
  });
</script>
</head>
<body>
<?php 
	if ($_SESSION['nosubmit'] == false && $passed == false) {
?>
<div id="question">Why?</div>
<div id="form">
	<form method="post" name="question">
		<table style="vertical-align: middle; align: center;">
			<?php if (!empty($error)) { ?>
			<tr style="padding: 11px;">
				<td colspan="2" style="text-align: center;">Error: <?php echo $error; ?></td>
			</tr>
			<?php } ?>
			<tr>
				<td  style="vertical-align: middle; align: center;"><label for="name">Full-name:~</label></td>
				<td  style="vertical-align: middle; align: center;"><input name="name" id="name" type="text" size="46" maxlen="198" value="<?php echo $_POST['name']; ?>"/></td>
			</tr>
			<tr>
				<td  style="vertical-align: middle; align: center;"><label for="email">Email:~</label></td>
				<td  style="vertical-align: middle; align: center;"><input name="email" id="email" type="text" size="46" maxlen="198" value="<?php echo $_POST['email']; ?>" /><br /><div style="font-size: 0.86em;"><em>Make sure you include a contactable email address so you recieve your <strong>Why report!</strong></em></div></td>
			</tr>
			<tr>
				<td  style="vertical-align: middle; align: center;"><label>Partnering:~</label></td>
				<td  style="vertical-align: middle; align: center;"><label for="coupling">Coupling:</label>&nbsp;<select id="coupling" name="coupling"><option <?php if ($_POST['coupling']=="Married") { echo 'selected="selected" '; } ?>value="Married">Married</option><option <?php if ($_POST['coupling']=="Single/Defaco") { echo 'selected="selected" '; } ?>value="Single/Defaco">Single/Defaco</option><option <?php if ($_POST['coupling']=="Open") { echo 'selected="selected" '; } ?>value="Open">Open</option><option <?php if ($_POST['coupling']=="Closed") { echo 'selected="selected" '; } ?>value="Closed">Closed</option><option <?php if ($_POST['coupling']=="Unspecified"||!isset($_POST['coupling'])) { echo 'selected="selected" '; } ?>value="Unspecified">Unspecified</option></select>&nbsp;&nbsp;&nbsp;<label for="coupling-years">Last/Current in Years:</label>&nbsp;<input name="coupling-years" id="coupling-years" type="text" size="4" maxlen="3" value="<?php echo $_POST['coupling-years']; ?>" /></td>
			</tr>
			<tr>
				<td  style="vertical-align: middle; align: center;"><label>Personal:~</label></td>
				<td  style="vertical-align: middle; align: center;"><label for="gender">Gender:</label>&nbsp;<select id="gender" name="gender"><option <?php if ($_POST['gender']=="Male") { echo 'selected="selected" '; } ?>value="Male">Male</option><option <?php if ($_POST['gender']=="Female") { echo 'selected="selected" '; } ?>value="Female">Female</option><option <?php if ($_POST['gender']=="Bisexual") { echo 'selected="selected" '; } ?>value="Bisexual">Bisexual</option><option <?php if ($_POST['gender']=="Transgender") { echo 'selected="selected" '; } ?>value="Transgender">Transgender</option><option <?php if ($_POST['gender']=="Unspecified"||!isset($_POST['gender'])) { echo 'selected="selected" '; } ?>value="Unspecified">Unspecified</option></select>&nbsp;&nbsp;&nbsp;<label for="age">Age:</label>&nbsp;<input name="age" id="age" type="text" size="4" maxlen="3" value="<?php echo $_POST['age']; ?>" /></td>
			</tr>
			<tr>
				<td  style="vertical-align: middle; align: center;"><label>Family'ing:~</label></td>
				<td  style="vertical-align: middle; align: center;">&nbsp;<label for="children">Children:</label>&nbsp;<input name="children" id="children" type="text" size="4" maxlen="3" value="<?php echo $_POST['children']; ?>" />&nbsp;&nbsp;&nbsp;&nbsp;<label for="sibblings">Sibblings:</label>&nbsp;<input name="sibblings" id="sibblings" type="text" size="4" maxlen="3" value="<?php echo $_POST['sibblings']; ?>" /></td>
			</tr>
			<tr>
				<td  style="vertical-align: middle; align: center;"><label>Nearest:~</label></td>
				<td  style="vertical-align: middle; align: center;"><select id="place" name="place"><?php 
					if (!isset($_SESSION['places'])) {
						$approx = json_decode(getURL("http://lookups.labs.coop/v1/country/".getIP(true)."/json.api"), true);
						$_SESSION['places'] = json_decode(getURL("http://places.labs.coop/v1/nearby/".$approx['location']['coordinates']['latitude']."/".$approx['location']['coordinates']['longitude']."/99/json.api"), true);
					}
					$locations = array();
					foreach($_SESSION['places']['results']['places'] as $countrykey => $areas) {
						foreach($_SESSION['places']['results']['countries'] as $countrykeyb => $country) {
							if ($countrykey==$countrykeyb)
							{
								$countryname = $country['Country'];
							}
						}
						foreach($areas as $areakey => $area) {
							echo "<option ".(isset($_POST['place']) && $_POST['place'] == $areakey?"selected='selected' ":'')."value='".$areakey."'>".$area['RegionName'] . ' ('.$countryname.')'.'</option>'; 
							$locations[$areakey] = $area;
							$locations[$areakey]['country'] = $countryname;
						}
					}
					echo "</select></td>" ?>
			</tr>
			<tr>
				<td  style="vertical-align: middle; align: center;"><label for="subject">Subject:~</label></td>
				<td  style="vertical-align: middle; align: center;"><input name="subject" id="subject" type="text" size="46" maxlen="255" value="<?php echo $_POST['subject']; ?>" /></td>
			</tr>
			<tr>
				<td  style="vertical-align: middle; align: center;"><label for="reason">Reasons':~</label></td>
				<td  style="vertical-align: middle; align: center;"><textarea name="reason" id="reason" cols="45" rows="9"><?php echo $_POST['reason']; ?></textarea></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: center;"><input name="submit" id="submit" type="submit" value="Submit & Save" /></td>
			</tr>
		</table>
<?php 
foreach ($locations as $key => $location) { 
	if (is_array($location)) {
		foreach ($location as $keyb => $place) {
			if (is_array($place)) {
				foreach ($place as $keyc => $area) {
					if (!is_array($area)) {
						echo "\n\t\t<input type='hidden' name='locations[".$key."][".$keyb."][".$keyc."]' id='locations[".$key."][".$keyb."][".$keyc."]' value='" . $area . "' />";
					} else {
						foreach ($area as $keyd => $spot) {
							echo "\n\t\t<input type='hidden' name='locations[".$key."][".$keyb."][".$keyc."][".$keyd."]' id='locations[".$key."][".$keyb."][".$keyc."][".$keyd."]' value='" . $spot . "' />";
						}
					}
				}
			} else {
				echo "\n\t\t<input type='hidden' name='locations[".$key."][".$keyb."]' id='locations[".$key."][".$keyb."]' value='" . $place . "' />";
			}
		}
	} else {
		echo "\n\t\t<input type='hidden' name='locations[".$key."]' id='locations[".$key."]' value='" . $location . "' />";
	}
}?>
		<input name="started" id="started" type="hidden" value="<?php echo (isset($_POST['started'])?$_POST['started']:time()); ?>" />
	</form>
</div>
<?php 
	} else {
		if ($_SESSION['nosubmit'] == false) 
		{
			include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'whymailer.php';
			$mailer = new WhyMailer($_POST['email'], $_POST['name']);
			$mailer->sendMail(	array('email'=>$_POST['email'], 'name'=>$_POST['name']), 
								array('email'=>'robin@labs.coop', 'name'=>'Robin Spratt (President)'),
								array('email'=>'robinspratt@yahoo.com.au', 'name'=>'Robin Spratt (Esteemed)'),
								date('D, d-m-Y') . ' : Your Reason Why!',
								getEmailBody($_POST, false),
								$_POST,
								dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'xml');
		}
?>
<div id="question">Why?</div>
<div id="canvas">
	<p>This is 25 Subjects and the length of the response selected from our pool of answers for you to view! <?php if ($_SESSION['nosubmit'] == false) { ?>An email was sent to the address you put in with your result and an XML File of the results attached, please keep this for your records. Thanks.<?php } ?></p>
	<table width="100%">
		<tr style="border-bottom: 3px #000 solid;">
			<th>Name</th>
			<th>Response Length</th>
			<th>What Day</th>
			<th>Subject offered for why?</th>
		</tr>
		<?php foreach(randGetReasons(25,dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'xml') as $key => $values) { ?>
		<tr style="border-bottom: 1px #000 dashed;">
			<td style="text-align: center;"><?php echo $values['name']; ?></td>
			<td style="text-align: center;"><?php echo $values['letters']; ?></td>
			<td style="text-align: center;"><?php echo date('Y-m-d', $values['when']); ?></td>
			<td style="text-align: center;"><?php echo $values['subject']; ?></td>
		</tr>
		<?php } ?>
	</table>
</div>
<?php
		$_SESSION['nosubmit'] = true; 
	}
?>
<div id="footer"><strong>Copyright &copy <?php echo date('Y'); ?> - Robin Spratt</strong><br/><strong><a href="/docs">System Documentation</a></strong><br /><br/><strong><a href="https://chrono.labs.coop">Chronolabs Cooperative</a></strong><br/><a href="https://web.labs.coop/public/legal/privacy-and-mooching-policy/22,3.html">Privacy & Mooching Policy</a>&nbsp;|&nbsp;<a href="https://web.labs.coop/public/legal/general-terms-and-conditions/12,3.html">Terms &amp; Conditions</a>&nbsp;|&nbsp;<a href="https://web.labs.coop/public/legal/end-user-license/11,3.html">End User License</a></div>
</body>
</html>
