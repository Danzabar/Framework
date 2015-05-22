<?php namespace Wasp\Utils;

use Wasp\Utils\Collection;

/**
 * Section sorter trait - sorts arrays into mapped collections by keys
 *
 * @package Wasp
 * @subpackage Utils
 * @author Dan Cox
 */
Trait SectionSorterTrait
{
	/**
	 * Sorts the sortable array by the dictated keys in sortKeys
	 *
	 * @param Array $sortable
	 * @param Array $sortKeys
	 * @return Collection
	 * @author Dan Cox
	 */
	public function sortSections(Array $sortable, Array $sortKeys)
	{
		$collection = new Collection;

		foreach ($sortKeys as $key => $value)
		{
			$section = new Collection;

			foreach ($sortable as $k => $v)
			{
				if (in_array($k, $value))
				{
					$section->add($k, $v);		
				}
			}		
			
			$collection->add($key, $section);
		}

		return $collection;
	}
}
