<?php
include ( 'canfeg.php' );

$text = head ( );

$query = "select id, rate, appby, time, quote from " . 
         PREFIX . "_plain where is_approved=1 order by id desc limit 50";

$mysql_query = query ( $query );

$i = 0;
while ( $mysql_result = mysql_fetch_assoc ( $mysql_query ) )
{
  if ($i == 3) {
  	$text .= '<script type="text/javascript"><!--
google_ad_client = "ca-pub-3568979665397357";
/* nwn quote */
google_ad_slot = "6403728213";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script><br /><br />';
  }
  $text .= add_quote ( $mysql_result['quote'], $mysql_result['id'], $mysql_result['rate'],
  $mysql_result['appby'], $mysql_result['time'] );
  $i++;

}

$text .= footer ( );

echo $text;

?>
