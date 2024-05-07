<?php

$FLAG = "FLAG";

if (!empty($_POST['url'])) {
    $query = "http://127.0.0.1";
    if (substr($_POST['url'], 0, strlen($query)) === $query){
        $url = file_get_contents(parse_url($_POST['url'], PHP_URL_PATH));
    } else {
        $curl_handle=curl_init();
        curl_setopt($curl_handle,CURLOPT_URL,$_POST['url']);
        curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
        curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
        $buffer = curl_exec($curl_handle);
        curl_close($curl_handle);
        if (empty($buffer)){
            $url = "Nothing returned from url.";
        } else {
            $url = $buffer;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="//bootswatch.com/4/flatly/bootstrap.min.css">
    <title>ReadMe Website Downloader</title>
    <style>
        h2 {
            color: rgba(0, 0, 0, .75);
        }
        pre {
            padding: 15px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            background-color: #ECF0F1;
        }
        .container {
            width: 850px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="pb-2 mt-4 mb-2">
            <h2>Download a Website</h2>
        </div>
        <form method="POST">
            <div class="form-group">
                <label for="url"><strong>Website URL</strong></label>
                <input type="text" class="form-control" name="url" id="url" value="<?= htmlspecialchars($_POST['url'], ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Execute</button>
        </form>
<?php if ($url): ?>
        <div class="pb-2 mt-4 mb-2">
            <h2> Output </h2>
        </div>
        <pre>
<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>
        </pre>
<?php elseif (!$url && $_SERVER['REQUEST_METHOD'] == 'POST'): ?>
        <div class="pb-2 mt-4 mb-2">
            <h2> Output </h2>
        </div>
        <pre><small>No result.</small></pre>
<?php endif; ?>
    </div>
</body>
</html>
