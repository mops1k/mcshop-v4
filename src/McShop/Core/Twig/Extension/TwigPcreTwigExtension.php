<?php
/**
 * Created by PhpStorm.
 * User: mops1k
 * Date: 13.04.2017
 * Time: 16:18
 */

namespace McShop\Core\Twig\Extension;

class TwigPcreTwigExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'Twig_PCRE_Filters';
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('preg_filter', [$this, 'pregFilter']),
            new \Twig_SimpleFilter('preg_grep', [$this, 'pregGrep']),
            new \Twig_SimpleFilter('preg_match', [$this, 'pregMatch']),
            new \Twig_SimpleFilter('preg_quote', [$this, 'pregQuote']),
            new \Twig_SimpleFilter('preg_replace', [$this, 'pregReplace']),
            new \Twig_SimpleFilter('preg_split', [$this, 'pregSplit']),
        ];
    }


    /**
     * Perform a regular expression search and replace, returning only matched subjects.
     *
     * @param string $subject
     * @param string $pattern
     * @param string $replacement
     * @param int $limit
     * @return string
     */
    public function pregFilter($subject, $pattern, $replacement = '', $limit = -1)
    {
        if (!isset($subject)) {
            return null;
        } else {
            return preg_filter($pattern, $replacement, $subject, $limit);
        }
    }


    /**
     * Perform a regular expression match and return an array of entries that match the pattern
     *
     * @param array $subject
     * @param string $pattern
     * @return array
     */
    public function pregGrep($subject, $pattern)
    {
        if (!isset($subject)) {
            return null;
        } else {
            return preg_grep($pattern, $subject);
        }
    }


    /**
     * Perform a regular expression match.
     *
     * @param string $subject
     * @param string $pattern
     * @return boolean
     */
    public function pregMatch($subject, $pattern)
    {
        if (!isset($subject)) {
            return null;
        } else {
            return preg_match($pattern, $subject);
        }
    }


    /**
     * Quote regular expression characters.
     *
     * @param string $subject
     * @param string $delimiter
     * @return string
     */
    public function pregQuote($subject, $delimiter)
    {
        if (!isset($subject)) {
            return null;
        } else {
            return preg_quote($subject, $delimiter);
        }
    }


    /**
     * Perform a regular expression search and replace.
     *
     * @param string $subject
     * @param string $pattern
     * @param string $replacement
     * @param int $limit
     * @return string
     */
    public function pregReplace($subject, $pattern, $replacement = '', $limit = -1)
    {
        if (!isset($subject)) {
            return null;
        } else {
            return preg_replace($pattern, $replacement, $subject, $limit);
        }
    }


    /**
     * Split text into an array using a regular expression.
     *
     * @param string $subject
     * @param string $pattern
     * @return array
     */
    public function pregSplit($subject, $pattern)
    {
        if (!isset($subject)) {
            return null;
        } else {
            return preg_split($pattern, $subject);
        }
    }
}
