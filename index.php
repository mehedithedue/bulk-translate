
<?php
require __DIR__ . '/vendor/autoload.php';

$file = file_get_contents('en.json');
$jsonFile = json_decode($file, true);
$chunkText = [];
$lineValues = [];
$lineKeys = [];
$jsonArray = [];


$i = 0;
$j = 0;
foreach($jsonFile as $key=>$line){
    $chunkText[$i][$key] = $line;
    $j++;
    if($j == 150){
        $i ++;
        $j = 0;
    }
} 


foreach($chunkText as $chunKey => $chunk){

    foreach($chunk as $key => $line){

        $lineValues[$chunKey][] = $line;
        $lineKeys[$chunKey][] = $key;

    }
}

if( isset($_POST) && !empty( $_POST)){

    $chunks = $_POST['chunk'];

    foreach($chunks as $chunKey=>$chunk){

        $arrayValue = explode(PHP_EOL, $chunk);


        foreach($arrayValue as $key=>$line){
            
            $jsonArray[$lineKeys[$chunKey][$key]] = preg_replace('~[\r\n]+~', '', $line);
        }

    }

    $language = !empty($_POST['language']) ? $_POST['language'] : 'bn';

    header('Content-disposition: attachment; filename='.$language.'.json');
    header('Content-type: application/json');

    echo json_encode($jsonArray,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    die();

}


?>








<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="" method="POST">

<input type="text" name="language" placeholder="enter language name"/>
<br>

<?php foreach($lineValues as $keys => $value){?> 

<textarea name="chunk[<?php echo $keys ?>]" cols="33" rows="45"><?php echo implode(PHP_EOL, $value); ?></textarea>

<?php }?>
<br>
<input type="submit" />
</form>
</body>
</html>