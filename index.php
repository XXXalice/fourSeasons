<?php

    require_once 'vendor/autoload.php';
    require_once 'data.php';
    $tokenizer = new Fieg\Bayes\Tokenizer\WhitespaceAndPunctuationTokenizer();
    $classifier = new Fieg\Bayes\Classifier($tokenizer);
    $fres = '';

    //学習部分
    foreach($fourSeasons as $key => $season){
        foreach($season as $word){
            $classifier->train($key,$word);
        }
    }
    //学習部分終わり

    //判定部分
    if(!empty($_POST["str"])){
        $json = json_decode(file_get_contents('http://yapi.ta2o.net/apis/mecapi.cgi?sentence='.urlencode($_POST["str"]).'&format=json'));
        foreach($json as $block){
            $sent[] = $block->surface;
        }
        $res = $classifier->classify(implode(' ',$sent));
        switch(array_keys($res,max($res))[0]){
            case 'Spring':$fres='春です';break;
            case 'August':$fres='夏です';break;
            case 'Autumn':$fres='秋です';break;
            case 'Winter':$fres='冬です';break;
            default:$fres='わかりませんでした';
        }
    }
    //判定部分終わり

    

?>
<!doctype html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <form method="post" action="">
            <p><label>文章を入力してください<input type="text" name="str" size="45">
            <input type="submit" value="実行"></label></p>
        </form>
            <p><?php echo $fres; ?></p>
    </body>
</html>