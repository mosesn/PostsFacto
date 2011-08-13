<?php 

genHeader();

if(!empty($_POST['type'])){
  $email=strip_tags($_POST['email']);
  $subject=strip_tags($_POST['heading']);
  $content=($_POST['content']);

  if(valid($email,$subject,$content)){
    require_once('recaptchalib.php');
    require_once ('secrets.php');

    $resp = recaptcha_check_answer ($privatekey,
				    $_SERVER["REMOTE_ADDR"],
				    $_POST["recaptcha_challenge_field"],
				    $_POST["recaptcha_response_field"]);

    if (!$resp->is_valid) {
      // What happens when the CAPTCHA was entered incorrectly
      notValidCaptcha($email,$subject,$content);
    } else {
      $data = explode("\\",$content);
      $cleaned = implode("",$data);
      $headers = "From: {$email}" . "\r\n" .
	"Reply-To: {$email}" . "\r\n" .
	"X-Mailer: PHP/" . phpversion();
      mail($email, $subject, $cleaned,$headers);
      sentNotice($email);
    }
  }
  else{
    notValid($email,$subject,$content);
  }
}
else{
  main();
}

function valid($email,$subject,$content){
  if(!emailValid($email) || empty($subject) || empty($content)){
    return false;
  }
  else{
    return true;
  }
}

function emailValid($emailVal){
  if(empty($emailVal)){
    return false;
  }

  $strArray=explode('@',$emailVal);

  if(count($strArray)!=2){
    return false;
  }
  else{
    return true;
  }
}

function genHeader(){
print<<<_HTML_
<!doctype html>
<html>
<head>
<title>PostsFacto</title>
<script type="text/javascript" src="jquery.js" ></script>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
_HTML_;
}

function main(){

print<<<_HTML_
<body>
<h1>POSTS<em>FACTO</em></h1>
<h4>Mail your past self</h4>
<form method="post" action="$_SERVER[PHP_SELF]">
Email:<br /> <input class="real" type="text" name="email" />
<br />
Subject Heading:<br /> <input class="real" type="text" name="heading" />
<br />
Content:<br /> <textarea rows="10" type="text" name="content"> </textarea>
<br />
_HTML_;
require_once('recaptchalib.php');
require_once('secrets.php');
  echo recaptcha_get_html($publickey);
print<<<_HTML_
<input class="submit" type="submit" name="type" value="Submit">
</form>
<div id="content">
<p><a href="about.html">about</a>*<a href="tos.html">terms</a>*<a href="mailto:admin@postsfacto.com">contact</a></p>
</div>
</body>
</html>
_HTML_;
}

function sentNotice($email){

print<<<_HTML_
<body>
<h1>POSTS<em>FACTO</em></h1>
<h4>Mail your past self</h4>
<p>Sent email to $email.</p>
<form method="post" action="$_SERVER[PHP_SELF]">
Email:<br /> <input class="real" type="text" name="email" />
<br />
Subject Heading:<br /> <input class="real" type="text" name="heading" />
<br />
Content:<br /> <textarea rows="5" type="text" name="content"> </textarea>
<br />
_HTML_;
require_once('recaptchalib.php');
require_once('secrets.php');

  echo recaptcha_get_html($publickey);
print<<<_HTML_
<input class="submit" type="submit" name="type" value="Submit">
</form>
<div id="content">
<p><a href="about.html">about</a>*<a href="tos.html">terms</a>*<a href="mailto:admin@postsfacto.com">contact</a></p>
</div>
</body>
</html>
_HTML_;
}

function notValid($email,$subject,$content){

print<<<_HTML_
<body>
<h1>POSTS<em>FACTO</em></h1>
<h4>Mail your past self</h4>
<p>You're missing some content!</p>
<form method="post" action="$_SERVER[PHP_SELF]">
Email:<br /> <input class="real" type="text" name="email" value="{$email}" />
<br />
Subject Heading:<br /> <input class="real" type="text" name="heading" value="{$subject}" />
<br />
Content:<br /> <textarea rows="5" type="text" name="content" >$content</textarea>
<br />
_HTML_;
require_once('recaptchalib.php');
require_once ('secrets.php');
  echo recaptcha_get_html($publickey);
print<<<_HTML_
<input class="submit" type="submit" name="type" value="Submit">
</form>
<div id="content">
<p><a href="about.html">about</a>*<a href="tos.html">terms</a>*<a href="mailto:admin@postsfacto.com">contact</a></p>
</div>
</body>
</html>
_HTML_;
}

function notValidCaptcha($email,$subject,$content){

print<<<_HTML_
<body>
<h1>POSTS<em>FACTO</em></h1>
<p>Are you human?</p>
<form method="post" action="$_SERVER[PHP_SELF]">
Email:<br /> <input class="real" type="text" name="email" value="{$email}" />
<br />
Subject Heading:<br /> <input class="real" type="text" name="heading" value="{$subject}" />
<br />
Content:<br /> <textarea rows="5" type="text" name="content" >$content</textarea>
<br />
_HTML_;
require_once('recaptchalib.php');
require_once ('secrets.php');
  echo recaptcha_get_html($publickey);
print<<<_HTML_
<input class="submit" type="submit" name="type" value="Submit">
</form>
<div id="content">
<p><a href="about.html">about</a>*<a href="tos.html">terms</a>*<a href="mailto:admin@postsfacto.com">contact</a></p>
</div>
</body>
</html>
_HTML_;
}
?>