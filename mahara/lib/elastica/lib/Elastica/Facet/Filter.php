<?php

namespace Elastica\Facet;

use Elastica\Filter\AbstractFilter;

/**
 * Filter facet
 *
 * @category Xodoa
 * @package Elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 * @link http://www.elastic.co/guide/en/elasticsearch/reference/current/search-facets-filter-facet.html
 */
class Filter extends AbstractFacet
{
    /**
     * Set the filter for the facet.
     *
     * @param  \Elastica\Filter\AbstractFilter $filter
     * @return $this
     */
    public function setFilter(AbstractFilter $filter)
    {
        return $this->_setFacetParam('filter', $filter->toArray());
    }
}
