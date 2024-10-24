<?php
	class Pizza
	{
		public $price;
		public $cheese;
		public $size;

		public function __destruct()
		{
			echo $this->size->what;
		}
	}