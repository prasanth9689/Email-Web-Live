<!DOCTYPE html>
<html>
<head>
  <title>Upload & Attach to Draft</title>
</head>
<body>
  <h2>Upload Attachment to Draft</h2>

  <label>Draft ID: <input type="text" id="draftId" value="1" /></label><br><br>
  <input type="file" id="fileInput" />
  <button onclick="uploadFile()">Upload</button>

  <p id="msg"></p>

  <script>
    function uploadFile() {
      const file = document.getElementById("fileInput").files[0];
      const draftId = document.getElementById("draftId").value;
      if (!file || !draftId) {
        alert("Select a file and provide a draft ID.");
        return;
      }

      const formData = new FormData();
      formData.append("attachment", file);
      formData.append("draft_id", draftId);

      fetch("upload.php", {
        method: "POST",
        body: formData
      })
      .then(res => res.text())
      .then(data => {
        document.getElementById("msg").innerText = data;
      });
    }
  </script>
</body>
</html>
