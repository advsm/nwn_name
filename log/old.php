<?php
echo "<html><head>
  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\">
  <title>Обработчик логов v1.0</title>
  </head>
  <body>";
$act=$_POST["act"];
if($act=="do") {
  $text=$_POST["text"];

  $filer1="<";
  $filer2=">";
  $filter3="\\";
  $text=str_replace($filter1, "&lt;", $text);
  $text=str_replace($filter2, "&gt;", $text);
  $text=str_replace($filter3, "&#092;", $text);

  $cwt=$_POST["cwt"]; # Вырезать [CHAT WINDOW TEXT]
  $time=$_POST["time"]; # Вырезать [## ### ## ##:##:##]
  $tell=$_POST["tell"]; # Вырезить строки с [Tell]
  $party=$_POST["party"]; # Вырезать строки с [Party]
  $server=$_POST["server"]; # Вырезать строки с [Server]
  $save=$_POST["save"];
  $player=$_POST["player"];
  $casts=$_POST["casts"]; # Вырезать сроки с casts
  $casting=$_POST["casting"]; # Вырезать строки с casting
  $item=$_POST["item"]; # Вырезать строки с item
  $roll=$_POST["roll"]; # Вырезать строки с Roll
  $exp=$_POST["exp"]; # Вырезать строки с Experience
  $attacks=$_POST["attacks"]; # Вырезать строки с attacks
  $damages=$_POST["damages"]; # Вырезить строки с damages
  $baseexp=$_POST["baseexp"]; # Вырезать строки с Базовый опыт за монстра
  $acquired=$_POST["acquired"]; # Вырезать строки с Acquired
  $barter=$_POST["barter"]; # Вырезать строки с Barter
  $healed=$_POST["healed"]; # Вырезать строки с Healed
  $offtop=$_POST["offtop"]; # Вырезать фразы с \\ и //

  $stringm=explode("\n", $text);
  $i=0;
  while($stringm[$i]) {
    $string=$stringm[$i];
    $w="yes";
    $string=trim($string);

    if($cwt) {
      $find="[CHAT WINDOW TEXT]";
      if(strstr($string, $find)) {
        $string=str_replace($find, "", $string);
      }
    }

    if($time) {
      preg_replace("/\[[A-Z]{2,3}\s[a-z]{2,3}\s+(\d{2}:\d{2}:\d{2})\]/", "", $string, 1);
   }
    
    if($tell) {
      $find="[Tell]";
      if(strstr($string, $find)) {
        unset($string);
        $w="no";
      }
    }

    if($party) {
      $find="[Party]";
      if(strstr($string, $find)) {
        $string=str_replace($find, "", $string);
      }
    }
    
    if($server) {
      $find="[Server]";
      if(strstr($string, $find)) {
        unset($string);
        $w="no";
      }
    }
    
    if($casts) {
      $find="casts";
      if(strstr($string, $find)) {
        unset($string);
        $w="no";
      }
    }
    
    if($casting) {
      $find="casting";
      if(strstr($string, $find)) {
        unset($string);
        $w="no";
      }
    }
    
    if($save) {
      $find="Save";
      if(strstr($string, $find)) {
        unset($string);
        $w="no";
      }
      $find="*failure*";
      if(strstr($string, $find)) {
        unset($string);
        $w="no";
      }
      $find="*success*";
      if(strstr($string, $find)) {
        unset($string);
        $w="no";
      }
    }
    
    if($item) {
      $find="item";
      if(strstr($string, $find)) {
        unset($string);
        $w="no";
      }
    }
    
    if($roll) {
      $find="Roll";
      if(strstr($string, $find)) {
        unset($string);
        $w="no";
      }
      $find="roll";
      if(strstr($string, $find)) {
        unset($string);
        $w="no";
      }
      $find="uses";
      if(strstr($string, $find)) {
        unset($string);
        $w="no";
      }
    }
    
    if($exp) {
      $find="Experience";
      if(strstr($string, $find)) {
        unset($string);
        $w="no";
      }
    }
    
    if($acquired) {
      $find="Acquired";
      if(strstr($string, $find)) {
        unset($string);
        $w="no";
      }
    }
    
    if($player) {
      $find="a player";
      if(strstr($string, $find)) {
        unset($string);
        $w="no";
      }
    }
    
    if($damages) {
      $find="damages";
      if(strstr($string, $find)) {
        unset($string);
        $w="no";
      }
      $find="Damage";
      if(strstr($string, $find)) {
        unset($string);
        $w="no";
      }
    }

    if($attacks) {
      $find="attacks";
      if(strstr($string, $find)) {
        unset($string);
        $w="no";
      }
      $find="attempts";
      if(strstr($string, $find)) {
        unset($string);
        $w="no";
      }
      $find="Immune";
      if(strstr($string, $find)) {
        unset($string);
        $w="no";
      }
    }
    
        if($baseexp) {
      $find="Базовый опыт за монстра";
      if(strstr($string, $find)) {
        unset($string);
        $w="no";
      }
    }
    
        if($barter) {
      $find="Barter";
      if(strstr($string, $find)) {
        unset($string);
        $w="no";
      }
    }
    
        if($healed) {
      $find="Healed";
      if(strstr($string, $find)) {
        unset($string);
        $w="no";
      }
    }
    
        if($offtop) {
      $find='&#092;&#092;';
      if(strstr($string, $find)) {
        unset($string);
        $w="no";
      }
      $find='//';
      if(strstr($string, $find)) {
        unset($string);
        $w="no";
      }
    }
    
    $find="&#092;'";
    if(strstr($string, $find)) {
      $string=str_replace($find, "'", $string);
    }

    $find='&#092;"';
    if(strstr($string, $find)) {
      $string=str_replace($find, '"', $string);
    }

    if($w=="yes") {
      echo "$string<br />";
    }
    $i++;
  }
} else {
  echo "<center>
  <h1>Обработчик логов.</h1><form action=\"index.php\" method=post>
  <input type=hidden name=\"act\" value=\"do\">
  <INPUT type=\"checkbox\" name=\"cwt\" checked> Вырезать [CHAT WINDOW TEXT]<br />
  <INPUT type=\"checkbox\" name=\"time\" checked> Вырезать время<br />
  <INPUT type=\"checkbox\" name=\"tell\" checked> Вырезать [Tell]<br />
  <INPUT type=\"checkbox\" name=\"party\" checked> Вырезать [Party]<br />
  <INPUT type=\"checkbox\" name=\"server\" checked> Вырезать строки с [Server]<br />
  <INPUT type=\"checkbox\" name=\"casts\" checked> Вырезать строки с casts<br />
  <INPUT type=\"checkbox\" name=\"casting\" checked> Вырезать строки с casting<br />
  <INPUT type=\"checkbox\" name=\"casting\" checked> Вырезать строки с item<br />
  <INPUT type=\"checkbox\" name=\"player\" checked> Вырезать строки с a player<br />
  <INPUT type=\"checkbox\" name=\"roll\" checked> Вырезать строки с Roll<br />
  <INPUT type=\"checkbox\" name=\"save\" checked> Вырезать строки с Save<br />
  <INPUT type=\"checkbox\" name=\"exp\" checked> Вырезать строки с Experience<br />
  <INPUT type=\"checkbox\" name=\"attacks\" checked> Вырезать строки с attacks<br />
  <INPUT type=\"checkbox\" name=\"damages\" checked> Вырезить строки с damages<br />
  <INPUT type=\"checkbox\" name=\"baseexp\" checked> Вырезать строки с Базовый опыт за монстра<br />
  <INPUT type=\"checkbox\" name=\"acquired\" checked> Вырезать строки с Acquired<br />
  <INPUT type=\"checkbox\" name=\"barter\" checked> Вырезать строки с Barter<br />
  <INPUT type=\"checkbox\" name=\"healed\" checked> Вырезать строки с Healed<br />
  <INPUT type=\"checkbox\" name=\"offtop\" checked> Вырезать фразы с &#092;&#092; и //<br /><br />
  <h3>Введите ваш лог:</h3><br /><textarea cols=\"80%\" rows=\"30%\" name=\"text\"></textarea><br />
  <input type=\"submit\" value=\"Обработать\"></form><br /><br /><hr><p align=\"right\"><h4>by Anti </h4><font size=-1>(О всех багах в icq: 207202)</font></p></body></html>";
}
?>