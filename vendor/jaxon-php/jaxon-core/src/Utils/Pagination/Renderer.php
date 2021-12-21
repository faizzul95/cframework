<?php

/**
 * Renderer.php - Paginator renderer
 *
 * Render pagination links.
 *
 * @package jaxon-core
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-core
 */

namespace Jaxon\Utils\Pagination;

use Jaxon\Utils\View\Renderer as ViewRenderer;
use Jaxon\Utils\View\Store;
use Jaxon\Utils\Template\Engine;
use Jaxon\Request\Factory\Request;
use Jaxon\Request\Factory\Parameter;

class Renderer
{
    /**
     * The template renderer.
     *
     * Will be used to render HTML code for links.
     *
     * @var ViewRenderer
     */
    protected $xRenderer = null;

    /**
     * The Jaxon request to be paginated
     *
     * @var Request
     */
    protected $xRequest = null;

    /**
     * @var string
     */
    protected $previousText = '&laquo;';

    /**
     * @var string
     */
    protected $nextText = '&raquo;';

    /**
     * @var string
     */
    protected $ellipsysText = '...';

    /**
     * @var integer
     */
    protected $totalPages = 0;

    /**
     * @var integer
     */
    protected $currentPage = 0;

    /**
     * @var integer
     */
    protected $maxPagesToShow = 10;

    /**
     * The class contructor
     *
     * @param ViewRenderer          $xRenderer
     */
    public function __construct(ViewRenderer $xRenderer)
    {
        $this->xRenderer = $xRenderer;
    }

    /**
     * Set the text for the previous page link
     *
     * @param string $text The text for the previous page link
     *
     * @return void
     */
    public function setPreviousText($text)
    {
        $this->previousText = $text;
    }

    /**
     * Set the text for the next page link
     *
     * @param string $text The text for the previous page link
     *
     * @return void
     */
    public function setNextText($text)
    {
        $this->nextText = $text;
    }

    /**
     * Set the request to be paginated
     *
     * @param Request $xRequest The request to be paginated
     *
     * @return void
     */
    public function setRequest(Request $xRequest)
    {
        $this->xRequest = $xRequest;
        // Append the page number to the parameter list, if not yet given.
        if(($this->xRequest) && !$this->xRequest->hasPageNumber())
        {
            $this->xRequest->addParameter(Parameter::PAGE_NUMBER, 0);
        }
    }

    /**
     * Set the current page number
     *
     * @param int $currentPage The current page number
     *
     * @return void
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = intval($currentPage);
    }

    /**
     * Set the max number of pages to show
     *
     * @param int $maxPagesToShow The max number of pages to show
     *
     * @return void
     */
    public function setMaxPagesToShow($maxPagesToShow)
    {
        $this->maxPagesToShow = intval($maxPagesToShow);
        if($this->maxPagesToShow < 4)
        {
            $this->maxPagesToShow = 4;
        }
    }

    /**
     * Get the js call to a given page
     *
     * @param int $pageNum The page number
     *
     * @return string
     */
    protected function getPageCall($pageNum)
    {
        return $this->xRequest->setPageNumber($pageNum)->getScript();
    }

    /**
     * Render the previous link.
     *
     * @param integer   $nNumber        The page number
     * @param string    $sTemplate      The template for the call to the page
     * @param string    $sEnabledText   The text of the link if it is enabled
     * @param string    $sDisabledText  The text of the link if it is disabled
     *
     * @return null|Store
     */
    protected function getLink($nNumber, $sTemplate, $sEnabledText, $sDisabledText)
    {
        if($nNumber > 0)
        {
            return $this->xRenderer->render('pagination::links/' . $sTemplate, [
                'text' => $sEnabledText,
                'call' => $this->getPageCall($nNumber),
            ]);
        }
        return $this->xRenderer->render('pagination::links/disabled', ['text' => $sDisabledText]);
    }

    /**
     * Render the previous link.
     *
     * @return null|Store
     */
    protected function getPrevLink()
    {
        $nNumber = ($this->currentPage > 1 ? $this->currentPage - 1 : 0);
        return $this->getLink($nNumber, 'prev', $this->previousText, $this->previousText);
    }

    /**
     * Render the next link.
     *
     * @return null|Store
     */
    protected function getNextLink()
    {
        $nNumber = ($this->currentPage < $this->totalPages ? $this->currentPage + 1 : 0);
        return $this->getLink($nNumber, 'next', $this->nextText, $this->nextText);
    }

    /**
     * Render the pagination links.
     *
     * @param integer        $nNumber         The page number
     *
     * @return null|Store
     */
    protected function getPageLink($nNumber)
    {
        $sTemplate = ($nNumber == $this->currentPage ? 'current' : 'enabled');
        return $this->getLink($nNumber, $sTemplate, $nNumber, $this->ellipsysText);
    }

    /**
     * Get the array of page numbers to be printed.
     *
     * Example: [1, 0, 4, 5, 6, 0, 10]
     *
     * @return array
     */
    protected function getPageNumbers()
    {
        $pageNumbers = [];

        if($this->totalPages <= $this->maxPagesToShow)
        {
            for($i = 0; $i < $this->totalPages; $i++)
            {
                $pageNumbers[] = $i + 1;
            }

            return $pageNumbers;
        }

        // Determine the sliding range, centered around the current page.
        $numAdjacents = (int)floor(($this->maxPagesToShow - 4) / 2);

        $slidingStart = 1;
        $slidingEndOffset = $numAdjacents + 3 - $this->currentPage;
        if($slidingEndOffset < 0)
        {
            $slidingStart = $this->currentPage - $numAdjacents;
            $slidingEndOffset = 0;
        }

        $slidingEnd = $this->totalPages;
        $slidingStartOffset = $this->currentPage + $numAdjacents + 2 - $this->totalPages;
        if($slidingStartOffset < 0)
        {
            $slidingEnd = $this->currentPage + $numAdjacents;
            $slidingStartOffset = 0;
        }

        // Build the list of page numbers.
        if($slidingStart > 1)
        {
            $pageNumbers[] = 1;
            $pageNumbers[] = 0; // Ellipsys;
        }
        for($i = $slidingStart - $slidingStartOffset; $i <= $slidingEnd + $slidingEndOffset; $i++)
        {
            $pageNumbers[] = $i;
        }
        if($slidingEnd < $this->totalPages)
        {
            $pageNumbers[] = 0; // Ellipsys;
            $pageNumbers[] = $this->totalPages;
        }

        return $pageNumbers;
    }

    /**
     * Render an HTML pagination control.
     *
     * @param integer   $totalPages         The total number of pages
     *
     * @return null|Store
     */
    public function render($totalPages)
    {
        $this->totalPages = $totalPages;

        $aLinks = array_map(function($nNumber) {
            return $this->getPageLink($nNumber);
        }, $this->getPageNumbers());

        return $this->xRenderer->render('pagination::wrapper', [
            'links' => $aLinks,
            'prev' => $this->getPrevLink(),
            'next' => $this->getNextLink(),
        ]);
    }
}
