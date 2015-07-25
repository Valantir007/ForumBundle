<?php

namespace Valantir\ForumBundle\Parser;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class to parse bbcode
 * 
 * TODO: Write parser without bb_code extension
 *
 * @author Kamil
 */
class BBCodeParser {
    
    /**
     * Tags to replace
     * 
     * @var array
     */
    protected $tags = array(
        'b'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<b>', 'close_tag'=>'</b>'),
        'u'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<u>', 'close_tag'=>'</u>'),
        'i'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<i>', 'close_tag'=>'</i>'),
        's'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<strike>', 'close_tag'=>'</strike>'),
        'sup'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<sup>', 'close_tag'=>'</sup>'),
        'sub'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<sub>', 'close_tag'=>'</sub>'),
        'img'=> array('type'=>BBCODE_TYPE_ARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<img {PARAM} src="', 'close_tag'=>'" />',
            'param_handling' => 'self::parseImg'),
        'youtube'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<iframe src="http://www.youtube.com/embed/', 'close_tag'=>'"></iframe>'),
        'url'=> array('type'=>BBCODE_TYPE_ARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'default_arg'=>'{CONTENT}', 'open_tag'=>'<a href="{PARAM}">', 'close_tag'=>'</a>'),
        'li'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<li>', 'close_tag'=>'</li>'),
        'ul'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<ul>', 'close_tag'=>'</ul>'),
        'ol'=> array('type'=>BBCODE_TYPE_OPTARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<ol>', 'close_tag'=>'</ol>'),
        'color'=> array('type'=>BBCODE_TYPE_ARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'default_arg'=>'{CONTENT}', 'open_tag'=>'<span style="color:{PARAM}">', 'close_tag'=>'</span>'),
        'size'=> array('type'=>BBCODE_TYPE_ARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'default_arg'=>'{CONTENT}', 'open_tag'=>'<span style="font-size:{PARAM}0px">', 'close_tag'=>'</span>'),
        'font'=> array('type'=>BBCODE_TYPE_ARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'default_arg'=>'{CONTENT}', 'open_tag'=>'<span style="font-family:\'{PARAM}\'">', 'close_tag'=>'</span>'),
        'left'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<p style="text-align:left">', 'close_tag'=>'</p>'),
        'center'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<p style="text-align:center">', 'close_tag'=>'</p>'),
        'justify'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<p style="text-align:justify">', 'close_tag'=>'</p>'),
        'right'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<p style="text-align:right">', 'close_tag'=>'</p>'),
        'quote'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<blockquote>', 'close_tag'=>'</blockquote>'),
        'code'=> array('type'=>BBCODE_TYPE_NOARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'open_tag'=>'<code>', 'close_tag'=>'</code>'),
        'td'=> array('type'=>BBCODE_TYPE_NOARG, 'open_tag'=>'<td>', 'close_tag'=>'</td>'),
        'tr'=> array('type'=>BBCODE_TYPE_NOARG, 'open_tag'=>'<tr>', 'close_tag'=>'</tr>'),
        'table'=> array('type'=>BBCODE_TYPE_NOARG, 'open_tag'=>'<table class="table table-bordered">', 'close_tag'=>'</table>'),
        'email'=> array('type'=>BBCODE_TYPE_ARG, 'flags'=>BBCODE_FLAGS_REMOVE_IF_EMPTY, 'default_arg'=>'{CONTENT}', 'open_tag'=>'<a href="mailto:{PARAM}">', 'close_tag'=>'</a>'),
        'ltr'=> array('type'=>BBCODE_TYPE_NOARG, 'open_tag'=>'<p dir="ltr">', 'close_tag'=>'</p>'),
        'rtl'=> array('type'=>BBCODE_TYPE_NOARG, 'open_tag'=>'<p dir="rtl">', 'close_tag'=>'</p>'),
    );
    
    /**
     * Smiles and names of files
     * 
     * @var array
     */
    protected $smiles = array(
        ":)" => 'smile.png',
        ":angel:" => 'angel.png',
        ":angry:" => 'angry.png',
        "8-)" => 'cool.png',
        ":'(" => 'cwy.png',
        ":ermm:" => 'ermm.png',
        ":D" => 'grin.png',
        "<3" => 'heart.png',
        ":(" => 'sad.png',
        ":O" => 'shocked.png',
        ":P" => 'tongue.png',
        ";)" => 'wink.png',
        ":alien:" => 'alien.png',
        ":blink:" => 'blink.png',
        ":blush:" => 'blush.png',
        ":cheerful:" => 'cheerful.png',
        ":devil:" => 'devil.png',
        ":dizzy:" => 'dizzy.png',
        ":getlost:" => 'getlost.png',
        ":happy:" => 'happy.png',
        ":kissing:" => 'kissing.png',
        ":ninja:" => 'ninja.png',
        ":pinch:" => 'pinch.png',
        ":pouty:" => 'pouty.png',
        ":sick:" => 'sick.png',
        ":sideways:" => 'sideways.png',
        ":silly:" => 'silly.png',
        ":sleeping:" => 'sleeping.png',
        ":unsure:" => 'unsure.png',
        ":woot:" => 'w00t.png',
        ":wassat:" => 'wassat.png',
    );
    
    protected $handler; //bbcode handler
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }
    
    /**
     * Adds smiles to handler
     */
    protected function addSmiles() {
        $path = $this->container->get('templating.helper.assets')->getUrl('/bundles/valantirforum/plugin/sceditor/emoticons/');

        foreach($this->smiles as $smile => $filename) {
            $tag = '<img src="' . $path . $filename . '" alt="' . $smile . '" />';
            bbcode_add_smiley($this->handler, $smile, $tag);
        }
    }
    
    /**
     * Converts bbcode to html
     * 
     * @param string $text
     */
    public function bb2html($text) {
        $this->handler = bbcode_create($this->tags);
        $this->addSmiles();
        $parsedText = bbcode_parse($this->handler, $text);
        bbcode_destroy($this->handler);
        return $parsedText;
    }
    
    /**
     * Img callback
     * 
     * @param string $content
     * @param string $param
     */
    public static function parseImg($content = '', $param = '') {
        $sizes = explode('x', $param);
        $return = '';
        if(empty($sizes)) {
            return $param;
        }
        
        if(isset($sizes[0])) {
            $return .= ' width="' . $sizes[0] . '"';
        }
        
        if(isset($sizes[1])) {
            $return .= ' height="' . $sizes[1] . '"';
        }
        return $return;
    }
}
