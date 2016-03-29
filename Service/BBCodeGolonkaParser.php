<?php

namespace Valantir\ForumBundle\Service;

use Golonka\BBCode\BBCodeParser;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class to parse bbcode using golonka parser
 * 
 * @link https://github.com/golonka/BBCodeParser
 *
 * @author Kamil Demurat
 */
class BBCodeGolonkaParser extends AbstractParser
{
    /**
     * @var array
     */
    protected $customPatterns = array(
        'sub' => array (
            'pattern' => '/\[sub\](.*?)\[\/sub\]/s',
            'replace' => '<sub>$1</sub>',
            'content' => '$1'
        ),
        'sup' => array (
            'pattern' => '/\[sup\](.*?)\[\/sup\]/s',
            'replace' => '<sup>$1</sup>',
            'content' => '$1'
        ),
        'justify' => array (
            'pattern' => '/\[justify\](.*?)\[\/justify\]/s',
            'replace' => '<p style="text-align:justify">$1</p>',
            'content' => '$1'
        ),
        'email' => array (
            'pattern' => '/\[email=(.*?)\](.*?)\[\/email\]/s',
            'replace' => '<a href="mailto:$1">$2</a>',
            'content' => '$2'
        ),
        'font' => array (
            'pattern' => '/\[font=(.*?)\](.*?)\[\/font\]/s',
            'replace' => '<span style="font-family:\'$1\'">$2</span>',
            'content' => '$2'
        ),
        'image' => array (
            'pattern' => '/\[img=(.*?)x(.*?)\](.*?)\[\/img\]/s',
            'replace' => '<img width="$1" height="$2" src="$3">',
            'content' => ''
        ),
        'image_clear' => array (
            'pattern' => '/\[img\](.*?)\[\/img\]/s',
            'replace' => '<img src="$1">',
            'content' => ''
        ),
        'ul' => array (
            'pattern' => '/\[ul\](.*?)\[\/ul\]/s',
            'replace' => '<ul>$1</ul>',
            'content' => '$1'
        ),
        'ol' => array (
            'pattern' => '/\[ol\](.*?)\[\/ol\]/s',
            'replace' => '<ol>$1</ol>',
            'content' => '$1'
        ),
        'li' => array (
            'pattern' => '/\[li\](.*?)\[\/li\]/s',
            'replace' => '<li>$1</li>',
            'content' => '$1'
        ),
        'table' => array (
            'pattern' => '/\[table\](.*?)\[\/table\]/s',
            'replace' => '<table>$1</table>',
            'content' => '$1'
        ),
        'tbody' => array (
            'pattern' => '/\[tbody\](.*?)\[\/tbody\]/s',
            'replace' => '<tbody>$1</tbody>',
            'content' => '$1'
        ),
        'thead' => array (
            'pattern' => '/\[thead\](.*?)\[\/thead\]/s',
            'replace' => '<thead>$1</thead>',
            'content' => '$1'
        ),
        'tfoot' => array (
            'pattern' => '/\[tfoot\](.*?)\[\/tfoot\]/s',
            'replace' => '<tfoot>$1</tfoot>',
            'content' => '$1'
        ),
        'tr' => array (
            'pattern' => '/\[tr\](.*?)\[\/tr\]/s',
            'replace' => '<tr>$1</tr>',
            'content' => '$1'
        ),
        'td' => array (
            'pattern' => '/\[td\](.*?)\[\/td\]/s',
            'replace' => '<td>$1</td>',
            'content' => '$1'
        ),
        'linebreak' => array(
            'pattern' => '/\r\n/',
            'replace' => '',
            'content' => ''
        )
    );

    /**
     * @var BBCodeParser
     */
    protected $parser;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Smiles and names of files
     * 
     * @var array
     */
    protected $smiles = array (
        ":)" => array('file' => 'smile.png', 'pattern' => '/\\:\\)/s', 'name' => 'smile', 'content' => ''),
        ":angel:" => array('file' => 'angel.png', 'pattern' => '/\\:angel\\:/s', 'name' => 'angel', 'content' => ''),
        ":angry:" => array('file' => 'angry.png', 'pattern' => '/\\:angry\\:/s', 'name' => 'angry', 'content' => ''),
        "8-)" => array('file' => 'cool.png', 'pattern' => '/8\\-\\)/s', 'name' => 'cool', 'content' => ''),
        ":'(" => array('file' => 'cwy.png', 'pattern' => '/\\:\\\'\\(/s', 'name' => 'cwy', 'content' => ''),
        ":ermm:" => array('file' => 'ermm.png', 'pattern' => '/\\:ermm\\:/s', 'name' => 'ermm', 'content' => ''),
        ":D" => array('file' => 'grin.png', 'pattern' => '/\\:D/s', 'name' => 'grin', 'content' => ''),
        "<3" => array('file' => 'heart.png', 'pattern' => '/\\<3/s', 'name' => 'heart', 'content' => ''),
        ":(" => array('file' => 'sad.png', 'pattern' => '/\\:\\(/s', 'name' => 'sad', 'content' => ''),
        ":O" => array('file' => 'shocked.png', 'pattern' => '/\\:O/s', 'name' => 'shocked', 'content' => ''),
        ":P" => array('file' => 'tongue.png', 'pattern' => '/\\:P/s', 'name' => 'tongue', 'content' => ''),
        ";)" => array('file' => 'wink.png', 'pattern' => '/\\;\\)/s', 'name' => 'wink', 'content' => ''),
        ":alien:" => array('file' => 'alien.png', 'pattern' => '/\\:alien\\:/s', 'name' => 'alien', 'content' => ''),
        ":blink:" => array('file' => 'blink.png', 'pattern' => '/\\:blink\\:/s', 'name' => 'blink', 'content' => ''),
        ":blush:" => array('file' => 'blush.png', 'pattern' => '/\\:blush\\:/s', 'name' => 'blush', 'content' => ''),
        ":cheerful:" => array('file' => 'cheerful.png', 'pattern' => '/\\:cheerful\\:/s', 'name' => 'cheerful', 'content' => ''),
        ":devil:" => array('file' => 'devil.png', 'pattern' => '/\\:devil\\:/s', 'name' => 'devil', 'content' => ''),
        ":dizzy:" => array('file' => 'dizzy.png', 'pattern' => '/\\:dizzy\\:/s', 'name' => 'dizzy', 'content' => ''),
        ":getlost:" => array('file' => 'getlost.png', 'pattern' => '/\\:getlost\\:/s', 'name' => 'getlost', 'content' => ''),
        ":happy:" => array('file' => 'happy.png', 'pattern' => '/\\:happy\\:/s', 'name' => 'happy', 'content' => ''),
        ":kissing:" => array('file' => 'kissing.png', 'pattern' => '/\\:kissing\\:/s', 'name' => 'kissing', 'content' => ''),
        ":ninja:" => array('file' => 'ninja.png', 'pattern' => '/\\:ninja\\:/s', 'name' => 'ninja', 'content' => ''),
        ":pinch:" => array('file' => 'pinch.png', 'pattern' => '/\\:pinch\\:/s', 'name' => 'pinch', 'content' => ''),
        ":pouty:" => array('file' => 'pouty.png', 'pattern' => '/\\:pouty\\:/s', 'name' => 'pouty', 'content' => ''),
        ":sick:" => array('file' => 'sick.png', 'pattern' => '/\\:sick\\:/s', 'name' => 'sick', 'content' => ''),
        ":sideways:" => array('file' => 'sideways.png', 'pattern' => '/\\:sideways\\:/s', 'name' => 'sideways', 'content' => ''),
        ":silly:" => array('file' => 'silly.png', 'pattern' => '/\\:silly\\:/s', 'name' => 'silly', 'content' => ''),
        ":sleeping:" => array('file' => 'sleeping.png', 'pattern' => '/\\:sleeping\\:/s', 'name' => 'sleeping', 'content' => ''),
        ":unsure:" => array('file' => 'unsure.png', 'pattern' => '/\\:unsure\\:/s', 'name' => 'unsure', 'content' => ''),
        ":woot:" => array('file' => 'w00t.png', 'pattern' => '/\\:woot\\:/s', 'name' => 'woot', 'content' => ''),
        ":wassat:" => array('file' => 'wassat.png', 'pattern' => '/\\:wassat\\:/s', 'name' => 'wassat', 'content' => ''),
    );

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->parser = new BBCodeParser();
        $this->addCustomPatterns();
    }

    /**
     * {@inheritdoc}
     */
    public function bb2html($text)
    {
        return $this->parser->parse($text);
    }

    /**
     * Adds custom patterns for parser
     */
    public function addCustomPatterns()
    {
        foreach ($this->customPatterns as $name => $pattern) {
            $this->parser->setParser($name, $pattern['pattern'], $pattern['replace'], $pattern['content']);
        }

        $this->addSmiles();
    }

    /**
     * Adds emoticons
     * 
     * @return void
     */
    protected function addSmiles()
    {
        $path = $this->container->get('templating.helper.assets')->getUrl('/bundles/valantirforum/plugin/sceditor/emoticons/');

        foreach($this->smiles as $smile) {
            $tag = '<img src="' . $path  . $smile['file'] . '" alt="' . $smile['name'] . '">';
            $this->parser->setParser($smile['name'], $smile['pattern'], $tag, $smile['content']);
        }
    }
}
