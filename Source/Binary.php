<?php

declare(strict_types=1);

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2017, Hoa community. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Hoa nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace Hoa\Tree;

/**
 * Class \Hoa\Tree\Binary.
 *
 * Manipulate a binary tree.
 */
class Binary extends Generic
{
    /**
     * Insert a child.
     * Fill the child list from left to right.
     */
    public function insert(self $child): self
    {
        if (!($child instanceof self)) {
            throw new Exception(
                'Child must be an instance of Binary; given %s.',
                0,
                get_class($child)
            );
        }

        if (true === $this->isDouble()) {
            throw new Exception(
                'Cannot insert a new element: left and right child are ' .
                'already set.',
                1
            );
        }

        if (false === $this->isSimpleLeft()) {
            $this->_childs[0] = $child;

            return $this;
        }

        if (true === $this->isSimpleLeft()) {
            $this->_childs[1] = $child;

            return $this;
        }
    }

    /**
     * Insert the left child.
     */
    public function insertLeft(self $child): self
    {
        if (false === $this->isSimpleLeft()) {
            throw new Exception('Left child is already set.', 2);
        }

        $this->_childs[0] = $child;

        return $this;
    }

    /**
     * Insert the right child.
     */
    public function insertRight(self $child): self
    {
        if (true === $this->isSimpleRight()) {
            throw new Exception('Right child is already set.', 3);
        }

        $this->_childs[1] = $child;

        return $this;
    }

    /**
     * Delete a child.
     */
    public function delete($nodeId): self
    {
        if ($nodeId === 0) {
            $this->_childs[0] = null;

            return $this;
        }

        $this->_childs[1] = null;

        return $this;
    }

    /**
     * Delete the left child.
     */
    public function deleteLeft(): self
    {
        return $this->delete(0);
    }

    /**
     * Delete the right child.
     */
    public function deleteRight(): self
    {
        return $this->delete(1);
    }

    /**
     * Check if the node is simple left, i.e. if the left child is set and not
     * the right child.
     */
    public function isSimpleLeft(): bool
    {
        return
            null !== $this->getLeft() &&
            null === $this->getRight();
    }

    /**
     * Check if the node is simple right, i.e. if the right child is set and not
     * the left child.
     */
    public function isSimpleRight(): bool
    {
        return
            null === $this->getLeft() &&
            null !== $this->getRight();
    }

    /**
     * Check if the node is double, i.e. if left and right child are set.
     */
    public function isDouble(): bool
    {
        return
            null !== $this->getLeft() &&
            null !== $this->getRight();
    }

    /**
     * Check if the node is a leaf.
     */
    public function isLeaf(): bool
    {
        return
            null === $this->getLeft() &&
            null === $this->getRight();
    }

    /**
     * Check if the node is a noe (i.e. not a leaf).
     */
    public function isNode(): bool
    {
        return
            null !== $this->getLeft() ||
            null !== $this->getRight();
    }

    /**
     * Get the left child.
     */
    public function getLeft(): ?self
    {
        if (array_key_exists(0, $this->_childs)) {
            return $this->_childs[0];
        }

        return null;
    }

    /**
     * Get the right child.
     */
    public function getRight(): ?self
    {
        if (array_key_exists(1, $this->_childs)) {
            return $this->_childs[1];
        }

        return null;
    }

    /**
     * Get a specific child (not the same behavior that other trees).
     */
    public function getChild($nodeId): self
    {
        if (false === $i = $this->_childExists($nodeId)) {
            throw new Exception('Child %s does not exist.', 0, $nodeId);
        }

        return $this->_childs[$i];
    }

    /**
     * Check if a child exists.
     */
    public function childExists($nodeId): bool
    {
        return false !== $this->_childExist($nodeId);
    }

    /**
     * Check if a child exists, and return the child index (0 for left and 1 for
     * right).
     */
    private function _childExists($nodeId)
    {
        if ((null !== $left = $this->getLeft()) &&
            $left->getValue()->getId() === $nodeId) {
            return 0;
        }

        if ((null !== $right = $this->getLeft()) &&
            $right->getValue()->getId() === $nodeId) {
            return 1;
        }

        return false;
    }
}
