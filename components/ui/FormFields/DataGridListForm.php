<?php

class DataGridListForm extends Form {

    public function getFields() {
        return array(
            array(
                'value' => '<div ng-init=\"value[$index].show = false\" style=\"cursor:pointer;padding-bottom:1px;\" ng-click=\"value[$index].show = !value[$index].show\">
<div class=\"label data-filter-name pull-right\"> 
{{value[$index].columnType}}</div>
{{value[$index].label}} 
<div class=\"clearfix\"></div>
</div>',
                'type' => 'Text',
            ),
            array(
                'value' => '<hr ng-show=\"value[$index].show\"
style=\"margin:4px -12px 6px -4px;float:left;width:100%;padding:0px 4px;\" />',
                'type' => 'Text',
            ),
            array(
                'value' => '<div ng-show=\\"value[$index].show\\">',
                'type' => 'Text',
            ),
            array(
                'label' => 'Type',
                'name' => 'columnType',
                'options' => array(
                    'ng-model' => 'value[$index].columnType',
                    'ng-change' => '$parent.changeButtonType(value[$index]);updateListView()',
                ),
                'labelOptions' => array(
                    'style' => 'text-align:left;',
                ),
                'list' => array(
                    'string' => 'String',
                    'buttons' => 'Buttons',
                    'dropdown' => 'Dropdown',
                ),
                'labelWidth' => '3',
                'fieldWidth' => '9',
                'type' => 'DropDownList',
            ),
            array(
                'label' => 'Name',
                'name' => 'name',
                'labelWidth' => '3',
                'fieldWidth' => '9',
                'options' => array(
                    'ng-model' => 'value[$index].name',
                    'ng-change' => 'updateListView()',
                    'ng-delay' => '500',
                    'ng-if' => 'value[$index].columnType != \\\'buttons\\\'',
                ),
                'labelOptions' => array(
                    'style' => 'text-align:left;',
                ),
                'fieldOptions' => array(
                    'class' => 'list-view-item-text',
                ),
                'type' => 'TextField',
            ),
            array(
                'label' => 'Header',
                'name' => 'label',
                'labelWidth' => '3',
                'fieldWidth' => '9',
                'options' => array(
                    'ng-model' => 'value[$index].label',
                    'ng-change' => 'updateListView()',
                    'ng-delay' => '500',
                ),
                'labelOptions' => array(
                    'style' => 'text-align:left;',
                ),
                'type' => 'TextField',
            ),
            array(
                'name' => 'TypeDropDown',
                'subForm' => 'application.components.ui.FormFields.DataGridListFormDropdown',
                'options' => array(
                    'ng-if' => 'value[$index].columnType == \\\'dropdown\\\'',
                ),
                'inlineJS' => 'DataGrid/typeDropDownCtrl.js',
                'type' => 'SubForm',
            ),
            array(
                'name' => 'TypeButton',
                'subForm' => 'application.components.ui.FormFields.DataGridListFormButton',
                'options' => array(
                    'ng-if' => 'value[$index].columnType == \\\'buttons\\\'',
                ),
                'type' => 'SubForm',
            ),
            array(
                'label' => 'Options',
                'fieldname' => 'options',
                'show' => 'Show',
                'options' => array(
                    'ng-model' => 'value[$index].options',
                    'ng-change' => 'updateListView()',
                ),
                'type' => 'KeyValueGrid',
            ),
            array(
                'value' => '<div style=\\"margin-bottom:-3px;\\"></div>',
                'type' => 'Text',
            ),
            array(
                'value' => '</div>',
                'type' => 'Text',
            ),
        );
    }

    public function getForm() {
        return array(
            'formTitle' => 'DataFilterListForm',
            'layout' => array(
                'name' => 'full-width',
                'data' => array(
                    'col1' => array(
                        'type' => 'mainform',
                    ),
                ),
            ),
        );
    }

    public $name = '';
    public $label = '';
    public $options = array();

    ### dropdown Options
    public $listType = 'php';
    public $listExpr = '';

    ### button Options
    public $buttonCollapsed = 'Yes';
    public $buttons = array(
        array(
            'label' => '',
            ''
        )
    );
    public $columnType = 'string';

}
