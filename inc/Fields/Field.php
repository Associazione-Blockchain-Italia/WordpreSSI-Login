<?php

namespace Inc\Fields;

/**
 * A field represent a configuration field for a provider.
 * A configuration field has an $id, $label, $property, $group and $subgroup property.
 *
 */
abstract class Field
{

    /**
     * The id of the field
     *
     * @var string
     */
    private string $id;

    /**
     * The group of the field.
     * This should be the provider id
     * @var string
     */
    private string $group;

    /**
     * The name of the field
     * @var string
     */
    private string $label;

    /**
     * The subgroup of the field
     * @see getValue()
     * @var string|mixed
     */
    private string $subGroup;

    /**
     * Whether the field should be visible or not in the configuration form
     * @var bool|mixed
     */
    private bool $hidden;

    public function __construct($label, $id, $group, $subGroup = '', $hidden=false)
    {
        $this->label = $label;
        $this->id = $id;
        $this->group = $group;
        $this->subGroup = $subGroup;
        $this->hidden = $hidden;
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
     * @return Field
     */
    public function setId(string $id): Field
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getGroup(): string
    {
        return $this->group;
    }

    /**
     * @param string $group
     *
     * @return Field
     */
    public function setGroup(string $group): Field
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getSubGroup()
    {
        return $this->subGroup;
    }

    /**
     * @param mixed|string $subGroup
     *
     * @return Field
     */
    public function setSubGroup($subGroup): Field
    {
        $this->subGroup = $subGroup;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return Field
     */
    public function setLabel(string $label): Field
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * @param bool $hidden
     *
     * @return Field
     */
    public function setHidden(bool $hidden): Field
    {
        $this->hidden = $hidden;

        return $this;
    }

    public function getFieldName(): string
    {
        if ($this->getGroup()) {
            if ($this->getSubGroup()) {
                return "{$this->getGroup()}[{$this->getSubGroup()}][{$this->getId()}]";
            }

            return "{$this->getGroup()}[{$this->getId()}]";
        }

        return $this->getId();
    }

    /**
     * Get the value of the field from the database.
     * eg.
     * provider id => group id = ssi_provider,
     * field id = field_id,
     * subgroup = subgroup_id
     *
     * A field is saved in the db as ssi_provider[field_id[subgroup_id]]
     *
     * @return false|mixed|void|null
     */
    public function getValue()
    {
        if ($this->getGroup()) {
            if ($this->getSubGroup()) {
                return get_option($this->getGroup())[$this->getSubGroup()][$this->getId()] ?? null;
            }
            return get_option($this->getGroup())[$this->getId()] ?? null;
        }
        return get_option($this->getId()) ?? null;
    }

    public abstract function render();

}
