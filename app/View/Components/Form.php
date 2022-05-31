<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Form extends Component
{
    /**
     * Имя формы для передачи в запросе
     *
     * @var string
     */
    public $name;

    /**
     * Тип формы (text, url, password)
     *
     * @var string
     */
    public $type;

    /**
     * Длина значения формы
     *
     * @var int
     */
    public $length;

    /**
     * Отображаемое имя
     *
     * @var string
     */
    public $displayName;

    /**
     * Описание под формой
     *
     * @var string
     */
    public $description;

    /**
     * Значение формы
     *
     * @var mixed|string
     */
    public $value;

    /**
     * Обязательное поле
     *
     * @var bool
     */
    public $required;

    /**
     * Create a new component instance.
     *
     * @param $name
     * @param $displayName
     * @param $description
     * @param $value
     * @param $type
     * @param $length
     * @return void
     */
    public function __construct($name, $displayName, $required=false,$description='', $value='', $type='text', $length=255)
    {
        $this->name = $name;
        $this->type = $type;
        $this->length = $length;
        $this->displayName = $displayName;
        $this->description = $description;
        $this->value = $value;
        $this->required = $required;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form');
    }
}
