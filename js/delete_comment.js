$(document).ready(function() {
    $('.remove').click(function(e) {
        e.preventDefault();
        var container = $(this).parent();
        var cid = $(this).attr('id');
        $.ajax({
            type: "post",
            url: "delete_comment.php",
            data: "cid=" + cid,
            success: function(response) {
                if(response == 'YES') {
                    container.slideUp('slow', function() {
                        container.remove();
                    });
                } else {
                    alert(response);
                    alert('System error');
                }
            }
        });    
    }); 
});