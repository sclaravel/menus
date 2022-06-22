<?php

namespace TysonLaravel\Menus\Presenters;

abstract class Presenter implements PresenterInterface
{
    /**
     * Get open tag wrapper.
     *
     * @return string
     */
    public function getOpenTagWrapper()
    {
    }

    /**
     * Get close tag wrapper.
     *
     * @return string
     */
    public function getCloseTagWrapper()
    {
    }

    /**
     * Get menu tag with dropdown wrapper.
     *
     * @param \TysonLaravel\Menus\Item $item
     *
     * @return string
     */
    public function getMenuWithDropDownWrapper($item)
    {
    }

    /**
     * Get child menu items.
     *
     * @param \TysonLaravel\Menus\Item $item
     *
     * @return string
     */
    public function getChildItems(Item $item)
    {
        $results = '';
        foreach ($item->getChilds() as $child) {
            if ($child->hidden()) {
                continue;
            }

            if ($child->hasSubMenu()) {
                $results .= $this->getMultiLevelDropdownWrapper($child);
            } elseif ($child->isHeader()) {
                $results .= $this->getHeaderWrapper($child);
            } elseif ($child->isDivider()) {
                $results .= $this->getDividerWrapper();
            } else {
                $results .= $this->getMenuWithoutDropdownWrapper($child);
            }
        }

        return $results;
    }

    /**
     * Get multi level dropdown menu wrapper.
     *
     * @param \TysonLaravel\Menus\Item $item
     *
     * @return string
     */
    public function getMultiLevelDropdownWrapper($item)
    {
    }

    /**
     * Get header dropdown tag wrapper.
     *
     * @param \TysonLaravel\Menus\Item $item
     *
     * @return string
     */
    public function getHeaderWrapper($item)
    {
    }

    /**
     * Get divider tag wrapper.
     *
     * @return string
     */
    public function getDividerWrapper()
    {
    }

    /**
     * Get menu tag without dropdown wrapper.
     *
     * @param \TysonLaravel\Menus\Item $item
     *
     * @return string
     */
    public function getMenuWithoutDropdownWrapper($item)
    {
    }
}
