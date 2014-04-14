<?php

class CommentParserTest extends TestCase {

    public function testCommentTagsAreParsed()
    {
        $parser = new ParserStub;
        $parser->parse( $this->getComment() );

        $tags = $parser->getTags();

        $this->assertTrue(is_array($tags));
        $this->assertEquals(3, count($tags));
        $this->assertEquals('fooVar', $tags['getter']);
        $this->assertEquals('barVar', $tags['setter']);
        $this->assertEquals('fooTable', $tags['table']);
    }

    protected function getComment()
    {
        return <<<COMMENT
/**
 * This is a short description.
 *
 * This is a *long* description.
 *
 * @getter fooVar
 * @setter barVar
 * @table fooTable
 */
COMMENT;
    }
}


class ParserStub {
    use EntityMapper\Parser\CommentParser;

    public function parse($comment)
    {
        $comment = $this->cleanInput($comment);
        $tags = $this->splitComment($comment);
        $this->tags = $this->parseTags($tags);
    }
}