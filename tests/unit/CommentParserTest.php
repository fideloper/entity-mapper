<?php 

class CommentParserTest extends TestCase {

    public function testCommentTagsAreParsed()
    {
        $parser = new EntityMapper\Parser\CommentParser( $this->getComment() );

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