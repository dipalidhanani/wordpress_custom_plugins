<?php

class Aghadi_Package{

	private $length = 0;

	private $width = 0;
	
	private $height = 0;
	
	private $weight = 0;
	
	private $volume = 0;
	
	private $value = 0;
	
	private $percent = 0;
	
	private $items_packed;
	
	private $items_not_packed;
	
	public function __construct( $package ){
	
		$this->items_packed = $this->items_not_packed = array();
		
		$dimensions = array( $package['length'], $package['width'], $package['height'] );
			
		rsort($dimensions);
		
		$this->set_length( $dimensions[0] );
		$this->set_width( $dimensions[1] );
		$this->set_height( $dimensions[2] );
		
		if( isset( $package['weight'] ) )
			$this->set_weight( $package['weight'] );
			
		if( isset( $package['value'] ) )
			$this->set_value( $package['value'] );
		
	}
	
	public function get_width(){
		return $this->width;
	}
	
	public function get_length(){
		return $this->length;
	}
	
	public function get_height(){
		return $this->height;
	}
	
	public function get_weight(){
		return $this->weight;
	}
	
	public function get_volume(){
		return $this->volume;
	}
	
	public function get_value(){
		return $this->value;
	}
	
	public function get_percent(){
		return $this->percent;
	}
	
	public function get_items_packed(){
		return $this->items_packed;
	}
	
	public function get_items_not_packed(){
		return $this->items_not_packed;
	}
	
	public function set_width($value){
		$this->width = $value;
	}
	
	public function set_length($value){
		$this->length = $value;
	}
	
	public function set_height($value){
		$this->height = $value;
	}
	
	public function set_weight($value){
		$this->weight = $value;
	}
	
	private function set_volume(){
		if( !empty( $this->width ) && !empty( $this->length ) && !empty( $this->height ) )
			$this->volume = $this->width * $this->length * $this->height;
	}
	
	private function calculate_percent(){
		$this->percent = ( count( $this->items_packed ) / ( count( $this->items_packed ) + count( $this->items_not_packed ) ) ) * 100;
	}
	
	public function add_item_packed( $item ){
		//Add item to packed list
		$this->items_packed[] = $item;
		
		$this->value += $item->get_value();
		
		$this->weight += $item->get_weight();
		
		$this->volume += $item->get_volume();
		
		$this->calculate_percent();
		
	}
	
	public function add_item_not_packed( $item ){
		//Add item to not packed list
		$this->items_not_packed[] = $item;
		
		$this->calculate_percent();
		
	}
	
}

?>