<?php
include ( 'canfeg.php' );

$text = head ( );


if ( isset ( $_POST['submit'] ) and strlen ( $_POST['find'] ) > 1
     and stristr ( $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] )
   )

{

  $search_array = explode ( " ", $_POST['find'] );

  //  Составляем условие запроса.
  $condition = '';
  foreach ( $search_array as $key => $search_string )
  {
    $search_string = trim ( $search_string );
    if ( strlen ( $search_string ) > 1 )
    {
      $condition = " quote like '%$search_string%' or ";
    }
  }
  $condition = substr ( $condition, 0, -3 );

  $query = "select id, rate, appby, time, quote from " . PREFIX . "_plain where $condition and is_approved=1 limit 50";
  $mysql_query = query ( $query );
  
  if ( mysql_num_rows ( $mysql_query ) > 0 )
  {

    while ( $mysql_result = mysql_fetch_assoc ( $mysql_query ) )
    {

      $text .= add_quote ( $mysql_result['quote'], $mysql_result['id'], $mysql_result['rate'],
               $mysql_result['appby'], $mysql_result['time'] );

    }
    
    mysql_free_result ( $mysql_query );

  }
  else
  {
    redirect ( './search', 'Ничего не найдено.', 2 );
  }

}
else
{

  $text .= "
  <div id='search'>
    
    <form action='' method='post'>

     <input type='text' name='find' size='50' />
     <input type='submit' name='submit' value='submit' />
     
    </form>

  </div>
  ";

}

$text .= footer ( );

echo $text;

?>