<?php

namespace Valantir\ForumBundle\Parser;

/**
 * Class to parse bbcode
 *
 * @author Kamil
 */
class BBCodeParser {
    
    protected $tags = array(
        'b'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<b>', 'close_tag'=>'</b>'),
        'u'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<u>', 'close_tag'=>'</u>'),
        'i'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<i>', 'close_tag'=>'</i>'),
        's'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<strike>', 'close_tag'=>'</strike>'),
        'sup'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<sup>', 'close_tag'=>'</sup>'),
        'sub'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<sub>', 'close_tag'=>'</sub>'),
        'img'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<img src="', 'close_tag'=>'" />'),
        'video'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<iframe src="http://www.youtube.com/embed/', 'close_tag'=>'"></iframe>'),
        'url'=> array('type'=>BBCODE_TYPE_ARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'default_arg'=>'{CONTENT}', 'open_tag'=>'<a href="{PARAM}">', 'close_tag'=>'</a>'),
        '*'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<li>', 'close_tag'=>'</li>'),
        'list'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<ul>', 'close_tag'=>'</ul>'),
        'ol'=> array('type'=>BBCODE_TYPE_OPTARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<ol>', 'close_tag'=>'</ol>'),
        'color'=> array('type'=>BBCODE_TYPE_ARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'default_arg'=>'{CONTENT}', 'open_tag'=>'<span style="color:{PARAM}">', 'close_tag'=>'</span>'),
        'size'=> array('type'=>BBCODE_TYPE_ARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'default_arg'=>'{CONTENT}', 'open_tag'=>'<span style="font-size:{PARAM}%">', 'close_tag'=>'</span>'),
        'font'=> array('type'=>BBCODE_TYPE_ARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'default_arg'=>'{CONTENT}', 'open_tag'=>'<span style="font-family:\'{PARAM}\'">', 'close_tag'=>'</span>'),
        'left'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<p style="text-align:left">', 'close_tag'=>'</p>'),
        'center'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<p style="text-align:center">', 'close_tag'=>'</p>'),
        'right'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<p style="text-align:right">', 'close_tag'=>'</p>'),
        'quote'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<q>', 'close_tag'=>'</q>'),
        'code'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<code>', 'close_tag'=>'</code>'),
        'td'=> array('type'=>BBCODE_TYPE_NOARG, 'open_tag'=>'<td>', 'close_tag'=>'</td>'),
        'tr'=> array('type'=>BBCODE_TYPE_NOARG, 'open_tag'=>'<tr>', 'close_tag'=>'</tr>'),
        'table'=> array('type'=>BBCODE_TYPE_NOARG, 'open_tag'=>'<table class="table table-bordered">', 'close_tag'=>'</table>'),
    );
    
    protected $handler;
    
    public function __construct() {
        $this->handler = bbcode_create($this->tags);
        $this->setOptions();
    }
    
    public function setOptions() {
        bbcode_set_flags($this->handler, BBCODE_CORRECT_REOPEN_TAGS, BBCODE_SET_FLAGS_SET);
    }
    
    /**
     * Converts bbcode to html
     * 
     * @param string $text
     */
    public function bb2html($text) {
        return bbcode_parse($this->handler, $text);
    }
}
