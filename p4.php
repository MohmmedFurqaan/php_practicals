<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical - 4</title>
</head>
<body>
    <?php
        echo "<h2>if else else...if</h2>";

        $marks = 49;
        $total_marks = 100;

        echo "<h4>Marks Is : $marks out of 100</h4>";

        
        // Grade calculation using comparison & logical operators
        if ($marks <= 100 && $marks >= 85) {
            echo "A Grade!";
        } elseif ($marks < 85 && $marks >= 65) {
            echo "B Grade!";
        } elseif ($marks < 65 && $marks >= 45) {
            echo "C Grade!";
        } elseif ($marks < 45 && $marks >= 33) {
            echo "D Grade!";
        } else {
            echo "You Failed!";
        }

        echo "<h2>Swirch Case</h2>";

        $operators = '*';
        $a = 89;
        $b = 34;

        switch ($operators) {
            case '+':
                echo "A + B : ".$a+$b;
                break;
            
            case '/':
                if ($b == 0) {
                    echo "B is Not be 0 !";
                    break;
                }
                else {
                    echo "A / B : ".$a/$b;
                    break;
                }
            
            case '-':
                echo "A - B : ".$a-$b;
                break;
        
            case '*':
                echo "A * B : ".$a*$b;
                break;
            
            default:
                echo "No operator !";
                break;
        }

    ?>
    
</body>
</html>