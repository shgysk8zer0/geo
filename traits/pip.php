<?php

namespace shgysk8zer0\Geo\Traits;
trait PIP
{
	/**
	 * Performs a check if point is in bounding rectangle, then if it lies in polygon
	 *
	 * @param  Point $coords The {x,y} position of the point (from Point class)
	 *
	 * @return bool          Whether or not the points are inside the polygon
	 */
	public function containsPoint(Point $coords)
	{
		return $this->isInBounds($coords) and $this->_containsPoints($coords);
	}

	/**
	 * Protected function to check whether a point lies inside of a polygon
	 *
	 * @param  Point $coords    The {x,y} position of the point (from Point class)
	 *
	 * @return bool             Whether or not the points are inside the polygon
	 * @todo Actually test this.
	 * @see http://assemblysys.com/php-point-in-polygon-algorithm/
	 */
	protected function _containtsPoints(Point $coords)
	{
		return true;
	}

	/**
	 * A quick and rough estimate to determine if Coords are in a rectangle
	 * defined by the min & max X & Y coordinates of the polygon
	 *
	 * @param  Coords $coords Coordinates to check
	 *
	 * @return [type]         [description]
	 */
	public function isInBounds(Point $coords)
	{
		list($max_x, $min_x, $max_y, $min_y) = $this->getRectBounds();

		return (
			$coords->x >= $min_x and $coords->x <= $max_x
			and $coords->y >= $min_y and $coords->y <= $max_y
		);
	}
}
