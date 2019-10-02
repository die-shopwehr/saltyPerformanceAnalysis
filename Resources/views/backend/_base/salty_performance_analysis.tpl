<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{link file="backend/_resources/css/bootstrap.min-4.3.1.css"}">
</head>
<body role="document">

<!-- Fixed navbar -->
<nav class="navbar navbar-dark bg-dark">
    <a class="navbar-brand" href="https://shopwehr.de" target="_blank">
        <img src="{link file="backend/_resources/images/logo.png"}" width="30" height="30" class="d-inline-block align-top" alt="">
        <strong>Die Shopwehr</strong>
    </a>
    <div class="my-1 ">
        <a class="navbar-text text-light" href="https://shopwehr.de" target="_blank">https://shopwehr.de</a>
    </div>
</nav>

<div class="salty-performance-analysis" role="main">
    {block name="content/main"}{/block}
</div> <!-- /container -->



<script type="text/javascript" src="{link file="backend/base/frame/postmessage-api.js"}"></script>
<script type="text/javascript" src="{link file="backend/_resources/js/jquery-3.4.1.min.js"}"></script>
<script type="text/javascript" src="{link file="backend/_resources/js/bootstrap.min-4.3.1.js"}"></script>

{block name="content/layout/javascript"}
{/block}

{block name="content/javascript"}{/block}
</body>
</html>