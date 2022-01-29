<?php
function get_number($data=array(),$type='number',$only_control=false) {
    return get_text($data,$type,$only_control);
}
function get_password($data=array(),$type='password',$only_control=false) {
    return get_text($data,$type,$only_control);
}
function get_text($data=array(),$type='text',$only_control=false) {
    $id = $data['id'];
    $name = (isset($data['name']) && $data['name'])?$data['name']:$data['id'];
    $name = str_replace('-','_',$name);
    $title = $data['title'];
    $value = (isset($data['value']))?$data['value']:'';
    $label_title = (isset($data['label_title']))?$data['label_title']:$data['title'];
    $vue_model = (isset($data['vue_model']) && $data['vue_model'])?$data['vue_model']:'';

    $class = "form-control";
    if(isset($data['class']) && $data['class']){
        $class .= ' ' . $data['class'];
    }

    $placeholder = "";
    if(isset($data['placeholder']) && $data['placeholder']) {
        $placeholder = $data['placeholder'];
    }


    $attribute = '';
    $required = false;
    if(isset($data['attribute']) && $data['attribute']) {
        $attribute = $data['attribute'];
        if (strpos($attribute, 'required') !== false || strpos($attribute, ' required') !== false || strpos($attribute, 'required ') !== false) {
            $required = true;
            $string = (isset($data['placeholder']) && $data['placeholder'])?$data['placeholder']:$title;
            $attribute .= ' data-parsley-required-message="'.$string.' is mandatory."';
        }
    }

    $html = '';
    if(!$only_control) {
        $html = '<div class="form-group row">';
        $html .= '<label for="' . $id . '" class="col-sm-'.LABEL_COLUMNS.' col-form-label';
        if($required) {
            $html .= ' text-danger';
        }
        $html .= '">' . $label_title;
        if($required) {
            $html .= '*';
        }
        $html .= '</label><div class="col-sm-'.TEXT_COLUMNS.'">';
    }
    $html .= '<input type="'.$type.'"';
    if($vue_model) {
        $html .= ' v-model="' . $vue_model . '"';
    }
    $html .= ' name="'.$name.'" class="'.$class.'" '.$attribute.' id="'.$id.'" placeholder="'.$placeholder.'"';
    if(!$vue_model) {
        $html .= ' value="' . stripslashes($value) . '"';
    }
    $html .= '/>';
    if(!$only_control) {
        $html .= '</div></div>';
    }

    return $html;
}
function get_textarea($data,$only_control=false) {
    $id = $data['id'];
    $name = (isset($data['name']) && $data['name'])?$data['name']:$data['id'];
    $name = str_replace('-','_',$name);
    $title = $data['title'];
    $label_title = (isset($data['label_title']))?$data['label_title']:$data['title'];
    $value = (isset($data['value']))?$data['value']:'';
    $rows = (isset($data['rows']))?$data['rows']:5;
    $vue_model = (isset($data['vue_model']) && $data['vue_model'])?$data['vue_model']:'';

    $class = "form-control";
    if(isset($data['class']) && $data['class']) {
        $class = $class . ' ' . $data['class'];
    }

    $attribute = '';
    $required = false;
    if(isset($data['attribute']) && $data['attribute']){
        $attribute = $data['attribute'];
        if (strpos($attribute, 'required') !== false || strpos($attribute, ' required') !== false || strpos($attribute, 'required ') !== false) {
            $required = true;
            $string = (isset($data['placeholder']) && $data['placeholder'])?$data['placeholder']:$title;
            $attribute .= ' data-parsley-required-message="'.$string.' is mandatory."';
        }
    }

    $placeholder = "";
    if(isset($data['placeholder']) && $data['placeholder']){
        $placeholder = $data['placeholder'];
    }

    $html = '';
    if(!$only_control) {
        $html = '<div class="form-group">';
        $html .= '<label for="' . $id . '"';
        if($required) {
            $html .= 'class="text-danger"';
        }
        $html .= '>' . $label_title;
        if($required) {
            $html .= '*';
        }
        $html .= '</label>';
    }
    $html .= '<textarea';
    if($vue_model) {
        $html .= ' v-model="' . $vue_model . '"';
    }
    $html .= ' name="'.$name.'"  class="'.$class.'" '.$attribute.' id="'.$id.'" placeholder="'.$placeholder.'" rows="'.$rows.'">'.stripslashes($value).'</textarea>';
    if(!$only_control) {
        $html .= '</div>';
    }

    return $html;
}
function get_hidden($data=array()) {
    $id = (isset($data['id']) && $data['id'])?$data['id']:$data['name'];
    $name = $data['name'];
    $name = str_replace('-','_',$name);
    $value = (isset($data['value']))?$data['value']:'';

    $class = "form-control";
    if(isset($data['class']) && $data['class']){
        $class = $class . ' ' . $data['class'];
    }

    $attribute = '';
    if(isset($data['attribute']) && $data['attribute']){
        $attribute = $data['attribute'];
    }

    $html = '<input type="hidden" name="'.$name.'" id="'.$id.'" value="'.$value.'" class="'.$class.'" '.$attribute.' />';

    return $html;
}
function get_button($data=array(),$type='button') {
    $id = $data['id'];
    //$name = (isset($data['name']) && $data['name'])?$data['name']:$data['id'];
    $title = $data['title'];
    $value = (isset($data['value']))?$data['value']:'';

    $class = "form-control";
    if(isset($data['class']) && $data['class']){
        $class = $data['class'];
    }

    $attribute = '';
    if(isset($data['attribute']) && $data['attribute']){
        $attribute = $data['attribute'];
    }

    $html = '<button id="'.$id.'" type="'.$type.'" class="'.$class.'" '.$attribute.' '.$value.'>'.$title.'</button>';

    return $html;
}
function get_anchor($data=array()) {
    $id = $data['id'];
    $name = (isset($data['name']) && $data['name'])?$data['name']:$data['id'];
    $title = $data['title'];
    $href = $data['href'];

    $class = "form-control";
    if(isset($data['class']) && $data['class']){
        $class = $data['class'];
    }

    $attribute = '';
    if(isset($data['attribute']) && $data['attribute']){
        $attribute = $data['attribute'];
    }

    $html = '<a href="'.$href.'" id="'.$id.'" class="'.$class.'" '.$attribute.'>'.$title.'</a>';

    return $html;
}
function get_vue_anchor($data=array()) {
    $id = $data['id'];
    $content = $data['content'];
    $click = $data['click'];

    $class = "";
    if(isset($data['class']) && $data['class']){
        $class = $data['class'];
    }

    $attribute = '';
    if(isset($data['attribute']) && $data['attribute']){
        $attribute = $data['attribute'];
    }

    $html = '<a @click.prevent="'.$click.'" id="'.$id.'" class="'.$class.'" '.$attribute.'>'.$content.'</a>';

    return $html;
}
function get_select($data=array(),$populate=array(),$default_value='value',$default_id='id',$only_control=false) {

    $id = $data['id'];
    $name = (isset($data['name']) && $data['name'])?$data['name']:$data['id'];
    $name = str_replace('-','_',$name);
    $title = $data['title'];
    $value = (isset($data['value']))?$data['value']:'';
    $label_title = (isset($data['label_title']))?$data['label_title']:$data['title'];
    $vue_model = (isset($data['vue_model']) && $data['vue_model'])?$data['vue_model']:'';
    $vue_for = (isset($data['vue_for']) && $data['vue_for'])?$data['vue_for']:'';
    $button = (isset($data['button']) && $data['button'])?$data['button']:'';

    $class = "form-control";
    if(isset($data['class']) && $data['class']){
        $class = $class . ' ' . $data['class'];
    }

    $attribute = '';
    $required = false;
    if(isset($data['attribute']) && $data['attribute']) {
        $attribute = $data['attribute'];
        if (strpos($attribute, 'required') !== false || strpos($attribute, ' required') !== false || strpos($attribute, 'required ') !== false) {
            $required = true;
            $string = (isset($data['placeholder']) && $data['placeholder'])?$data['placeholder']:$title;
            $attribute .= ' data-parsley-required-message="'.$string.' is mandatory."';
        }
    }

    $html = '';
    if(!$only_control) {
        $html = '<div class="form-group row">';
        $html .= '<label for="' . $id . '" class="col-sm-'.LABEL_COLUMNS.' col-form-label';
        if($required) {
            $html .= ' text-danger';
        }
        $html .= '">' . $label_title;
        if($required) {
            $html .= '*';
        }
        if($button) {
            $html .= $button;
        }
        $html .= '</label><div class="col-sm-'.TEXT_COLUMNS.'">';
    }
    $html .= '<select name="'.$name.'" class="'.$class.'" '.$attribute.' id="'.$id.'"';
    if($vue_model) {
        $html .= ' v-model="' . $vue_model . '"';
    }
    $html .= ">";
    if($default_value) {
        $html .= '<option ';
        if($vue_for) {
            $html .= 'v-for="single in '.$vue_for.'"';
        }
        if($vue_model && $vue_for) {
            $html .= ':value="single.' . $default_id . '">{{ single.' . $default_value . ' }}</option>';
        }else {
            $html .= 'value="' . $default_id . '">' . $default_value . '</option>';
        }
    }
    if($populate){
        foreach($populate as $row){
            $selected = ($row['id']==$value)?' selected':'';
            $html .= '<option value="'.$row['id'].'"'.$selected.'>'.$row['value'].'</option>';
        }
    }
    $html .= '</select>';
    if(!$only_control) {
        $html .= '</div></div>';
    }

    return $html;
}

function get_radio_set($data,$children) {

    $id = $data['id'];
    $name = (isset($data['name']) && $data['name'])?$data['name']:$data['id'];
    $name = str_replace('-','_',$name);
    $title = $data['title'];
    $vue_model = (isset($data['vue_model']) && $data['vue_model'])?$data['vue_model']:'';

    $required = false;
    if(isset($data['attribute']) && $data['attribute']) {
        $attribute = $data['attribute'];
        if (strpos($attribute, 'required') !== false || strpos($attribute, ' required') !== false || strpos($attribute, 'required ') !== false) {
            $required = true;
        }
    }

    $html = '';
    $html .= '<div class="form-group row">';
    $html .= '<label class="col-sm-'.LABEL_COLUMNS.' col-form-label';
    if($required) {
        $html .= ' text-danger';
    }
    $html .= '">' . $title;
    if($required) {
        $html .= '*';
    }
    $html .= '</label><div class="align-self-center col-sm-'.TEXT_COLUMNS.'">';
    foreach ($children as $child) {
        $child['id'] = $id;
        $child['name'] = $name;
        $child['required_title'] = $title;
        if($vue_model) {
            $child['vue_model'] = $vue_model;
        }
        $html .= get_radio($child,false);
    }
    $html .= '</div></div>';

    return $html;
}
function get_radio($data,$inline=false) {

    $type = 'radio';
    $name = (isset($data['name']) && $data['name'])?$data['name']:$data['id'];
    $name = str_replace('-','_',$name);
    $title = $data['title'];
    $value = (isset($data['value']))?$data['value']:'';
    $label_title = (isset($data['label_title']))?$data['label_title']:$data['title'];
    $id = $data['id'] . '-' . $value;
    $vue_model = (isset($data['vue_model']) && $data['vue_model'])?$data['vue_model']:'';

    $class = "custom-control-input";
    if(isset($data['class']) && $data['class']){
        $class .= ' ' . $data['class'];
    }

    $parent_class = 'custom-control custom-radio';
    if($inline) {
        $parent_class .= ' custom-control-inline';
    }

    $attribute = '';
    $required = false;
    if(isset($data['attribute']) && $data['attribute']) {
        $attribute = $data['attribute'];
        if (strpos($attribute, 'required') !== false || strpos($attribute, ' required') !== false || strpos($attribute, 'required ') !== false) {
            $required = true;
            if(isset($data['required_title']) && $data['required_title']) {
                $string = $data['required_title'];
            }else {
                $string = (isset($data['placeholder']) && $data['placeholder']) ? $data['placeholder'] : $title;
            }
            $attribute .= ' data-parsley-required-message="'.$string.' is mandatory."';
        }
    }

    $html = '<div class="'.$parent_class.'">';
    $html .= '<input type="'.$type.'" name="'.$name.'" class="'.$class.'" '.$attribute.' id="'.$id.'" value="'.stripslashes($value).'"';
    if($vue_model) {
        $html .= ' v-model="' . $vue_model . '"';
    }
    $html .= ">";

    $html .= '<label for="' . $id . '" class="custom-control-label';
    if($required) {
        $html .= ' text-danger';
    }
    $html .= '">' . $label_title;
    if($required) {
        $html .= '';
    }
    $html .= '</label>';
    $html .= '</div>';

    return $html;
}
?>
