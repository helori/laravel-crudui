<!DOCTYPE html>
<html ng-app="crudui">
<head>

    <title>Laravel CRUD UI</title>

    <base href="/">

    <meta charset="UTF-8" />
    <meta name="fragment" content="!" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />

    <link rel="stylesheet" type="text/css" href="<% elixir('css/crudui.css') %>">

</head>

<body>

@yield('content')

<script src="<% elixir("js/crudui.js") %>"></script>
<script src="<% url('tinymce/tinymce.min.js') %>"></script>

</body>
