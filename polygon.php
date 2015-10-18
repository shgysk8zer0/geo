<?php
namespace shgysk8zer0\Geo;
class Polygon
{
	use \shgysk8zer0\Core_API\Traits\Magic_Methods;
	const MAGIC_PROPERTY = '_points';

	const MAX_COORD = 360;

	const MIN_COORD = -360;

	/**
	 * Array of coordinates defining polygon
	 *
	 * @var array
	 */
	public $coords = array();

	private $_points = array();

	/**
	 * Creates a polygon by defining sets of Coords
	 *
	 * @param Coords ...
	 */
	public function __construct()
	{
		$args = func_get_args();
		$this->coords = array_filter($args, [$this, '_isCoord']);
		if (count($this->coords) < 3) {
			trigger_error(sprintf('A polygon requires at least 3 sets of points, %n given'), count($this->coords));
		}
		if (!$this->coords[0]->isSameAs(end($this->coords))) {
			trigger_error('Points create an un-closed polygon. Making final line segment.');
			array_push($this->coords, $this->coords[0]);
		}
	}

	public function __toString()
	{
		return json_encode($this->coords);
	}

	/**
	 * Performs a check if point is in bounding rectangle, then if it lies in polygon
	 *
	 * @param  Point $coords [description]
	 *
	 * @return bool             [description]
	 */
	public function containsPoint(Point $coords)
	{
		return $this->isInBounds($coords) and $this->_containsPoints($coords);
	}

	/**
	 * Protected function to check whether a point lies inside of a polygon
	 *
	 * @param  Point $coords [description]
	 *
	 * @return bool             [description]
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

	public function getRectBounds()
	{
		$x = $this->getAll('x');
		$y = $this->getAll('y');
		return array(max($x), min($x), max($y), min($y));
	}

	protected function _isCoord($var)
	{
		return $var instanceof Point;
	}

	public function getAll($axis = 'x')
	{
		return array_map(function(Point $coords) use ($axis)
		{
			return $coords->$axis;
		}, $this->coords);
	}

	public function drawSVG(array $attrs = array())
	{
		$points = array_map('strval', $this->coords);
		list($max_x, $min_x, $max_y, $min_y) = $this->getRectBounds();

		$svg = new \shgysk8zer0\DOM\SVG(['viewport' => "$min_x $min_y $max_x $max_y"]);
		$polygon = $svg->createElement('polygon');
		$svg->documentElement->appendChild($polygon);
		$attrs['points'] = join(' ', $points);
		array_map([$polygon, 'setAttribute'], array_keys($attrs), array_values($attrs));
		return $svg->documentElement;
	}
}
