<?php
namespace shgysk8zer0\Geo;
class Point
{
	use \shgysk8zer0\Core_API\Traits\CreateFromArgsArray;
	const ERR_FORMAT = 'Expected X & Y to be floats. Got {%s, %s}';

	const MAX = 360;

	const MIN = 0;
	/**
	 * X-coordinate
	 *
	 * @var float
	 */
	public $x = 0.0;

	/**
	 * Y-coordinate
	 *
	 * @var float
	 */
	public $y = 0.0;

	/**
	 * Create a new coordinate set
	 *
	 * @param float $x X-coordinate
	 * @param float $y Y-Coordinate
	 */
	public function __construct($x, $y)
	{
		if (is_numeric($x) and is_numeric($y)) {
			$this->x = floatval($x);
			$this->y = floatval($y);
		} else {
			trigger_error(sprintf(self::ERR_FORMAT,gettype($x), gettype($y)));
		}
	}

	public function __toString()
	{
		return "{$this->x},{$this->y}";
	}

	public function getAngle($out = 'rad')
	{
		if ($this->y === 0) {
			$angle = 0;
		} else {
			$angle = atan($this->x / $this->y);
		}
		return $out == 'rad' ? $angle : rad2deg($angle);
	}

	public function isSameAs(Point $point)
	{
		return $this->x === $point->x and $this->y === $point->y;
	}

	public function appendToSVG(\shgysk8zer0\DOM\XMLElement $svg, array $attrs = array())
	{
		$attrs['cx'] = $this->x;
		$attrs['cy'] = $this->y;
		return $svg->append('circle', null, $attrs);
		$circle->cx = $this->x;
		$circle->cy = $this->y;
		return $circle;
		$circle = $svg->appendChild($svg->ownerDocument->createElement('circle'));
		$circle->setAttribute('cx', $this->x);
		$circle->setAttribute('cy', $this->y);
		array_map([$circle, 'setAttribute'], array_keys($attrs), array_values($attrs));
		return $circle;
	}
}
