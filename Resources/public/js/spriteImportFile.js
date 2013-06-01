function handleFileSelect(evt) {
    var files = evt.target.files; 
    
    // files is a FileList of File objects. List some properties.
    for (var i = 0, f; f = files[i]; i++) {
      // Only process image files.
        if (!f.type.match('image.*')) {
            continue;
        }
    
        var reader = new FileReader();
    
      // Closure to capture the file information.
        reader.onload = (function(theFile) {
            return function(e) {
            // Render thumbnail.
            var span = document.createElement('span');
            span.innerHTML = ['<img class="thumb" src="', e.target.result,
                        '" title="', escape(theFile.name), '"/>'].join('');
            document.getElementById('list').insertBefore(span, null);
            };
        })(f);
    
        // Read in the image file as a data URL.
        reader.readAsDataURL(f);
    }
}

document.getElementById('form-upload').addEventListener('change', handleFileSelect, false);

function handleDragOver(evt) {
    evt.stopPropagation();
    evt.preventDefault();
    evt.dataTransfer.dropEffect = 'copy';
}

/*var dropZone = document.getElementById('drop_zone');
dropZone.addEventListener('dragover', handleDragOver, false);
dropZone.addEventListener('drop', handleFileSelect, false);*/
/*
[].forEach.call(document.querySelectorAll(".radio input[type=radio]"), function(el) {
  el.addEventListener("click", function() {
    this.parentNode.querySelector("input[checked=checked]").removeAttribute("checked");
    this.setAttribute("checked", "checked");
  });
});

document.querySelector("#showDeleteCheckbox").addEventListener("click", function() {
    [].forEach.call(document.querySelectorAll('#imgToSprite input[type=checkbox]'), function(el) {
        el.style.display = "block";
        el.parentNode.addEventListener("click", function() {
            if (el.getAttribute("checked")) {
                el.removeAttribute("checked");
            } else {
                el.setAttribute("checked", "checked");
            }
        }, false);
    });
}, false);
*/


/*$(function () {
    $('.thumbnail').hover(function() {
        $(this).tooltip(options.delay({ show: 500, hide: 100 }));
    });
    
});*/
