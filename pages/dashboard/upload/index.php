<!DOCTYPE html>
<!-- Coding By Learn With Daniel- youtube.com/@learnwithdanial417-->
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>File Upload JavaScript with Progress Ba | Learn With Daniel</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
</head>
<body>
  <div class="wrapper">
    <header>File Uploader JavaScript</header>
    <form action="#">
      <input class="file-input" type="file" name="files[]" multiple  hidden>
      <i class="fas fa-cloud-upload-alt"></i>
      <p>Browse File to Upload</p>
    </form>
    <section class="progress-area"></section>
    <section class="uploaded-area"></section>
  </div>

  <script src="script.js"></script>

</body>
</html>