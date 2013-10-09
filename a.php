<?php
include ( 'canfeg.php' );

$text = head ( );

if ( isset ( $_GET['logout'] ) )
{
  session_unset ( );
  redirect ( ROOT_PATH . 'a' );
}

if ( ! isset ( $_SESSION['usr_id'] ) )
{

  if ( isset ( $_POST['submit'] ) and $_POST['name'] != '' and $_POST['pass'] != '' )
  {

    $name = quote_filter ( $_POST['name'] );
    $pass = $_POST['pass'];

    $query = "select id, usr_pass from " . PREFIX . "_usrs where hidden_usr_name='$name'";
    $mysql_query = query ( $query );

    if ( mysql_num_rows ( $mysql_query ) > 0 )
    {
      
      $mysql_result = mysql_fetch_assoc ( $mysql_query );

      if ( md5 ( $pass ) == $mysql_result['usr_pass'] )
      {
        $_SESSION['usr_id'] = $mysql_result['id'];
        redirect ( ROOT_PATH . 'a?', 'Залогинелись.', 2 );
      }
      else
      {
        $error = 1;
      }

    }
    else
    {
      $error = 1;
    }
    
    if ( $error )
    {
      error ( "Неверное имя пользователя или пароль.", 1 );
    }
    
  }

  $text .= "
  <div id='auth'>

    <form name='auth' id='auth' action='' method='post'>
    <input type='text' name='name' value='login' /> <br />
    <input type='password' name='pass' value='password' /> <br />
    <input type='submit' name='submit' value='submit' />
    </form>

  </div>
  ";

}
else
{






  if ( isset ( $_GET['id'] ) and is_numeric ( $_GET['id'] ) and $_GET['id'] > 0 )
  {

    $id = $_GET['id'];

    if ( $_GET['act'] == 'approve' )
    {

      $query = "update " . PREFIX . "_plain set is_approved=1, appby=" .
               $_SESSION['usr_id'] . ", time=" . time() . " where id=$id limit 1";
      $mysql_query = query ( $query );
      redirect ( ROOT_PATH . 'a', 'Добавлено!', 1 );

    }
    elseif ( $_GET['act'] == 'delete' )
    {

      $query = "select id from " . PREFIX . "_plain where id=$id and is_approved=0";
      $mysql_query = query ( $query );
      
      if ( mysql_num_rows ( $mysql_query ) > 0 or is_root ( ) )
      {
        
        $query = "delete from " . PREFIX . "_plain where id=$id limit 1";
        $mysql_query = query ( $query );
        redirect ( ROOT_PATH . 'a', 'Удалено!', 1 );
      
      }
      else
      {
        error ( '=\\', 1 );
      }

    }
    elseif ( $_GET['act'] == 'edit' )
    {
      
      $query = "select id from " . PREFIX . "_plain where id=$id and is_approved=0";
      $mysql_query = query ( $query );
      
      if ( mysql_num_rows ( $mysql_query ) > 0 or is_root ( ) )
      {
        
        if ( isset ( $_POST['submit'] ) )
        {
          $quote = quote_filter ( $_POST['text'] );

          $query = "update " . PREFIX . "_plain set quote='$quote' where id=$id";
          $mysql_query = query ( $query );
          redirect ( ROOT_PATH . $id, 'Отредактировано!', 1 );

        }

        $query = "select quote from " . PREFIX . "_plain where id=$id";
        $mysql_query = query ( $query );

        $quote = mysql_result ( $mysql_query, 0, 0 );
        mysql_free_result ( $mysql_query );

        $quote = str_replace ( "<br />", "", $quote );
        $filter_array = array ( "&lt;" => "<", "&gt;" => ">", "&quot;" => '"', "&#39;" => "'", "&amp;" => "&" );
        $quote = strtr ( $quote, $filter_array );

        $text .= "
        <div id='add'>

          <form action='?id=$id&act=edit' method='post'>
          <textarea name='text' cols='100' rows='15'>$quote</textarea>
          <br />
          <input type='submit' name='submit' value='submit' />
          </form>

        </div>
        ";
        
      }
      else
      {
        error ( '=\\', 1 );
      }

    }

  }

  $query = "select id, ip, time, quote from " . PREFIX . "_plain where is_approved=0 order by id desc";
  $mysql_query = query ( $query );
  while ( $mysql_result = mysql_fetch_assoc ( $mysql_query ) )
  {

    $text .= add_quote ( $mysql_result['quote'], $mysql_result['id'], false,
    $mysql_result['ip'], $mysql_result['time'], false, 1 );

  }

  mysql_free_result ( $mysql_query );

}

$text .= footer ( );
echo $text;

?>