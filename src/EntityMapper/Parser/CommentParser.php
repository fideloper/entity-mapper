<?php namespace EntityMapper\Parser;

/**
 * Parse Comment "Tags"
 * Usage:
 * $comment = $this->cleanInput($comment);
 * $tags = $this->splitComment($comment);
 * $this->tags = $this->parseTags($tags);
 *
 * Class CommentParser
 * @package EntityMapper\Parser
 */
trait CommentParser {

    /**
     * Strips the asterisks from the DocBlock comment.
     *
     * @param string $comment String containing the comment text.
     *
     * @return string
     */
    protected function cleanInput($comment)
    {
        $comment = trim(
            preg_replace(
                '#[ \t]*(?:\/\*\*|\*\/|\*)?[ \t]{0,1}(.*)?#u',
                '$1',
                $comment
            )
        );

        // reg ex above is not able to remove */ from a single line comment
        if (substr($comment, -2) == '*/') {
            $comment = trim(substr($comment, 0, -2));
        }

        // normalize strings
        $comment = str_replace(array("\r\n", "\r"), "\n", $comment);

        return $comment;
    }

    /**
     * Splits the comments into a short description, long description and
     * block of tags.
     */
    protected function splitComment($comment)
    {
        if (strpos($comment, '@') === 0) {
            $matches = array('', '', $comment);
        } else {
            // clears all extra horizontal whitespace from the line endings
            // to prevent parsing issues
            $comment = preg_replace('/\h*$/Sum', '', $comment);

            /*
             * Splits the comment into a short description, long description and
             * tags section
             * - The short description is started from the first character until
             *   a dot is encountered followed by a newline OR
             *   two consecutive newlines (horizontal whitespace is taken into
             *   account to consider spacing errors)
             * - The long description, any character until a new line is
             *   encountered followed by an @ and word characters (a tag).
             *   This is optional.
             * - Tags; the remaining characters
             *
             * Big thanks to RichardJ for contributing this Regular Expression
             */
            preg_match(
                '/
        \A (
          [^\n.]+
          (?:
            (?! \. \n | \n{2} ) # disallow the first seperator here
            [\n.] (?! [ \t]* @\pL ) # disallow second seperator
            [^\n.]+
          )*
          \.?
        )
        (?:
          \s* # first seperator (actually newlines but it\'s all whitespace)
          (?! @\pL ) # disallow the rest, to make sure this one doesn\'t match,
          #if it doesn\'t exist
          (
            [^\n]+
            (?: \n+
              (?! [ \t]* @\pL ) # disallow second seperator (@param)
              [^\n]+
            )*
          )
        )?
        (\s+ [\s\S]*)? # everything that follows
        /ux',
                $comment,
                $matches
            );
            array_shift($matches);
        }

        while (count($matches) < 3) {
            $matches[] = '';
        }

        // Return only tags
        return $matches[2];
    }

    /**
     * Creates the tag objects.
     *
     * @param string $tags Tag block to parse.
     *
     * @throws \LogicException
     * @return array
     */
    protected function parseTags($tags)
    {
        $result = [];
        $final = [];
        $tags = trim($tags);
        if ('' !== $tags) {
            if ('@' !== $tags[0]) {
                throw new \LogicException(
                    'A tag block started with text instead of an actual tag,'
                    . ' this makes the tag block invalid: ' . $tags
                );
            }
            foreach (explode("\n", $tags) as $tag_line) {
                if (isset($tag_line[0]) && ($tag_line[0] === '@')) {
                    $result[] = $tag_line;
                } else {
                    $result[count($result) - 1] .= "\n" . $tag_line;
                }
            }

            // Parse Tags
            foreach ($result as $tag_line) {
                if (!preg_match(
                    '/^@([\w\-\_\\\\]+)(?:\s*([^\s].*)|$)?/us',
                    $tag_line,
                    $matches
                )) {
                    throw new \InvalidArgumentException(
                        'Invalid tag_line detected: ' . $tag_line
                    );
                }
                $final[$matches[1]] = $matches[2];
            }
        }

        return $final;
    }

    /**
     * Convert CamelCaseClassName to underscore_class_name
     * The Underscore style class name is what we'll be
     * assuming is used for database table names
     * @param string $camelCase
     * @return string
     */
    protected function camelToUnderscore($camelCase)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $camelCase));
    }
} 