<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'libraries/Grocery_CRUD.php';

class grocery_CRUD_multi
{   

    public $css = array();
    public $js = array();
    public $output = array();
    public $grids = array();
    public function grid_add($id)
    {
        $this->grids[$id] = new Grocery_crud($id);
        $this->grids[$id]->set_theme('flexiajax');
    }
    public function render()
    {
        $ci = &get_instance();
        $seg = $ci->uri->segments;
        
        foreach($this->grids as $k => $v)
        {
            $temp = $v->render();
            $this->output[$k] = $temp->output;
            $this->css = array_merge($this->css,(array)$temp->css_files);
            $this->js = array_merge($this->js,(array)$temp->js_files);
        }
        return array('output'=>$this->output[1],'output2'=>$this->output[2],'css_files'=>$this->css);
        
        
        // print_r($this->output[1]);
    
    }
}