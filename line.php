<?php
namespace shgysk8zer0\Geo;

class Line
{
	private $_pt1 = null;
	private $_pt2 = null;

	public function __construct(Point $pt1, Point $pt2)
	{
		$this->_pt1 = $pt1;
		$this->_pt2 = $pt2;
	}

	public function __toString()
	{
		return json_encode(array($this->_pt1, $this->_pt2));
	}

	public function appendToSVG(\DOMElement $svg, array $attrs = array())
	{
		$line = $svg->appendChild($svg->ownerDocument->createElement('line'));
		$line->setAttribute('x1', $this->_pt1->x);
		$line->setAttribute('y1', $this->_pt1->y);
		$line->setAttribute('x2', $this->_pt2->x);
		$line->setAttribute('y2', $this->_pt2->y);
		array_map([$line, 'setAttribute'], array_keys($attrs), array_values($attrs));
		return $line;
	}
}
