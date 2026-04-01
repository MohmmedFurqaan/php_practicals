<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>practical - 6</title>
</head>
<body>
    <?php
        // problem statement --> 1 : Program to find the reverse of the array using function concept

        function reverseArray($arr) {

            
            if (empty($arr)){
                echo "Array is Empty";
            }


            $count_arr_item = count($arr);
            for ($i=$count_arr_item - 1; $i >= 0; $i--) { 
                echo $arr[$i].", ";
            }
        }

        $arr = array(1, 6, 7, 4, 3, 9, 5);
        reverseArray($arr);


        // problem Statement --> 2 : Program to find the number is prime or not using function concept

        echo "<br>";


        $num = 12;

        function isPrime($num){
            if ($num < 1){
                return false;
            }

            for ($i=2; $i < $num; $i++) { 
                if ($num % $i == 0){
                    return false;
                
                }else{
                    return true;
                }

            }
        }

        $res = isPrime($num);

        if($res){
            echo $num." is a prime Number";
        } else{
            echo $num." is not a prime Number";
        }

    ?>
</body>
</html>