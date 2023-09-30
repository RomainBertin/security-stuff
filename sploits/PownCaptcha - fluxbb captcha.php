<?php

$res = new PownCaptcha();

if (($res->setimage('captcha.png')) == false)
	exit ("Impossible d'acceder au captcha !");

if (($pos = $res->get_position()) == false)
	print "Error";
else
	print_r($pos);
	
unset($res);

class PownCaptcha
{
	private $url;
	private $resources;
	private $size = array();
	private $pixel_r = array();
	
	public function setimage($image)
	{
		if (($this->resources = @imagecreatefrompng($image)) === FALSE)
			return false;
		
		$this->size = getimagesize($image);		
		return true;
	}
	
	public function get_position()
	{
		$i = 0;

		for ($width = 0; $width < ($this->size[0]); $width++)
		{
			for ($height = 0; $height < ($this->size[1]); $height++, $i++)
			{
				$this->pixel_r[$i] = imagecolorat($this->resources, $width, $height);
			}
		}
		
		$this->pixel_r = array_count_values($this->pixel_r);
		arsort($this->pixel_r);
		
		next($this->pixel_r);
		$square = key($this->pixel_r);
		
		return ($this->get_yx($square));
	}
	
	private function get_yx($color)
	{
		for ($width = 0; $width < ($this->size[0]); $width++)
		{
			for ($height = 0; $height < ($this->size[1]); $height++)
			{
				if (imagecolorat($this->resources, $width, $height) == $color)
				{
					$pos = array(($width+10), ($height+10));
					return ($pos);
				}
			}
		}
		
		return false;
	}
}
