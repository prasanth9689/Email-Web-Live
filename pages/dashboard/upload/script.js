const form = document.querySelector("form"),
fileInput = document.querySelector(".file-input"),
progressArea = document.querySelector(".progress-area"),
uploadedArea = document.querySelector(".uploaded-area");

form.addEventListener("click", () =>{
  fileInput.click();
});

fileInput.onchange = ({ target }) => {
  const files = target.files;
  let totalSize = 0;

  // Calculate total size of all selected files
  for (let i = 0; i < files.length; i++) {
    totalSize += files[i].size;
  }

  const maxTotalSize = 25 * 1024 * 1024; // 25MB in bytes

  if (totalSize > maxTotalSize) {
    alert("Total file size exceeds 25MB. Please select smaller files.");
    fileInput.value = ""; // Reset selection
    return;
  }

  // Proceed with upload for each file
  for (let i = 0; i < files.length; i++) {
    const file = files[i];
    let fileName = file.name;

    if (fileName.length >= 12) {
      let splitName = fileName.split('.');
      fileName = splitName[0].substring(0, 13) + "... ." + splitName[1];
    }

    uploadFile(file, fileName);
  }
};


function uploadFile(file, name){
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "upload.php");

  xhr.upload.addEventListener("progress", ({ loaded, total }) => {
    let fileLoaded = Math.floor((loaded / total) * 100);
    let fileTotal = Math.floor(total / 1000);
    let fileSize = (fileTotal < 1024)
      ? fileTotal + " KB"
      : (loaded / (1024 * 1024)).toFixed(2) + " MB";

    let progressHTML = `<li class="row">
                          <i class="fas fa-file-alt"></i>
                          <div class="content">
                            <div class="details">
                              <span class="name">${name} • Uploading</span>
                              <span class="percent">${fileLoaded}%</span>
                            </div>
                            <div class="progress-bar">
                              <div class="progress" style="width: ${fileLoaded}%"></div>
                            </div>
                          </div>
                        </li>`;
    uploadedArea.classList.add("onprogress");
    progressArea.innerHTML = progressHTML;

    if (loaded === total) {
      progressArea.innerHTML = "";
      let uploadedHTML = `<li class="row">
                            <div class="content upload">
                              <i class="fas fa-file-alt"></i>
                              <div class="details">
                                <span class="name">${name} • Uploaded</span>
                                <span class="size">${fileSize}</span>
                              </div>
                            </div>
                            <i class="fas fa-check"></i>
                          </li>`;
      uploadedArea.classList.remove("onprogress");
      uploadedArea.insertAdjacentHTML("afterbegin", uploadedHTML);
    }
  });

  let formData = new FormData();
  formData.append("file", file); // attach the actual file

  xhr.send(formData);
}
