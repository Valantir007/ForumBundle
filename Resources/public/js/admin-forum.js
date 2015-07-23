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
            $('.bb-editor').wysibb({
                allButtons: {
                    numlist : {
                        title: CURLANG.numlist,
                        buttonHTML: '<span class="fonticon ve-tlb-numlist1">\uE00a</span>',
                        excmd: 'insertOrderedList',
                        transform : {
                            '<ol>{SELTEXT}</ol>':"[ol]{SELTEXT}[/ol]",
                            '<li>{SELTEXT}</li>':"[*]{SELTEXT}[/*]"
                        }
                    }
                }
            });
        },
//        bbGhostEditor: function(text) {
//            var wysibb = $('<textarea></textarea>').wysibb();
//            return wysibb.bbcode(text);
//        },
//        bb2html: function() {
//            var forum = this;
//            $('.bb2html').each(function(index, item){
//                var $item = $(item),
//                    editor = forum.bbGhostEditor($item.text()),
//                    $html = editor.htmlcode();
//                    console.log(editor.htmlcode());
////                $html.find('.wbbtab').remove();
////                $html.each(function(index2, item2){
////                    if($(item2).hasClass('wbbtab')){
////                        $(item2).remove();
////                    }
////                })
//                console.log('test', $html);
//                $item.html($html);
//            });
//        }
    };
    var forum = new Forum();
    forum.init();
});