<?php
echo "<html><head>
  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\">
  <title>Обработчик логов v2.0</title>
  </head>
  <body>
  ";


$delete=array("tell" => "[Tell]",
              "server" => "[Server]",
              "party" => "party",
              "save" => "save",
              "player" => "player",
              "cast" => "cast",
              "item" => "item",
              "roll" => "roll",
              "experience" => "experience",
              "attack" => "attack",
              "damage" => "damage",
              "acquired" => "acquired",
              "barter" => "barter",
              "heal" => "heal",
              "success" => "*success",
              "*hit*" => "*hit*",
              "failure" => "*failure*",
              "miss" => "*miss*",
              "resisted" => "*resisted*",
              "failed" => "failed",
              "killed" => "killed",
              "spell" => "spell",
              "fight_mode" => "режиме боя.",
              '\\\\' => '&#092;&#092;',
              '//' => '//'
             );

$cut=array("chat_window_text" => "[CHAT WINDOW TEXT]",
           "party" => "[Party]"
          );


if ( isset ( $_POST['submit'] ) )
{

  $log = explode ( "\n", str_replace ( "\\\\", "&#092;&#092;", ( stripslashes ( $_POST['text'] ) ) ) );

  foreach ( $log as $key => $str )
  {

    if ( isset ( $_POST['time'] ) )
    {

      preg_match ( "/(\[[A-Za-z]{2,3}\s[A-Za-z]{2,3}\s[0-9]{1,2}\s[0-9]{1,2}\:[0-9]{1,2}\:[0-9]{1,2}\])/" ,
      $str, $result );

      if ( $result[1] != '' )
      {

        $str = str_ireplace ( $result[1], '', $str );

      }

    }

    foreach ( $cut as $k => $c )
    {

      if ( isset ( $_POST[$k] ) and stripos ( $str, $c ) !== false  )
      {

        $log[$key] = str_ireplace ( $c, '', $str );

      } 
      
    }
    
    
    foreach ( $delete as $k => $del )
    {

      if ( isset ( $_POST[$k] ) and stripos ( $str, $del ) !== false )
      {

        unset ( $log[$key] );
        continue 2;
      }
      
    }
    
  }

  $log = nl2br ( join ( '', $log ) );

  echo $log;
  
} 
else
{
  
  $text = "<center>
  <font size='+2'><b>Обработчик логов v2.0.</b></font><br />
  <a href='old.php'>old version</a><br /><br />
  <form action='' method=post>
  ";
  
  foreach ( $delete as $key => $del )
  {
    
    $text .= "<input type='checkbox' name='$key' checked>Вырезать строки с $del
    <br />";
    
  }
  
  $text .= "<br />";

  foreach ( $cut as $key => $c )
  {
    
    $text .= "<input type='checkbox' name='$key' checked>Вырезать $c
    <br />";
    
  }
  
  $text .= "<input type='checkbox' name='time' checked>Вырезать время
  <br />";

  $text .= "<h3>Введите ваш лог:</h3>
  <br />
  <textarea cols=\"80%\" rows=\"30%\" name=\"text\"></textarea>
  <br />
  <input name='submit' type=\"submit\" value=\"Обработать\">
  </form>
  </body>
  </html>";
  
  echo $text;
  
  echo "<center><a href='source.txt'>source</a></center>";

}