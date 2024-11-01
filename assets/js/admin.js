jQuery(document).ready(function($){
    $('.htmlexport').click(function(e){
        e.preventDefault(); 

        let thePost = $(this).attr('data-id');

        let url = HTMLEXPORT.ajaxurl;
        let nonce = HTMLEXPORT.nonce;
        let siteName = HTMLEXPORT.sitename;
        let date = HTMLEXPORT.date;


        var link = document.createElement('a');
        link.href =  url +'?action=htmlexportlist&nonce='+nonce + '&posts[]=' + thePost;
        link.target = '_blank';
        link.click();
    });

});