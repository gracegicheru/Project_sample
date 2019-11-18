<head>
    <meta charset="utf-8"/>
    <title>Bae</title>

    <!-- Google web fonts -->
    <link href="http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700" rel='stylesheet' />

    <!-- The main CSS file -->
    <link href="css/style.css" rel="stylesheet" />
</head>

<body>

<form id="upload" method="post" action="/upload" enctype="multipart/form-data">
    @csrf
    <div id="drop">
        Drop Here

        <a>Browse</a>
        <input type="file" name="file" multiple />
    </div>

    <ul>
        <!-- The file uploads will be shown here -->
    </ul>

</form>

<!-- JavaScript Includes -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="js/jquery.knob.js"></script>

<!-- jQuery File Upload Dependencies -->
<script src="js/jquery.ui.widget.js"></script>
<script src="js/jquery.iframe-transport.js"></script>
<script src="js/jquery.fileupload.js"></script>

<!-- Our main JS file -->
<script src="js/script.js"></script>

</body>
</html>