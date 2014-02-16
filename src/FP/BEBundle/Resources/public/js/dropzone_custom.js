$("document").ready(function(){
    Dropzone.options.mydropzone = {
        autoProcessQueue: false,
        parallelUploads: 10,
        acceptedFiles: "image/*",
        maxFileSize: 4,
        init: function() {

            var submit_button = $("#dropzone_btn");
            var myDropzone = this;
            submit_button.on("click", function(){
                myDropzone.processQueue();
            });

            this.on("addedfile", function(file){
                var removeButton = Dropzone.createElement("<a>Remove file</a>");
                var _this = this;

                removeButton.addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    _this.removeFile(file);
                });

                file.previewElement.appendChild(removeButton);
            });

            this.on("complete", function(file){
                if(this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0)
                {
                    location.reload();
                }
            });
        }
    };

    $(".thumbnail-delete").on("click", function(event){
        event.preventDefault();
        $(event.currentTarget).attr("disabled", "disabled");
        $(event.currentTarget).parentsUntil(".category_1").find(".delete-loader").css("display", "block");
        var delete_url = $(event.currentTarget).parent().attr("action");
        $.ajax({
            type: "POST",
            url: delete_url
        }).done(function(){
                $(event.currentTarget).parentsUntil(".margin-top-20").fadeOut().removeAttr("disabled").remove();
            });
    });

});
