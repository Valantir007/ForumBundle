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
            'replace' => '<sub>$1</sub>'
        ),
        'sup' => array (
            'pattern' => '/\[sup\](.*?)\[\/sup\]/s',
            'replace' => '<sup>$1</sup>'
        ),
        'justify' => array (
            'pattern' => '/\[justify\](.*?)\[\/justify\]/s',
            'replace' => '<p style="text-align:justify">$1</p>'
        ),
        'email' => array(
            'pattern' => '/\[email=(.*?)\](.*?)\[\/email\]/s',
            'replace' => '<a href="mailto:$1">$2</a>'
        ),
        'font' => array(
            'pattern' => '/\[font=(.*?)\](.*?)\[\/font\]/s',
            'replace' => '<span style="font-family:\'$1\'">$2</span>'
        ),
        'image' => array(
            'pattern' => '/\[img=(.*?)x(.*?)\](.*?)\[\/img\]/s',
            'replace' => '<img width="$1" height="$2" src="$3">'
        ),
        'image_clear' => array(
            'pattern' => '/\[img\](.*?)\[\/img\]/s',
            'replace' => '<img src="$1">'
        ),
        'ul' => [
            'pattern' => '/\[ul\](.*?)\[\/ul\]/s',
            'replace' => '<ul>$1</ul>'
        ],
        'ol' => [
            'pattern' => '/\[ol\](.*?)\[\/ol\]/s',
            'replace' => '<ol>$1</ol>'
        ],
        'li' => [
            'pattern' => '/\[li\](.*?)\[\/li\]/s',
            'replace' => '<li>$1</li>'
        ],
        'table' => [
            'pattern' => '/\[table\](.*?)\[\/table\]/s',
            'replace' => '<table>$1</table>'
        ],
        'tbody' => [
            'pattern' => '/\[tbody\](.*?)\[\/tbody\]/s',
            'replace' => '<tbody>$1</tbody>'
        ],
        'thead' => [
            'pattern' => '/\[thead\](.*?)\[\/thead\]/s',
            'replace' => '<thead>$1</thead>'
        ],
        'tfoot' => [
            'pattern' => '/\[tfoot\](.*?)\[\/tfoot\]/s',
            'replace' => '<tfoot>$1</tfoot>'
        ],
        'tr' => array(
            'pattern' => '/\[tr\](.*?)\[\/tr\]/s',
            'replace' => '<tr>$1</tr>'
        ),
        'td' => array(
            'pattern' => '/\[td\](.*?)\[\/td\]/s',
            'replace' => '<td>$1</td>'
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
    protected $smiles = array(
        ":)" => array('file' => 'smile.png', 'pattern' => '/\\:\\)/s', 'name' => 'smile'),
        ":angel:" => array('file' => 'angel.png', 'pattern' => '/\\:angel\\:/s', 'name' => 'angel'),
        ":angry:" => array('file' => 'angry.png', 'pattern' => '/\\:angry\\:/s', 'name' => 'angry'),
        "8-)" => array('file' => 'cool.png', 'pattern' => '/8\\-\\)/s', 'name' => 'cool'),
        ":'(" => array('file' => 'cwy.png', 'pattern' => '/\\:\\\'\\(/s', 'name' => 'cwy'),
        ":ermm:" => array('file' => 'ermm.png', 'pattern' => '/\\:ermm\\:/s', 'name' => 'ermm'),
        ":D" => array('file' => 'grin.png', 'pattern' => '/\\:D/s', 'name' => 'grin'),
        "<3" => array('file' => 'heart.png', 'pattern' => '/\\<3/s', 'name' => 'heart'),
        ":(" => array('file' => 'sad.png', 'pattern' => '/\\:\\(/s', 'name' => 'sad'),
        ":O" => array('file' => 'shocked.png', 'pattern' => '/\\:O/s', 'name' => 'shocked'),
        ":P" => array('file' => 'tongue.png', 'pattern' => '/\\:P/s', 'name' => 'tongue'),
        ";)" => array('file' => 'wink.png', 'pattern' => '/\\;\\)/s', 'name' => 'wink'),
        ":alien:" => array('file' => 'alien.png', 'pattern' => '/\\:alien\\:/s', 'name' => 'alien'),
        ":blink:" => array('file' => 'blink.png', 'pattern' => '/\\:blink\\:/s', 'name' => 'blink'),
        ":blush:" => array('file' => 'blush.png', 'pattern' => '/\\:blush\\:/s', 'name' => 'blush'),
        ":cheerful:" => array('file' => 'cheerful.png', 'pattern' => '/\\:cheerful\\:/s', 'name' => 'cheerful'),
        ":devil:" => array('file' => 'devil.png', 'pattern' => '/\\:devil\\:/s', 'name' => 'devil'),
        ":dizzy:" => array('file' => 'dizzy.png', 'pattern' => '/\\:dizzy\\:/s', 'name' => 'dizzy'),
        ":getlost:" => array('file' => 'getlost.png', 'pattern' => '/\\:getlost\\:/s', 'name' => 'getlost'),
        ":happy:" => array('file' => 'happy.png', 'pattern' => '/\\:happy\\:/s', 'name' => 'happy'),
        ":kissing:" => array('file' => 'kissing.png', 'pattern' => '/\\:kissing\\:/s', 'name' => 'kissing'),
        ":ninja:" => array('file' => 'ninja.png', 'pattern' => '/\\:ninja\\:/s', 'name' => 'ninja'),
        ":pinch:" => array('file' => 'pinch.png', 'pattern' => '/\\:pinch\\:/s', 'name' => 'pinch'),
        ":pouty:" => array('file' => 'pouty.png', 'pattern' => '/\\:pouty\\:/s', 'name' => 'pouty'),
        ":sick:" => array('file' => 'sick.png', 'pattern' => '/\\:sick\\:/s', 'name' => 'sick'),
        ":sideways:" => array('file' => 'sideways.png', 'pattern' => '/\\:sideways\\:/s', 'name' => 'sideways'),
        ":silly:" => array('file' => 'silly.png', 'pattern' => '/\\:silly\\:/s', 'name' => 'silly'),
        ":sleeping:" => array('file' => 'sleeping.png', 'pattern' => '/\\:sleeping\\:/s', 'name' => 'sleeping'),
        ":unsure:" => array('file' => 'unsure.png', 'pattern' => '/\\:unsure\\:/s', 'name' => 'unsure'),
        ":woot:" => array('file' => 'w00t.png', 'pattern' => '/\\:woot\\:/s', 'name' => 'woot'),
        ":wassat:" => array('file' => 'wassat.png', 'pattern' => '/\\:wassat\\:/s', 'name' => 'wassat'),
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
            $this->parser->setParser($name, $pattern['pattern'], $pattern['replace']);
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
            $this->parser->setParser($smile['name'], $smile['pattern'], $tag);
        }
    }
}
