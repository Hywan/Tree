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

use Hoa\Visitor;

/**
 * Class \Hoa\Tree\Generic.
 *
 * Here is an abstract tree.
 */
abstract class Generic
    implements Visitor\Element,
               \Iterator,
               \SeekableIterator,
               \Countable
{
    /**
     * Node value.
     *
     * @var \Hoa\Tree\ITree\Node
     */
    protected $_value  = null;

    /**
     * List of childs.
     *
     * @var array
     */
    protected $_childs = [];


    /**
     * Build a node. It can be a root, a node or a leaf.
     */
    public function __construct($value = null)
    {
        $this->setValue($value);

        return;
    }

    /**
     * Set the node value.
     */
    public function setValue($value): ?ITree\Node
    {
        if (!($value instanceof ITree\Node)) {
            $value = new SimpleNode(md5($value), $value);
        }

        $old          = $this->_value;
        $this->_value = $value;

        return $old;
    }

    /**
     * Get the node value.
     */
    public function getValue(): ?ITree\Node
    {
        return $this->_value;
    }

    /**
     * Get the current child for the iterator.
     */
    public function current()
    {
        return current($this->_childs);
    }

    /**
     * Get the current child id for the iterator.
     */
    public function key()
    {
        return key($this->_childs);
    }

    /**
     * Advance the internal child pointer, and return the current child.
     */
    public function next()
    {
        return next($this->_childs);
    }

    /**
     * Rewind the internal child pointer, and return the first child.
     */
    public function rewind()
    {
        return reset($this->_childs);
    }

    /**
     * Check if there is a current element after calls to the rewind or the next
     * methods.
     */
    public function valid() : bool
    {
        if (empty($this->_collection)) {
            return false;
        }

        $key    = key($this->_collection);
        $return = (bool) next($this->_childs);
        prev($this->_collection);

        if (false === $return) {
            end($this->_childs);
            if ($key === key($this->_childs)) {
                $return = true;
            }
        }

        return $return;
    }

    /**
     * Seek to a position.
     */
    public function seek($position)
    {
        if (!array_key_exists($position, $this->_collection)) {
            return;
        }

        $this->rewind();

        while ($position != $this->key()) {
            $this->next();
        }

        return;
    }

    /**
     * Count number of elements in collection.
     */
    public function count(): int
    {
        return count($this->_childs);
    }

    /**
     * Get a specific child.
     */
    public function getChild($nodeId): self
    {
        if (false === $this->childExists($nodeId)) {
            throw new Exception('Child %s does not exist.', 0, $nodeId);
        }

        return $this->_childs[$nodeId];
    }

    /**
     * Get all childs.
     */
    public function getChilds(): array
    {
        return $this->_childs;
    }

    /**
     * Check if a child exists.
     */
    public function childExists($nodeId): bool
    {
        return array_key_exists($nodeId, $this->getChilds());
    }

    /**
     * Insert a child.
     * Fill the child list from left to right.
     */
    abstract public function insert(Generic $child): self;

    /**
     * Delete a child.
     */
    abstract public function delete($nodeId): self;

    /**
     * Check if the node is a leaf.
     */
    abstract public function isLeaf(): bool;

    /**
     * Check if the node is a node (i.e. not a leaf).
     */
    abstract public function isNode(): bool;

    /**
     * Accept a visitor.
     */
    public function accept(
        Visitor\Visit $visitor,
        &$handle = null,
        $eldnah  = null
    ) {
        return $visitor->visit($this, $handle, $eldnah);
    }
}
