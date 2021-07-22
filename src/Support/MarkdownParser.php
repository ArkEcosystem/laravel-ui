<?php

declare(strict_types=1);

namespace ARKEcosystem\UserInterface\Support;

use tidy;
use DOMAttr;
use DOMNodeList;
use DOMDocument;
use DOMXPath;
use Illuminate\Support\Arr;
use League\CommonMark\MarkdownConverterInterface;
use Exception;


final class MarkdownParser
{
    /**
     * Only this tags and attributes will be allowed (globally) when working with
     * markdown.
     */
    private static array $validUserTagsAndAttributes = [
        'h2' => [],
        'h3' => [],
        'h4' => [],
        'ins' => [],
        'i' => [],
        'a' => ['id', 'href', 'name', 'class', 'aria-hidden', 'title', 'target', 'data-external'],
        'p' => [],
        'br' => [],
        'ul' => [],
        'ol' => [],
        'li' => [],
        'strong' => [],
        'b' => [],
        'em' => [],
        'i' => [],
        'img' => ['src', 'alt'],
        'del' => [],
        'strike' => [],
        'blockquote' => [],
        'table' => [],
        'tbody' => [],
        'thead' => [],
        'tr' => [],
        'td' => [],
        'code' => [],
        'pre' => [],
    ];

    /**
     * Tags that will be kept when using the basic markdown.
     */
    private static array $basicAllowedTags = [
        'a',
        'p',
        'br',
        'ul',
        'ol',
        'li',
        // bold variants
        'strong',
        'b',
        // italic variants
        'em',
        'i',
    ];

    /**
     * Tags that will be kept when using the full markdown.
     */
    private static array $fullAllowedTags = [
        'h2',
        'h3',
        'h4',
        'a',
        'p',
        'br',
        'ul',
        'ol',
        'li',
        'blockquote',
        'table',
        'tbody',
        'thead',
        'pre',
        'code',
        'tr',
        'td',
        // underline
        'ins',
        // bold variants
        'strong',
        'b',
        // italic variants
        'em',
        'i',
        // strile variants
        'del',
        'strike',
    ];

    /**
     * Temporaly will hold the custom components content that is being stripped
     * while the markdown is parsed.
     */
    private static array $replaced = [];

    private MarkdownConverterInterface $markdownConverter;

    public function __construct()
    {
        $this->markdownConverter = resolve(MarkdownConverterInterface::class);
    }

    public function getMarkdownCoverter(): MarkdownConverterInterface
    {
        return $this->markdownConverter;
    }

    public static function basic(string | null $text): string
    {
        if ($text === null) {
            return '';
        }

        // Encodes the header symbols (`#`) so they are not converted to HTML
        $text = static::encodeMarkdownHeaders($text);

        $html = static::getHtml($text);

        $html = static::removeUnallowedHTMLTags($html, static::$basicAllowedTags);

        return $html;
    }

    public static function full(string | null $text): string
    {
        if ($text === null) {
            return '';
        }

        $html = static::getHtml($text);

        $html = static::removeUnallowedHTMLTags($html, static::$fullAllowedTags);

        return $html;
    }

    /**
     * The HTML converter keeps the line breaks (`\n`) that are inside a
     * HTML tag like a paragraph (`<p>`) and that creates unexpected HTML when
     * converted from mardown to HTML.
     * This method replaces every line break for the HTML `<br />` tag.
     */
    private static function replaceLineBreaksInsideTagsForBr(string $text): string
    {
        $regex = '/<(p|a|strong|b|em|i|li|ins|h2|h3|h4|td)\b(?:[^>]*)>[^<]*<\/\1>/m';

        preg_match_all($regex, $text, $matches, PREG_SET_ORDER, 0);

        foreach ($matches as $match) {
            $replaced = preg_replace('/\r\n|\r|\n/', '<br />', $match[0]);
            $text = str_replace($match[0], $replaced, $text);
        }

        return $text;
    }

    /**
     * Replaces every user HTML Paragraph Tag for the markdown equivalent: Two
     * break lines. (So we can work with normalized text).
     */
    private static function replaceParagraphsForMarkdown(string $text): string
    {
        // Any text inside a <p>
        $regex = '/<p\b[^>]*>((?:\s|\S)*?)<\/p>\s*/m';

        // Adds two break lines
        $substitution = "$1\n\n";

        return preg_replace($regex, $substitution, $text);
    }

    /**
     * Searchs for every HTML `inline` tag (a, strong, etc) and wraps it in a
     * paragraph (`p`). Prevents unexpected HTML and unexpected stripped spaces
     * when converting from markdown to HTML.
     */
    private static function wrapBasicTagsInAParagraph(string $text): string
    {
        $regex = '/<((?:a|strong|b|em|i|li|ins))\b([^>]*)>((?:\s|\S)*?)<\/\1>\s*/m';

        $substitution = "<p><$1$2>$3</$1></p>";

        return preg_replace($regex, $substitution, $text);
    }

    /**
     * Searchs for every HTML `img` tag and wraps it in a paragraph (`p`) in order
     * to have normalized data.
     */
    private static function wrapImagesTagsInAParagraph(string $text): string
    {
        // Any text inside a <p>
        $regex = '/<img\b([^>]*)\/>/m';

        // Adds two break lines
        $substitution = "<p><img$1/></p>";

        return preg_replace($regex, $substitution, $text);
    }

    /**
     * Takes both user HTML and markdown content and normalizes it to preparate
     * it to be converted to HTML.
     */
    private static function normalizeHTMLAndMarkdown(string $text): string
    {
        $markdown = static::wrapBasicTagsInAParagraph($text);

        $markdown = static::wrapImagesTagsInAParagraph($markdown);

        $markdown = static::replaceLineBreaksInsideTagsForBr($markdown);

        return static::replaceParagraphsForMarkdown($markdown);
    }

    /**
     * Uses the markdown converter to convert the markdown content to HTML.
     */
    private static function convertMarkdownToHtml(string $markdown): string
    {
        $html = (new static)->getMarkdownCoverter()->convertToHtml($markdown);

        return static::replaceLineBreaksInsideTagsForBr($html);
    }

    /**
     * Removes all the tags that are not allowed to be used.
     */
    private static function removeUnallowedHTMLTags(string $html, array $tags): string
    {
        $allowedTagsStr = '<' . implode('><', $tags) . '>';

        return strip_tags($html, $allowedTagsStr);
    }

    /**
     * Replaces the temporal placeholders with the original components.
     */
    private static function rollbackMarkdownComponents(string $html): string
    {
        foreach(static::$replaced as $id => $originalHTML)
        {

            $html = str_replace('[' . $id . ']', $originalHTML, $html);
        }

        return $html;
    }

    /**
     * Removes every used input that is not expected
     */
    private static function cleanHtml(string $html): string
    {
        $html = static::removeUnallowedHTMLTags($html, array_keys(static::$validUserTagsAndAttributes));

        $html = static::removeUnallowedHTMLAttributes($html);

        return $html;
    }

    /**
     * Gets a clean, xss-safe HTML string from a markdown string.
     */
    private static function getHtml(string $text): string
    {
        $markdown = static::normalizeHTMLAndMarkdown($text);

        $html = static::convertMarkdownToHtml($markdown);

        $safeHtml = static::cleanHtml($html);

        return $safeHtml;
    }

    /**
     * Used for basic markdown. Encodes the `#` character so its rendered as it
     * is instead of being converted to a header.
     */
    private static function encodeMarkdownHeaders(string $text): string
    {
        return str_replace('#', '&#35;', $text);
    }

    /**
     * Removes every HTML attribute that is not allowed.
     */
    private static function removeUnallowedHTMLAttributes(string $html): string
    {
        $tidy = new tidy();

        $dom = new DOMDocument;

        // Normalizes the HTML which reduce the possibility of having an unexpected
        // exception in the DomDocument library because some bad HTML was found.
        // Once the HTML is normalized, we will parse it again.
        $html = $tidy->repairString($html, [
            'output-xhtml' => true,
            'show-body-only' => true,
        ], 'utf8');

        try {
            // Needs a XML encoding declaration to ensure is treated as UTF-8
            $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
        } catch (Exception $e) {
            return '';
        }

        $attributes = static::getAttributesNodes($dom);

        collect($attributes)
            ->filter(fn ($attribute) => static::isAttributeAllowedForTag($attribute))
            ->each(fn($node) => $node->parentNode->removeAttribute($node->nodeName));

        return $tidy->repairString($dom->saveHTML(), [
            'output-xhtml' => true,
            'show-body-only' => true,
        ], 'utf8');
    }

    /**
     * Returns all the nodes that have attributes
     */
    private static function getAttributesNodes(DOMDocument $dom): DOMNodeList
    {
        $xpath = new DOMXPath($dom);
        return $xpath->query('//@*');
    }

    /**
     * Verifies if the attribute is allowed for the parent node tag name.
     */
    private static function isAttributeAllowedForTag(DOMAttr $attribute): bool
    {
        return !in_array(
            $attribute->nodeName,
            Arr::get(static::$validUserTagsAndAttributes, $attribute->parentNode->tagName, [])
        );
    }
}
