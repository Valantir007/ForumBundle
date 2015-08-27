$(document).ready(function(){
    var Forum = function(){};
    Forum.prototype = {
        init: function() {
            this.callTabs();
            this.addBbEditor();
//            this.bb2html();
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
//            $editor.sceditor('instance').width('100%');
//            $('.bb-editor').wysibb({
//                allButtons: {
//                    numlist : { //change ol tag from [list=1] to [ol]
//                        title: CURLANG.numlist,
//                        buttonHTML: '<span class="fonticon ve-tlb-numlist1">\uE00a</span>',
//                        excmd: 'insertOrderedList',
//                        transform : {
//                            '<ol>{SELTEXT}</ol>':"[ol]{SELTEXT}[/ol]",
//                            '<li>{SELTEXT}</li>':"[*]{SELTEXT}[/*]"
//                        }
//                    }
//                }
//            });
        },
    };
    var forum = new Forum();
    forum.init();
});