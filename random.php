<?php
include ( 'canfeg.php' );

$text = head ( );

// Рейтинг.
if ( isset ( $_GET['id'] ) and is_numeric ( $_GET['id'] )
     and $_GET['id'] > 0 and ( $_GET['act'] == "rulez" or $_GET['act'] == "sux" )
     and stristr ( $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] )
   )

{

  $id = $_GET['id'];
  $act = $_GET['act'];
  $ip = $_SERVER['REMOTE_ADDR'];

  // Ты уже голосовал ?
  $query = "select count(id) from " . PREFIX . "_ip where quote_id=$id and ip='$ip'";
  $mysql_query = query ( $query );

  $are_you_rate = mysql_result ( $mysql_query, 0, 0 );
  mysql_free_result ( $mysql_query );

  if ( $are_you_rate > 0 )
  {
    redirect ( ROOT_PATH . $id, "Не пиздите, вы уже голосовале!", 2 );
  }

  // Добавляем IP юзера в бд
  $query_insert_ip = "insert into " . PREFIX . "_ip (quote_id, ip, time) values ($id, '$ip', " . time() . ")";
  $mysql_query_insert_ip = query ( $query_insert_ip );

  // Находим рейтинг цитаты
  $query_select_rate = "select rate from " . PREFIX . "_plain where id=$id and is_approved=1";
  $mysql_query_select_rate = query ( $query_select_rate );
  
  $rate = mysql_result ( $mysql_query_select_rate, 0, 0 );
  mysql_free_result ( $mysql_query_select_rate );
  
  if ( $act == 'rulez' )
  {
    $rate++;
  }
  else
  {
    $rate--;
  }

  $query_update_rate = "update " . PREFIX . "_plain set rate=$rate where id=$id limit 1";
  $mysql_query_update_rate = query ( $query_update_rate );

  redirect ( ROOT_PATH . $id, "Голос учтен!", 1 );

}


// Отдельная цитата.
if ( isset ( $_GET['id'] ) and is_numeric ( $_GET['id'] ) and $_GET['id'] > 0 ) 
{

  $id = $_GET['id'];
  
  $query = "select rate, appby, time, quote from " .
           PREFIX . "_plain where id=$id and is_approved=1";
  $mysql_query = query ( $query );
  
  if ( mysql_num_rows ( $mysql_query ) > 0 )
  {
    
    $mysql_result = mysql_fetch_assoc ( $mysql_query );
    $text .= add_quote ( $mysql_result['quote'], $id, $mysql_result['rate'],
    $mysql_result['appby'], $mysql_result['time'], false, false, 1 );
    
    mysql_free_result ( $mysql_query );

  }

}
else
{
  // Рандомные цитаты.
  
  $count = 10;  # Колличество цитат.

  $query = "select id from " . PREFIX . "_plain where is_approved=1";
  $mysql_query = query ( $query );

  $all_id = array ( );
  while ( $mysql_result = mysql_fetch_assoc ( $mysql_query ) )
  {
    $all_id[] = $mysql_result['id'];
  }
  
  mysql_free_result ( $mysql_query );
  
  if ( count ( $all_id ) >= $count )
  {
    
    shuffle ( $all_id );
    
    $i = 0;
    foreach ( $all_id as $id )
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
      $query = "select rate, appby, time, quote from " .
               PREFIX . "_plain where id=$id";
      $mysql_query = query ( $query );
      
      $mysql_result = mysql_fetch_assoc ( $mysql_query );
      $text .= add_quote ( $mysql_result['quote'], $id, $mysql_result['rate'],
               $mysql_result['appby'], $mysql_result['time'] );
      
      mysql_free_result ( $mysql_query );

      $i++;
      if ( $i >= $count )
      {
        break;
      }
    
    }

  }


}

$text .= footer ( );

echo $text;

?>
