<!DOCTYPE html>
<html>
<head>
    <title>Practical - 10</title>
</head>

<body>
<?php
    $s  = "Tapi Diploma Engineering College, Surat";
    $s1 = "tapi diploma engineering college, surat";
    $s2 = "tapi Diploma engineering college, surat";

    echo "<h1>Motiwala Mohmmed</h1>";
    echo "<h2>String:</h2>";
    echo "<p>$s</p>";

    echo "<h3>1. chr()</h3>";
    echo "<p>Character = " . chr(99) . "</p><hr/>";

    echo "<h3>2. ord()</h3>";
    echo "<p>ASCII Value = " . ord($s[0]) . "</p><hr/>";

    echo "<h3>3. strtolower()</h3>";
    echo "<p>String in Lowercase = " . strtolower($s) . "</p><hr/>";

    echo "<h3>4. strtoupper()</h3>";
    echo "<p>String in Uppercase = " . strtoupper($s) . "</p><hr/>";

    echo "<h3>5. strlen()</h3>";
    echo "<p>Length of given string = " . strlen($s) . "</p><hr/>";

    echo "<h3>6. ltrim()</h3>";
    echo "<p>Left side trimmed string = " . ltrim($s, "Tapi") . "</p><hr/>";

    echo "<h3>7. rtrim()</h3>";
    echo "<p>Right side trimmed string = " . rtrim($s, " ,Surat") . "</p><hr/>";

    echo "<h3>8. trim()</h3>";
    echo "<p>Both sides trimmed string = " . trim($s, "Tapi ,Surat") . "</p><hr/>";

    echo "<h3>9. substr()</h3>";
    echo "<p>Substring = " . substr($s, 5, 19) . "</p><hr/>";

    echo "<h3>10. strcmp() (Case Sensitive)</h3>";
    echo "<p>Result = " . strcmp($s2, $s1) . "</p><hr/>";

    echo "<h3>11. strcmp() (Case Insensitive)</h3>";
    echo "<p>Result = " . strcasecmp($s1, $s2) . "</p><hr/>";

    echo "<h3>12. stripos()</h3>";
    echo "<p>Position of 'l' = " . stripos($s, 'l') . "</p><hr/>";

    echo "<h3>13. stripos() (e)</h3>";
    echo "<p>Position of 'e' = " . stripos($s, 'e') . "</p><hr/>";

    echo "<h3>14. stristr()</h3>";
    echo "<p>First occurrence of 'e' = " . stristr($s, 'e') . "</p><hr/>";

    echo "<h3>15. str_replace()</h3>";
    echo "<p>Replaced String = " . str_replace("Tapi", "tapi", $s) . "</p><hr/>";

    echo "<h3>16. strrev()</h3>";
    echo "<p>Reversed String = " . strrev($s) . "</p>";
?>
</body>
</html>