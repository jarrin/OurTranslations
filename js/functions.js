
$.fn.extend({
    filedrop: function (options) {
        var defaults = {
            callback : null,
            dragenter : null,
            dragleave : null,
        }
        options =  $.extend(defaults, options)
        return this.each(function() {
            var files = []
            var $this = $(this)

            // Stop default browser actions
            $this.bind('dragover dragleave', function(event) {
                event.stopPropagation()
                event.preventDefault()
            });
            if(options.dragenter) $this.bind("dragenter", options.dragenter);
            if(options.dragleave) $this.bind("dragleave", options.dragleave);

            // Catch drop event
            $this.bind('drop', function(event) {
                // Stop default browser actions
                event.stopPropagation()
                event.preventDefault()

                // Get all files that are dropped
                files = event.originalEvent.target.files || event.originalEvent.dataTransfer.files
                // Convert uploaded file to data URL and pass trought callback
                if(options.callback) {
                    var reader = new FileReader()
                    reader.onload = function(event) {
                        options.callback(event.target.result, files)
                    }
                    reader.readAsDataURL(files[0])
                }
                return false
            })
        })
    }
});

function formatFileSize(bytes)
{
    if(bytes/1024 < 1024)  return Math.round(bytes/1024) + " kb";
    if(bytes/1024/1024 < 1024)  return Math.round(bytes/1024/1024) + " mb";
}