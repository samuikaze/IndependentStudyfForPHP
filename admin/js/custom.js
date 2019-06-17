// 編輯頁面預覽圖
$(document).ready(function () {
    $("#carouselImg, #prodimage, #boardimage, #goodimage").change(function () {
        if ($(this).data('prevtype') == 'add') {
            // 若有上傳檔案
            if (this.files && this.files[0]) {
                // 宣告新物件
                var reader = new FileReader();
                // 當檔案被選擇後
                reader.onload = function (e) {
                    if($("#imgPreview").length){
                        $('#imgPreview').attr('src', e.target.result);
                    }else{
                        $('#prevImg').after("<img id=\"imgPreview\" src=\"" + e.target.result + "\" style=\"width: 100%;\" />");
                    }
                }
                reader.readAsDataURL(this.files[0]);
            }
        } else {
            // 若有上傳檔案
            if (this.files && this.files[0]) {
                // 宣告新物件
                var reader = new FileReader();
                // 當檔案被選擇後
                reader.onload = function (e) {
                    // 若已經有預覽存在
                    if($("#imgPreview").length){
                        $('#imgPreview').attr('src', e.target.result);
                    }else{
                        var appendDOM = "&nbsp;&nbsp;<span style=\"font-size: 1.5em;\"><strong><i class=\"fas fa-angle-double-right\"></i></strong></span>&nbsp;&nbsp;<img id=\"imgPreview\" src=\"" + e.target.result + "\" style=\"width: 45%;\"/>";
                        $('#prevImg').css('width', '100%');
                        $('#nowimage').css('width', '45%').after(appendDOM);
                    }
                }
                reader.readAsDataURL(this.files[0]);
            }
        }
    });
});