<?

echo $_POST['nom'];
echo $_POST['prenoms'];
echo $_POST['adresse'];
echo $_POST['telephone'];
echo $_POST['email'];
echo $_POST['message'];


$mailto="jf@residence-eburnea.net";
$file="thanks.html";
$pcount=0;
$gcount=0;
$subject = "Message contact";
$from="jf@residence-eburnea.net";
while (list($key,$val)=each($HTTP_POST_VARS))
{
$pstr = $pstr."$key : $val \n ";
++$pcount;
}
while (list($key,$val)=each($HTTP_GET_VARS))
{
$gstr = $gstr."$key : $val \n ";
++$gcount;
}
if ($pcount > $gcount)
{
$message_body=$pstr;
mail($mailto,$subject,$message_body,"From:".$from);
include("$file");
}
else
{
$message_body=$gstr;
mail($mailto,$subject,$message_body,"From:".$from);
include("$file");
}
?>
