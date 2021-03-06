<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011-2012 In Cite Solution <technique@in-cite.net>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/


/**
 * Class 'RestaurantSortingProvider' for the 'ics_sitlor_query' extension.
 *
 * @author	Tsi YANG <tsi@in-cite.net>
 * @package	TYPO3
 * @subpackage	tx_icssitlorquery
 */
class tx_icssitlorquery_RestaurantSortingProvider implements tx_icssitquery_ISortingProvider {
	private $value;
	private $sortings = array(
		'random',
		// 'rating',	// TODO : stand by till Nancy get the classment to use
		'price',
	);

	/**
	 * Constructor
	 *
	 * @param	string		$value : Sorting's value
	 * @param	string		$extra : order "ASC"/"DESC" or random extra data
	 * @return	void
	 */
	function __construct($value, $extra='') {
		if (!is_string($value))
			throw new Exception('Type must be a string.');
		if (!in_array($value, $this->sortings))
			throw new Exception('Unkown type sorting ' . $value . '.');
		if ($value != 'random' && $extra && !in_array(strtoupper($extra), array('ASC', 'DESC')))
			$extra = 'ASC';

		$this->value = array($value, $extra);
	}

	/**
	 * Apply sorting
	 *
	 * @param	IQuery		$query : The IQuery
	 * @return	void
	 */
	function apply(tx_icssitquery_IQuery $query) {
		$query->setParameter('restaurantSorting', $this->value);
	}
}