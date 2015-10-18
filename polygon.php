<?php
namespace shgysk8zer0\Geo;
class Polygon
{
	use Traits\PIP;

	const MAX_COORD = 360;

	const MIN_COORD = -360;

	/**
	 * Array of coordinates defining polygon
	 *
	 * @var array
	 */
	public $coords = array();

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

	/**
	 * Converts Polygon to an array of point (JSON happens to be good format)
	 *
	 * @return string A JSON encoded string of coordinates
	 */
	public function __toString()
	{
		return json_encode($this->coords);
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

	/**
	 * Create an SVG from a Polygon
	 *
	 * @param  array  $attrs Array of attributes to set on <polygon>
	 *
	 * @return \DOMElement   The resulting SVG
	 */
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
