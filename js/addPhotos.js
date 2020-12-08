document.getElementById('addInput').addEventListener('click',
  function(e) {
    e.preventDefault();
  });
let counter = 0;

function addInputs() {
  const row = document.getElementById('imageInputs');
  const wrapper = document.createElement('div');
  const inputFile = document.createElement('input');
  const inputHidden = document.createElement('input');

  if (counter < 5) {
    addWrapperProperty(wrapper);
    addInputFileProperty(inputFile);
    addInputHiddenProperty(inputHidden);
    appendElements(row, wrapper, inputFile, inputHidden);
  }
  counter++;
}

function addWrapperProperty(wrapper) {
  wrapper.classList = "col-12 d-flex align-items-center justify-content-center mt-4 mb-3";
}

function addInputFileProperty(inputFile) {
  inputFile.type ="file";
  inputFile.name = "userfile[]";
  inputFile.id = "uploaded_file";
  inputFile.multiple = "multiple";
}

function addInputHiddenProperty(inputHidden) {
  inputHidden.type = "hidden";
  inputHidden.name = "MAX_FILE_SIZE";
  inputHidden.value="1000000";
}

function appendElements(row, wrapper, inputFile, inputHidden) {
  wrapper.appendChild(inputFile);
  wrapper.appendChild(inputHidden);
  row.appendChild(wrapper);     
} 