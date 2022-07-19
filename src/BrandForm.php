<?php

    namespace EcomerceBy1ya;
    
    class BrandForm
    {
        public int $id;
        public string $naziv_brand;
    
        protected $brandFormArr = ['naziv_brand'];
    
        public function __construct()
        {
    
        }
    
        public static function loadBrand($brand)
        {
            $form = new self();
    
            foreach(get_object_vars($brand) as $key=>$value){
                $form->$key = $brand->$key;
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
                 if(in_array($key, $this->brandFormArr)) {
    
                // ako postoji property u objektu dodeljuje mu vrednost
                    if (property_exists($this, $key)){
                        $this->$key = $value;
                    }
                }
            }
            return $this;
        }
    }