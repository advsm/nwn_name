<?php
include ( 'canfeg.php' );

$text = head ( );

//  Отображать цитат на странице.
$per_page = 20;


//  Всего цитат в базе.
$query_count = "select count(id) from " . PREFIX . "_plain where is_approved=1";
$mysql_query_count = query ( $query_count );

$quote_count = mysql_result ( $mysql_query_count, 0, 0 );
mysql_free_result ( $mysql_query_count );


//  Всего страниц.
$page_count = ceil ( $quote_count / $per_page );


//  Отображаемая страница.
if ( isset ( $_GET['page'] ) and
     is_numeric ( $_GET['page'] ) and
     $_GET['page'] > 0 and
     $_GET['page'] <= $page_count
   )
   
{
  $page = $_GET['page'];
}
else
{
  
  if ( $_GET['page'] == 'all' )
  {
    $page = 'all';
  }
  else
  {
    $page = 1;
  }
  
}

//  Заполняем массив страницами, которые будут отображаться в выборке.
$page_array = array ( );
if (  $page != 'all' ) $page_array[] = 1;
if ( $page_count > 1 )
{
  if ( $page - 3 > 2 ) $page_array[] = '';
  for ( $i = $page - 3; $i <= $page; $i++ ) if ( $i > 1 ) $page_array[] = $i;
  if ( $page != $page_count )
  {
    $last = ( $page + 3 < $page_count - 1 ) ? $page + 3 : $page_count - 1;
    for ( $i = $page + 1; $i <= $last; $i++ ) $page_array[] = $i;
    if ( $page + 3 < $page_count - 1 ) $page_array[] = '';
    $page_array[] = $page_count;
  }
}


//  Формируем строку из массива.
$navigator = '';
if ( $page > 1 )
{
  $navigator .= " [ <a href='" . ROOT_PATH . "browse/" . ( $page - 1 ) . "'>previous</a> ] ";
}

foreach ( $page_array as $page_num )
{

  if ( $page_num == '' )
  {
    $navigator .= " ... ";
  }
  else
  {
    $navigator .= " [ <a href='" . ROOT_PATH . "browse/$page_num'>$page_num</a> ] ";
  }

}

if ( $page != $page_count and $page != 'all')
{
  $navigator .= " [ <a href='" . ROOT_PATH . "browse/" . ( $page + 1 ) . "'>next</a> ] ";
}

$navigator .= " [ <a href='" . ROOT_PATH . "browse/all'>all</a> ] ";


//  Добавляем на страницу.
$text .= "
<div id='nav'>
  $navigator
</div>
";


//  Нужные цитаты запрашиваются тут.
if ( $page != 'all' ) 
{
  $start = $page * $per_page - 20;
  $limit = "limit $start, $per_page";
}
else
{
  $limit = '';
}
$query = "select id, rate, appby, time, quote from " . PREFIX .
         "_plain where is_approved=1 order by id " . $limit;

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

mysql_free_result ( $mysql_query );

$text .= "
<div id='nav'>
  $navigator
</div>
";

$text .= footer ( );
echo $text;

?>
