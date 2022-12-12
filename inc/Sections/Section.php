<?php

namespace Inc\Sections;

use Inc\Fields\Field;

class Section
{

    private string $id;

    private string $title;

    private array $fields = [];

    public function __construct($id, $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return Section
     */
    public function setId(string $id): Section
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Section
     */
    public function setTitle(string $title): Section
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     *
     * @return Section
     */
    public function setFields(array $fields): Section
    {
        $this->fields = $fields;

        return $this;
    }

    public function addField(Field $field)
    {
        $this->fields[] = $field;
    }

}
