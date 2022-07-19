<?php

    namespace EcomerceBy1ya;
    
    class SpecificationForm 
    {
        public int $id;
        public string $processor;
        public int $ram;
        public int $rom;
    
        protected $specFormArr = ['processor', 'ram', 'rom'];
    
        public function __construct()
        {
    
        }
    
        public static function loadSpec($spec)
        {
            $form = new self();
    
            foreach(get_object_vars($spec) as $key=>$value){
                $form->$key = $spec->$key;
            }
            
            // $form->id = $product->id;
            // $form->naziv = $product->naziv;
            // $form->cena = $product->cena;
            // $form->id_kat = $product->id_kat;
            // $form->id_spec = $product->id_spec;
            // $form->id_marka = $product->id_marka;
            // $form->availibleQuantity = $product->availibleQuantity;
    
            return $form;
        }
    
        public function updateForm($niz) //dobija se updated objekat forme
        {
            
            foreach ($niz as $key=>$value) {
                    
                // proverava da li property postoji u nizu productFormArr
                 if(in_array($key, $this->specFormArr)) {
    
                // ako postoji property u objektu dodeljuje mu vrednost
                    if (property_exists($this, $key)){
                        $this->$key = $value;
                    }
                }
            }
            return $this;
        }
    }