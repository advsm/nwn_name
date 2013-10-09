<?php
include ( 'canfeg.php' );

$text = head ( );

if ( isset ( $_POST['submit'] ) and $_POST['text'] != "" 
     and stristr ( $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] )
     and $_SESSION['id'] == $_POST['id'] and strlen ( $_POST['text'] ) < 2000
   )

{

  $quote = $_POST['text'];
  if ( false !== stripos ( $quote, "<a href" )
       or false !== stripos ( $quote, "[url=" )
       or false !== stripos ( $quote, "http://" )
     )

  {
    error ( "Нельзя постить ссылки!", 1 );
  }

  $quote = quote_filter ( $quote );
  
  $ip = $_SERVER['REMOTE_ADDR'];
  
  $query = "insert into " . PREFIX . "_plain (time, rate, is_approved, ip, quote) values (" .
           time() . ", 0, 0, '$ip', '$quote')";
  $mysql_query = query ( $query );

  redirect( "./", "Отправлено на рассмотрение.", 3 );

} 
else 
{

  $session = rand ( 1, 100000 );
  $_SESSION['id'] = $session;

  $text .= "
  <div id='add'>

    <form name='form' action='' method='post'
    onSubmit='
    if ( document.form.text.value.length > 2000 )
    {
      alert(\"Too long\");
      return false;
    }
    else if ( document.form.text.value.length < 10 )
    {
      alert(\"Too short\");
      return false;
    }
    else
    {
      return true;
    }
    '>

    <textarea name='text' cols='100' rows='15'></textarea>
    
    <br />
    <br />
    
    <input type='hidden' name='id' value='$session' />
    <input type='submit' name='submit' value='submit' />
    </form>

  </div>
  ";

}

$text .= footer ( );

echo $text;

?>