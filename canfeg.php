<?php
session_start ( );

$mysql_host = 'localhost';
$mysql_user = 'nwn_quotes';
$mysql_pass = 'seTeGh4!3%_sH';
$mysql_db = 'nwn_quotes';
define ( PREFIX, 'quo' );

define ( TITLE, "Цитатник" );
define ( NOTE, "Все что вы скажете будет выдернуто из контекста и искажено!" );
define ( ROOT_PATH, "/" );

if ( ! @mysql_connect ( $mysql_host, $mysql_user, $mysql_pass ) )
{

  error ( 'Не удалось подключиться к mysql.' , 1 );
}

if ( ! @mysql_select_db ( $mysql_db ) )
{
  error( 'Не удалось выбрать базу данных.', 1 );
}

mysql_query('set names cp1251');




function error ( $text, $fatal=0 )
{
  echo "
  <div id='error'>
    $text
  </div>
  ";

  if ( $fatal )
  {
    die();
  }

}





function quote_filter ( $text )
{

  $text = trim ( $text );

  while ( false !== strpos ( $text, "\r\n\r\n\r\n" ) )
  {
    $text = str_replace ( "\r\n\r\n\r\n", "\r\n\r\n", $text );
  }

  $filter_array = array ( "<" => "&lt;", ">" => "&gt;", '"' => "&quot;", "'" => "&#39;", "&" => "&amp;" );
  $text = strtr ( $text, $filter_array );

  $text = nl2br ( $text );

  return $text;
  
}


function redirect ( $filename, $text='', $timeout=0 )
{
  $timeout = 0;
  die ( "<div id='redirect'>" . $text . "<meta http-equiv='refresh' content='$timeout;url=$filename' /></div>" );
}


function add_quote ( $text, $id=false, $rate=false, $approved=false, $time=false, $add_text=false, $admin=false, $comments = false )
{
  
  $quote = '';
  $title = '';

  if ( $add_text !== false )
  {
    $title .= " $add_text ";
  }

  if ( $id !== false )
  {
    $title .= "<a href='". ROOT_PATH . "$id'>#$id</a> ";
  }

  #  if ( $rate !== false )
  if ( false )
  {
    $title .= " [ <a href='". ROOT_PATH . "$id/rulez'>+</a> $rate <a href='". ROOT_PATH . "$id/sux'>&minus;</a> ] ";
  }
  
  if ( $approved !== false )
  {
    if ( is_numeric ( $approved ) )
    {
      $query_approved_by = "select usr_name from " . PREFIX . "_usrs where id=" . $approved;
      $mysql_query_approved_by = query ( $query_approved_by );

      $approved_str = mysql_result ( $mysql_query_approved_by, 0, 0 );
      $title .= " approved by $approved_str";

      mysql_free_result ( $mysql_query_approved_by );

    }
    else
    {
      $title .= " added by $approved";
    }
  }

  if ( $time !== false )
  {
    $title .= ", " . ndate ( $time );
  }

  if ( is_admin( ) )
  {
    $title .= " ... 
    ";
    if ( ! is_numeric ( $approved ) )
    {
      $title .= "<a href='". ROOT_PATH . "a?id=$id&act=approve'>Approve</a> | ";
    }
    if ( ! is_numeric ( $approved ) or is_root ( ) )
    {
      $title .= "
      <a href='". ROOT_PATH . "a?id=$id&act=delete' onClick='return confirm(\"Sure?\");'>Delete</a> |
      <a href='". ROOT_PATH . "a?id=$id&act=edit'>Edit</a> ";
    }
    
  }
  

  $quote = "
  <div class='single_quote'>
    <div class='title'>
      <div class='title-text'>$title</div>
    </div>
    <div class='quote'>
      $text
    </div>";
    
    if ($comments) {
    	$quote .= '
<!-- Put this script tag to the <head> of your page -->
<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?47"></script>

<script type="text/javascript">
  VK.init({apiId: 2787238, onlyWidgets: true});
</script>

<div id="vk_comments"></div>
<script type="text/javascript">
VK.Widgets.Comments("vk_comments", {limit: 20, width: "786", attach: "*"});
</script>';
    } 
    
    $quote .= '</div>';
  
  return $quote;

}

function ndate ( $timestamp = false )
{
  $return = ( $timestamp ) ? date( "d.n.Y G:i", $timestamp ) : date("d-n-Y G:i");
  return $return;
}


function head ( )
{
  

  //$css = ( isset ( $_COOKIE['css'] ) and file_exists ( "./css/" . $_COOKIE['css'] . ".css" ) ) ? $_COOKIE['css'] : 'default';
  $css = 'default';
  $text = "
  <html>
  <head>
  <meta http-equiv='Content-Type' content='text/html; charset=Windows-1251' />
  <title>Цитатник игры Neverwinter Nights</title>
  <meta name='verify-reformal' content='247a4ffcfc5d1f7d11038353' />
  <meta name='description' content='Смешные цитаты, рассказы и истории игроков русского комьюнити игры Neverwinter Nights' />
  <meta name='keywords' content='nwn, цитаты nwn, цитатник nwn, истории nwn' />
  <link rel='stylesheet' type='text/css' href='/static/default.css'>
  <script type='text/javascript'>
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28774393-1']);
  _gaq.push(['_setDomainName', 'nwn.name']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
  </head>
  <body>
  <div id='wrap'>
	
	<table border='0' id='mid_table'><tr id='mid_tr'><td id='left-border'>&nbsp;</td><td id='mid'>
    
    	<div id='header'>
    		<a href='/'><img src='/static/header.png' width='780px' /></a>
    	</div>
    	
  
    " . navigation ( 1 ) . "
    
  ";

  return $text;

}

function footer ( )
{
  
  $text = navigation() . "
  </td><td id='right-border'>&nbsp;</td></tr></table>
  </div>
  " . '
<script type="text/javascript">
    var reformalOptions = {
        project_id: 54842,
        project_host: "feedback.nwn.name",
        tab_orientation: "left",
        tab_indent: 300,
        tab_bg_color: "#111111",
        tab_border_color: "#c49321",
        tab_image_url: "http://tab.reformal.ru/0J7QsdGA0LDRgtC90LDRjyDRgdCy0Y%252FQt9GM/c49321/c014d2f30c67f75a81c1f8cd088661fd/left/1/tab.png",
        tab_border_width: 2
    };
    
    (function() {
        var script = document.createElement(\'script\');
        script.type = \'text/javascript\'; script.async = true;
        script.src = (\'https:\' == document.location.protocol ? \'https://\' : \'http://\') + \'media.reformal.ru/widgets/v2/reformal.js\';
        document.getElementsByTagName(\'head\')[0].appendChild(script);
    })();
</script>

' . "

  </body>
  </html>";
  
  return $text;
  
}  

/**
<div id='design' style='margin:0px;padding:0px;'>
      <form name='change_design' id='design' action='" . ROOT_PATH . "' method='get'>
      <select name='design' onchange='change_design.submit();'>
      <option value='default' ";
      
      if ( $_COOKIE['css'] == 'default' ) 
      {
        $text .= "selected='selected' ";
      }
      
      $text .=">default</option>
      <option value='black' ";
      
      if ( $_COOKIE['css'] == 'black' )
      {
        $text .= "selected='selected' ";
      }
      
      $text .= ">black</option>
      </select>
      </form>
*/
$navCount = 0;
function navigation ( $ad = 0 ) 
{
  #  [ <a href='" . ROOT_PATH . "best'>best</a> ]

  $text = "
  <div id='nav'>
    &nbsp;&nbsp;<a href='" . ROOT_PATH . "'>Последние</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='" . ROOT_PATH . "random'>Случайные</a>&nbsp;
    &nbsp;&nbsp;&nbsp;<a href='" . ROOT_PATH . "browse'>Все</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='" . ROOT_PATH . "add'>Добавить</a>
    &nbsp;&nbsp;&nbsp;&nbsp;<a href='" . ROOT_PATH . "search'>Искать</a>&nbsp;&nbsp;&nbsp;<a class='new' href='http://nwn.printdirect.ru/'>Футболки с символикой NWN</a>";
  
  if ( is_admin ( ) )
  {
    $text .= "&nbsp;&nbsp;&nbsp;<a href='" . ROOT_PATH . "a'>Админка</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='". ROOT_PATH . "a?logout'>Выйти</a>&nbsp;&nbsp;&nbsp;";
  }
  
if ($ad)  {

	$text .= '<br />
<div><script type="text/javascript"><!--
google_ad_client = "ca-pub-3568979665397357";
/* nwn menu */
google_ad_slot = "5208592920";
google_ad_width = 728;
google_ad_height = 15;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>';

	$text1 = '';
	$text1 .= '<div style="margin-top: 8px; margin-bottom: -25px;">
	
<form action="http://www.google.ru" id="cse-search-box">
  <div>
    <input type="hidden" name="cx" value="partner-pub-3568979665397357:6496360415" />
    <input type="hidden" name="ie" value="UTF-8" />
    <input type="text" name="q" size="55" />
    <input type="submit" name="sa" value="&#x041f;&#x043e;&#x0438;&#x0441;&#x043a;" />
  </div>
</form>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">google.load("elements", "1", {packages: "transliteration"});</script>
<script type="text/javascript" src="http://www.google.com/cse/t13n?form=cse-search-box&t13n_langs=en"></script>

<script type="text/javascript" src="http://www.google.ru/coop/cse/brand?form=cse-search-box&amp;lang=ru"></script>


</div>';



}
  
  $text .= "
  </div>
  ";
  
  return $text;
  
}

function query ( $query )
{

  if ( ! $mysql_query = @mysql_query ( $query ) )
  {
    error ( mysql_error(), 1 );
  }
  else
  {
    return $mysql_query;
  }
  
}

function is_admin ( )
{
  if ( isset ( $_SESSION['usr_id'] ) )
  {
    return true;
  }
  else
  {
    return false;
  }
}

function is_root ( )
{
  if ( $_SESSION['usr_id'] == 1 )
  {
    return true;
  }
  else
  {
    return false;
  }
}

/*

if ( isset ( $_GET['design'] ) )
{

  $design = quote_filter ( $_GET['design'] );
  setcookie ( 'css', $design, time()+60*60*24*30, ROOT_PATH, false, false, true );
  redirect ( ROOT_PATH );

}
*/

?>
