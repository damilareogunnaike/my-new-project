<?php
class CODE_MAKER
{
    public $length;
    public $quantity;
    public $return_as_string;
    public $string_seperator;
    
    public $allow_numbers;
    public $allow_chars;
    public $allow_uppercae;
    public $allow_lowercase;
    public $allow_symbols; // 
    
    public $prefix; // String to be added at the beginning of each generated pin
    public $suffix; // String to be added at the end of each generated pin
    
    
    public $codes;
    
    public function __construct()
    {
        $this->length           = 8;
        $this->quantity         = 10;
        $this->return_as_string = false;
        $this->string_seperator = null;
        
        $this->allow_numbers    = true;
        $this->allow_chars      = true;
        $this->allow_uppercase  = true;
        $this->allow_lowercase  = true;
        $this->allow_symbol     = true;
        
        $this->prefix           = null;
        $this->suffix           = null;
        
        $this->codes            = null;
        
    }
    
    public function generate()
    {
        
        $values = array();
        $length = $this->length = $this->length - (strlen($this->prefix) + strlen($this->suffix));
        $this->length = $length;
        if(!$this->allow_chars && !$this->allow_symbols) 
        {
            $values = $this->generate_numbers();
            return $this->generate_numbers();
        }
        for($i = 0; $i < $this->quantity; $i++)
        {   
            $values[] = null;
            $values[$i] .= $this->prefix;
            for($j = 0; $j < $length; $j++)
            {
                $index = rand(0,sizeof($this->char_set) - 1);
                
                $values[$i] .= $this->char_set[$index];
            }
            $values[$i] .= $this->suffix;
        }
        $this->codes = $values;
        
        if($this->return_as_string)
        {
            return $this->to_string($values);
        }
        return $values;
    }
    
    
    private $char_set = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    private $symbol_set = array('`','~','!','@','#','$','%','^','&','*','(',')','_','-','+','=','|','\\','{','}','[',']',':',';','"','\'','?','/','<','>',',','.');
    
    public function return_as_string($seperator)
    {
        $this->return_as_string = true;
        $this->string_seperator = $seperator;
    }
    
    function generate_numbers()
    {
        $values = array();
        for($i = 0; $i < $this->quantity; $i++)
        {   
            $values[] = null;
            $values[$i] .= $this->prefix;
            for($j = 0; $j < $this->length; $j++)
            {
                $index = rand(0,9);
                
                $values[$i] .= $index;
            }
            $values[$i] .= $this->suffix;
        }
        if($this->return_as_string)
        {
            return $this->to_string($values);
        }
        return $values;
    }
    
    function to_string($codes)
    {
        return implode($this->string_seperator,$codes);
        
    }
    
    
}
?>