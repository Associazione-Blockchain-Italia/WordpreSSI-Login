<?php

namespace Inc\Pages;

use Inc\Sections\Section;

/**
 * This class represent an Abstract Page on the Admin Area
 * Each page has an id, pageTitle and menuTitle property.
 *
 * For a provider the id property is the same as the page id.
 *
 */
abstract class Page
{

    /**
     * The id of the page.
     * The property is used to save the settings of a page under this property in the database
     *
     * @var string
     */
    protected string $id;

    /**
     * The page title shown in the settings form
     *
     * @var string
     */
    protected string $pageTitle;

    /**
     * The menu page shown on the side bar of the wordpress admin area
     *
     * @var string
     */
    protected string $menuTitle;

    /**
     * A list of subpages to register for the page
     * @var array
     */
    protected array $subpages = [];

    /**
     * A list of sections
     *
     * @var array
     */
    protected array $sections = [];

    /**
     * A list of scripts to enqueue when this page is loaded
     * @var array
     */
    protected array $scripts = [];

    /**
     * A list of styles to enqueue when this page is loaded
     * @var array
     */
    protected array $styles = [];

    /**
     * @param $id
     * @param $pageTitle
     * @param $menuTitle
     */
    public function __construct($id, $pageTitle, $menuTitle)
    {
        $this->id = $id;
        $this->pageTitle = $pageTitle;
        $this->menuTitle = $menuTitle;
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
     * @return Page
     */
    public function setId(string $id): Page
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getPageTitle(): string
    {
        return $this->pageTitle;
    }

    /**
     * @param string $pageTitle
     *
     * @return Page
     */
    public function setPageTitle(string $pageTitle): Page
    {
        $this->pageTitle = $pageTitle;

        return $this;
    }

    /**
     * @return String
     */
    public function getMenuTitle(): string
    {
        return $this->menuTitle;
    }

    /**
     * @param String $menuTitle
     *
     * @return Page
     */
    public function setMenuTitle(string $menuTitle): Page
    {
        $this->menuTitle = $menuTitle;

        return $this;
    }

    /**
     * @return array
     */
    public function getSubpages(): array
    {
        return $this->subpages;
    }

    /**
     * @param array $subpages
     *
     * @return Page
     */
    public function setSubpages(array $subpages): Page
    {
        $this->subpages = $subpages;

        return $this;
    }

    /**
     * @return array
     */
    public function getSections(): array
    {
        return $this->sections;
    }

    /**
     * @param array $sections
     *
     * @return Page
     */
    public function setSections(array $sections): Page
    {
        $this->sections = $sections;

        return $this;
    }

    /**
     * @param Page $subpage
     *
     * @return void
     */
    public function addSupbage(Page $subpage)
    {
        $this->subpages[] = $subpage;
    }

    /**
     * @param Section $section
     *
     * @return void
     */
    public function addSection(Section $section)
    {
        $this->sections[] = $section;
    }

    /**
     * @return array
     */
    public function getScripts(): array
    {
        return $this->scripts;
    }

    /**
     * @param array $scripts
     *
     * @return Page
     */
    public function setScripts(array $scripts): Page
    {
        $this->scripts = $scripts;

        return $this;
    }

    /**
     * @return array
     */
    public function getStyles(): array
    {
        return $this->styles;
    }

    /**
     * @param array $styles
     *
     * @return Page
     */
    public function setStyles(array $styles): Page
    {
        $this->styles = $styles;

        return $this;
    }

    /**
     * Path of the view that echoes the page
     *
     * @return mixed
     */
    public abstract function render();

}
