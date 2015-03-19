	  <?php 
	   class person {
	   	        public $id;
	   	        public $name;
	   	        public  static  $count=77;
	   	        const  tax_rate=0.081;
	   	      
	   	      public function  __construct($id,$name){   	      	  
	   	      	     $this->id=$id;	 
	   	      	     $this->name=$name;	 
	   	      	} 
	   	      	
	   	      	
	   	      	public function  computetaxamt($amt){   	      	  
	   	      	    return   self::tax_rate*$amt; 	 
	   	      	}
	   	    }
  ?>