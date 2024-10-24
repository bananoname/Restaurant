<?php
	class IceCream
	{
		public $flavors;
		public $topping;

		public function __invoke()
		{
			foreach ($this->flavors as $flavor) {
				echo $flavor;
			}
		}
	}