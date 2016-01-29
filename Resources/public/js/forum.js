(function ($) {
    var Forum = function(){};
    Forum.prototype = {
        init: function() {
            this.callTabs();
            this.addBbEditor();
            this.scrollToEditor();
            this.confirmationModal();
        },
        callTabs: function() {
            $('.nav-tabs a').click(function (e) {
                e.preventDefault();
                $(this).tab('show');
            });
        },
        addBbEditor: function() {
            var $editor = $(".bb-editor");
            $editor.sceditor({
                plugins: "bbcode",
                style: "/bundles/valantirforum/plugin/sceditor/minified/jquery.sceditor.default.min.css",
                locale: $editor.data('locale'),
                emoticonsRoot: "/bundles/valantirforum/plugin/sceditor/",
                toolbar: 'bold,italic,underline,strike|subscript,superscript|left,center,right,justify|font,size,color,removeformat|cut,copy,paste,pastetext|bulletlist,orderedlist|table,code,quote|image,email,link,unlink,emoticon,youtube|date,time|ltr,rtl|print,maximize|source'
            });
        },
        scrollToEditor: function() {
            if ($('[data-scroll="1"]').length) {
                $('html, body').animate({
                    scrollTop: $("form[name='post_type']").offset().top
                }, 2000);
            }
        },
        confirmationModal: function() {
            $('#confirm-remove').on('show.bs.modal', function(e) {
                $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
                $('#confirm-remove .modal-header').html($(e.relatedTarget).data('modaltitle'));
                $('#confirm-remove .modal-body').html($(e.relatedTarget).data('modalbody'));
            });
        }
    };
    var forum = new Forum();
    forum.init();
}( jQuery ));